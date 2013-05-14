<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Tree extends Users {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('repository_model');
	}

	public function index($username,$reponame,$SHA)
	{
		$data = array();
		$blobs = array();
		$trees = array();
		$file = '';
		$dir_name = $this->input->get('dir_name');
		if(!empty($dir_name))
			$path = $this->input->get('path').'/'.$dir_name;
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
		$this->load->view('header',array('username' => $this->username,'title' => "$reponame/$path 目录- Gitty"));
		$temp = $this->repository_model->select_repos($username,$reponame);
		$this->load->view('ordinary/repo_header',array('username' => $username,'repo_name' => $reponame,'SHA' => $SHA,'message' => $temp['description']));
		$this->load->view('ordinary/tree_browser',array('path' => $path,'trees' =>$trees,'blobs' => $blobs,'SHA' => $SHA));

		// 代码首页,查找 README.md 文件,如果查找到则显示
		if($this->input->get('home') == true)
		{
			if(empty($file))
				$this->load->view('ordinary/no_file',array('error' => 'README.md还没有编辑,立即编辑README.md。'));
			else
			{
				$result = $this->markdown($username,$reponame,$file);
				$result['username'] = $username;
				$result['reponame'] = $reponame;
				$result['SHA'] = $file;
				$this->load->view('ordinary/preview',$result);
			}
		}
		else
			$this->load->view('ordinary/no_file',array('error' => '请在左边栏选择文件或文件夹。'));

		$this->load->view('footer');
	}

	// 获取 
	private function markdown($username,$reponame,$SHA)
	{
		$result['line'] = '';
		$result['code'] = '';
		$data = array();
		exec("./scripts/cat-file.sh $username $reponame -p $SHA",$data);
		$i = 1;
		foreach($data as $item)
		{
			$result['code'] .= $item.'<br/>';
			$result['line'] .= $i.'<br/>';
			$i++;
		}
		return $result;
	}
}

/* End of file tree.php */
/* Location: ./applilcation/constrollers/ordinary/tree.php */
