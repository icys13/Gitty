<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class Signup extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('session');
		$username = $this->session->userdata('username');

		if(isset($username) && $username != FALSE && $this->session->userdata('log_in'))
		{
			if($this->session->userdata('admin'))
				redirect(site_url('admin/index'));
			else
				redirect(site_url('ordinary/index'));
		}
	}

	public function index()
	{
		$data['username'] = $this->input->post('username');
		$data['password'] = $this->input->post('password');

		if(!$this->user_model->is_empty($data))
		{
			$msg = $this->user_model->signup($data);
			if($msg['flag'])
			{
				$sess = array(
					'username' => $data['username'],
					'log_in' => TRUE
				);
				$this->session->set_userdata($sess);

				if($this->user_model->is_admin($data['username']))
				{
					$this->session->set_userdata('admin',TRUE);
					redirect(site_url('admin/index'));
				}
				else
				{
					$this->session->set_userdata('admin',FALSE);
					redirect(site_url('ordinary/index'));
				}
			}
			else
			{
				$this->load->view('header');
				$this->load->view('base/signup',$msg['remind']);
				$this->load->view('footer');
			}
		}
		else
		{
			$this->load->view('header');
			$this->load->view('base/signup');
			$this->load->view('footer');
		}
	}
}

/* End file of signup,php */
/* Location:./application/controllers/signup.php */
