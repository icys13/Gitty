<?php if( ! defined('BASEPATH')) exit('No direct script access allowed!');

class Repository_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}	

	public function select_creates($username)
	{
		$this->db->select('username,repo_name');
		$query = $this->db->get_where('creates',array('username' => $username));
		$temp = $query->result_array();
		$result = array();

		foreach($temp as $item)
		{
			$this->db->select('repo_name,creator,description,create_date,update_date');
			$query = $this->db->get_where('repositories',array('repo_name' => $item['repo_name'],'creator' => $item['username'],'owner' => $item['username']));
			$result[] = $query->row_array();
		}
		return $result;
	}

	public function select_forks($username)
	{
		$this->db->select('repo_name,username');
		$query = $this->db->get_where('forks',array('username' => $username));
		$temp = $query->result_array();
		$result = array();

		foreach($temp as $item)
		{
			$this->db->select('repo_name,creator,owner,description,create_date,update_date');
			$query = $this->db->get_where('repositories',array('repo_name' => $item['repo_name'],'owner' => $item['username']));
			$result[] = $query->row_array();
		}
		return $result;
	}

	public function select_participates($username)
	{
		$this->db->select('repo_name,username');
		$query = $this->db->get_where('participates',array('username' => $username));
		$temp = $query->result_array();
		$result = array();

		foreach($temp as $item)
		{
			$this->db->select('repo_name,creator,description,create_date,update_date');	
			$query = $this->db->get_where('repositories',array('repo_name' => $item['repo_name'],'owner' => $item['username']));
			$result[] = $query->row_array();
		}
		return $result;
	}

	public function search($keyword)
	{
		// 模糊查询创建的项目
		$this->db->like(array('repo_name' => $keyword));
		$this->db->select('repo_name,username');
		$query = $this->db->get('creates');
		$result['creates'] = $query->result_array();

		// 模糊查询克隆的项目
		$this->db->like(array('repo_name' => $keyword));
		$this->db->select('repo_name,username,creator');
		$query = $this->db->get('forks');
		$result['forks'] = $query->result_array();

		// 模糊查询参与项目
		$this->db->like(array('repo_name' => $keyword));
		$this->db->select('repo_name,username,creator');
		$result['participates'] = $query->result_array();

		return $result;
	}

	public function create_repo($data)
	{
		// 插入数据到数据库
		$data['create_date'] = date("Y-m-d");
		$data['update_date'] = $data['create_date'];
		$query1 = $this->db->insert_string('repositories',$data);
		$this->db->query($query1);
		$query2 = $this->db->insert_string('creates',array('username' => $data['creator'],'repo_name' => $data['repo_name']));
		$this->db->query($query2);

		// 在 server 上创建相应的裸仓库 
		// 在 server 上进行相应的配置
		// 修改 gitosis.conf 文件
		$this->load->helper('file');
		$tmp = "\n[group ".strtotime("now")."]\nmembers = ".$data['creator']."\nwritable = ".$data['creator'].'@'.$data['repo_name']."\n";
		write_file('./gitosis-conf/gitosis-admin/gitosis.conf',$tmp,'a+');
	}
}

/* End of file respository_model.php */
/* Location: ./application/models/respository_model.php */
