<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class Regist extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 *	处理 post 表单传来的数据，进行过滤.
	 *	注册成功者将 转移到各个用户(包括管理员及普通用户)的首页.
	 */
	public function index()
	{
		
		// 收集 post 提交的数据
		$data['username'] = $this->input->post('username');
		$data['password'] = $this->input->post('password');
		$data['confirm'] = $this->input->post('confirm');
		$data['email'] = $this->input->post('email');
		$data['ssh_key'] = $this->input->post('ssh_key');
		$data['date'] = $this->input->post('date');
		$data['location'] = $this->input->post('location');
		$data['img'] = $this->input->post('img');
		$data['admin'] = $this->input->post('admin');

		$this->load->model('user_model');

		if($this->user_model->is_empty($data))
		{
			$this->load->view('header');
			$this->load->view('base/regist');
			$this->load->view('footer');
		}
		else
		{
			$msg = $this->user_model->check($data);
			if($msg['flag'])
			{
				$this->user_model->insert($data);
				redirect(site_url('base/signup'));
			}
			else
			{
				$this->load->view('header');
				$this->load->view('base/regist',$msg);
				$this->load->view('footer');
			}
		}
	}
}
/* End file of regist.php */
/* Location: ./application/controllers/regist.php */
