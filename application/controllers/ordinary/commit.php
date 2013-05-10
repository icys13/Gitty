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
		$this->load->view('ordinary/repo_header',array('username' => $username,'repo_name' => $reponame,'SHA' => $data[0]['commit']));
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

		// 检查 SHA 是否为最初始的提交
		// 此次提交已将是最原始提交
		$diff = '';
		if(empty($data['parent']))
		{
			// 获得此次提交的 tree
			// 解析提交的 tree
			$tree = array();
			exec("./scripts/ls-tree.sh $username $reponame {$data['tree']}",$tree);
			$size_tree = count($tree);

			for($i = 0;$i < $size_tree;$i++)
			{
				// 解析 key , value 值
				$tmp = explode(' ',$tree[$i]);
				$key['key'] = substr($tmp[2],0,40);
				$key['value'] = substr($tmp[2],41);

				// 分析标题
				unset($tmp);
				exec("./scripts/cat-file.sh $username $reponame -p {$key['key']}",$tmp);
				$diff .= '<div class="diff-view module" id="'.$key['value'].'">';
				$diff .= '<header class="module-hd"><h3>'.$key['value'].'</h3></header>';
				$diff .= '<div class="module-bd"><table cellpadding="0"><tbody>';
				$diff .= '<tr><td class="line-numbers">...</td>';
				$diff .= '<td class="line-numbers">...</td>';
				$diff .= '<td class="chunk"><pre>@@ -0,0 +1,'.count($tmp).' @@</pre></td></tr>';
				
				// 显示内容
				$tmp_size = count($tmp);
				for($j = 1;$j <= $tmp_size;$j++)
				{
					$diff .= '<tr><td class="line-numbers"></td>';
					$diff .= '<td class="line-numbers">'.$j.'</td>';
					$diff .= '<td class="line-add"><pre>'.$tmp[$j-1].'</pre></td></tr>';
				}
				$diff .= '</tbody></table></div></div>';
			}
		}
		else 
		{
			// 利用 git diff 获得文件的行变化
			unset($tmp);
			for($i = 0;$i < $size -2;$i++)
			{
				unset($tmp);
				exec("./scripts/diff.sh $username $reponame $parent $SHA $toc[$i]",$tmp[$i]);
	
				// 获取差异定位后的数据
				$tmp = $this->locate($tmp[$i]);
	
				$diff .= '<div class="diff-view module" id="'.$toc[$i].'">';
				$diff .= '<header class="module-hd"><h3>'.$toc[$i].'</h3></header>';
				$diff .= '<div class="module-bd"><table cellpadding="0"><tbody>';
	
				// 格式处理
				$tmp_size = count($tmp);
				for($j = 1;$j < $tmp_size;$j++)
				{
					// 处理
					$line_size = count($tmp[$j]);
	
					// 第一行,差异定位行
					$diff .= '<tr><td class="line-numbers">...</td>';
					$diff .= '<td class="line-numbers">...</td>';
					$diff .= '<td class=chunk><pre>'.$tmp[$j][0].'</pre></td></tr>';
	
					// 差异定位信息解析
					$locate = explode(' ',$tmp[$j][0]);
					$remove_array = explode(',',substr($locate[1],1));
					$remove = $remove_array[0];
					$add_array = explode(',',substr($locate[2],1));
					$add = $add_array[0];

					for($k = 1;$k < $line_size;$k++)
					{
						if($tmp[$j][$k][0] == '+')
						{
							$num1 = '';
							$num2 = $add++;
							$class = 'line-add';
						}
						elseif($tmp[$j][$k][0] == '-')
						{
							$num1 = $remove++;
							$num2 = '';
							$class = 'line-remove';
						}
						else
						{
							$num1 = $remove++;
							$num2 = $add++;
							$class = '';
						}
						$diff .= '<tr><td class="line-numbers">'.$num1.'</td>';
						$diff .= '<td class="line-numbers">'.$num2.'</td>';
						$diff .= '<td class="'.$class.'"><pre>'.$tmp[$j][$k].'</pre></td></tr>';
					}
				}
				$diff .= '</tbody></table></div></div>';
			}
		}

		$this->load->view('header');
		$this->load->view('ordinary/repo_header',array('username' => $username,'repo_name' => $reponame,'SHA' => $SHA));
		$this->load->view('ordinary/diff_header',$data);
		$this->load->view('ordinary/toc',array('toc' => $toc));
		$this->load->view('ordinary/diff',array('diff' => $diff));
		$this->load->view('footer');
	}

	// 差异定位
	private function locate($data)
	{
		$size = count($data);
		$result = array();
		for($j = 0,$i = 0;$i < $size;$i++)
		{
			if(('@@' == substr($data[$i],0,2)))
			{
				$j++;
				$result[$j][] = $data[$i];
			}
			else
			{
				$result[$j][] = $data[$i];
			}
		}
		return $result;
	}

}

/* End of file:commit.php */
/* Location:./applilcation/constrollers/ordinary/commit.php */
