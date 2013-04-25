<?php if ( ! defined('BASEPATH')) exit('No directscript access allowed!');

class Authorization extends CI_Controller {


	var $username;
	// 检查用户是否登录
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->username = $this->session->userdata('username');

		if(!$this->session->userdata('username') || !$this->session->userdata('log_in'))
			redirect(site_url('base/signup'));
	}	

	/*
	 * 用户直接访问 authorization 时，
	 * 直接跳转到登录页面.
	 */
	public function index()
	{
		redirect(site_url('base/signup'));
	}
}

/* End file of authorization.php */
/* Location: ./appllcation/controllers/autho.php */
