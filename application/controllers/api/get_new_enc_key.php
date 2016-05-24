<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Get_new_enc_key extends CI_Controller {
	// http://lazooz.b-buzzy.com:8080/api_get_screen_texts
	public function index()
	{

		log_message('debug', ' ******  Get_new_enc_key start  ******');
		log_message('debug', 'Get_new_enc_key $post parms: ' . json_encode($_POST));

		$response = array();


		$user_id = $this->input->post('user_id');
		$user_id = intval($user_id);
		$user_secret = $this->input->post('user_secret');
		
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		
		if($user_obj['_id'] > 0)
		{
			$encryption_key = $user_obj['encryption_key'];
		}
		else
		{
			$encryption_key = null;
		}
		
		
		if($encryption_key != null && $this->config->item('is_use_app_encryption'))
		{
			$this->load->model('mcrypt_model');
			$user_secret = $this->mcrypt_model->decrypt($user_secret,$encryption_key);
		}
		
		
		$user_last_api_time = time();
		
		
		
		//print_r($user_obj);
		
		$is_user_secret_valid = $this->users_model->is_user_secret_valid($user_obj,$user_secret);

		if($is_user_secret_valid)
		{
			$user_obj = $this->users_model->regenerate_and_save_new_encryption_key($user_obj['_id']);
			$encryption_key = $user_obj['encryption_key']; 
			
			
			$response['message'] = 'success';
			$response['encryption_key'] = $encryption_key;
				
		
		}
		else
		{
			$response['message'] = 'credentials_not_valid';
		}
		
		
		log_message('debug', 'Get_new_enc_key $response: ' . json_encode($response));
		log_message('debug', ' ******  Get_new_enc_key end  ******');
		
		
		
		$encoded_response = json_encode($response);
		
		if($encryption_key != null && $this->config->item('is_use_app_encryption'))
		{
			$encoded_response = $this->mcrypt_model->encrypt($encoded_response,$encryption_key);
		
		}
		
		die($encoded_response);
		
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */