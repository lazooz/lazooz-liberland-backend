<?php

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}

	
	
public function index()
{
	
	

	$data['title'] = 'home';
	
	
	$this->load->view('templates/site_header', $data);
	$this->load->view('home', $data);

	$this->load->view('templates/footer', $data);
/*	
 * 
	
	
 * 
 * 
	$response['message'] = 'error';
	//echo 'asdfasdfa';die;

	$user_product_pk = $this->input->post('product_id');
	$device_secret_token = $this->input->post('token');

	$this->load->model('user_products_model');
	
	$response['message'] = $this->user_products_model->is_product_token_and_activation_valid($user_product_pk,$device_secret_token);
	
	if($response['message']  == 'device_activation_is_valid')
	{
		$location_longitude = $this->input->post('location_longitude');
		$location_latitude = $this->input->post('location_latitude');
	
		$this->user_products_model->update_device_location($user_product_pk,$location_longitude,$location_latitude);
		$response['message'] = 'saved';
	}

	die(json_encode($response));
	*/
}
}