<?php
class Location_payload_model extends CI_Model {

	
/*
	 
======================================================================
object example	  
======================================================================

{
  "_id" : 26,
  "user_id" : 13,
  "loc_accuracy" : 500,
  "loc_timestamp" : ISODate("2014-07-09T07:58:54Z"),
  "loc" : {
    "type" : "Point",
    "coordinates" : [80.65, 83.32]
  },
  "tel_cid" : "21321ss",
  "tel_lac" : "adsaf2d",
  "tel_mcc" : "664SSf2",
  "tel_mnc" : "77ghh5",
  "is_wifi" : "yes",
  "wifi" : [{
      "bssid" : "as ss",
      "ssid" : "osnet",
      "capabilities" : "asdf as fas asdf",
      "frequency" : 321354
    }, {
      "bssid" : "w ass",
      "ssid" : "osnet2",
      "capabilities" : "11asdf as fas asdf",
      "frequency" : 3214
    }],
  "is_bt" : "yes",
  "bt" : [{
      "name" : "btjjkks",
      "address" : "321.165.1.15"
    }, {
      "name" : "bt-s",
      "address" : "31.165.1.15"
    }],
  "db_insert_time" : ISODate("2014-07-09T07:58:54Z"),
  "is_score_calc" : "yes",
  "score_calc_time" : ISODate("2014-07-09T07:58:54Z"),
  "distance_base_loc_id" : null,
  "distance" : null,
  "speed" : 0,
  "score_location_accuracy" : null,
  "score_speed" : 100,
  "score_distance" : 100,
  "score_bt" : 100,
  "score_wifi" : 100,
  "score_total" : 100,
  "is_zooz_calc" : "yes",
  "zooz_calc_time" : ISODate("2014-07-09T07:58:54Z"),
  "zooz" : 0.0,
  "distace_base_loc_id" : 25,
  "distace" : 0.0
}

======================================================================



	  
	 */
 
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
		 
    }
    
    function get_collection_name()
    {
    	return 'location_payload';
    }
    
    function calc_zooz_for_distance($obj)
    {
    	$zooz_conversion_rate = $this->config->item('zooz_conversion_rate');
    	
    	//For 100 km -one zooz * score /100
    	$zooz = ($obj['score_total'] / 100)  * $obj['distace'] / (1000 * $zooz_conversion_rate);
    	
    	$zooz  = round($zooz,8);
    	
    	$obj['zooz'] = $zooz;   
    	
    	return $obj;
    }
    
    function calc_score_for_location($obj)
    {
    	// todo score calculations
    	$obj['score_speed'] = 100;
    	$obj['score_distance'] = 100;
    	$obj['score_bt'] = 100;
    	$obj['score_wifi'] = 100;
    	$obj['score_location_accuracy'] = 100;
    	$obj['score_total'] = 100;
    	
    	return $obj;
    	
    }
    
    
    function delete_old_obj()
    {
    	$time = time()- 60 * 60 * 24 * 7; // last 7 days
    	$time = new MongoDate($time);
    	
    	$this->mongo_db->where_lt('db_insert_time' , $time)
    					->delete_all($this->get_collection_name());
    }
    
    
    
    function calc_user_distance_and_zooz_sum_for_time($user_id,$time_from,$time_to)
    {
    	//echo time();die;
    	$connection = new MongoClient();
		$collection = $connection->db->location_payload;
		//zooz_distance_balance
		$time_from = $time_from * 1000; // loc_timestamp is reported in miliseconds
		$time_to = $time_to * 1000;
		//1405503206989

		$time_from = 1405503206989;
		$time_to = 1405503206990;
		
		echo $time_from . ' ** ' . $time_to . '<br>';
		
		$out = $collection->aggregate(
		
    	array(
        //'$match' => array('activation_status' => array('$eq' => 'active')),
        '$match' => array('user_id' =>  $user_id ,
    		//	'loc_timestamp' => array('$gte' => $time_from,'$lt' => $time_to)	,
    		
    		'_id' => array('$gte' => 681359,'$lt' => 681360)	,
    		//	'loc_timestamp' => array('$lt' => $time_to)
    			)
    	),
    	array(
        '$group' => array(
    		'_id' => array('user_id' => '$user_id'),
            'distace' => array('$sum' => '$distace' ),
    		'zooz' => array('$sum' => '$zooz' )
        	)
    	)
    
		);
		
		print_r($out);
    	
    }
    
    
    
    function calc_distance_and_score_for_location($obj)
    {
    	$user_id = $obj['user_id'];
    	
    	$user_id = intval($user_id);
    	
    	$loc_timestamp = $obj['loc_timestamp'];
    	
    	//echo $obj['loc_timestamp']; die;
    	$last_location_obj = $this->get_user_last_locations_by_timestamp($user_id,$loc_timestamp);
    	
    	
    	if(!isset($last_location_obj['route'] ))
    	{
    		$last_location_obj['route'] = 0;
    	}
    	
    	if(is_nan($last_location_obj['route']))
    	{
    		$last_location_obj['route'] = 0;
    	}
    	
    	log_message('debug', 'calc_distance_and_score_for_location - last location route: ' . $last_location_obj['route'] . ' ** this route: ' . $obj['route']);
    	
    	if($last_location_obj['_id'] == 0)
    	{
    		log_message('debug', 'calc_distance_and_score_for_location - this is the first location for user');
    		
    		$obj['distace'] = 0;
    		$obj['speed']= 0;
    		$obj['distace_base_loc_id']= null;
    		$obj['zooz'] = 0;
    		
    		$obj['score_location_accuracy'] = 0;
    		$obj['score_speed'] = 0;
    		$obj['score_distance'] = 0;
    		$obj['score_bt'] = 0;
    		$obj['score_wifi'] = 0;
    		$obj['score_total'] = 0;
    	}
    	elseif($last_location_obj['route'] != $obj['route'])
    	{
    		log_message('debug', 'calc_distance_and_score_for_location - start a new route - no distance or zooz');
    		
    		$obj['distace'] = 0;
    		$obj['speed']= 0;
    		$obj['distace_base_loc_id']= $last_location_obj['_id'];;
    		$obj['zooz'] = 0;
    		
    		$obj['score_location_accuracy'] = 0;
    		$obj['score_speed'] = 0;
    		$obj['score_distance'] = 0;
    		$obj['score_bt'] = 0;
    		$obj['score_wifi'] = 0;
    		$obj['score_total'] = 0;
    		
    		$data = array();
    		 
    		$data['score_calc_time'] = new MongoDate();
    		$data['distace_base_loc_id'] = $obj['distace_base_loc_id'];
    		$data['distace'] = $obj['distace'];
    		$data['speed'] = $obj['speed'];
    		 
    		$data['is_score_calc'] = 'new_route';
    		$data['score_speed'] = $obj['score_speed'];
    		$data['score_distance'] = $obj['score_distance'];
    		$data['score_bt'] = $obj['score_bt'];
    		$data['score_wifi'] = $obj['score_wifi'];
    		$data['score_location_accuracy'] = $obj['score_location_accuracy'];
    		 
    		$data['score_total'] = $obj['score_total'];
    		 
    		$data['zooz'] = $obj['zooz'];
    		$data['zooz_calc_time'] = new MongoDate();
    		$data['is_zooz_calc'] = 'yes';
    		 
    		//echo $obj['_id'];die;
    		//print_r($data);die;
    		 
    		$obj['_id'] = intval($obj['_id']);
    		
    		$updated = $this->mongo_db->where('_id', $obj['_id'])
    		->set($data)
    		->update($this->get_collection_name());
    		 
    		log_message('debug', 'calc_distance_and_score_for_location - new data saved for new route location id: ' . $obj['_id'] . ' ** updated data: ' . json_encode($data) );
    			
    		
    		
    	}
    	else
    	{
    		log_message('debug', 'calc_distance_and_score_for_location - start a new route - no distance or zooz');
    		
    		$obj['distace'] = $this->calc_distance($obj['loc']['coordinates'][0],$obj['loc']['coordinates'][1],$last_location_obj['loc']['coordinates'][0],$last_location_obj['loc']['coordinates'][1]);
    		//echo $obj['distace'];die;
    		
    		if(is_nan($obj['distace']))
    		{
    			$obj['distace'] = 0;
    		}
    		
    		
    		$last_location_timestamp = $last_location_obj['loc_timestamp'];
    		$last_location_timestamp = $this->mongo_db->get_php_time($last_location_timestamp);
    		
    		$loc_timestamp = $this->mongo_db->get_php_time($loc_timestamp);

    		$time_passed_from_last_location = $loc_timestamp - $last_location_timestamp;
    		
    		$obj['speed'] = ($obj['distace'] / 1000) /  ($time_passed_from_last_location/(60 * 60)); // speed in km/hr
    		
    		if($obj['speed'] > 200)
    		{
    			$this->load->model('suspicious_users_model');
    			$this->suspicious_users_model->create_and_save_obj($user_id,$obj,$last_location_obj);
    			
    			// handle very fast users
    			$obj['speed'] = 150;
    			//$obj['distace'] = (150 * 1000) * ($time_passed_from_last_location / (60 * 60));
    			$obj['distace'] = 0;
    			
    		}
    		
    		$obj['distace_base_loc_id'] = $last_location_obj['_id'];
    		
    		$obj = $this->calc_score_for_location($obj);
    		$obj = $this->calc_zooz_for_distance($obj);
    		
    	}
    	
    	$data = array();
    	
    	$data['score_calc_time'] = new MongoDate();
    	$data['distace_base_loc_id'] = $obj['distace_base_loc_id'];
    	$data['distace'] = $obj['distace'];
    	$data['speed'] = $obj['speed'];
    	
    	$data['is_score_calc'] = 'yes';
    	$data['score_speed'] = $obj['score_speed'];
    	$data['score_distance'] = $obj['score_distance'];
    	$data['score_bt'] = $obj['score_bt'];
    	$data['score_wifi'] = $obj['score_wifi'];
    	$data['score_location_accuracy'] = $obj['score_location_accuracy'];
    	
    	$data['score_total'] = $obj['score_total'];
    	
    	$data['zooz'] = $obj['zooz'];
    	$data['zooz_calc_time'] = new MongoDate();
    	$data['is_zooz_calc'] = 'yes';
    	
    	//echo $obj['_id'];die;
    	//print_r($data);die;
    	
    	$obj['_id'] = intval($obj['_id']);
    	
    	$updated = $this->mongo_db->where('_id', $obj['_id'])
									->set($data)
									->update($this->get_collection_name());
    	
    	log_message('debug', 'calc_distance_and_score_for_location - new data saved for location id: ' . $obj['_id'] . ' ** updated data: ' . json_encode($data) );
									
		//echo $updated;	

		// update user zooz balance
		if($data['zooz'] > 0)
		{
			$this->load->model('users_model');
			$this->users_model->update_zooz_balance($obj);

			// update zooz and distance statistical data
			$this->load->model('stats_users_day_model');
			$this->stats_users_day_model->save_or_update_obj($obj);

			$this->load->model('stats_all_users_day_model');
			$this->stats_all_users_day_model->save_or_update_obj($obj);
			
			
			$this->load->model('stats_users_month_model');
			$this->stats_users_month_model->save_or_update_obj($obj);
				
			
			
		}
							


    }
    
    function get_user_last_locations_by_timestamp($user_id,$loc_timestamp)
    {
    	//$loc_timestamp = new MongoDate($loc_timestamp);

    	$user_id = intval($user_id);

    	$obj_list = $this->mongo_db
    		->where(array("user_id" => $user_id))
	    	->where_lt('loc_timestamp', $loc_timestamp)
   		 	->order_by(array('loc_timestamp' => 'DESC'))
   	 		->limit(1)
    		->get($this->get_collection_name());

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

     function get_user_last_locations($user_id)
    {
    	//$loc_timestamp = new MongoDate($loc_timestamp);

    	$user_id = intval($user_id);

    	$obj_list = $this->mongo_db
    		->where(array("user_id" => $user_id))
            ->limit(1000)
            ->order_by(array('loc_timestamp' => 'DESC'))
            ->get($this->get_collection_name());

        return $obj_list;
    	if(sizeof($obj_list) > 0)
    	{
    		$obj = $obj_list[0];
            $obj = $obj_list;
    	}
    	else
    	{
    		$obj = array();
    		$obj['_id'] = 0;
    	}

    	return $obj;
    }
     function get_user_last_locations_p($user_id,$skip,$limit)
    {
    	//$loc_timestamp = new MongoDate($loc_timestamp);

    	$user_id = intval($user_id);

    	$obj_list = $this->mongo_db
    		->where(array("user_id" => $user_id))
            ->limit($limit)
            ->offset($skip)
            ->order_by(array('loc_timestamp' => 'DESC'))
            ->get($this->get_collection_name());

        return $obj_list;
    }


    function calc_distance($lat1,$lon1,$lat2,$lon2)
    {

    	//echo '$lat1: ' . $lat1 . ' ** $lon1: ' . $lon1 . ' ** $lat2: ' . $lat2 . ' ** $lon2: ' . $lon2 . '<br><br>';
    	/* tester
    	$lat1 = 32.1731565;
    	$lon1 = 34.8425521;
    	$lat2 = 33.1731765;
    	$lon2 = 35.8425721;
    	*/


    	$theta = $lon1 - $lon2;
 		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

  		$dist = acos($dist);

  		$dist = rad2deg($dist);

  		$dist_meter = $dist * 60 * 1.1515 * 1.609344 *1000;
  		
  		$dist_meter = round($dist_meter,2);
  		
  		return $dist_meter;
    	
    }
    
   	function save_obj($obj)
    {

    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());

    	$obj['_id'] = $id;
    	$obj['db_insert_time'] = new MongoDate();

    	$obj['is_score_calc'] = 'no';
    	$obj['score_calc_time'] = null;
    	$obj['distance_base_loc_id'] = null;
    	$obj['distance'] = null;
    	$obj['speed'] = null;

    	$obj['score_location_accuracy'] = null;
    	$obj['score_speed'] = null;
    	$obj['score_distance'] = null;
    	$obj['score_bt'] = null;
    	$obj['score_wifi'] = null;

    	$obj['score_total'] = null; // total location acore

    	$obj['is_zooz_calc'] = 'no';
    	$obj['zooz_calc_time'] = null;
    	$obj['zooz'] = null;// the zoozes that where calculated for distance from last location
    	

    	$result = $this->mongo_db->insert($this->get_collection_name(), $obj);

    	if ($result != $id)
    	{
    		$obj['save_message'] = 'db_error_insert';
    	}
    	else
    	{
    		$obj['save_message'] = 'insert_success';
    	}
    	
    	return $obj;
    	

    }
    function remove_by_id($id)
    {
              $this->mongo_db
    		 ->where(array("_id" => $id))
              ->limit(1)
             ->delete($this->get_collection_name());
    }
}
?>
