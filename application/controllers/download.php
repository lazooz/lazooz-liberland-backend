<?php

class Download extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}

	
	
	public function index()
	{
	
		$this->load->helper('url');
		
		$url = $this->config->item('download_app_link_on_playstore');
		redirect($url, 'refresh');

	}
}