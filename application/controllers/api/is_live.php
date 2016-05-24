<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Is_live extends CI_Controller {
	
	
	public function __construct()
	{
		parent::__construct();
	
	}
	
	public function index()
	{
		log_message('debug', ' ******  is_live start  ******');
		log_message('debug', 'is_live $post parms: ' . json_encode($_POST));
		
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
		//if(true)
		{

			//"network_location":"{\"long\":34.9746455,\"location_accuracy\":2004,\"lat\":32.7925533}"}

			$network_location = $this->input->post('network_location');
			$network_location = json_decode($network_location);

			if(isset($network_location->long))
			{
				$long = $network_location->long;
			}
			else
			{
				$long = null;
			}

			if(isset($network_location->lat))
			{
				$lat = $network_location->lat;
			}
			else
			{
				$lat = null;
			}


			if(isset($network_location->location_accuracy))
			{
				$location_accuracy = $network_location->location_accuracy;
			}
			else
			{
				$location_accuracy = null;
			}


			$client_build_num = (string) $this->input->post('android_build_num');

            $match_request_id = (string) $this->input->post('match_request_id');



			log_message('debug', 'is_live $client_build_num: ' . $client_build_num .
					' ** $lat: ' . $lat . ' ** $long: ' . $long . ' ** $location_accuracy: ' . $location_accuracy);

			$this->users_model->update_user_data_for_is_live($user_obj['_id'],time(),$client_build_num,$lat,$long,$location_accuracy);


            $users_around_me = $this->users_model->get_a_list_of_users_near_me($user_obj['_id'],1000*100,1);
            $users_around_me_for_ride_sharing = $this->users_model->get_a_list_of_users_near_me($user_obj['_id'],1000*3,1);


            if ($users_around_me !=null)
                $users_around_me_count['100_KM'] = $users_around_me->count();
            else
                $users_around_me_count['100_KM'] = 0;

            if ($users_around_me_for_ride_sharing !=null)
                $users_around_me_count['3_KM'] = $users_around_me_for_ride_sharing->count();
            else
                $users_around_me_count['3_KM'] = 0;

            $response['usersAroundMe'] = $users_around_me_count;//['100_KM']+":"+$users_around_me_count['3_KM'];



            $this->load->model('push_messages_model');

			$current_notification_num = $this->push_messages_model->get_last_notification_id();

			$response['current_notification_num'] = $current_notification_num;


			$this->load->model('client_const_data_model');

			$min_build_num = $this->client_const_data_model->get_value_by_key('client_min_build_num');
			$response['min_build_num'] = $min_build_num;

			$current_build_num = $this->client_const_data_model->get_value_by_key('client_current_build_num');
			$response['current_build_num'] = $current_build_num;

            if ($match_request_id !="") {
                $this->load->model('match_request_model');
                $response['MatchAccepted'] = $this->match_request_model->is_match_accepted($match_request_id, 0);
            }
            else {
                $this->load->model('match_request_model');
                $response['MatchAccepted'] = $this->match_request_model->is_match_accepted(0,$user_id);
            }

			
			$response['message'] = 'success';
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'is_live $response: ' . json_encode($response));
		log_message('debug', ' ******  is_live end  ******');
		
		die(json_encode($response));
		
		
	}
	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */