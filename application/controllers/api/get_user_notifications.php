<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Get_user_notifications extends CI_Controller {
	
	
	public function __construct()
	{
		parent::__construct();
	
	}
	
	public function index()
	{
		log_message('debug', ' ******  get_user_notifications start  ******');
		log_message('debug', 'get_user_notifications $post parms: ' . json_encode($_POST));
		
		$response = array();

		$user_id = $this->input->post('user_id');
		$user_id = intval($user_id);
		
		
		$user_secret = $this->input->post('user_secret');

		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);

		//print_r($user_obj);
		
		//log_message('debug', '$user_obj: : ' . json_encode($user_obj));
		
		$is_user_secret_valid = $this->users_model->is_user_secret_valid($user_obj,$user_secret);
		
		if($is_user_secret_valid)
		//if(true)//7777
		{
			
			$from_number = $this->input->post('from_number');
			$from_number = intval($from_number);
			
			
			$this->load->model('push_messages_model');
			$from_time = $user_obj['created_time'];
			
			
			
			$global_messages = $this->push_messages_model->get_global_messages($from_number,$from_time);
			
			$notifications = array();
			
			if(sizeof($global_messages) > 0)
			{
				foreach ($global_messages as $global_messages_tmp) 
				{
					$notification_tmp = array();
					$notification_tmp['num'] = $global_messages_tmp['_id'];
					$notification_tmp['title'] = $global_messages_tmp['title'];
					$notification_tmp['body'] = $global_messages_tmp['body'];
					$notification_tmp['type'] = $global_messages_tmp['type'];
					$notification_tmp['is_popup'] = $global_messages_tmp['is_popup'];
					$notification_tmp['is_notification'] = $global_messages_tmp['is_notification'];;
					$notifications[] = $notification_tmp;;
				}
				
			}
			
			$private_messages = $this->push_messages_model->get_private_messages($from_number,$user_obj['_id']);
			
			if(sizeof($private_messages) > 0)
			{
				foreach ($private_messages as $private_messages_tmp)
				{
					$notification_tmp = array();
					$notification_tmp['num'] = $private_messages_tmp['_id'];
					$notification_tmp['title'] = $private_messages_tmp['title'];
					$notification_tmp['body'] = $private_messages_tmp['body'];
					$notification_tmp['type'] = $private_messages_tmp['type'];
					$notification_tmp['is_popup'] = $private_messages_tmp['is_popup'];
					$notification_tmp['is_notification'] = $private_messages_tmp['is_notification'];;
					$notifications[] = $notification_tmp;;
				}
			
			}
			
			/*
			
			$notification_tmp = array();
			$notification_tmp['num'] = 1;
			$notification_tmp['title'] = 'Important 1';
			$notification_tmp['body'] = 'The body 1234';
			$notification_tmp['type'] = 'out_data';
			$notification_tmp['is_popup'] = 'yes';
			$notification_tmp['is_notification'] = 'yes';
			$notifications[] = $notification_tmp;
			
			$notification_tmp['num'] = 2;
			$notification_tmp['title'] = 'Important 2';
			$notification_tmp['body'] = 'The bofadsfady 1234';
			$notification_tmp['type'] = 'out_data';
			$notification_tmp['is_popup'] = 'no';
			$notification_tmp['is_notification'] = 'yes';
			$notifications[] = $notification_tmp;
			
			$notification_tmp['num'] = 3;
			$notification_tmp['title'] = 'Important 3';
			$notification_tmp['body'] = 'Tady 1234';
			$notification_tmp['type'] = 'out_data';
			$notification_tmp['is_popup'] = 'yes';
			$notification_tmp['is_notification'] = 'no';
			$notifications[] = $notification_tmp;
			
			$notification_tmp['num'] = 4;
			$notification_tmp['title'] = 'Important 4';
			$notification_tmp['body'] = 'Tass4';
			$notification_tmp['type'] = 'out_data';
			$notification_tmp['is_popup'] = 'yes';
			$notification_tmp['is_notification'] = 'yes';
			$notifications[] = $notification_tmp;
			*/
			$response['notifications'] = $notifications;

			$response['message'] = 'success';
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}

       // log_message('debug', 'get_user_notifications $response notif: ' . $notifications);
		//log_message('debug', 'get_user_notifications $response: ' . json_encode($response));
        log_message('debug', 'get_user_notifications oren $response: ' . json_encode($response,JSON_UNESCAPED_SLASHES));

        log_message('debug', 'get_user_notifications oren $response: ' . json_encode($response,JSON_UNESCAPED_SLASHES));

		log_message('debug', ' ******  get_user_notifications end  ******');
		
		die(json_encode($response));
		
		
	}
	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */