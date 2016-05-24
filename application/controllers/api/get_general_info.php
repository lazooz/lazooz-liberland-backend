<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Get_general_info extends CI_Controller {
	// http://lazooz.b-buzzy.com:8080/api_get_screen_texts
	public function get_screen_texts()
	{
		
		log_message('debug', ' ******  get_screen_texts start  ******');
		log_message('debug', 'get_screen_texts $post parms: ' . json_encode($_POST));
		
		$response = array();
		
	//	echo getcwd ();die;
		
		$this->load->model('client_const_data_model');
		
		
		/*
		$path = '../application/views/screen_texts/intro_screen_text';
		$response['intro_screen_text'] = file_get_contents($path);
		
		$path = '../application/views/screen_texts/before_cellphone_validation_screen_text';
		$response['before_cellphone_validation_screen_text'] = file_get_contents($path);
		
		// todo replace zooz amount with amount from server 
		$path = '../application/views/screen_texts/second_step_screen_text';
		$response['second_step_screen_text'] = file_get_contents($path);
		
		$path = '../application/views/screen_texts/before_shake_screen_text';
		$response['before_shake_screen_text'] = file_get_contents($path);
		
		$path = '../application/views/screen_texts/disclaimer_screen_text';
		$response['disclaimer_screen_text'] = file_get_contents($path);
		
		$path = '../application/views/screen_texts/disclaimer_screen_headline_text';
		$response['disclaimer_screen_headline_text'] = file_get_contents($path);
		
		*/
		
		
		$before_cellphone_validation_screen_text = $this->client_const_data_model->get_value_by_key('before_cellphone_validation_screen_text');
		$response['before_cellphone_validation_screen_text'] = $before_cellphone_validation_screen_text;
		
		$before_shake_screen_text = $this->client_const_data_model->get_value_by_key('before_shake_screen_text');
		$response['before_shake_screen_text'] = $before_shake_screen_text;
		
		$disclaimer_screen_headline_text = $this->client_const_data_model->get_value_by_key('disclaimer_screen_headline_text');
		$response['disclaimer_screen_headline_text'] = $disclaimer_screen_headline_text;
		
		$disclaimer_screen_text = $this->client_const_data_model->get_value_by_key('disclaimer_screen_text');
		$response['disclaimer_screen_text'] = $disclaimer_screen_text;
		
		$intro_screen_text = $this->client_const_data_model->get_value_by_key('intro_screen_text');
		$response['intro_screen_text'] = $intro_screen_text;
		
		$second_step_screen_text = $this->client_const_data_model->get_value_by_key('second_step_screen_text');
		$response['second_step_screen_text'] = $second_step_screen_text;
		
		$whats_next_question_mark_text = $this->client_const_data_model->get_value_by_key('whats_next_question_mark_text');
		$response['whats_next_question_mark_text'] = $whats_next_question_mark_text;
		
		
		$popup_after_100_km_milestone_text = $this->client_const_data_model->get_value_by_key('popup_after_100_km_milestone_text');
		$response['popup_after_100_km_milestone_text'] = $popup_after_100_km_milestone_text;
		
		
		/*
		include '../application/config/client_ver.php';
		$response['min_build_num'] = $client_min_build_num;
		$response['current_build_num'] = $client_current_build_num;
		*/
		
		$min_build_num = $this->client_const_data_model->get_value_by_key('client_min_build_num');
		$response['min_build_num'] = $min_build_num;
		
		$current_build_num = $this->client_const_data_model->get_value_by_key('client_current_build_num');
		$response['current_build_num'] = $current_build_num;
		
		
		
		
		$response['message'] = 'success';
		
		log_message('debug', 'get_screen_texts $response: ' . json_encode($response));
		log_message('debug', ' ******  get_screen_texts end  ******');
		
		die(json_encode($response));
	
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */