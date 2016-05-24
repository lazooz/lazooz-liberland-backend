<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_number_of_users_per_country extends CI_Controller {
	
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

			$data['title'] = 'admin_numbers_of_users_per_country';


           	$data = array();

			//$data['obj_list'] = $obj_list;


			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_number_of_users_per_country', $data);

			$this->load->view('templates/footer', $data);
		}
		else
		{


			$this->load->helper('url');
			redirect('/admin_login', 'refresh');

		}


	}

    function ajax_edit_messages()
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

		   

			$country = $this->input->post('country');


			$this->load->model('users_model');

		    $obj_list = $this->users_model->get_all_user_locations_for_specific_country($country);

       //     $this->load->model('match_request_model');
            $obj_list = $this->users_model->get_a_list_of_users_near_me(null);

            echo "<br/>";
            echo count($obj_list);
            echo "<br/>";
/*
            $this->load->model('match_request_model');
            $obj_list = $this->match_request->get_a_list_of_users_near_me($country);
*/
            echo "<br/>";
            echo count($obj_list);
            echo "<br/>";


        }
	}

}

