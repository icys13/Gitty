<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Tree extends Users {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($username,$reponame,$SHA,$path,$dir_name)
	{
		$data = array();
		$blobs = array();
		$trees = array();
		$path .= ' / '.$dir_name;
		exec("./scripts/ls-tree.sh $username $reponame $SHA",$data);
		$size = count($data);	
		$i = 0;$j = 0;$k = 0;
		for(;$i < $size;$i++)
		{
			$temp = explode(' ',$data[$i]);
			if($temp[1] == 'blob')
			{
				$blobs[$j]['SHA'] = substr($temp[2],0,40);
				$blobs[$j]['file_name'] = substr($temp[2],41);
				$j++;
			}
			else
			{
				$trees[$k]['SHA'] = substr($temp[2],0,40);
				$trees[$k]['dir_name'] = substr($temp[2],41);
				$k++;
			}
		}
		$this->load->view('header');
		$this->load->view('ordinary/tree_browser',array('username' => $username,'repo_name' => $reponame,'path' => $path,'trees' =>$trees,'blobs' => $blobs));
		$this->load->view('ordinary/no_file',array('error' => '请在左边栏选择文件或文件夹。'));
		$this->load->view('footer');
	}
}

/* End of file tree.php */
/* Location: ./applilcation/constrollers/ordinary/tree.php */
