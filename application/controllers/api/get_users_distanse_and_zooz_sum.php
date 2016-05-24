<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Get_users_distanse_and_zooz_sum extends CI_Controller {

	public function index()
	{
		
		log_message('debug', ' ******  get_users_distanse_and_zooz_sum start  ******');
		log_message('debug', 'get_users_distanse_and_zooz_sum $post parms: ' . json_encode($_POST));
		
		
		$server_token = $this->input->post('server_token');

		$config_server_token = $this->config->item('server_token');
       // header("Access-Control-Allow-Origin: http://lazooz.org");
        header("Access-Control-Allow-Origin: *");


        if(1)
		{
			$this->load->model('users_model');
			$obj_list = $this->users_model->get_distanse_and_zooz_sum();
			
			if($obj_list['message'] = 'success')
			{
				//echo $obj_list['zooz_distance_balance'];die;
				$response['zooz_distance_balance'] = $obj_list['zooz_distance_balance'];
				$response['zooz_balance'] = $obj_list['zooz_balance'];
                $response['number_of_users'] =$obj_list['number_of_users'];

				$response['message'] = 'success';
			
			}
			else 
			{
				$response['message'] = $obj_list['message'];
			}
			
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'get_users_distanse_and_zooz_sum $response: ' . json_encode($response));
		log_message('debug', ' ******  get_users_distanse_and_zooz_sum end  ******');
		
		die(json_encode($response));
	
	}
}

