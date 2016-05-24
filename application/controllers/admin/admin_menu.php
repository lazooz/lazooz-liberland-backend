<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_menu extends CI_Controller {
	
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
			$data['title'] = 'menu';
				
				
			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_menu', $data);
				
			$this->load->view('templates/footer', $data);
			
		}
		else 
		{
			
			
			$this->load->helper('url');
			redirect('/admin_login', 'refresh');
				
		}
		
	
	}
}

