<?php if ( ! defined ('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Blob extends Users {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($tree,$username,$reponame,$SHA)
	{
		// 显示文件内容
		$data = array();
		$result['line'] = '';
		$result['code'] = '';
		$path = $this->input->get('path').'/'.$this->input->get('file_name');
	//	$path .= '/'.$file_name;
		exec("./scripts/cat-file.sh $username $reponame -p $SHA",$data);
		$i = 1;
		foreach($data as $item)
		{
			$result['line'] .= $i.'<br/>';
			$result['code'] .= $item.'<br/>';
			$i++;
		}

		$result['username'] = $username;
		$result['reponame'] = $reponame;
		$result['SHA'] = $SHA;
		$this->load->view('header',array('username' => $this->username,'title' => "$username/$reponame - Gitty"));
		$this->load->view('ordinary/repo_header',array('username' => $username,'repo_name' => $reponame,'SHA' => $SHA,'message' => ''));

		// 获取文件夹信息
		unset($data);
		$blobs = array();
		$trees = array();
		$file = '';
		exec("./scripts/ls-tree.sh $username $reponame $tree",$data);
		$size = count($data);
		$i = 0;$j = 0;$k = 0;
		for(;$i < $size;$i++)
		{
			$temp = explode(' ',$data[$i]);
			if($temp[1] == 'blob')
			{
				$blobs[$j]['SHA'] = substr($temp[2],0,40);
				$blobs[$j]['file_name'] = substr($temp[2],41);
				if($blobs[$j]['file_name'] == 'README.md')
					$file = $blobs[$j]['SHA'];
				$j++;
			}
			else
			{
				$trees[$k]['SHA'] = substr($temp[2],0,40);
				$trees[$k]['dir_name'] = substr($temp[2],41);
				$k++;
			}
		}

		$this->load->view('ordinary/tree_browser',array('path' => $this->input->get('path'),'file' => $this->input->get('file_name'),'trees' => $trees,'blobs' => $blobs,'SHA' => $tree));
		$this->load->view('ordinary/preview',$result);
		$this->load->view('footer');
	}

	public function raw($username,$reponame,$SHA)
	{
		$data = array();
		exec("./scripts/cat-file.sh $username $reponame -p $SHA",$data);
		foreach($data as $item)
			echo $item.'<br/>';
	}
}

/* End file of blob.php */
/* Location: ./applilcation/constrollers/ordinary/blob.php */
