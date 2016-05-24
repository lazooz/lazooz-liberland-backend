<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Get_all_users_locations extends CI_Controller {
	// http://lazooz.b-buzzy.com:8080/api_get_all_users_locations
	public function index()
	{
		
		log_message('debug', ' ******  get_all_users_locations start  ******');
		log_message('debug', 'get_all_users_locations $post parms: ' . json_encode($_POST));
		
		
		$server_token = $this->input->post('server_token');

		$config_server_token = $this->config->item('server_token');
		header("Access-Control-Allow-Origin: http://lazooz.org"); 
		//if($config_server_token == $server_token)
		if(true)
		{
			$this->load->model('users_model');
			$obj_list = $this->users_model->get_all_user_locations();
			
			$response = array();
			$response['type'] = 'FeatureCollection';
			
			$features = array();
			if(sizeof($obj_list) > 0)
			{
				$id = 0;
				foreach ($obj_list as $obj_tmp)
				{
					if(!isset($obj_tmp['last_location_longitude']))
					{
						$obj_tmp['last_location_longitude'] = null;
					}
					
					if(!isset($obj_tmp['last_location_latitude']))
					{
						$obj_tmp['last_location_latitude'] = null;
					}
					
					if($obj_tmp['last_location_longitude'] != null && $obj_tmp['last_location_latitude'] != null)
					{
						$feature_tmp = array();
						$feature_tmp['type'] = 'Feature';
						$feature_tmp['id'] = $id;
							
						$geometry = array();
						$geometry['type'] = 'Point';
							
						$coordinates = array();
						$coordinates[] = $obj_tmp['last_location_longitude'];
						$coordinates[] = $obj_tmp['last_location_latitude'];
							
						$geometry['coordinates'] = $coordinates;
							
						$feature_tmp['geometry'] = $geometry;
							
						$features[] = $feature_tmp;
							
						$id++;
					}
					
				}
				
				
				
				
			}
			
			
			$response['features'] = $features;
			
			
			//$response['obj_list'] = json_encode($obj_list);
			//$response['message'] = 'success';
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
	   //	log_message('debug', 'get_all_users_locations $response: ' . json_encode($response));
		log_message('debug', ' ******  get_all_users_locations end  ******');
		
		die(json_encode($response));
	
	}
}

