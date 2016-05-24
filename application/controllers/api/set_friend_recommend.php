<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Set_friend_recommend extends CI_Controller {

	public function index()
	{
		
		log_message('debug', ' ******  set_friend_recommend start  ******');
		log_message('debug', 'set_friend_recommend $post parms: ' . json_encode($_POST));
		
		
		$user_id = $this->input->post('user_id');
		$user_id = intval($user_id); 
		$user_secret = $this->input->post('user_secret');
		
		$user_last_api_time = time();
		
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		//print_r($user_obj);
		
		$is_user_secret_valid = $this->users_model->is_user_secret_valid($user_obj,$user_secret);
		
		if($is_user_secret_valid)
		//if(true)
		{
			$this->load->model('friend_recommend_requests_model');
			$this->load->model('sms_messages_model');
			
			$download_link = $this->config->item('download_app_link');
			
			$friend_recommend_request_list = $this->input->post('friend_recommend_request_list');
			$pesonal_message = $this->input->post('message');
			$friend_recommend_request_list = json_decode($friend_recommend_request_list);
			
			if(sizeof($friend_recommend_request_list) > 0)
			{
				foreach ($friend_recommend_request_list as $friend_recommend_request_tmp) 
				{
					$name = $friend_recommend_request_tmp->name;
					$cellphone = $friend_recommend_request_tmp->cellphone_int;
					
					$cellphone = str_replace('+','',$cellphone); 
					
					$friend_recommend_obj = $this->friend_recommend_requests_model->get_obj_by_recommending_user_id_and_cellphone($user_id,$cellphone);
					
					
					$send_recommendation_case = $this->friend_recommend_requests_model->get_send_recommendation_case($friend_recommend_obj);
					
					
					if($send_recommendation_case == 'create')
					{
						$friend_recommend_obj = $this->friend_recommend_requests_model->create_and_save_request($user_id,$name,$cellphone);
					}
					elseif ($send_recommendation_case == 'again')
					{
						$friend_recommend_obj = $this->friend_recommend_requests_model->update_for_send_request_again($friend_recommend_obj);
					}
					else 
					{
						$friend_recommend_obj = array();
						$friend_recommend_obj['save_message'] = 'recommedation_already_exists';
					}

					/*
					
					$is_send_recommendion = $this->friend_recommend_requests_model->is_send_recommendation($friend_recommend_obj);
					
					$is_recommending_obj_exists = $this->friend_recommend_requests_model->is_obj_exists_by_recommending_user_id_and_cellphone($user_id,$cellphone);
					
					if(!$is_recommending_obj_exists)
					{
						$friend_recommend_obj = $this->friend_recommend_requests_model->create_and_save_request($user_id,$name,$cellphone);
					}
					else 
					{
						$friend_recommend_obj = array();
						$friend_recommend_obj['save_message'] = 'recommedation_already_exists';
					}
					
					*/
					
					if($friend_recommend_obj['save_message'] != 'insert_success')
					{
						$response['message'] = $friend_recommend_obj['save_message'];
					}
					else
					{
						// send a new sms message
						$this->sms_messages_model->send_friends_recommend_sms($user_id,$name,$cellphone,$friend_recommend_obj['token'] . '-' . $friend_recommend_obj['_id'],$download_link,$pesonal_message);
							
					}
					
					
				}
			}
			
			
			$response['message'] = 'success';
			

		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'set_friend_recommend $response: ' . json_encode($response));
		log_message('debug', ' ******  set_friend_recommend end  ******');
		
		die(json_encode($response));
	
	}
	
	
	public function validation()
	{
	
		log_message('debug', ' ******  set_friend_recommend_validation start  ******');
		log_message('debug', 'set_friend_recommend_validation $post parms: ' . json_encode($_POST));
		
		$response = array();
		$response['message'] = 'error';
		
		
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
			
			$friend_request_token = $this->input->post('friend_request_token');

			$this->load->model('friend_recommend_requests_model');
		
			$validate_token_response = $this->friend_recommend_requests_model->validate_token($user_obj['_id'],$friend_request_token);
			
			
			if(isset($validate_token_response['recommending_user_id']))
			{
				$recommending_user_id = $validate_token_response['recommending_user_id'];
			}
			else 
			{
				$recommending_user_id = null;
			}
			
		
			if($validate_token_response['is_valid'] == 'valid')
			{
				
				// check if to validate recommending user
				if($recommending_user_id > 0)
				{
					$number_of_recommendation = $this->friend_recommend_requests_model->calc_number_of_recommendation_for_recommending_user($recommending_user_id);
					
					// check recommender activation_status
					if($number_of_recommendation >=3)
					{
						$user_recommending_obj = $this->users_model->get_obj_by_user_id($recommending_user_id);
						
						if($user_recommending_obj['_id'] > 0 && $user_recommending_obj['activation_status'] != 'activated')
						{
							$this->users_model->update_activation_status_to_activated($user_recommending_obj['_id']);
						}
						
					}
					
									
				}
				
			
				
				
				
				
				
					$response['message'] = 'success';
			}
			else 
			{
				$response['message'] = 'token_not_valid';
			}
		
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'set_friend_recommend_validation $response: ' . json_encode($response));
		log_message('debug', ' ******  register_validation end  ******');
		
		die(json_encode($response));
	}
	
}

