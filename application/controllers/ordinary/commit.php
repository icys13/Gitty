<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once(dirname(dirname(__FILE__)).'/base/users.php');

class Commit extends Users {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('repository_model');
	}

	// 显示所有提交历史
	public function index($username,$reponame)
	{
		$data = $this->repository_model->select_commits($username,$reponame);

		// 获取当前提交的分支
		$msg['commits_title'] = $data[0]['commit'];

		// 显示提交历史
		$size = count($data);

		// 日期初始化 
		$date = date("Y-m-d",strtotime($data[0]['date']));
		$msg['commits'] = '<div class="commit-group">';
		$msg['commits'] .= '<h3>'.$date.'</h3>';

		for($i = 0;$i < $size;)
		{
			if($date == date("Y-m-d",strtotime($data[$i]['date'])))
			{
				$msg['commits']	.= '<li><div class="commit-desc">';
				$msg['commits'] .= '<span class="username"><a href="">'.$data[$i]['committer'].'</a></span> : ';
				$msg['commits'] .= '<span>'.$data[$i]['message'].'</span></div>';
				$msg['commits'] .= '<div class="commit-meta fr">';
				$msg['commits'] .= '<a class="sha" href="'.base_url().'index.php/ordinary/commit/detail/'.$username.'/'.$reponame.'/'.$data[$i]['commit'].'"><i class="icon-push"></i>'.substr($data[$i]['commit'],0,8).'</a>';
				$msg['commits'] .='</div></li>';
				$i++;
			}
			else
			{
				$date = date("Y-m-d",strtotime($data[$i]['date']));
				$msg['commits'] .= '</ol></div><div class="commit-group">';
				$msg['commits'] .= '<h3>'.$date.'</h3>';
			}
		}
		$msg['commits'] .= '</ol></div>';

		// 载入模板
		$this->load->view('header');
		$this->load->view('ordinary/repo_header',array('username' => $username,'repo_name' => $reponame));
		$this->load->view('ordinary/commits',$msg);
		$this->load->view('footer');
	}

	// 比较两次提交之间的差异
	public function detail($username,$reponame,$SHA)
	{

		// 获得更详细信息
		$data = $this->repository_model->select_commit(array('username' => $username,'repo_name' => $reponame,'commit' => $SHA));

		// 脚本执行前准备
		$diff = array();

		// 父提交
		$parent = $SHA.'^';

		exec("./scripts/diff.sh $username $reponame $parent $SHA",$diff);
		//print_r($diff);

		$this->load->view('header');
		$this->load->view('ordinary/diff_header',$data);
		//$this->load->view('ordinary/diff');
		$this->load->view('footer');
	}
}

/* End of file:commit.php */
/* Location:./applilcation/constrollers/ordinary/commit.php */
