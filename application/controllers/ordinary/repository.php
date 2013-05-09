<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Repository extends Users {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('repository_model');
	}

	// 项目主页
	// 类 GitCafe 显示方式
	// 显示 HEAD 版本库的信息
	// 显示 项目历史 commit 信息
	public function index($username,$reponame)
	{
		// 检查 commits 是否更新
		$data = $this->repository_model->db_is_empty($username,$reponame);

		if($data['empty'] && $this->repository_model->git_is_empty($username,$reponame))
		{
			$msg['error'] = '您还没有推送项目!';
			$msg['username'] = $username;
			$temp = $this->user_model->select_email($username);
			$msg['email'] = $temp['email'];
			$msg['repo_name'] = $reponame;
			$this->load->view('header');
			$this->load->view('ordinary/guide',$msg);
			$this->load->view('footer');
		}
		else
		{
			$HEAD = 'HEAD';
			$result = array();
			$content = array();
			exec("./scripts/rev-parse.sh $username $reponame $HEAD",$result);
			$latest = $result[0];

			if(empty($data['HEAD']))
			{
				$this->repository_model->insert_latest_commit($data['table'],array('username' => $username,'repo_name' => $reponame),$latest);

				// ls-tree
				$tree = array();
				exec("./scripts/ls-tree.sh $username $reponame $latest",$tree);
				$this->ls_tree($username,$reponame,$tree);

				while($HEAD != $result[0])
				{
					// 获取commits详细信息
					unset($content);
					exec("./scripts/cat-file.sh $username $reponame -p $HEAD",$content);

					$temp = $this->format($content);
					$temp['username'] = $username;
					$temp['repo_name'] = $reponame;
					$temp['commit'] = $result[0];

					$this->repository_model->insert_commits($temp);
					$HEAD = $result[0].'^';
					unset($result);

					exec("./scripts/rev-parse.sh $username $reponame $HEAD",$result);
				}
			}
			else
			{
				$HEAD = 'HEAD';
				$flag = FALSE;
				unset($result);

				exec("./scripts/rev-parse.sh $username $reponame $HEAD",$result);

				// 版本库有更新
				if($data['HEAD'] != $result[0])
				{
					$tree = array();
					exec("./scripts/ls-tree.sh $username $reponame $latest",$tree);
					$this->ls_tree($username,$reponame,$tree);
					$flag = TRUE;
				}

				while($data['HEAD'] != $result[0])
				{
					// 获取 commits 详细信息
					unset($content);
					exec("./scripts/cat-file.sh $username $reponame -p $HEAD",$content);

					$temp = $this->format($content);
					$temp['username'] = $username;
					$temp['repo_name'] = $reponame;
					$temp['commit'] = $result[0];

					$this->repository_model->insert_commits($temp);
					$HEAD = $result[0].'^';
					unset($result);
					exec("./scripts/rev-parse.sh $username $reponame $HEAD",$result);
				}
				if($flag)
					$this->repository_model->insert_latest_commit($data['table'],array('username' => $username,'repo_name' => $reponame),$latest);
			}

			$this->load->view('header');

			// 项目文件结构
			$this->load->view('ordinary/repo_header',array('username' => $username,'repo_name' => $reponame,'SHA' => $result[0]));

			$browser = $this->tree_browser($username,$reponame);
			$browser['path'] = $reponame;
			$this->load->view('ordinary/tree_browser',$browser);

			// README.md(README)文件内容(如果存在的话)
			$file = 'README.md';
			$preview = $this->preview($username,$reponame,$file);
			
			if(!empty($preview))
			{
				$temp = array();
				$msg['file'] = '';
				exec("./scripts/cat-file.sh $username $reponame -p {$preview['SHA']}",$temp);
				foreach($temp as $item)
					$msg['file'] .= $item.'<br/>';
				$this->load->view('ordinary/preview',$msg);
			}
			else
			{
				$this->load->view('ordinary/no_file',array('error' => 'README.md还没有编辑,立即编辑README.md。'));
			}

			$this->load->view('footer');
		}
	}

	private function tree_browser($username,$reponame)
	{
		$this->db->select('SHA,dir_name');
		$this->db->order_by('dir_name');
		$query = $this->db->get_where('trees',array('username' => $username, 'repo_name' => $reponame));
		$result = $query->result_array();
		$trees = array();
		$i = 0;
		foreach($result as $item)
		{
			$trees[$i]['dir_name'] = $item['dir_name'];
			$trees[$i++]['SHA'] = $item['SHA'];
		}

		$this->db->select('SHA,file_name');
		$this->db->order_by('file_name','asc');
		$query = $this->db->get_where('blobs',array('username' => $username,'repo_name' => $reponame));
		$result = $query->result_array();
		$blobs = array();
		$i = 0;
		foreach($result as $item)
		{
			$blobs[$i]['file_name'] = $item['file_name'];
			$blobs[$i++]['SHA'] = $item['SHA'];
		}
		return array('trees' => $trees,'blobs' => $blobs);
	}

	private function preview($username,$reponame,$file)
	{
		$this->db->select('SHA');
		$query = $this->db->get_where('blobs',array('username' => $username,'repo_name' => $reponame,'file_name' => $file));
		$result = $query->row_array();
		return $result;
	}

	// 派生模块
	// fork $username,$reponame 的项目到，当前用户的 $reponame 中
	public function fork($username,$reponame)
	{
		// 更新 repositories , forks 表
		$data_repos = $this->repository_model->select_repos($username,$reponame);
		$data_forks = $this->repository_model->select_creates($username,$reponame);

		// 更改相应的数据
		$data_repos['owner'] = $this->username;
		//$data_repos['owner'] = 'Sir Alex';
		$data_repos['creator'] = $username;
		$data_repos['create_date'] = date("Y-m-d");
		$data_repos['update_date'] = $data_repos['create_date'];

		$data_forks['username'] = $this->username;
		$data_forks['creator'] = $username;

		// 向表中增加相应的数据
		$this->repository_model->insert_repos($data_repos);
		$this->repository_model->insert_forks($data_forks);

		// 复制版本库
		//
		// 跳转到个人主页
		redirect(site_url().'/ordinary/index');
	}

	// 下载模块
	// 下载 $username@reponame.git 版本库下的提交为 $SHA 的工作区文件
	// 默认下载格式为 .tar.gz
	public function download($username,$reponame,$SHA)
	{
		$data = array();
		header('Content-type: application/octet.stream');
		exec("./scripts/archive.sh $username $reponame $SHA",$data);
		$tmp = '';
		foreach($data as $item)
			$tmp .= $item;
		header('Content-Disposition: attachment;filename="'.$reponame.'_'.$SHA.'.tar.gz"');
		echo $tmp; 
	}

	// 将 commit 信息数据库化
	private function format($content)
	{
		$judge = explode(" ",$content[1]);
		$data = array(
			'tree' => '',
			'parent' => '',
			'author' => '',
			'committer' => '',
			'date' => '',
			'message' => ''
		);
		if($judge[0] == 'parent')
			$i = 4;
		else
			$i = 3;
		for($j = 0;$j < $i;$j++)
		{
			$temp = explode(" ",$content[$j]);
			// 分类
			if($temp[0] == 'tree')
				$data['tree'] = $temp[1];
			elseif($temp[0] == 'parent')
				$data['parent'] = $temp[1];
			elseif($temp[0] == 'author')
				$data['author'] = $temp[1];
			elseif($temp[0] == 'committer')
			{
				$data['committer'] = $temp[1];
				$data['date'] = $temp[3];
			}
		}
		$size = count($content);
		for($j = $i + 1;$j < $size;$j++)
		{
			$data['message'] .=$content[$j]."\n";
		}
		return $data;
	}

	private function ls_tree($username,$reponame,$tree)
	{
		$size = count($tree);
		$temp = array();
		$this->repository_model->del_trees(array('username' => $username,'repo_name' => $reponame));
		$this->repository_model->del_blobs(array('username' => $username,'repo_name' => $reponame));

		for($i = 0;$i < $size;$i++)
		{
			$temp = explode(" ",$tree[$i]);
			if($temp[1] == 'blob')
			{
				// 文件
				$this->repository_model->insert_blobs(array('username' => $username,'repo_name' => $reponame,'SHA' => substr($temp[2],0,40),'file_name' => substr($temp[2],41)));
			}
			else
			{
				// 目录
				$this->repository_model->insert_trees(array('username' => $username,'repo_name' => $reponame,'SHA' => substr($temp[2],0,40),'dir_name' => substr($temp[2],41)));
			}
		}
	}
}

/* End of file repository.php */
/* Location: ./application/controllers/ordinary/repository.php */
