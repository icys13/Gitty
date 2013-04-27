<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Index extends Users {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('repository_model');
	}

	// 普通用户主页
	public function index()
	{
	//	$profile = $this->show_profile();
		$msg1 = $this->show_profile();
		$msg1['username'] = $this->username;

		$creates = $this->show_creates();
		if(count($creates))
		{
			$msg2['creates'] = '';
			foreach($creates as $item)
			{
				$msg2['creates'] .= '<div class="repo">';
				$msg2['creates'] .= '<div class="cf"><div class="repo-info"><h5><a href="">'.$item['creator'].'/'.$item['repo_name'].'</a></h5></div></div>';
				$msg2['creates'] .= '<p class="desc">'.$item['description'].'</p>';
				$msg2['creates'] .= '<p class="updated-at"><i class="icon-time"></i>创建于 '.$item['create_date'].',更新于 '.$item['update_date'].'</p></div>';
			}
		}
		else
			$msg2['creates'] = '<p class="repo-nothing">没有项目</p>';

		$forks = $this->show_forks();
		if(count($forks))
		{
			$msg2['forks'] = '';
			foreach($forks as $item)
			{
				$msg2['forks'] .= '<div class="repo">';
				$msg2['forks'] .= '<div class="cf"><div class="repo-info"><h5><a href="">'.$item['owner'].'/'.$item['repo_name'].'-------克隆于'.$item['creator'].'</a></h5></div></div>';
				$msg2['forks'] .= '<p class="desc">'.$item['description'].'</p>';
				$msg2['forks'] .= '<p class="updated-at"><i class="icon-time"></i>克隆于 '.$item['create_date'].',更新于'.$item['update_date'].'</p></div>';
			}
		}
		else
			$msg2['forks'] = '<p class="repo-nothing">没有项目</p>';

		$participates = $this->show_participates();
		if(count($participates))
		{
			$msg2['participates'] = '';
			foreach($participates as $item)
			{
				$msg2['participates'] .= '<div class="repo">';
				$msg2['participates'] .= '<div class="cf"><div class="repo-info"><h5><a href="">'.$item['owner'].'/'.$item['repo_name'].'-------参加于'.$item['creator'].'</a></h5></div></div>';
				$msg2['participates'] .= '<p class="desc">'.$item['description'].'</p>';
				$msg2['participates'] .= '<p class="updated-at"><i class="icon-time"></i>创建于 '.$item['create_date'].',更新于'.$item['update_date'].'</p></div>';
			}
		}
		else
			$msg2['participates'] = '<p class="repo-nothing">没有项目</p>';

		$this->load->view('header');
		$this->load->view('ordinary/profile',$msg1);
		$this->load->view('ordinary/repo',$msg2);
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
		return $this->repository_model->select_creates($this->username);
	}

	// 显示 fork 的项目
	private function show_forks()
	{
		return $this->repository_model->select_forks($this->username);
	}

	// 显示 participate 的项目
	private function show_participates()
	{
		return $this->repository_model->select_participates($this->username);
	}

	// 用户创建项目
	public function create_repo()
	{
		$data['repo_name'] = $this->input->post('repo_name');
		$data['creator'] = $this->input->post('creator');
		$data['owner'] = $data['creator'];
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
