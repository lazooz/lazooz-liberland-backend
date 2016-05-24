<?php

class Legal_and_privacy extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}

	// http://lazooz.b-buzzy.com:8080/legal_and_privacy
	// http://client.lazooz.org/legal_and_privacy
	
	public function index()
	{
	
		$this->load->helper('url');
		
		$url = $this->config->item('legal_and_privacy_url');
		redirect($url, 'refresh');
		
	}
}

