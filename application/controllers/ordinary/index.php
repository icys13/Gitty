<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Index extends Users {
	
	public function __construct()
	{
		parent::__construct();
	}

	// 普通用户主页
	public function index()
	{
	//	$profile = $this->show_profile();
		$msg1 = $this->show_profile();
		$msg1['username'] = $this->username;

		$this->load->view('header');
		$this->load->view('ordinary/profile',$msg1);
		$this->load->view('ordinary/repo');
	//	$this->load->view('ordinary/index');
		$this->load->view('footer');
	}

	// 显示个人信息 full_name & location & join in date
	private function show_profile()
	{		
		$data['date'] = $this->user_model->select_date($this->username);	
		$data['full_name'] = $this->user_model->select_full_name($this->username);
		$data['location'] = $this->user_model->select_location($this->username);
		return $data;
	}

	// 显示独自创建的项目
	private function show_creates()
	{
		$creates = $this->repository_model->select_creates($this->username);
		return $creates;
	}

	// 显示 fork 的项目
	private function show_forks()
	{
		$forks = $this->repository_model->select_forks($this->username);
		return $forks;
	}

	// 显示 participate 的项目
	private function show_participates()
	{
		$participates = $this->repository_model->select_participates($this->username);
		return $participates;
	}

	// 用户创建项目
	public function create_repo()
	{
		$data['repo_name'] = $this->input->post('repo_name');
		$data['creator'] = $this->input->post('creator');
		$data['description'] = $this->input->post('description');

		if(!empty($data['repo_name']) && !empty($data['creator']))
		{
			$this->repository_model->create_repo($data);
			$msg['error'] = '创建项目成功!';
			$msg['username'] = $data['creator'];
			$temp = $this->user_model->select_email($data['creator']);
			$msg['email'] = $temp['email'];
			$msg['repo_name'] = $data['repo_name'];

			// 显示项目创建后的 向导 页面
			$this->load->view('header');
			$this->load->view('ordinary/guide',$msg);
			$this->load->view('footer');
		}
		else
		{
			$msg['username'] = $this->username;
			$this->load->view('header');
			$this->load->view('ordinary/create_repo',$msg);
			$this->load->view('footer');
		}
	}
}

/* End of file index.pho */
/* Location: ./application/controllers/ordinary/index.php */
