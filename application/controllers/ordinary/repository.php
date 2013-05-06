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
			print_r($result);
			if(empty($data['HEAD']))
			{
				$this->repository_model->insert_latest_commit($data['table'],array('username' => $username,'repo_name' => $reponame),$result[0]);
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
				if($data['HEAD'] != $result[0])
					$flag = TRUE;

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
			//$this->load->view();
			$this->load->view('footer');
		}
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
}

/* End of file repository.php */
/* Location: ./application/controllers/ordinary/repository.php */
