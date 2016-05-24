<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Get_user_info extends CI_Controller {
	
	
	// http://lazooz.b-buzzy.com:8080/api_get_users_location_near_me
	public function get_users_location_near_me()
	{
		log_message('debug', ' ******  get_users_location_near_me start  ******');
		log_message('debug', 'get_users_location_near_me $post parms: ' . json_encode($_POST));
		
		$response = array();
		
		$user_id = $this->input->post('user_id');
		$user_id = intval($user_id);
		//$user_id = 3;
		$user_secret = $this->input->post('user_secret');
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		//print_r($user_obj);
		
		//log_message('debug', '$user_obj: : ' . json_encode($user_obj));
		
		$is_user_secret_valid = $this->users_model->is_user_secret_valid($user_obj,$user_secret);
		
		if($is_user_secret_valid)
		//if(true)
		{
			
		
			$this->load->model('users_model');
			$obj_list = $this->users_model->get_all_user_locations_neer_me($user_id);
				
			$response['obj_list'] = $obj_list;
			$response['title'] = '';
			$response['message'] = 'success';
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'get_users_location_near_me $response: ' . json_encode($response));
		log_message('debug', ' ******  get_users_location_near_me end  ******');
		
		die(json_encode($response));
		
		
	}
	
	
	public function get_recommendation_data()
	{
	
		log_message('debug', ' ******  get_recommendation_data start  ******');
		log_message('debug', 'get_recommendation_data $post parms: ' . json_encode($_POST));
		
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
		{
			$this->load->model('friend_recommend_requests_model');
			
			$recommendation_obj_list = $this->friend_recommend_requests_model->get_obj_by_user($user_id);
			
			$recommendations = array();
			$recommendations_to_check = array();
			
			$num_pending = 0;
			$num_installed = 0;
			
			if(sizeof($recommendation_obj_list) > 0)
			{
				foreach ($recommendation_obj_list as $recommendation_obj_tmp) 
				{
					$recommendation = array();
					$recommendation['name'] = $recommendation_obj_tmp['name'];
					$recommendation['cellphone'] = $recommendation_obj_tmp['cellphone'];

					if(@$recommendation_obj_tmp['new_user_id'] > 0)
					{
						$recommendation['is_installed'] = 'yes';
						$num_installed++;
					}
					else 
					{
						$recommendation['is_installed'] = 'no';
						$num_pending++;
					}
					
					//$recommendations[$recommendation['cellphone']] = $recommendation;
					if(!isset($recommendations_to_check[$recommendation['cellphone']]))
					{
						$recommendations[] = $recommendation;
						$recommendations_to_check[$recommendation['cellphone']]  = 1;
					}
					
					
				}
				
			}
			
			
			$response['num_pending'] = $num_pending;
			$response['num_accepted'] = $num_installed;
			$response['contacts'] = $recommendations;
			$response['message'] = 'success';
			
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'get_recommendation_data $response: ' . json_encode($response));
		log_message('debug', ' ******  get_recommendation_data end  ******');
		
		die(json_encode($response));
	
	}
	
	
	public function get_contacts_data()
	{
	
		log_message('debug', ' ******  get_contacts_data start  ******');
		log_message('debug', 'get_contacts_data $post parms: ' . json_encode($_POST));
		
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
		{
			$this->load->model('contacts_model');
			
			$contact_obj_list = $this->contacts_model->get_obj_by_user($user_id);

			$contacts = array();
			
			if(sizeof($contact_obj_list) > 0)
			{
				foreach ($contact_obj_list as $contact_obj_tmp) 
				{
					$contact = array();
					$contact['name'] = $contact_obj_tmp['name'];
					$contact['cellphone'] = $contact_obj_tmp['cellphone'];
					$contact['cellphone_int'] = $contact_obj_tmp['cellphone_int'];
					//$contact['contact_user_id'] = @$contact_obj_tmp['contact_user_id'];
					
					if(@$contact_obj_tmp['contact_user_id'] > 0)
					{
						$contact['is_installed'] = 'yes';
					}
					else 
					{
						$contact['is_installed'] = 'no';
					}
					
					$contacts[] = $contact;
				}
				
			}
			
			
			$response['contacts'] = $contacts;
			$response['message'] = 'success';
			
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'get_contacts_data $response: ' . json_encode($response));
		log_message('debug', ' ******  get_contacts_data end  ******');
		
		die(json_encode($response));
	
	}
	
	// http://lazooz.b-buzzy.com:8080/api_get_user_stat_data_mined_distance
	public function get_user_stat_data_mined_distance()
	{
	
		log_message('debug', ' ******  get_user_stat_data_mined_distance start  ******');
		log_message('debug', 'get_user_stat_data_mined_distance $post parms: ' . json_encode($_POST));
	
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
			
			$this->load->model('stats_users_day_model');
			
			$stats_data_days_array = $this->stats_users_day_model->get_all_data_array($user_id);
			
			$stats_data_days = $stats_data_days_array['obj_list_out'];
			$initial_date = $stats_data_days_array['initial_date'];
			
			$response['stats_data_days_user'] = $stats_data_days;
			$response['initial_date_user'] = $initial_date;

			$this->load->model('stats_all_users_day_model');
				
			$stats_data_days_array_all_users = $this->stats_all_users_day_model->get_all_data_array($user_id);
				
			$stats_data_days_all_users = $stats_data_days_array_all_users['obj_list_out'];
			$initial_date_all_users = $stats_data_days_array_all_users['initial_date'];
				
			$response['stats_data_days_all_users'] = $stats_data_days_all_users;
			$response['initial_date_all_users'] = $initial_date_all_users;
			
			
			/*
			$week_to_date_data = $this->stats_users_day_model->get_week_to_date_data($user_id);
			
			$response['stats_data_week_total'] = $week_to_date_data['total_distance'];
			$response['stats_data_week'] = $week_to_date_data['obj_list_out'];
			
			
			$month_to_date_data = $this->stats_users_day_model->get_month_to_date_data($user_id);
				
			$response['stats_data_month_total'] = $month_to_date_data['total_distance'];
			$response['stats_data_month'] = $month_to_date_data['obj_list_out'];
			
			
			$this->load->model('stats_users_month_model');
			$year_to_date_data = $this->stats_users_month_model->get_year_to_date_data($user_id);
			
			$response['stats_data_year_total'] = $year_to_date_data['total_distance'];
			$response['stats_data_year'] = $year_to_date_data['obj_list_out'];
			*/
		
	
			$response['message'] = 'success';
				
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
	
	
		log_message('debug', 'get_user_stat_data_mined_distance $response: ' . json_encode($response));
		log_message('debug', ' ******  get_user_stat_data_mined_distance end  ******');
	
		die(json_encode($response));
	
	}
	
	/*
	public function get_user_stat_data()
	{
	
		log_message('debug', ' ******  get_user_stat_data start  ******');
		log_message('debug', 'get_user_stat_data $post parms: ' . json_encode($_POST));
		
		$response = array();
		
		$user_id = $this->input->post('user_id');
		$user_id = intval($user_id); 
		$user_secret = $this->input->post('user_secret');
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		
		$is_user_secret_valid = $this->users_model->is_user_secret_valid($user_obj,$user_secret);
		
		if($is_user_secret_valid)
		{
			$stats_data = array();
			
			$stats_tmp = array();
			$stats_tmp['time'] = '2014-07-10 00:00:00';
			$stats_tmp['distance'] = 32135454;
			$stats_tmp['zooz'] = 321;
			$stats_data[] = $stats_tmp;
			
			$stats_tmp['time'] = '2014-07-11 00:00:00';
			$stats_tmp['distance'] = 42135454;
			$stats_tmp['zooz'] = 421;
			$stats_data[] = $stats_tmp;
			
			$stats_tmp['time'] = '2014-07-12 00:00:00';
			$stats_tmp['distance'] = 52135454;
			$stats_tmp['zooz'] = 521;
			$stats_data[] = $stats_tmp;
			
			$response['stats_data'] = $stats_data;
			$response['message'] = 'success';
			
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'get_user_stat_data $response: ' . json_encode($response));
		log_message('debug', ' ******  get_user_stat_data end  ******');
		
		die(json_encode($response));
	
	}
	
	*/
	// http://lazooz.b-buzzy.com:8080/api_get_user_stat_data_miners
	public function get_user_stat_data_miners()
	{
	//{"initial_date":20140930,"stats_data":[{"day":1,"count":2},{"day":2,"count":2}],"message":"success"}
		log_message('debug', ' ******  get_user_stat_data_miners start  ******');
		log_message('debug', 'get_user_stat_data_miners $post parms: ' . json_encode($_POST));
	
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
			
			
			
			$data = $this->users_model->get_users_count_for_get_user_stat_data_miners();
			
		//	print_r($data);
			
		/*
			$this->load->model('stats_total_users_day_model');
			
			$data = $this->stats_total_users_day_model->get_all_data();
			*/
			$response['initial_date'] = $data['initial_date'];
			$response['stats_data'] = $data['obj_list_out'];
			
			$response['message'] = 'success';
			
			
			
			
			
			
			/*
			
			
			$stats_data = array();
				
			$stats_tmp = array();

			$stats_tmp['month'] = '3';
			$stats_tmp['count'] = 233;
			$stats_data[] = $stats_tmp;
			
			$stats_tmp['month'] = '4';
			$stats_tmp['count'] = 1821;
			$stats_data[] = $stats_tmp;
			
			$stats_tmp['month'] = '5';
			$stats_tmp['count'] = 5911;
			$stats_data[] = $stats_tmp;
			
			$stats_tmp['month'] = '6';
			$stats_tmp['count'] = 12697;
			$stats_data[] = $stats_tmp;
			
			$stats_tmp['month'] = '7';
			$stats_tmp['count'] = 35971;
			$stats_data[] = $stats_tmp;
			
			$stats_tmp['month'] = '8';
			$stats_tmp['count'] = 83699;
			$stats_data[] = $stats_tmp;
				
				
			$response['stats_data'] = $stats_data;
			$response['total_miners'] = 83699;
			$response['message'] = 'success';
			*/
				
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
	
	
		log_message('debug', 'get_user_stat_data_miners $response: ' . json_encode($response));
		log_message('debug', ' ******  get_user_stat_data_miners end  ******');
	
		die(json_encode($response));
	
	}
	
	
	

	public function get_user_key_data()
	{
		
		log_message('debug', ' ******  get_user_key_data start  ******');
		log_message('debug', 'get_user_key_data $post parms: ' . json_encode($_POST));
		
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
		//	if(true)
		{
			
			if(!isset($user_obj['is_distance_achievement']) || $user_obj['is_distance_achievement'] == null)
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
			
			if(!isset($user_obj['is_distance_achievement']))
			{
				$user_obj['is_distance_achievement'] = 'no';
			}
			
			$this->load->model('friend_recommend_requests_model');
			$recommendation_obj_list = $this->friend_recommend_requests_model->get_obj_by_user($user_id);
			
			
			
			$response['potential_zooz_balance'] = $user_obj['zooz_balance'];
			$response['zooz_balance'] = 0;
			
			if(!isset($user_obj['zooz_balance']))
			{
				$user_obj['zooz_balance'] = 0;
			}
			else 
			
			
			$response['zooz_distance_balance'] = $user_obj['zooz_distance_balance'];
			$response['is_distance_achievement'] = $user_obj['is_distance_achievement'];
			$response['num_shaked_users'] = 0;
			$response['num_invited_contacts'] = sizeof($recommendation_obj_list);
			$response['user_id'] = $user_id;
			$response['wallet_num'] = 123;
			
			
			//$response['zooz_to_dolar_conversion_rate'] = $this->config->item('zooz_to_dolar_conversion_rate');
			
			$this->load->model('client_const_data_model');
			$zooz_to_dolar_conversion_rate = $this->client_const_data_model->get_value_by_key('zooz_to_dolar_conversion_rate');
			
			$response['zooz_to_dolar_conversion_rate'] = $zooz_to_dolar_conversion_rate;
			
			
			$critical_mass_tab = $this->client_const_data_model->get_value_by_key('critical_mass_tab');
			$response['critical_mass_tab'] = $critical_mass_tab;
			
			
			$response['message'] = 'success';
			
			/*
			include '../application/config/server_ver.php';
			$response['server_version'] = $server_version;
			*/
			
			$this->load->model('client_const_data_model');
			$server_version = $this->client_const_data_model->get_value_by_key('server_version');
			
			$response['server_version'] =$server_version;
			
			
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'get_user_key_data $response: ' . json_encode($response));
		log_message('debug', ' ******  get_user_key_data end  ******');
		
		die(json_encode($response));
	
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */