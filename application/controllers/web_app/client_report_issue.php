<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// http://lazooz.b-buzzy.com:8080/client_report_issue/3
// https://client.lazooz.org/client_report_issue/3
class Client_report_issue extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
	}

	public function index($user_id)
	{
		$user_id = intval($user_id);
		//echo 'report issue screen for user ' . $user_id . ' ..';
		
		$data = array();
		
		/*
		$this->load->library('encrypt');
		$client_id = $this->encrypt->encode($user_id);
		*/
		
		
		$data['user_id'] = $user_id;
		
		$data['is_mobile_css'] = true;
		
		
		$this->load->view('templates/site_header', $data);
		$this->load->view('client/client_report_issue', $data);
			
		$this->load->view('templates/footer', $data);
		
		
		
	}
	
	function ajax_client_report_issue()
	{
		$user_id = $this->input->post('c');
		$issue_subject = $this->input->post('issue_subject');
		$issue_desc = $this->input->post('issue_desc');
		$user_id = intval($user_id);
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		if($user_obj['_id'] > 0)
		{
			$cellphone = $user_obj['cellphone'];
		}
		else 
		{
			$cellphone = '';
		}
		
		$this->load->model('client_const_data_model');
		
		//$client_current_build_num = $this->client_const_data_model->get_value_by_key('client_current_build_num');
		if(isset($user_obj['client_build_num']))
		{
			$client_current_build_num = $user_obj['client_build_num'];
		}
		else 
		{
			$client_current_build_num = '';
		}
		
		$server_version = $this->client_const_data_model->get_value_by_key('server_version');
		
		
		$this->load->model('client_report_issue_model');
		$result_id = $this->client_report_issue_model->save_obj($user_id,$issue_subject,$issue_desc,$cellphone,
					$client_current_build_num,$server_version);
		
		$response = array();
		
		if($result_id > 0)
		{
			$response['message'] = 'success';
			
		}
		else 
		{
			$response['message'] = 'error_db';
			
		}
		
		die(json_encode($response));
		
	}
	
	
	
}

