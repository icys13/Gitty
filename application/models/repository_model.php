<?php if( ! defined('BASEPATH')) exit('No direct script access allowed!');

class Repository_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}	

	public function select_creates($username)
	{
		$this->db->select('repo_name');
		$query = $this->db->get_where('creates',array('username' => $username));
		return $query->result_array();
	}

	public function select_forks($username)
	{
		$this->db->select('repo_name');
		$query = $this->db->get_where('forks',array('username' => $username));
		return $query->result_array();
	}

	public function select_participates($username)
	{
		$this->db->select('repo_name');
		$query = $this->db->get_where('participates',array('username' => $username));
		return $query->result_array();
	}
}

/* End of file respository_model.php */
/* Location: ./application/models/respository_model.php */
