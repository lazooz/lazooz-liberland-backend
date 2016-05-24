<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin_fix_duplicate_payload extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
	}
	

	public function index()
	{
		session_start();
          echo " ...zooz:11..";

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
            for ($user_id = 0; $user_id <= 2721; $user_id++)
            {
                /*
                echo " ...zooz:22..";
                $this->load->model('users_model');
                $this->users_model->update_zooz_balance_mul($user_id,10);
                echo " ...zooz:33..";
                */

            }
            return;
            echo " ...zooz:44..";

			$data = array();

			$data['title'] = 'admin_fix_duplicate_issue';
            for ($i=20;$i<40;$i++)
            {

            for ($user_id = 636; $user_id <= 636; $user_id++)
            {
              echo $i;
              echo ":";
              echo $user_id;
              echo "<br/>";






            $this->load->model('location_payload_model');

			$obj_list = $this->location_payload_model->get_user_last_locations_p($user_id,$i*2000,2000);


             $accumulate_distance = 0;
             $prev_loc_timestamp = 0;
               $accumulate_distance = 0;
               $counter = 0;
               $counter_t =0;
               $zooz =0;
                              $zooz_t =0;
                       $p = 1;
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

                 $this->load->model('location_payload_model');
                  $this->location_payload_model->remove_by_id($obj_tmp['_id']);
                   /*
                 $del = $this->mongo_db
    		 ->where(array("_id" => $obj_tmp['_id']))
              ->limit(1)
             ->get('location_payload_model');

                    print_r($del);

                    */
                   //echo "----";
                   //echo $obj_tmp['_id'];
                     //echo $counter;
                   $obj_tmp['zooz'] = $obj_tmp['zooz']*-1;
                  // echo " ...zooz:..";
                   //echo $obj_tmp['zooz'];
                   $obj_tmp['distace'] = $obj_tmp['distace']*-1;
                  //  echo "2";
                    if ($obj_tmp['zooz'])
                    {

                   $this->load->model('users_model');
      			   $this->users_model->update_zooz_balance($obj_tmp);

			// update zooz and distance statistical data
                  // print_r($obj_tmp);
			       $this->load->model('stats_users_day_model');
			       $this->stats_users_day_model->save_or_update_obj($obj_tmp);
                                 //      echo "4";
        			$this->load->model('stats_all_users_day_model');
		        	$this->stats_all_users_day_model->save_or_update_obj($obj_tmp);
                    }
                    $obj_tmp['zooz'] = $obj_tmp['zooz']*-1;
                /*Erase current from db*/
                 }
                                  //   echo "5";
                 $counter_t = $counter_t+1;
                //  echo " ";
                // echo $counter_t;

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
            if ( $zooz >0)
            {
             print_r($obj);
             echo "<br/>";
             }
             echo $user_id;
             echo "<br/>";

            } /*for*/
            }
            echo "<br/>";
            echo "Done!";
            echo "<br/>";
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

