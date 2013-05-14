<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

require_once('authorization.php');

/*
 *	管理员用户与普通用户共同父类.
 *	提供两者都需要的操作.
 */
class Users extends Authorization {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	/*
	 *	用户信息更新.
	 *	只更新用户全名及位置信息.
	 */
	public function account()
	{
		$data['full_name'] = $this->input->post('full_name');
		$data['location'] = $this->input->post('location');

		if(!$this->user_model->is_empty($data))
		{
			if($this->user_model->account_update($this->username,$data))
			{
				$msg['error'] = '用户基本信息更新成功!';
				$msg['flag'] = TRUE;
			}
			else
			{
				$msg['error'] = '用户基本信息更新失败!';
				$msg['flag'] = FALSE;
			}
		}

		$msg['admin'] = $this->session->userdata('admin');
		//	$msg['data'] = $this->user_model->select_profile($this->username);
		$msg['full_name'] = $this->user_model->select_full_name($this->username);
		$msg['location'] = $this->user_model->select_location($this->username);

		$this->load->view('header',array('username' => $this->username,'title' => '账户设置 - Gitty'));
		$this->load->view('base/settings_header');
		$this->load->view('base/account',$msg);
		$this->load->view('footer');
	}

	// 更新密码
	public function password()
	{
		$data['old_pwd'] = $this->input->post('old_pwd');
		$data['new_pwd'] = $this->input->post('new_pwd');
		$data['con_pwd'] = $this->input->post('con_pwd');

		if(!$this->user_model->is_empty($data))
		{
			$temp = $this->user_model->signup(array('username' =>$this->username,'password' => $data['old_pwd']));
			if($temp['flag'])
			{
				if(!preg_match('/^[a-zA-Z0-9\_]{6,20}$/',$data['new_pwd']) || !preg_match('/^[a-zA-Z0-9\_]{6,20}$/',$data['con_pwd']))
					$msg['error'] = '新密码或确认密码格式不正确!';
				else
				{
					if($data['new_pwd'] !== $data['con_pwd'])
						$msg['error'] = '新密码与确认密码不同!';
					else
					{
						$this->user_model->account_update($this->username,array('password' => md5($data['new_pwd'])));
						$msg['error'] = '新密码更新成功!';
					}
				}
			}
			else
			{
				$msg['error'] = '旧密码不正确!';
			}
		}

		$msg['admin'] = $this->session->userdata('admin');
		$this->load->view('header',array('username' => $this->username,'title' => '修改密码 - Gitty'));
		$this->load->view('base/settings_header');
		$this->load->view('base/password',$msg);
		$this->load->view('footer');
	}

	// 更新邮箱
	public function email()
	{
		$data['email'] = $this->input->post('email');
		if(!$this->user_model->is_empty($data))
		{
			if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
				$msg['error'] = '更新失败!新邮件格式不正确!';
			else
			{
				$this->user_model->account_update($this->username,array('email' => $data['email']));
				$msg['error'] = '邮件更新成功!';
			}
		}

		$msg['data'] = $this->user_model->select_email($this->username);
		$msg['admin'] = $this->session->userdata('admin');

		$this->load->view('header',array('username' => $this->username,'title' => '修改email - Gitty'));
		$this->load->view('base/settings_header');
		$this->load->view('base/email',$msg);
		$this->load->view('footer');
	}

	// ssh_key 公钥
	public function public_key()
	{
		$data = $this->user_model->select_ssh_key($this->username);
		$msg['admin'] = $this->session->userdata('admin');
		if(empty($data['ssh_key']))
		{
			$this->load->view('header',array('username' => $this->username,'title' => '添加ssh-key - Gitty'));
			$this->load->view('base/settings_header');
			$this->load->view('base/key_empty',$msg);
			$this->load->view('footer');
		}
		else
		{
			$msg['ssh_key'] = $data['ssh_key'];
			$msg['username'] = $this->username;
			$this->load->view('header',array('username' => $this->username,'title' => '编辑ssh-key - Gitty'));
			$this->load->view('base/settings_header');
			$this->load->view('base/key_exists',$msg);
			$this->load->view('footer');
		}
	}

	// 创建新的 ssh_key	
	public function new_key()
	{
		$data['ssh_key'] = $this->input->post('ssh_key');
		if(!empty($data['ssh_key']))
		{
			// 解析 ssh_key 格式
			$temp = explode(' ',$data['ssh_key']);
			if(count($temp) > 1) 
			{
				$ssh_key = $temp[0].' '.$temp[1];
			}

			// 将新 key 存入 db
			$this->user_model->account_update($this->username,array('ssh_key' => $ssh_key));

			// 更新 server 上gitosis的 key 信息
			$this->load->helper('file');
			write_file('./gitosis-conf/gitosis-admin/keydir/'.$this->username.'.pub',$ssh_key);
			if($this->session->userdata('admin'))
				redirect(site_url('admin/index/public_key'));
			else
				redirect(site_url('ordinary/index/public_key'));
		}
		else
		{
			$msg['admin'] = $this->session->userdata('admin');
			$msg['username'] = $this->username;
			$this->load->view('header');
			$this->load->view('base/new_key',$msg);
			$this->load->view('footer');
		}
	}

	// 删除旧 ssh_key
	public function del_key($username = '')
	{
		// 数据库操作
		$this->user_model->account_update($this->username,array('ssh_key' => ''));

		// 删除 server 上的 .pub 文件
		unlink('./gitosis-conf/gitosis-admin/keydir/'.$this->username.'.pub');

		if($this->session->userdata('admin'))
			redirect(site_url('admin/index/public_key'));
		else
			redirect(site_url('ordinary/index/public_key'));
	}

	public function img()
	{
		$msg['admin'] = $this->session->userdata('admin');
		if(!empty($_FILES['img']))
		{
			$types = array('jpg','gif','bmp','jpeg','png');
			if(!in_array(strtolower($this->img_type()),$types))
			{
				$msg['error'] = '您只能上传以下类型文件: '.implode(' .',$types);
				$this->load->view('header');
				$this->load->view('base/img',$msg);
				$this->load->view('footer');
			}
			else
			{
				$data = addslashes(fread(fopen($_FILES['img']['tmp_name'],'r'),filesize($_FILES['img']['tmp_name'])));
				$this->user_model->account_update($this->username,array('img' => $data,'img_type' => $_FILES['img']['type']));
				if($this->session->userdata('admin'))
					redirect(site_url('admin/index/img'));
				else
					redirect(site_url('ordinary/index/img'));
			}
		}
		else
		{
			$msg['username'] = $this->username;
			$this->load->view('header');
			$this->load->view('base/img',$msg);
			$this->load->view('footer');
		}
	}

	private function img_type()
	{
		return substr(strrchr($_FILES['img']['name'],'.'),1);
	}

	public function get_img()
	{
		if(isset($_GET['username']))
		{
			$img = $this->user_model->select_img($_GET['username']);
			$type = $this->user_model->select_img_type($_GET['username']);
			Header("Content-type:{$type['img_type']}");
			echo $img['img'];
		}
	}

	// 退出
	public function signout()
	{
		$this->session->unset_userdata(array('username' => '','log_in' => '','admin' => ''));
		redirect(base_url().'index.php');
	}
}

/* End of file users.php */
/* Location: ./application/controllers/base/users.php */
