<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/authorization.php');

class Index extends Authorization {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('ordinary/index');
	}
}

/* End of file index.pho */
/* Location: ./application/controllers/ordinary/index.php */
