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
			exec("./scripts/rev-parse.sh $username $reponame $HEAD",$result);
			$latest = $result[0];

			if(empty($data['HEAD']))
			{
				while($HEAD != $result[0])
				{
					// 获取commits详细信息
					// ....
					$this->repository_model->insert_commits('commits',$username,$reponame,$result[0]);
					$HEAD = $HEAD.'^';
					unset($result);
					exec("./scripts/rev-parse.sh $username $reponame $HEAD",$result);
				}
				$this->repository_model->insert_latest_commit($data['table'],$username,$reponame,$latest);
			}
			else
			{
				$HEAD = 'HEAD';
				$flag = FALSE;
				unset($result);
				exec("./scripts/rev-parse.sh $username $reponame $HEAD",$result);

				while($data['HEAD'] != $result[0])
				{
					$flag = TRUE;
				}
				if($flag)
					$this->repository_model->insert_latest_commit($data['table'],$username,$reponame,$latest);
			}
			$this->load->view('header');
			//$this->load->view();
			$this->load->view('footer');
		}
	}
}

/* End of file repository.php */
/* Location: ./application/controllers/ordinary/repository.php */
