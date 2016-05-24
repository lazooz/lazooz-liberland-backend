<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Match_request extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->model('match_request_model'); 
	
	}
	

	public function index()
	{
		
		log_message('debug', ' ******  ask_match start  ******');
		log_message('debug', 'ask_match $post parms: ' . json_encode($_POST));

		
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
        $accept = $this->input->post('accept');
        if (($accept == "yes")||($accept == "no")) {
            $match_request_id = $this->input->post('match_request_id');
            $this->load->model('match_request_model');
            $CI =& get_instance();
            $CI->match_request_model->send_accept_message_for_match($match_request_id,$user_id,$accept);
        }
        else {
                $obj['user_id'] = $user_id;
                $obj['start_route_loc']['long'] = $this->input->post('source_long');
                $obj['start_route_loc']['lat'] = $this->input->post('source_lat');
                $obj['start_route_id'] = $this->input->post('source_id');
                $obj['dest_route_loc']['long'] = $this->input->post('dest_long');
                $obj['dest_route_loc']['lat'] = $this->input->post('dest_lat');
                $obj['dest_route_id'] = $this->input->post('dest_id');
                $obj['share_taxi'] = $this->input->post('share_taxi');
                $obj['share_car'] = $this->input->post('share_car');
                $obj['sport_team'] = $this->input->post('sportteam');
                log_message('debug', ' ******  ask_match start 223 ******');
                $this->load->model('match_request_model');
                $CI =& get_instance();
                log_message('debug', ' ******  ask_match start 222 ******');
                $CI->match_request_model->create_and_save_new_match_request($obj);
            }
		$response['message'] = 'success';
			

		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'ask_match $response: ' . json_encode($response));
		log_message('debug', ' ******  ask_match end  ******');
		
		die(json_encode($response));
	
	}
	

	
}

