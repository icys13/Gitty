<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class Regist_status_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_status()
	{
		$this->db->select('status');
		$query = $this->db->get('regist_status');
		$result = $query->row_array();
		return $result['status'];
	}

	public function set_status($data)
	{
		$this->db->update('regist_status',$data);
	}
}

/* End of file regist_status_model.php */
/* Location: ./application/models/regist_status_model.php */
