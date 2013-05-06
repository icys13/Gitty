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
		$this->repository_model->commits_update($username,$reponame);
//		$data = $this->repository_model->is_empty($username,$reponame);
/*
		if($data['empty'])
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
			// 显示项目主页
			echo $data['HEAD'].'<br/>';
			$array = array();
			$rc;
			exec('./scripts/rev-parse.sh HEAD',$array,$rc);
			echo $rc;
			print_r($array);
		}
 */
	}
}

/* End of file repository.php */
/* Location: ./application/controllers/ordinary/repository.php */
