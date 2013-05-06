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
			$msg['remind']['error'] = '<p>数据库连接失败:(</p><p>请检查数据库参数hostname,username,password是否正确配置!</p>';
			$msg['remind']['cover'] = FALSE;
			$msg['flag']= FALSE;
			return $msg;
		}
		else
		{
			if(@mysql_select_db($this->database,$con))
			{
				$msg['remind']['error'] = "<p>数据库{$this->database}已经存在是否覆盖并安装?</p>";
				$msg['remind']['cover'] = TRUE;
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

		// 创建数据库
		if(mysql_query("CREATE DATABASE {$this->database}",$con))
		{
			mysql_select_db($this->database,$con);

			// 创建用户表
			$user_sql = 'CREATE TABLE users(
					username VARCHAR(20) NOT NULL,
					email VARCHAR(50) NOT NULL,
					password VARCHAR(32) NOT NULL,
					full_name VARCHAR(20) NOT NULL ,
					ssh_key TEXT,
					date DATE NOT NULL,
					location VARCHAR(50) default NULL,
					img BLOB,
					img_type VARCHAR(10),
					admin BOOLEAN NOT NULL default FALSE,
					PRIMARY KEY (username)
				)';

			// 初始化管理员
			$user_sql_insert = 'INSERT INTO users (username,password,email,date,admin)VALUES("admin","'.md5('123456').'","admin@admin.com","'.date("Y-m-d").'",1)';

			// 创建项目表
			$res_sql = 'CREATE TABLE repositories(	
					repo_name VARCHAR(20) NOT NULL,
					owner VARCHAR(20) NOT NULL,
					creator VARCHAR(20) NOT NULL,
					description TEXT,
					create_date DATE NOT NULL,
					update_date DATE NOT NULL,
					PRIMARY KEY (repo_name,owner,creator),
					FOREIGN KEY (owner) REFERENCES users(username),
					FOREIGN KEY (creator) REFERENCES users(username)
				)';

			// 创建 注册检测表
			$regist_sql = 'CREATE TABLE regist_status(
				status BOOLEAN NOT NULL DEFAULT TRUE,
				PRIMARY KEY (status)
			)';

			// 初始化 regist_status 表
			$regist_sql_insert = 'INSERT INTO regist_status (status) VALUES (\'1\')';

			// 创建 fork 表
			$fork_sql = 'CREATE TABLE forks(
					username VARCHAR(20) NOT NULL,
					repo_name VARCHAR(20) NOT NULL,
					creator VARCHAR(20) NOT NULL,
					HEAD CHAR(40),
					PRIMARY KEY (username,repo_name),
					FOREIGN KEY (username) REFERENCES users(username),
					FOREIGN KEY (repo_name) REFERENCES repositories(repo_name),
					FOREIGN KEY (creator)  REFERENCES users(username)
				)';

			// 创建 create 表
			$create_sql = 'CREATE TABLE creates(
					username VARCHAR(20) NOT NULL,
					repo_name VARCHAR(20) NOT NULL,
					HEAD CHAR(40),
					PRIMARY KEY (username,repo_name),
					FOREIGN KEY (username) REFERENCES users(username),
					FOREIGN KEY (repo_name) REFERENCES repositories(repo_name)
				)';
			
			// 创建 participate 表
			$parti_sql = 'CREATE TABLE participates(
					username VARCHAR(20) NOT NULL,
					repo_name VARCHAR(20) NOT NULL,
					creator VARCHAR(20) NOT NULL,
					HEAD CHAR(40),
					PRIMARY KEY (username,repo_name),
					FOREIGN KEY (username) REFERENCES users(username),
					FOREIGN KEY (creator) REFERENCES users(username),
					FOREIGN KEY (repo_name) REFERENCES repositories(repo_name)
				)';

			// 创建 branches 表
			// 创建 tags 表
			// 创建 ommits 表
			$commits_sql = 'CREATE TABLE commits(
					username VARCHAR(20) NOT NULL,
					repo_name VARCHAR(20) NOT NULL,
					commit CHAR(40) NOT NULL,
					tree CHAR(40),
					parent CHAR(40),
					author VARCHAR(20),
					committer VARCHAR(20),
					date DATE,
					message TEXT,
					PRIMARY KEY (username,repo_name,commit)
				)';
			// 创建 trees 表
			// 创建 blobs 表
	//		if(!mkdir('./gitosis-conf'))
	//		{
	//			$msg['flag'] = FALSE;
	//			$msg['remind']['error'] = 'gitosis-conf 文件夹创建失败!	请检查相应权限!';
	//			return $msg;
	//		}
			if(mysql_query($user_sql) && mysql_query($res_sql) && mysql_query($fork_sql) && mysql_query($create_sql) && mysql_query($parti_sql) && mysql_query($regist_sql) && mysql_query($regist_sql_insert)  && mysql_query($user_sql_insert) && mysql_query($commits_sql))
			{
				mysql_close($con);
				$msg['flag'] = TRUE;
				return $msg;
			}
			else
			{
				$msg['remind']['error'] = mysql_error();
				mysql_close($con);
				$msg['flag'] = FALSE;
				return $msg;
			}
		}
		else
		{
			$msg['remind']['error'] = mysql_error();
			$msg['flag'] = FALSE;
			return $msg;
		}
	}

	public function drop()
	{
		$con = @mysql_connect($this->hostname,$this->username,$this->password);
		mysql_query("DROP DATABASE {$this->database}");
	}
} 

/* End of file Install_model.php */
/* Location: ./application/controllers/install_model.php */
