<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_show_suspicious_users extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
	}
	

	public function index()
	{
		session_start();
		
		
		if(!isset($_SESSION['is_admin_loggedin']))
		{
			$is_admin_loggedin = 'no';
		}
		else 
		{
			$is_admin_loggedin = $_SESSION['is_admin_loggedin'];
		}
		

		if($is_admin_loggedin == 'yes')
		{
			$data = array();
			
			$data['title'] = 'admin_show_suspicious_users';

			
			$this->load->model('suspicious_users_model');
			$obj_list = $this->suspicious_users_model->get_all_obj_for_admin_screen();
			
			$data['obj_list'] = $obj_list;
			//print_r($obj_list);
				
			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_show_suspicious_users', $data);
			$this->load->view('templates/footer', $data);
			
		}
		else 
		{
			
			
			$this->load->helper('url');
			redirect('/admin_login', 'refresh');
				
		}
		
	
	}
}

