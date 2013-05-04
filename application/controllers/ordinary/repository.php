<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Repository extends Users {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index($username,$repo_name)
	{
		echo $username;
		echo $repo_name;
	}
}

/* End of file repository.php */
/* Location: ./application/controllers/ordinary/repository.php */
