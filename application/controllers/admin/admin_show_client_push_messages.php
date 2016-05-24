<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_show_client_push_messages extends CI_Controller {

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


            log_message('debug', ' ******  oren msg2  ******');
			$this->load->model('push_messages_model');
			
			$obj_list = $this->push_messages_model->get_all_messages();
			
			$data = array();
			
			$data['obj_list'] = $obj_list; 
			
			
			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_show_client_push_messages', $data);

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
			
			$response = array();
			
			$title = $this->input->post('title');
			$body = $this->input->post('body');
			$is_popup = $this->input->post('is_popup');
			
			if($is_popup == 'true')
			{
				$is_popup = 'yes';
			}
			else 
			{
				$is_popup = 'no';
			}
			
			
			$is_notification = $this->input->post('is_notification');
			
			if($is_notification == 'true')
			{
				$is_notification = 'yes';
			}
			else
			{
				$is_notification = 'no';
			}

            log_message('debug', ' ******  oren msg1  ******');
		
			$user_id = $this->input->post('user_id');
            log_message('debug', ' ******  oren msg1  ******'.$user_id);
		
			$type = 'out_data';
			
			if($user_id == null)
			{
				$is_global = 'yes';
			}
			else 
			{
				$is_global = 'no';
			}


			
			$this->load->model('push_messages_model');
            log_message('debug', ' ******  create_and_save_new_message_2 a  ******'.$user_id);
			$this->push_messages_model->create_and_save_new_message_2($title,$body,$type,$is_popup,$is_notification,$is_global,$user_id);
			//create_and_save_new_message($title,$body,$type,$is_popup,$is_notification,$is_global = 'yes',$user_id = null)
			
			$response['message'] = 'success';
			
			
			die(json_encode($response));
			
		
		}	
	}
	
	
	
	
}

