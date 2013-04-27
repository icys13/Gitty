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

	public function create_repo($data)
	{
		$data['create_date'] = date("Y-m-d");
		$data['update_date'] = $data['create_date'];
		$query1 = $this->db->insert_string('repositories',$data);
		$this->db->query($query1);
		$query2 = $this->db->insert_string('creates',array('username' => $data['creator'],'repo_name' => $data['repo_name']));
		$this->db->query($query2);
	}
}

/* End of file respository_model.php */
/* Location: ./application/models/respository_model.php */
