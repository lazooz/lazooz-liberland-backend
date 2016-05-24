<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Set_client_exception extends CI_Controller {

	public function index()
	{
		
		log_message('debug', ' ******  set_client_exception start  ******');
		log_message('debug', 'set_client_exception $post parms: ' . json_encode($_POST));
		
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
			$exeptionTime = $this->input->post('exeptionTime');
			$exeptionData = $this->input->post('exeptionData');
			
			$obj = array();
			$obj['user_id'] = $user_id;
			$obj['exeptionTime'] = $exeptionTime;
			$obj['exeptionData'] = $exeptionData;
			
			$this->load->model('client_exception_model');
			$obj = $this->client_exception_model->save_obj($obj);
			
			if($obj['save_message'] == 'insert_success')
			{
				$response['message'] = 'success';
			}
			else 
			{
				$response['message'] = 'error_db';
				
			}
			
			
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'set_client_exception $response: ' . json_encode($response));
		log_message('debug', ' ******  set_client_exception end  ******');
		
		
		
		die(json_encode($response));
	
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */