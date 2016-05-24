<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_fix_duplicate_payload extends CI_Controller {
	
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

			$data['title'] = 'admin_fix_duplicate_issue';

            $user_id = 4;
            /*
            $obj = array();
                          $obj['counter_t'] = 1;
                                  $data['obj'] = $obj;

			print_r($obj_list);

			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_fix_duplicate_payload', $data);
			$this->load->view('templates/footer', $data);
             return;
             */
            $this->load->model('location_payload_model');

			$obj_list = $this->location_payload_model->get_user_last_locations($user_id);


             $accumulate_distance = 0;
             $prev_loc_timestamp = 0;
               $accumulate_distance = 0;
               $counter = 0;
               $counter_t =0;
               $zooz =0;
                              $zooz_t =0;
             if(sizeof($obj_list) > 0)
		     {
               $prev_loc_timestamp = 0;
               $accumulate_distance = 0;
               $counter = 0;
		       foreach ($obj_list as $obj_tmp)
		       {
		         $current_loc_timestamp = $obj_tmp['loc_timestamp'];
                 if ($current_loc_timestamp == $prev_loc_timestamp)
                 {
                   $accumulate_distance = $accumulate_distance +$obj_tmp['distace'];
                   $counter = $counter+1;
                   $zooz = $zooz+$obj_tmp['zooz'];
                /*Erase current from db*/
                 }
                 $counter_t = $counter_t+1;
                 $zooz_t = $zooz_t+$obj_tmp['zooz'];

               $prev_loc_timestamp = $current_loc_timestamp;
		       }
             }

            $obj = array();
			$obj['user_id'] = $user_id;
            $obj['wrong_dist'] = $accumulate_distance;
            $obj['counter'] = $counter;
                                    $obj['counter_t'] = $counter_t;
                                                $obj['wrong_zooz'] = $zooz;
                                    $obj['zooz_t'] = $zooz_t;
                                  $data['obj'] = $obj;

			//print_r($obj_list);

			$this->load->view('templates/site_header', $data);
			$this->load->view('admin/admin_fix_duplicate_payload', $data);
			$this->load->view('templates/footer', $data);
			
		}
		else 
		{
			
			
			$this->load->helper('url');
			redirect('/admin_login', 'refresh');
				
		}
		
	
	}
}

