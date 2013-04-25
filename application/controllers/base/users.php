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
	public function profile()
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
		$msg['data'] = $this->user_model->select_profile($this->username);

		$this->load->view('header');
		$this->load->view('base/profile',$msg);
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
		$this->load->view('header');
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
		$this->load->view('header');
		$this->load->view('base/email',$msg);
		$this->load->view('footer');
	}

	// ssh_key 公钥
	public function public_key()
	{
	}
}

/* End of file users.php */
/* Location: ./application/controllers/base/users.php */
