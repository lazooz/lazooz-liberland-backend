<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_login extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
	}
	

	public function index()
	{
		session_start();
		
		$admin_pass = $this->input->post('admin_pass');
		
		if($admin_pass == 'gotit9?')
		{
			$_SESSION['is_admin_loggedin'] = 'yes';
		}
		else 
		{
			sleep(2);
		}
		
		
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
			$this->load->helper('url');
			redirect('/admin_menu', 'refresh');
		}
		else 
		{
			$data['title'] = 'home';
			
			
			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_login', $data);
			
			$this->load->view('templates/footer', $data);
				
		}
		
	
	}
}

