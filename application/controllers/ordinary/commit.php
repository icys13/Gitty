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
		$log = array();
		
		// 获得版本差异的简易版本
		exec("./scripts/log.sh $username $reponame $SHA",$log);

		// 获取更改的文件列表
		$size = count($log);
		for($j = 0,$i = 1;$i < $size - 1;$i++,$j++)
		{
			$tmp = explode(' ',$log[$i]);
			$toc[$j] = $tmp[1];
		}

		// 父提交
		$parent = $SHA.'^';

		// 利用 git diff 获得文件的行变化
		unset($tmp);
		$diff = '';
		for($i = 0;$i < $size -2;$i++)
		{
			exec("./scripts/diff.sh $username $reponame $parent $SHA $toc[$i]",$tmp[$i]);
			$diff .= '<div class="diff-view module" id="'.$toc[$i].'">';
			$diff .= '<header class="module-hd"><h3>'.$toc[$i].'</h3></header>';
			$diff .= '<div class="module-bd"><table cellpadding="0"><tbody>';
			$diff .= '';
			$diff .= '</tbody></table></div></div>';
		}

		$this->load->view('header');
		$this->load->view('ordinary/diff_header',$data);
		$this->load->view('ordinary/toc',array('toc' => $toc));
		$this->load->view('ordinary/diff',array('diff' => $diff));
		$this->load->view('footer');
	}
}

/* End of file:commit.php */
/* Location:./applilcation/constrollers/ordinary/commit.php */
