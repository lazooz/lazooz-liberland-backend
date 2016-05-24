<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Set_user_profile extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
	}
	

	public function index()
	{
		
		log_message('debug', ' ******  set_user_pk start  ******');
		log_message('debug', 'set_user_pk $post parms: ' . json_encode($_POST));
		
		
		$user_id = $this->input->post('user_id');
		$user_id = intval($user_id); 
		$user_secret = $this->input->post('user_secret');
		
		$user_last_api_time = time();
		
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		//print_r($user_obj);
		
		$is_user_secret_valid = $this->users_model->is_user_secret_valid($user_obj,$user_secret);
		
		if($is_user_secret_valid)
		{
            $obj['user_id'] = 	$user_id;

            $obj['personName'] = $this->input->post('personName');
            $obj['personPhotoUrl']  = $this->input->post('personPhotoUrl');
            $obj['personGooglePlusProfile']   = $this->input->post('personGooglePlusProfile');
            $obj['email'] = $this->input->post('email');
            $obj['chatid'] = $this->input->post('chatid');

            $this->users_model->update_user_profile($user_id,$obj['personName'], $obj['personPhotoUrl'] , $obj['personGooglePlusProfile'] ,$obj['email'],$obj['chatid']);
			$response['message'] = 'success';
			

		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'set_user_pk $response: ' . json_encode($response));
		log_message('debug', ' ******  set_user_pk end  ******');
		
		die(json_encode($response));
	
	}
	

	
}

