<?php if (! defined('BASEPATH')) exit('No direct script access allowed!');

/*
 *	检查数据库配置是否正确，
 *	如果正确，正常进行安装.
 *	否则，返回错误信息，终止安装.
 */

class Install extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('install_model');
	}

	/*
	 *	检测 config/database.php 是否配置正确.
	 */

	public function index()
	{
		$msg = $this->install_model->conf_check();
		if($msg['flag'])
		{
			$this->action();
		}
		else
		{
			$this->load->view('readme',$msg['remind']);
		}
	}

	/*
	 * 如果指定数据库存在则覆盖,
	 * 如果指定数据库不存在则创建.
	 */

	public function action()
	{	
		if($this->install_model->is_exists())
		{
			$this->install_model->drop();
		}
		$msg = $this->install_model->install();
		if($msg['flag'])
		{
			// 创建成功
			$msg['error']= '数据库创建成功,请完善管理员信息.';
			$this->register($msg);
		}
		else
			//建立不成功
			$this->load->view('readme',$msg['remind']);
	}

	public function register($msg='')
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
			$this->load->view('register',$msg);
			$this->load->view('footer');
		}
		else
		{
			$msg = $this->user_model->check($data);
			if($msg['flag'])
			{
				$this->user_model->insert($data);
				redirect(site_url('admin/index'));
			}
			else
			{
				$this->load->view('header');
				$this->load->view('register',$msg);
				$this->load->view('footer');
			}
		}
	}
}

/* End of file install.php */
/* Location: ./application/controllers/install.php */
