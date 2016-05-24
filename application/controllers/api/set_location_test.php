<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Set_location_test extends CI_Controller {

	public function index()
	{
		die('not_allowed');
		log_message('debug', ' ******  set_location start  ******');
		log_message('debug', 'set_location $post parms: ' . json_encode($_POST));
		
		
		$user_id = $this->input->post('user_id');
		$user_id = intval($user_id); 
		$user_secret = $this->input->post('user_secret');
		
		$user_last_api_time = time();
		
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		//print_r($user_obj);
		
		log_message('debug', '$user_obj: : ' . json_encode($user_obj));
		
		$is_user_secret_valid = $this->users_model->is_user_secret_valid($user_obj,$user_secret);
		
		//if($is_user_secret_valid)
		if(true)
		{
			$this->load->model('location_payload_model');
			/*
			$fileName = $_FILES['location_data']['name'];
			$path  = $_FILES['location_data']['tmp_name'];
			$fileSize = $_FILES['location_data']['size'];
			$fileType = $_FILES['location_data']['type'];
			
			log_message('debug', '$fileName: ' . $fileName . ' ** $path: ' . $path . ' ** $fileSize: ' . $fileSize . ' ** $fileType: ' . $fileType);
			*/
			
			$location_list_tmp = urlencode('[{"long":33.1031565,"lat":36.2425521,"location_time":1407168060000,"location_accuracy":500,"cid":"21321ss","lac":"adsaf2d","mcc":"664SSf2","mnc":"77ghh5","is_wifi":"yes","wifi_obj_list":[{"wifi_bssid":"as ss","wifi_ssid":"osnet","wifi_capabilities":"asdf as fas asdf","wifi_frequency":321354},{"wifi_bssid":"w ass","wifi_ssid":"osnet2","wifi_capabilities":"11asdf as fas asdf","wifi_frequency":3214}],"is_bt":"yes","bt_obj_list":[{"bt_name":"btjjkks","bt_address":"321.165.1.15"},{"bt_name":"bt-s","bt_address":"31.165.1.15"}]}]');
			$location_obj_list =  urldecode($location_list_tmp);
			
			
			//$location_obj_list = gzfile ($path);
			
			log_message('debug', '$location_obj_list list: ' . $location_obj_list);
			
			//log_message('debug', 'sizeof($location_obj_list) from gzip: ' . sizeof($location_obj_list) );
			
			$location_obj_all_lines = '';
			
	
			$location_obj_list = json_decode($location_obj_list);
			
			
			log_message('debug', '$location_obj_list_size: ' . sizeof($location_obj_list));
			
			
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
					
					$new_location_payload_obj = $this->location_payload_model->save_obj($new_location_payload_obj);

					// todo - move to async crontab job with delay 
					//$this->location_payload_model->calc_distance_and_score_for_location($new_location_payload_obj);
				}
			}
			
			if(!isset($user_obj['is_distance_achievement']) || $user_obj['is_distance_achievement'] == null)
			{
				$user_obj['is_distance_achievement'] = 'no';
			}
			
			// update user zooz and last location
			//if($api_last_location_time > $user_obj['last_location_time'])
			if(false)
			{
				//$this->users_model->update_user_last_location($user_id,$api_last_location_time,$api_last_location_long,$api_last_location_lat,$user_last_api_time);
			}
			else
			{
				//$this->users_model->update_user_last_api_time($user_id,$user_last_api_time);
			}
			
			$user_obj = $this->users_model->get_obj_by_user_id($user_id);
			
			//$response['zooz'] = $user_obj['zooz_balance'];	
			//$response['distance'] = $user_obj['zooz_distance_balance'];
			//$response['is_distance_achievement'] = $user_obj['is_distance_achievement'];
			
			$response['message'] = 'success';
			
			//$this->location_payload_model->delete_old_obj();
			
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

