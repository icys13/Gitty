<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Index extends Users {

	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->load->view('admin/index');
	}	
}

/* End of file index.php */
/* Location: ./application/controllers/admin/index.php */
