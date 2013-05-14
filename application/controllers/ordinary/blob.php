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
		$result['line'] = '';
		$result['code'] = '';
		$path .= ' / '.$file_name;
		exec("./scripts/cat-file.sh $username $reponame -p $SHA",$data);
		$i = 1;
		foreach($data as $item)
		{
			$result['line'] .= $i.'<br/>';
			$result['code'] .= $item.'<br/>';
			$i++;
		}
		$this->load->view('header',array('username' => $this->username,'title' => "$username/$reponame - Gitty"));
		$this->load->view('ordinary/repo_header',array('username' => $username,'repo_name' => $reponame,'SHA' => $SHA,'message' => ''));
		//$this->load->view('ordinary/tree_browser');
		$this->load->view('ordinary/preview',$result);
		$this->load->view('footer');
	}
}

/* End file of blob.php */
/* Location: ./applilcation/constrollers/ordinary/blob.php */
