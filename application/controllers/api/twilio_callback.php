<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Twilio_callback extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
	}

	public function index()
	{
		log_message('debug', ' ******  twilio_callback start  ******');
		
		/*
		$this->load->model('sms_messages_model');
		
		//$this->sms_messages_model->get_sms_messages_from_twilio();
		$this->sms_messages_model->send_test_sms_to_twilio();
		*/
		
		log_message('debug', json_encode($_POST) . ' ** ' . json_encode($_GET));
		
		
		log_message('debug', ' ******  twilio_callback end  ******');
	}
}

