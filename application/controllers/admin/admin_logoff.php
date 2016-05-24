<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_logoff extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
	}
	

	public function index()
	{
		session_start();
		$_SESSION['is_admin_loggedin'] = 'no';
		
		$this->load->helper('url');
		redirect('/admin_login', 'refresh');
	}
}

