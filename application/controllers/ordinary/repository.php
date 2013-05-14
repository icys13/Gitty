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
			$this->load->view('header',array('username' => $this->username,'title' => "$reponame Empty - Gitty"));
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

			$this->load->view('header',array('username' => $username,'title' => "$reponame 项目 - Gitty"));

			// 项目文件结构
			$temp = $this->repository_model->select_repos($username,$reponame);
			$this->load->view('ordinary/repo_header',array('username' => $username,'repo_name' => $reponame,'SHA' => $result[0],'message' => $temp['description']));

			$browser = $this->tree_browser($username,$reponame);
			$browser['path'] = $reponame;
			$this->load->view('ordinary/tree_browser',$browser);

			// README.md(README)文件内容(如果存在的话)
			$file = 'README.md';
			$preview = $this->preview($username,$reponame,$file);
			
			if(!empty($preview))
			{
				$temp = array();
				$msg['code'] = '';
				$msg['line'] = '';
				$msg['username'] = $username;
				$msg['reponame'] = $reponame;
				$msg['SHA'] = $preview['SHA'];
				exec("./scripts/cat-file.sh $username $reponame -p {$preview['SHA']}",$temp);
				$i = 0;
				foreach($temp as $item)
				{
					$msg['code'] .= $item.'<br/>';
					$msg['line'] .= $i.'<br/>';
					$i++;
				}
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
	private function db_fork($username,$reponame)
	{
		// fork 相关的数据库操作
		// 更新 repositories , forks , commits , trees , blobs 表
		$data_repos = $this->repository_model->select_repos($username,$reponame);
		$data_forks = $this->repository_model->select_creates($username,$reponame);
		$data_commits = $this->repository_model->select_commits($username,$reponame,TRUE);
		$data_trees = $this->repository_model->select_trees($username,$reponame);
		$data_blobs = $this->repository_model->select_blobs($username,$reponame);

		// 更新相应的数据
		// repositories 表
		$data_repos['owner'] = $this->username;
		$data_repos['creator'] = $username;
		$data_repos['create_date'] = date("Y-m-d");
		$data_repos['update_date'] = $data_repos['create_date'];

		// forks 表
		$data_forks['username'] = $this->username;
		$data_forks['creator'] = $username;

		// commits 表
		$size_commits = count($data_commits);
		for($i = 0;$i < $size_commits;$i++)
		{
			$data_commits[$i]['username'] = $this->username;
		}

		// trees 表
		$size_trees = count($data_trees);
		for($i = 0;$i < $size_trees;$i++)
		{
			$data_trees[$i]['username'] = $this->username;
		}

		// blobs 表
		$size_blobs = count($data_blobs);
		for($i = 0;$i < $size_blobs;$i++)
		{
			$data_blobs[$i]['username'] = $this->username;
		}

		// 插入 repositories 表
		$this->repository_model->insert_repos($data_repos); 

		// 插入 forks 表
		$this->repository_model->insert_forks($data_forks);

		// 插入 commits 表
		for($i = 0;$i < $size_commits;$i++)
		{
			$this->repository_model->insert_commits($data_commits[$i],TRUE);
		}

		// 插入 trees 表
		for($i = 0;$i < $size_trees;$i++)
		{
			$this->repository_model->insert_trees($data_trees[$i]);
		}

		// 插入 blobs 表
		for($i = 0;$i < $size_blobs;$i++)
		{
			$this->repository_model->insert_blobs($data_blobs[$i]);
		}

		return TRUE;
	}

	public function fork($username,$reponame)
	{
		// 更新数据库
		if($this->db_fork($username,$reponame))
		{

			// 复制版本库
			exec("./scripts/cp.sh $username $reponame $this->username");

			// 修改 gitosis.conf 文件
			// 将当前用户对项目的权限设为可写
			$this->load->helper('file');
			$tmp = "\n[group ".strtotime("now")."]\nnumbers = ".$this->username."\nwritable = ".$this->username.'@'.$reponame."\n";
			write_file('./gitosis-conf/gitosis-admin/gitosis.conf',$tmp,'a+');

			// 跳转到当前用户派生项目主页
			redirect(site_url().'/ordinary/repository/index/'.$this->username.'/'.$reponame);
		}
		else
		{
			// 数据库更新失败
			// 不调转
			redirect(site_url().'/ordinary/repository/index/'.$username.'/'.$reponame);
		}
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
