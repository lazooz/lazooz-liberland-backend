<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_edit_client_texts extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

	}


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


		if($is_admin_loggedin == 'yes')
		{
			$data = array();
			$data['title'] = 'menu';


			
			$this->load->model('client_const_data_model');
			
			$before_cellphone_validation_screen_text = $this->client_const_data_model->get_value_by_key('before_cellphone_validation_screen_text');
			$data['before_cellphone_validation_screen_text'] = $before_cellphone_validation_screen_text;
			
			
			
			$before_shake_screen_text = $this->client_const_data_model->get_value_by_key('before_shake_screen_text');
			$data['before_shake_screen_text'] = $before_shake_screen_text;
			
			
			$whats_next_question_mark_text = $this->client_const_data_model->get_value_by_key('whats_next_question_mark_text');
			$data['whats_next_question_mark_text'] = $whats_next_question_mark_text;
			
			$disclaimer_screen_headline_text = $this->client_const_data_model->get_value_by_key('disclaimer_screen_headline_text');
			$data['disclaimer_screen_headline_text'] = $disclaimer_screen_headline_text;
				
			
			
			$disclaimer_screen_text = $this->client_const_data_model->get_value_by_key('disclaimer_screen_text');
			$data['disclaimer_screen_text'] = $disclaimer_screen_text;
			
			
			$intro_screen_text = $this->client_const_data_model->get_value_by_key('intro_screen_text');
			$data['intro_screen_text'] = $intro_screen_text;
			
			
			
			$second_step_screen_text = $this->client_const_data_model->get_value_by_key('second_step_screen_text');
			$data['second_step_screen_text'] = $second_step_screen_text;
			
			
			$popup_after_100_km_milestone_title_text = $this->client_const_data_model->get_value_by_key('popup_after_100_km_milestone_title_text');
			$data['popup_after_100_km_milestone_title_text'] = $popup_after_100_km_milestone_title_text;
			
			$popup_after_100_km_milestone_text = $this->client_const_data_model->get_value_by_key('popup_after_100_km_milestone_text');
			$data['popup_after_100_km_milestone_text'] = $popup_after_100_km_milestone_text;
			
			
			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_edit_client_texts', $data);

			$this->load->view('templates/footer', $data);
				
		}
		else
		{
				
				
			$this->load->helper('url');
			redirect('/admin_login', 'refresh');

		}


	}
	
	
	function ajax_set_texts()
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
		
		
		if($is_admin_loggedin == 'yes')
		{
			
			$response = array();
			
			
			
			$key = $this->input->post('key');
			$value = $this->input->post('value');
			
			$this->load->model('client_const_data_model');
			
			$this->client_const_data_model->save_or_update_obj($key,$value);
			
			
			$response['message'] = 'success';
			
			
			die(json_encode($response));
			
		
		}	
	}
	
	
	
	
}

