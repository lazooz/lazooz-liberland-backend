<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_edit_client_general_parms extends CI_Controller {

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
				
			$client_current_build_num = $this->client_const_data_model->get_value_by_key('client_current_build_num');
			$data['client_current_build_num'] = $client_current_build_num;
			
			$client_min_build_num = $this->client_const_data_model->get_value_by_key('client_min_build_num');
			$data['client_min_build_num'] = $client_min_build_num;
			
			$server_version = $this->client_const_data_model->get_value_by_key('server_version');
			$data['server_version'] = $server_version;
				
			$zooz_to_dolar_conversion_rate = $this->client_const_data_model->get_value_by_key('zooz_to_dolar_conversion_rate');
			$data['zooz_to_dolar_conversion_rate'] = $zooz_to_dolar_conversion_rate;
			
			$zooz_to_dolar_conversion_rate = $this->client_const_data_model->get_value_by_key('critical_mass_tab');
			$data['critical_mass_tab'] = $zooz_to_dolar_conversion_rate;
			
			
			$zooz_reward_for_recommendation_user = $this->client_const_data_model->get_value_by_key('zooz_reward_for_recommendation_user');
			$data['zooz_reward_for_recommendation_user'] = $zooz_reward_for_recommendation_user;
				
				
			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_edit_client_general_parms', $data);

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

