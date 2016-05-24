<?php
class Match_request_model extends CI_Model {
/*
======================================================================
object example	  
======================================================================
{
	
  "_id" : 26,
  "user_id" : 13,
  "created_time" :"",
  "start_route_loc" : {
    "type" : "Point",
    "coordinates" : [80.65, 83.32]
  },
  "start_route_id" : "1234",
  "dest_route_loc" : {
    "type" : "Point",
    "coordinates" : [80.65, 83.32]
  },
  "dest_route_id" : "1234",
  "share_taxi" : "yes",
  "share_car" : "yes",
  "sport_team" : "barcelona"
  "match_id"   : "123"
}
======================================================================
*/

    function __construct()
    {
        parent::__construct();
        log_message('debug', ' ******  model start 2  ******');
 		$this->load->library('mongo_db');
		 
    }
    
    function get_collection_name()
    {
    	return 'match_request';
    }
    
    function remove_by_user_id($user_id)
    {
              $this->mongo_db
    		 ->where(array("user_id" => $user_id))
              ->limit(1)
             ->delete($this->get_collection_name());
    }


    function find_match_list_for_specific_user($user_obj)
    {
        log_message('debug', 'find_match_list_for_specific_user : ');
        $connection = new MongoClient();
        $collection = $connection->db->match_request;


        $match_source = array("start_route_id"=>array('$eq'=>$user_obj['start_route_id'])); //count distinct
        $match_dest   = array("dest_route_id"=>array('$eq'=>$user_obj['dest_route_id'])); //count distinct
        $match_taxi   = array("share_taxi"=>array('$eq'=>$user_obj['share_taxi'])); //count distinct
        $match_car    = array("share_car"=>array('$eq'=>$user_obj['share_car'])); //count distinct
        $match_sportteam    = array("sport_team"=>array('$eq'=>$user_obj['sport_team'])); //count distinct


        $match = array('$match'=>array('$or'=>array($match_source,$match_dest,$match_taxi,$match_car,$match_sportteam)));
       // $match = array('$match'=>array($match_sportteam));

        $count = array('$group'=>array("_id"=>null,"count"=>array('$sum'=>1))); //count distinct

        //$results = $collection->aggregate(array($match,$count));
        $results = $collection->aggregate($match);


       // $results = $collection->aggregate($ops);

       // var_dump( $results['result'] );



        $res_array = json_encode($results);

        $res_array = json_encode($results);
        $res_array = json_decode($res_array);
       // log_message('debug', 'find_match_list_for_specific_user : ' .json_encode($results));

        if (count($res_array->result) > 1) {
            log_message('debug', 'find_match_list_for_specific_user : ' . $res_array->result[0]->user_id);
            log_message('debug', 'find_match_list_for_specific_user : ' . $res_array->result[1]->user_id);
            $this->send_match_success_notification_to_users($res_array->result[0]->user_id, $res_array->result[1]->user_id);
        }

    }


