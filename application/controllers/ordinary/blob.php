<?php if ( ! defined ('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Blob extends Users {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($username,$reponame,$SHA,$path,$file_name)
	{
		$data = array();
		$result = array();
		$path .= ' / '.$filename;
		exec("./scripts/cat-file.sh $username $reponame -p $SHA",$data);
		foreach($data as $item)
			echo $item.'<br/>';
	//		$result .= $item.'<br/>';
	//	print_r($result);
	}
}

/* End file of blob.php */
/* Location: ./applilcation/constrollers/ordinary/blob.php */
