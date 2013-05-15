<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Index extends Users {

	public function __construct()
	{
		parent::__construct();
		if(!$this->user_model->is_admin($this->username))
		{
			$this->session->sess_destroy();
			redirect(site_url('base/signup'));
		}
	}

	public function index()
	{
		if($this->regist_status())
		{
			$msg['regist_status'] = '已开放注册<a href="'.base_url().'index.php/admin/index/close_regist">关闭</a>';
		}
		else
		{
			$msg['regist_status'] = '已关闭注册<a href="'.base_url().'index.php/admin/index/open_regist">开启</a>';
		}

		$this->load->view('header',array('username' => $this->username,'title' => "管理员首页 - Gitty"));
		$this->load->view('admin/index',$msg);
		$this->load->view('footer');
	}	

	private function regist_status()
	{
		$this->load->model('regist_status_model');
		$data = $this->regist_status_model->get_status();
		return $data;
	}

	public function open_regist()
	{
		$this->load->model('regist_status_model');
		$this->regist_status_model->set_status(array('status' => TRUE));
		redirect('admin/index');
	}

	public function close_regist()
	{
		$this->load->model('regist_status_model');
		$this->regist_status_model->set_status(array('status' => FALSE));
		redirect('admin/index');
	}

}

/* End of file index.php */
/* Location: ./application/controllers/admin/index.php */