    function find_match_list_for_specific_user_near_him($user_obj,$match_request_id)
    {
        log_message('debug', 'find_match_list_for_specific_user_near_him 1: ' .$user_obj['user_id'] ." " .$user_obj['email']);
        $this->load->model('users_model');

        $match_user_obj = $this->users_model->get_obj_by_user_id($user_obj['user_id']);
        //$match_user_obj = $this->users_model->get_obj_by_user_id(4);
        //$match_user_2_obj = $this->users_model->get_obj_by_user_id(1802);
        log_message('debug', 'find_match_list_for_specific_user_near_him: no id');
        $user_list_near_me = $this->users_model->get_a_list_of_users_near_me($user_obj['user_id'],1000*100,1);
        if ($user_list_near_me == null) {
            log_message('debug', 'find_match_list_for_specific_user_near_him no location');
            return;
        }

        log_message('debug', 'find_match_list_for_specific_user_near_him: 111111');

        $title = "Ride Request";
        $personname = $match_user_obj['personName'];
        $photo = $match_user_obj['personPhotoUrl'];
        $personegoogleprofile = $match_user_obj['personGooglePlusProfile'];
        $email = $match_user_obj['email'];
        $chatid = $match_user_obj['chatid'];
        $user2lat = $match_user_obj['last_location_latitude'];
        $user2lon = $match_user_obj['last_location_longitude'];
        $destination_id = $user_obj['dest_route_id'];
        log_message('debug', 'find_match_list_for_specific_user_near_him: 2222');

        $autorize_users_list = array("4","3","1802","2073","2336","2389","1905","229","459");
        //$autorize_users_list = array("2389","1802","4");


        foreach ($user_list_near_me as $user) {


            log_message('debug', 'find_match_list_for_specific_user_near_him: ---------' . $user['_id']);
            $user_id = $user['_id'];
            /*if ((in_array($user_id, $autorize_users_list))&& (intval($user['client_build_num'])>=46)) {*/
              if (intval($user['client_build_num'])>=46) {
              //if (1) {
                $user1lat = $user['last_location_latitude'];
                $user1lon = $user['last_location_longitude'];
                log_message('debug', 'find_match_list_for_specific_user_near_him send to a: ' . $user_id);
                //$du = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$user1lat,$user1lon&destinations=$user2lat,$user2lon&sensor=false");
                $du = file_get_contents("http://maps.googleapis.com/maps/api/directions/json?origin=$user1lat,$user1lon&destination=$user2lat,$user2lon");
                $djd = json_decode($du);
                $duration = $djd->routes[0]->legs[0]->duration->text;
                  $duration_value = $djd->routes[0]->legs[0]->duration->value;

                log_message('debug', 'find_match_list_for_specific_user_near_him send to b: ' . $duration);
                $body['NAME'] = $personname;
                $body['PHOTO'] = $photo;
                $body['GOOGLE_PROFILE'] = $personegoogleprofile;
                $body['EMAIL'] = $email;
                $body['OPPONENTID'] = $chatid;
                $body['DESTINATION_ID'] = $destination_id;
                /*
                Rakefet
                $user1lat = "32.855569";
                $user1lon = "35.264236";
                */

                $body['LOC_1_LAT'] = $user1lat;
                $body['LOC_1_LON'] = $user1lon;
                $body['LOC_2_LAT'] = $user2lat;
                $body['LOC_2_LON'] = $user2lon;
                $body['MATCH_REQ_ID'] = $match_request_id;
                $body['TYPE'] = "match_request";
                $body['DURATION'] = $duration;
                  $body['DURATION_VALUE'] = $duration_value;

                  $djd->routes[0]->legs[0] = 0;

                  $du = json_encode($djd);

                  $body['DIRECTION'] = $du;


                log_message('debug', 'find_match_list_for_specific_user_near_him send to: ' . $user_id);
                $this->send_message_to_user($title, $body, $user_id);



            } else {
                log_message('debug', 'find_match_list_for_specific_user_near_him user not autorized: ' . $user_id);


            }

        }
    }

    function send_accept_message_for_match($match_request_id,$from_user_id,$accept)
    {
        $match_obj =$this->get_obj_by_id($match_request_id);
        $this->update_accept_match($match_request_id,$from_user_id,$accept,$match_obj);
        if ($match_obj['user_id'] == $from_user_id)
        {
            return;
        }

        if ($this->is_match_accepted($match_request_id,0)== "yes")
        {
            return;
        }

        $title = "Ride Request Accept";

        $this->load->model('users_model');
        $match_from_user_obj = $this->users_model->get_obj_by_user_id($from_user_id);
        $match_to_user_obj   = $this->users_model->get_obj_by_user_id($match_obj['user_id']);


        $personname = $match_from_user_obj['personName'];
        $photo    = $match_from_user_obj['personPhotoUrl'];
        $personegoogleprofile = $match_from_user_obj['personGooglePlusProfile'];
        $email    = $match_from_user_obj['email'];
        $chatid   = $match_from_user_obj['chatid'];

        $user1lat = $match_to_user_obj['last_location_latitude'];
        $user1lon = $match_to_user_obj['last_location_longitude'];
        $user2lat = $match_from_user_obj['last_location_latitude'];
        $user2lon = $match_from_user_obj['last_location_longitude'];

        $destination_id = "0";

        $du = file_get_contents("http://maps.googleapis.com/maps/api/directions/json?origin=$user1lat,$user1lon&destination=$user2lat,$user2lon");
        $djd = json_decode($du);
        $duration       = $djd->routes[0]->legs[0]->duration->text;
        $duration_value = $djd->routes[0]->legs[0]->duration->value;
        log_message('debug', 'find_match_list_for_specific_user_near_him send to b: ' . $duration);


        //$body = "NAME $personname PHOTO $photo GOOGLE_PROFILE $personegoogleprofile EMAIL $email OPPONENTID $chatid DESTINATION_ID $destination_id LOC $user1lat $user1lon $user2lat $user2lon MATCH_REQ_ID $match_request_id TYPE match_accept DURATION $duration";

        $body['NAME'] = $personname;
        $body['PHOTO'] = $photo;
        $body['GOOGLE_PROFILE'] = $personegoogleprofile;
        $body['EMAIL'] = $email;
        $body['OPPONENTID'] = $chatid;
        $body['DESTINATION_ID'] = $destination_id;
        $body['LOC_1_LAT'] = $user1lat;
        $body['LOC_1_LON'] = $user1lon;
        $body['LOC_2_LAT'] = $user2lat;
        $body['LOC_2_LON'] = $user2lon;
        $body['MATCH_REQ_ID'] = $match_request_id;
        $body['TYPE']         = "match_accept";
        $body['DURATION']     = $duration;
        $body['DURATION_VALUE']     = $duration_value;

        $djd->routes[0]->legs[0] = 0;

        $du = json_encode($djd);
        $body['DIRECTION'] = $du;


        $user_id = $match_obj['user_id'];
        log_message('debug', 'update_accept_match: ' .$match_request_id);

        $this->send_message_to_user($title,$body,$user_id);
    }

