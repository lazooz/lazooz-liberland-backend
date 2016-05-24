<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Set_location extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		
		log_message('debug', ' ******  set_location start  ******');
		log_message('debug', 'set_location $post parms: ' . json_encode($_POST));
		
		
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
			$this->load->model('location_payload_model');
			
			if(isset($_FILES['location_data']))
			{
				$fileName = $_FILES['location_data']['name'];
				$path  = $_FILES['location_data']['tmp_name'];
				$fileSize = $_FILES['location_data']['size'];
				$fileType = $_FILES['location_data']['type'];
					
				log_message('debug', '$fileName: ' . $fileName . ' ** $path: ' . $path . ' ** $fileSize: ' . $fileSize . ' ** $fileType: ' . $fileType);
					
				$location_obj_list = gzfile ($path);
				
				$location_obj_all_lines = '';
					
				if(sizeof($location_obj_list) > 0)
				{
					foreach ($location_obj_list as $location_obj_tmp)
					{
						$location_obj_all_lines .= $location_obj_tmp;
					}
				}
					
				$location_obj_list = $location_obj_all_lines;
					
				log_message('debug', '$location_obj_list list: ' . $location_obj_list);
					
				
				$location_obj_list = json_decode($location_obj_list);
				
				
			}
			else 
			{
				$location_obj_list = json_decode(urldecode($this->input->post('location_list')));
			}
			
			
			//print_r($location_obj_list);die;
			
			//log_message('debug', '$location_obj_list list: ' . json_encode($location_obj_list));
			
			//log_message('debug', 'sizeof($location_obj_list) from gzip: ' . sizeof($location_obj_list) );
			
		
			
			
			log_message('debug', '$location_obj_list_size: ' . sizeof($location_obj_list));
			
			
			if(!isset($user_obj['last_location_longitude']))
			{
				$user_obj['last_location_longitude'] = null;
			}
			
			if(!isset($user_obj['last_location_latitude']))
			{
				$user_obj['last_location_latitude'] = null;
			}
			
			if(!isset($user_obj['last_location_time']))
			{
				$user_obj['last_location_time'] = null;
			}

			$api_last_location_long = @$user_obj['last_location_longitude'];
			$api_last_location_lat = @$user_obj['last_location_latitude'];
			$api_last_location_time = @$user_obj['last_location_time'];
			
			if (sizeof($location_obj_list) > 0)
			{
				foreach ($location_obj_list as $location_obj_tmp) 
				{
					$new_location_payload_obj = array();
					$new_location_payload_obj['user_id'] = $user_obj['_id'];
					
					$location_long = $location_obj_tmp->long;
					$location_lat = $location_obj_tmp->lat;
					
					$location_timestamp = round($location_obj_tmp->location_time); // reported in miliseconds from client

					if($location_timestamp > $api_last_location_time)
					{
						$api_last_location_time = $location_timestamp;
						$api_last_location_long = $location_long;
						$api_last_location_lat = $location_lat;
					}
                    elseif ($location_timestamp == $api_last_location_time)
                    {
                      continue; //Ignore it..
                    }

					$location_accuracy = $location_obj_tmp->location_accuracy;
					
					if(isset($location_obj_tmp->cid))
					{
						$telephony_cid = $location_obj_tmp->cid;
					}
					else 
					{
						$telephony_cid = null;
					}
					
					
					if(isset($location_obj_tmp->lac))
					{
						$telephony_lac = $location_obj_tmp->lac;
					}
					else 
					{
						$telephony_lac = null;
					}					

					
					if(isset($location_obj_tmp->mcc))
					{
						$telephony_mcc = $location_obj_tmp->mcc;
					}
					else 
					{
						$telephony_mcc = null;
					}					
					
					
					if(isset($location_obj_tmp->mnc))
					{
						$telephony_mnc = $location_obj_tmp->mnc;
					}
					else 
					{
						$telephony_mnc = null;
					}					
					
					
					$new_location_payload_obj['loc_accuracy'] = $location_accuracy;
					$new_location_payload_obj['loc_timestamp'] = $location_timestamp;
					
					
					$new_location_payload_obj['loc'] = array(); 
					$new_location_payload_obj['loc']['type'] = 'Point';
					$new_location_payload_obj['loc']['coordinates'] = array();
					$new_location_payload_obj['loc']['coordinates'][] = $location_long;
					$new_location_payload_obj['loc']['coordinates'][] = $location_lat;
					
					
					
					$new_location_payload_obj['tel_cid'] = $telephony_cid;
					$new_location_payload_obj['tel_lac'] = $telephony_lac;
					$new_location_payload_obj['tel_mcc'] = $telephony_mcc;
					$new_location_payload_obj['tel_mnc'] = $telephony_mnc;
					
					$wifi_is_wifi_data = @$location_obj_tmp->is_wifi; // yes / no
					
					$new_location_payload_obj['is_wifi'] = $wifi_is_wifi_data;
					
					if($wifi_is_wifi_data == 'yes')
					{
						$new_location_payload_obj['wifi'] = array();
						
						$wifi_obj_list = @$location_obj_tmp->wifi_obj_list;
						 
						if(sizeof($wifi_obj_list) > 0)
						{
							foreach ($wifi_obj_list as $wifi_obj_tmp) 
							{
								if(isset($wifi_obj_tmp->wifi_bssid))
								{
									$wifi_bssid = $wifi_obj_tmp->wifi_bssid;
								}
								else
								{
									$wifi_bssid = null;
								}
								
								if(isset($wifi_obj_tmp->wifi_ssid))
								{
									$wifi_ssid = $wifi_obj_tmp->wifi_ssid;;
								}
								else
								{
									$wifi_ssid = null;
								}
								
								if(isset($wifi_obj_tmp->wifi_capabilities))
								{
									$wifi_capabilities = $wifi_obj_tmp->wifi_capabilities;
								}
								else
								{
									$wifi_capabilities = null;
								}
								
								
								if(isset($wifi_obj_tmp->wifi_frequency))
								{
									$wifi_frequency = $wifi_obj_tmp->wifi_frequency;;
								}
								else
								{
									$wifi_frequency = null;
								}
								
								$wifi_obj = array();
								$wifi_obj['bssid'] = $wifi_bssid;
								$wifi_obj['ssid'] = $wifi_ssid;
								$wifi_obj['capabilities'] = $wifi_capabilities;
								$wifi_obj['frequency'] = $wifi_frequency;
								
								$new_location_payload_obj['wifi'][] = $wifi_obj; 
								
							}
						}
						
					}
					
					$bt_is_bt_data = @$location_obj_tmp->is_bt; // yes / no
					
					$new_location_payload_obj['is_bt'] = $bt_is_bt_data;
					
					if($bt_is_bt_data == 'yes')
					{
						$new_location_payload_obj['bt'] = array();
						
						$bt_obj_list = @$location_obj_tmp->bt_obj_list;
						 
						if(sizeof($bt_obj_list) > 0)
						{
							foreach ($bt_obj_list as $bt_obj_tmp) 
							{
								if(isset($bt_obj_tmp->bt_name))
								{
									$bt_name = $bt_obj_tmp->bt_name;
								}
								else
								{
									$bt_name = null;
								}
								
								if(isset($bt_obj_tmp->bt_address))
								{
									$bt_address = $bt_obj_tmp->bt_address;
								}
								else
								{
									$bt_address = null; 
								}
								
								$bt_obj = array();
								$bt_obj['name'] = $bt_name;
								$bt_obj['address'] = $bt_address;
								
								$new_location_payload_obj['bt'][] = $bt_obj;
								
							}
						}
					}
					
					if(isset($location_obj_tmp->route))
					{
						$new_location_payload_obj['route'] = $location_obj_tmp->route; 
					}
					else 
					{
						$new_location_payload_obj['route'] = 0;
					}	
									
					
					$new_location_payload_obj = $this->location_payload_model->save_obj($new_location_payload_obj);

					// todo - move to async crontab job with delay 
					$this->location_payload_model->calc_distance_and_score_for_location($new_location_payload_obj);
				}//foreach ($location_obj_list as $location_obj_tmp)
			}
			
			if(!isset($user_obj['is_distance_achievement']) || $user_obj['is_distance_achievement'] == null)
			{
				$user_obj['is_distance_achievement'] = 'no';
			}
			
			
			if(!isset($user_obj['last_location_time']))
			{
				$user_obj['last_location_time'] = null;
			}
			
			// update user zooz and last location
			if($api_last_location_time > $user_obj['last_location_time'])
			{
				$this->users_model->update_user_last_location($user_id,$api_last_location_time,$api_last_location_long,$api_last_location_lat,$user_last_api_time);
			}
			else
			{
				$this->users_model->update_user_last_api_time($user_id,$user_last_api_time);
			}


			$user_obj = $this->users_model->get_obj_by_user_id($user_id);


			
			if(!isset($user_obj['zooz_balance']))
			{
				$user_obj['zooz_balance'] = 0;
			}
			
			if(!isset($user_obj['zooz_distance_balance']))
			{
				$user_obj['zooz_distance_balance'] = 0;
			}
			
			if(!isset($user_obj['is_distance_achievement']))
			{
				$user_obj['is_distance_achievement'] = 'no';
			}
			
			if(!isset($user_obj['zooz_balance']))
			{
				$user_obj['zooz_balance'] = 0;
			}
			
			if(!isset($user_obj['zooz_distance_balance']))
			{
				$user_obj['zooz_distance_balance'] = 0;
			}
			
			$response['zooz'] = 0;	
			$response['potential_zooz_balance'] = $user_obj['zooz_balance'];
			
			$response['distance'] = $user_obj['zooz_distance_balance'];
			$response['is_distance_achievement'] = $user_obj['is_distance_achievement'];

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
			
			$response['message'] = 'success';
			
			//todo move to batch job
			$this->location_payload_model->delete_old_obj();
			
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		log_message('debug', 'set_location $response: ' . json_encode($response));
		log_message('debug', ' ******  set_location end  ******');
		
		die(json_encode($response));
	
	}
}

