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
			$this->load->view('base/readme',$msg['remind']);
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
			redirect(site_url('base/regist'));
		}
		else
			//建立不成功
			$this->load->view('base/readme',$msg['remind']);
	}

}

/* End of file install.php */
/* Location: ./application/controllers/install.php */
