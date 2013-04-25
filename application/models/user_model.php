<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class User_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/* 检查变量是否为空
	 * 若为空返回 TRUE
	 * 若不为空返回 FALSE
	 */
	public function is_empty($data)
	{
		if(is_array($data))
		{
			foreach($data as $value)
			{
				if(is_array($value))
				{
					if(!$this->is_empty($value))
						return FALSE;
				}
				else
				{
					if(!empty($value))
						return FALSE;
				}
			}
			return TRUE;
		}
		else
		{
			if(empty($data))
				return TRUE;
			else
				return FALSE;
		}
	}

	public function check($data)
	{
		if(!preg_match('/^[a-zA-Z0-9\_]{5,20}$/',$data['username']))
		{
			$msg['flag'] = FALSE;
			$msg['error'] ='用户名格式错误!保证用户名由5-20位的英文大/小写字母，数字或下划线组成!';
			return $msg;
		}
		else if(!preg_match('/^[a-zA-Z0-9\_]{6,20}$/',$data['password']))
		{
			$msg['flag'] = FALSE;
			$msg['error'] = '密码格式错误!保证用户名由6-20位的英文大/小写字母，数字或下划线组成！';
			return $msg;
		}
		else if($data['password'] != $data['confirm'])
		{
			$msg['flag'] = FALSE;
			$msg['error'] = '确认密码错误!请保证密码与确认密码相同!';
			return $msg;
		}
		else if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
		{
			$msg['flag'] = FALSE;
			$msg['error'] = '邮件格式错误!';
			return $msg;
		}

		$msg['flag'] = TRUE;
		return $msg;
	}

	public function insert($data)
	{
		// 数据预处理
		$data['password'] = md5($data['password']);
		unset($data['confirm']);
		$data['date'] = date('Y-m-d');
		$data['admin'] = TRUE;

		$query = $this->db->insert_string('users',$data);
		if($this->db->query($query))
			return TRUE;
		return FALSE;
	}

	public function account_update($username,$data)
	{
		$this->db->where('username',$username);
		$this->db->update('users',$data);
		return TRUE;
	}

	public function select_profile($username)
	{
		$this->db->select('full_name,location');
		$query = $this->db->get_where('users',array('username' => $username));
		$result = $query->result();
		return (array)$result[0];
	}

	public function select_email($username)
	{
		$this->db->select('email');
		$query = $this->db->get_where('users',array('username' => $username));
		$result = $query->result();
		return (array)$result[0];
	}

	public function select_ssh_key($username)
	{
		$this->db->select('ssh_key');
		$query = $this->db->get_where('users',array('username' => $username));
		$result = $query->result();
		return (array)$result[0];
	}

	public function signup($data = array())
	{
		if(preg_match('/^[a-zA-Z0-9\_]{5,20}$/',$data['username']) && preg_match('/^[a-zA-Z0-9\_]{6,20}$/',$data['password']))
		{
			$query = $this->db->query('SELECT username,password FROM users LIMIT 1');
			$result = $query->row_array();
			if($query->num_rows())
			{
				if(($result['username'] == $data['username']) && ($result['password'] == md5($data['password'])))
					$msg['flag'] = TRUE;
				else
				{
					$msg['flag'] = FALSE;
					$msg['remind']['error'] = '用户名密码不匹配!';
				}
			}
		}
		else
		{
			$msg['flag'] = FALSE;
			$msg['remind']['error'] = '用户名或密码格式错误!';
		}
		return $msg;
	}

	public function is_admin($username)
	{
			$query = $this->db->query('SELECT admin FROM users WHERE username="'.$username.'"'.'LIMIT 1');
		$result = $query->row_array();
		return $result['admin'];
	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
