<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Get_all_users_routes_plus extends CI_Controller {
	// http://lazooz.b-buzzy.com:8080/api_get_all_users_locations
	public function index()
	{
	  	session_start();


		if(!isset($_SESSION['is_admin_loggedin']))
		{
			$is_admin_loggedin = 'no';
		}
		else
		{
			$is_admin_loggedin = $_SESSION['is_admin_loggedin'];
		}

        $is_admin_loggedin=$_GET["is_admin_loggedin"];
		if($is_admin_loggedin == 'no')
         return;


		log_message('debug', ' ******  get_all_users_locations start  ******');
		log_message('debug', 'get_all_users_locations $post parms: ' . json_encode($_POST));


		$server_token = $this->input->post('server_token');

		$config_server_token = $this->config->item('server_token');
		header("Access-Control-Allow-Origin: http://client.lazooz.org");

		//if($config_server_token == $server_token)
		if(true)
		{
		  /*
			$this->load->model('users_model');
			$obj_list = $this->users_model->get_all_user_locations();
            */
            $this->load->model('location_payload_model');
			$obj_list = $this->location_payload_model->get_user_last_locations($_GET["user_id"]);

			$response = array();
			$response['type'] = 'FeatureCollection';

			$features = array();
			if(sizeof($obj_list) > 0)
			{
				$id = 0;
                $feature_tmp = array();
				$feature_tmp['type'] = 'Feature';
				$feature_tmp['id'] = (sizeof($obj_list));

				$geometry = array();
				$geometry['type'] = 'MultiLineString';

                $coordinates_arr = array();
                $coordinates_arr_arr = array();

                $route_prev =  $obj_list[0]['route'];
                $count = 0;
				foreach ($obj_list as $obj_tmp)
				{
                      //  $coordinates_arr_arr[] =     $obj_tmp['route'];
                        if ($obj_tmp['route'] !=$route_prev )
                        {
                          // $coordinates_arr_arr[] =     $obj_tmp['route'];
                          $coordinates_arr_arr[] =   $coordinates_arr;
                          /*
                          $count=$count+1;

                          if ($count==7)
                          {

                           break;
                          }
                          */
                          $coordinates_arr = array();
                        }
                        $coordinates = array();
                         // $coordinates[] = $obj_tmp;
					    // $coordinates[] = $obj_tmp['loc']['coordinates'];
                          $coordinates[] = $obj_tmp['loc']['coordinates'][0];
                          $coordinates[] = $obj_tmp['loc']['coordinates'][1];
						//  $coordinates[] = $obj_tmp['last_location_latitude'];
                        $coordinates_arr[] = $coordinates;
						//

                    $route_prev =  $obj_tmp['route'];
                    $id++;

				}
                       // $coordinates_arr_arr[] =   $coordinates_arr;
                        $geometry['coordinates'] = $coordinates_arr_arr;
						$feature_tmp['geometry'] = $geometry;

                        $properties = array();
                        $properties['name'] = (string)$obj_tmp['user_id'];
                        $feature_tmp['properties'] = $properties;
						$features[] = $feature_tmp;
						$id++;
			}


			$response['features'] = $features;


			//$response['obj_list'] = json_encode($obj_list);
			//$response['message'] = 'success';
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}


		log_message('debug', 'get_all_users_locations $response: ' . json_encode($response));
		log_message('debug', ' ******  get_all_users_locations end  ******');

		die(json_encode($response));

	}
}