    function is_match_accepted($match_request_id,$user_id)
    {
        log_message('debug', 'is_match_accepted ' .$match_request_id);
        if ($match_request_id !=0) {
            $obj = $this->get_obj_by_id($match_request_id);
        }
        else {
            $obj = $this->get_obj_by_user_id($user_id);
        }


        if ($obj['_id'] != 0)
        {
            if (((time() * 1000) - $obj['request_time'])> (60*5*1000)) /*5 minutes*/
            {
                $timeout = 'time_out';
                return $timeout;
            }
            log_message('debug', 'is_match_accepted $objsss' .$obj['owner_accept_ride']);
            return $obj['owner_accept_ride'];
        }
    }


    function send_message_to_user($title,$body,$user_id)
    {
            log_message('debug', 'send_match_success_notification_to_users ss: ' .$body);
            $type = 'out_data';
            $is_global = 'no';
            $is_popup = 'yes';
            $is_notification = 'yes';
            $this->load->model('push_messages_model');
            $this->push_messages_model->create_and_save_new_message_2($title,$body,$type,$is_popup,$is_notification,$is_global,$user_id);
    }


    function send_match_success_notification_to_users($match_user_id,$match_user_id_2)
    {
        /*
        // send push notification to server
        $this->load->model('client_const_data_model');
        $popup_after_100_km_milestone_text = $this->client_const_data_model->get_value_by_key('popup_after_100_km_milestone_text');

        $this->load->model('client_const_data_model');
        $popup_after_100_km_milestone_title_text = $this->client_const_data_model->get_value_by_key('popup_after_100_km_milestone_title_text');
*/
        $title = "Match!!!";
        log_message('debug', 'send_match_success_notification_to_users ss: ' .$match_user_id);
        $this->load->model('users_model');
        log_message('debug', 'send_match_success_notification_to_users : ' .$match_user_id);
        log_message('debug', 'send_match_success_notification_to_users : ' .$match_user_id_2);
        $match_user_id_1_obj = $this->users_model->get_obj_by_user_id($match_user_id);
        $match_user_id_2_obj = $this->users_model->get_obj_by_user_id($match_user_id_2);

        $personname = $match_user_id_1_obj['personName'];
        $photo = $match_user_id_1_obj['personPhotoUrl'];
        $personegoogleprofile = $match_user_id_1_obj['personGooglePlusProfile'];
        $email = $match_user_id_1_obj['email'];
        $chatid = $match_user_id_1_obj['chatid'];


        $body = "NAME $personname PHOTO $photo GOOGLE_PROFILE $personegoogleprofile EMAIL $email OPPONENTID $chatid";

        log_message('debug', 'send_match_success_notification_to_users ss: ' .$body);

        $type = 'out_data';
        $is_global = 'no';
        $is_popup = 'yes';
        $is_notification = 'yes';

        $this->load->model('push_messages_model');
        $this->push_messages_model->create_and_save_new_message($title,$body,$type,$is_popup,$is_notification,$is_global,$match_user_id_2);

        $personname = $match_user_id_2_obj['personName'];
        $photo = $match_user_id_2_obj['personPhotoUrl'];
        $personegoogleprofile = $match_user_id_2_obj['personGooglePlusProfile'];
        $chatid = $match_user_id_2_obj['chatid'];


        $body = "NAME $personname PHOTO $photo GOOGLE_PROFILE $personegoogleprofile EMAIL $email OPPONENTID $chatid";


        $this->push_messages_model->create_and_save_new_message($title,$body,$type,$is_popup,$is_notification,$is_global,$match_user_id);
    }
    function create_and_save_new_match_request($new_obj)
    {
    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	 log_message('debug', ' ******  create_and_save_new_match_request  ******');
    	$obj['_id'] = $id;
    	$obj['created_time'] = new MongoDate();
    	$obj['user_id'] = $new_obj['user_id']; 
    	
    	$obj['start_route_loc'] = array(); 
    	$obj['start_route_loc']['type'] = 'Ponit'; 
    	$obj['start_route_loc']['coordinates'] = array();
		$obj['start_route_loc']['coordinates'][] = $new_obj['start_route_loc']['long'];
		$obj['start_route_loc']['coordinates'][] = $new_obj['start_route_loc']['lat'];
		
		$obj['start_route_id'] = $new_obj['start_route_id']; 
		
		$obj['dest_route_loc'] = array(); 
    	$obj['dest_route_loc']['type'] = 'Ponit'; 
    	$obj['dest_route_loc']['coordinates'] = array();
		$obj['dest_route_loc']['coordinates'][] = $new_obj['dest_route_loc']['long'];
		$obj['dest_route_loc']['coordinates'][] = $new_obj['dest_route_loc']['lat'];
		
		$obj['dest_route_id'] = $new_obj['dest_route_id']; 
		
		$obj['share_taxi'] = $new_obj['share_taxi']; 
		$obj['share_car'] = $new_obj['share_car']; 
		$obj['sport_team'] = $new_obj['sport_team']; 
			
		$obj['match_id'] = null;
        $obj['accept_list'] = null;

        $obj['match_list_size'] = 0;
        $obj['request_time'] =  time()*1000;

        $user_id = $obj['user_id'];
	     
	     $this->mongo_db
    		 ->where(array("user_id" => $user_id))
              ->limit(1)
             ->delete($this->get_collection_name());

    	$result = $this->mongo_db->insert($this->get_collection_name(), $obj);
        //log_message('debug', ' ******  insert_id : ******' .$result);


        //$this->find_match_list_for_specific_user($obj);
        $result = $this->find_match_list_for_specific_user_near_him($obj,$result);
    	
    	return $obj;


    }

