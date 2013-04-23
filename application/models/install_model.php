<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

/*
 *	安装阶段数据库操作模型
*/

class Install_model extends CI_Model {

	var $hostname;
	var $username;
	var $password;
	var $database;

	public function __construct()
	{
		parent::__construct();
		require_once(dirname(dirname(__FILE__)).'/config/database.php');
		$this->hostname = $db[$active_group]['hostname'];
		$this->username = $db[$active_group]['username'];
		$this->password = $db[$active_group]['password'];
		$this->database = $db[$active_group]['database'];
	}

	public function conf_check()
	{
		$con = @mysql_connect($this->hostname,$this->username,$this->password);
		if(!$con)
		{
			$msg['error']['remind'] = '<p>数据库连接失败:(</p><p>请检查数据库参数hostname,username,password是否正确配置!</p>';
			$msg['error']['cover'] = FALSE;
			$msg['flag']= FALSE;
			return $msg;
		}
		else
		{
			if(@mysql_select_db($this->database,$con))
			{
				$msg['error']['remind'] = "<p>数据库{$this->database}已经存在是否覆盖并安装?</p>";
				$msg['error']['cover'] = TRUE;
				$msg['flag'] = FALSE;
				mysql_close($con);
				return $msg;
			}
			else
			{
				$msg['flag'] =TRUE;
				mysql_close($con);
				return $msg;
			}
		}
	}

	public function is_exists()
	{
		$con = @mysql_connect($this->hostname,$this->username,$this->password);
		if(@mysql_select_db($this->database,$con))
		{
			mysql_close($con);
			return TRUE;
		}
		else
		{
			mysql_close($con);
			return FALSE;
		}
	}

	public function install()
	{
		$con = @mysql_connect($this->hostname,$this->username,$this->password);
		mysql_select_db($this->database,$con);
		$sql = 'adsf';
		if(@mysql_query($sql))
		{
			mysql_close($con);
			$msg['flag'] = TRUE;
			return $msg;
		}
		mysql_close($con);
		$msg['error']['remind'] = mysql_error();
		$msg['flag'] = FALSE;
		return $msg;
	}

	public function drop()
	{
		$con = @mysql_connect($this->hostname,$this->username,$this->password);
		mysql_query("DROP DATABASE {$this->database}");
	}
} 

/* End of file Install_model.php */
/* Location: ./application/controllers/install_model.php */
