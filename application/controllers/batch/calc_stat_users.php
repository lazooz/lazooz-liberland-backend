<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Calc_stat_users extends CI_Controller {

	// http://lazooz.b-buzzy.com:8080/batch_calc_stat_users/jsYHflaoiu855Gokoshs1glppaytt85fhfeddekdhghfdjkag2iGHGFO99okka98GFdfoos88ggs
	// http://lazooz.b-buzzy.com:8080/batch_calc_stat_users/jsYHflaoiu855Gokoshs1glppaytt85fhfeddekdhghfdjkag2iGHGFO99okka98GFdfoos88ggs
	
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function index()
	{
		
	}

	public function stat_users($batch_token=null)
	{
		
		//print_r($this->config);die;
		//print_r($_GET);die;
		//$batch_token = $this->input->get('t');
		
		log_message('debug', ' ******  calc_stat_users_batch start  ******');
		
		if($batch_token == $this->config->item('batch_token'))
		{
			
			echo 'start<br>';

			$this->load->model('stats_total_users_month_model');
			$this->stats_total_users_month_model->calc_stats_total_users_month();
			
			
			$this->load->model('stats_total_users_day_model');
			$this->stats_total_users_day_model->calc_stats_total_users_day();
			
			echo '<br>end';
		}
		
		log_message('debug', ' ******  calc_stat_users_batch end  ******');
		
	}
	
}