    function get_obj_by_id($id)
    {
        $id = intval($id);

        $obj_list = $this->mongo_db->where(array("_id" => $id  ))
            ->get($this->get_collection_name());

        //print_r($obj_list);

         //echo sizeof($obj_list);

        if(sizeof($obj_list) > 0)
        {
            $obj = $obj_list[0];

        }
        else
        {
            $obj = array();
            $obj['_id'] = 0;

        }
        return $obj;

    }

    function get_obj_by_user_id($user_id)
    {
        $user_id = intval($user_id);

        $obj_list = $this->mongo_db->where(array("user_id" => $user_id  ))
            ->get($this->get_collection_name());

        //print_r($obj_list);

        //echo sizeof($obj_list);

        if(sizeof($obj_list) > 0)
        {
            $obj = $obj_list[0];

        }
        else
        {
            $obj = array();
            $obj['_id'] = 0;

        }
        return $obj;

    }



    function update_accept_match($match_request_id,$match_user_id,$accept,$match_obj)
    {

        $match_request_id = intval($match_request_id);
        $list_size =    $match_obj['match_list_size'];
        $data = array();
        $data['match_id']   = $match_obj['match_id'];
        $data['accept_list']= $match_obj['accept_list'];
        $data['match_id'][] = $match_user_id;
        $data['accept_list'][] = $accept;
        $data['match_list_size'] = $list_size+1;
        log_message('debug', ' ******  update_accept_match  ******' .$match_user_id .$match_obj['user_id']);
        if ($match_user_id == $match_obj['user_id'])
        {
            $data['owner_accept_ride'] = $accept;
            /* Charge with 15 ZOOZ tokens*/
            $this->load->model('users_model');
            log_message('debug', ' ******  update_accept_match 2 ******' .$match_user_id .$match_obj['user_id']);
            $this->users_model->update_zooz_balance_minus($match_user_id,15);

        }

        $updated = $this->mongo_db->where('_id', $match_request_id)
            ->set($data)
            ->update($this->get_collection_name());
    }
}
?>