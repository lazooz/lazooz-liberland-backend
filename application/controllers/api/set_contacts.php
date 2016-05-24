<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Set_contacts extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
	}
	

	public function index()
	{
		
		log_message('debug', ' ******  set_contacts start  ******');
		log_message('debug', 'set_contacts $post parms: ' . json_encode($_POST));
		
		
		$user_id = $this->input->post('user_id');
		$user_id = intval($user_id); 
		$user_secret = $this->input->post('user_secret');
		
		$user_last_api_time = time();
		
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		//print_r($user_obj);
		
		//log_message('debug', '$user_obj: : ' . json_encode($user_obj));
		
		$is_user_secret_valid = $this->users_model->is_user_secret_valid($user_obj,$user_secret);
		
		if($is_user_secret_valid)
		{
			$this->load->model('contacts_model');
		
			
			$fileName = $_FILES['contacts_data']['name'];
			$path  = $_FILES['contacts_data']['tmp_name'];
			$fileSize = $_FILES['contacts_data']['size'];
			$fileType = $_FILES['contacts_data']['type'];
			
			log_message('debug', '$fileName: ' . $fileName . ' ** $path: ' . $path . ' ** $fileSize: ' . $fileSize . ' ** $fileType: ' . $fileType);
			
			$contacts_obj_list = gzfile ($path);
			
			$contacts_obj_all_lines = '';
			
			if(sizeof($contacts_obj_list) > 0)
			{
				foreach ($contacts_obj_list as $contacts_obj_tmp) 
				{
					$contacts_obj_all_lines .= $contacts_obj_tmp;
				}
			}
			
			$contacts_obj_list = $contacts_obj_all_lines;
			
			log_message('debug', '$contacts_obj_list list: ' . $contacts_obj_list);
			
			$contacts_obj_list = json_decode($contacts_obj_list);
			
			log_message('debug', '$contacts_obj_list_size: ' . sizeof($contacts_obj_list));
			
			$contacts_that_are_users = array();
			
			if (sizeof($contacts_obj_list) > 0)
			{
				foreach ($contacts_obj_list as $contacts_obj_tmp) 
				{
					$new_contact_obj = array();
					$new_contact_obj['user_id'] = $user_obj['_id'];
					$new_contact_obj['name'] = @$contacts_obj_tmp->name;
					
					$new_contact_obj['cellphone'] = @$contacts_obj_tmp->cellphone;
					$new_contact_obj['cellphone'] = $this->contacts_model->clean_cellphone($new_contact_obj['cellphone']);
					if(!isset($contacts_obj_tmp->cellphone_int))
					{
						$contacts_obj_tmp->cellphone_int = '';
					}
					
					$new_contact_obj['cellphone_int'] = @$contacts_obj_tmp->cellphone_int;
					$new_contact_obj['cellphone_int'] = $this->contacts_model->clean_cellphone($new_contact_obj['cellphone_int']);
					
					//save new obj
					$new_contact_obj = $this->contacts_model->save_obj($new_contact_obj);
					
					//find user with the same cellphone and save the link
					$contact_user_obj = $this->contacts_model->calc_and_save_contact_user_id($new_contact_obj);
					
					if($contact_user_obj['_id'] > 0)
					{
						// sends back the original number
						$contacts_that_are_users[] = @$contacts_obj_tmp->cellphone_int; 
					}
				}
			}
			
			$this->load->model('friend_recommend_requests_model');
			$recommended_contacts_that_arent_users = $this->friend_recommend_requests_model->get_recommended_contacts_that_arent_users_by_user($user_obj['_id']);
			
			$response['recommended_contacts_that_arent_users'] = $recommended_contacts_that_arent_users;
			
			
			$response['message'] = 'success';
			$response['contacts_that_are_users'] = $contacts_that_are_users;
			
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		log_message('debug', 'set_contacts $response: ' . json_encode($response));
		log_message('debug', ' ******  set_contacts end  ******');
		
		die(json_encode($response));
	
	}
}

