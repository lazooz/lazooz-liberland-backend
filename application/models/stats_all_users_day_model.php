<?php
class Stats_all_users_day_model extends CI_Model {
	

	
	
	
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
 		
		 
    }

    function calc_time_from_date($date)
    {
    	$day = intval(substr($date,6,2));
    	$month = intval(substr($date,4,2));
    	$year = intval(substr($date,0,4));
    	 
    	$time = mktime( 0, 0, 0, $month , $day, $year );
    	 
    	return $time;
    }
    
    
    function get_all_data_array()
    {
    	
    	 
    	$obj_list = $this->mongo_db->order_by(array('day' => 'ASC'))
    	//   	->limit(1)
    	->get($this->get_collection_name());
    	 
    	 
    	 
    	//	print_r($obj_list);die;
    	 
    	 
    	
    	$obj_list_out = array();
    	 
    	if(sizeof($obj_list) > 0)
    	{
    		$first_day_obj = $obj_list[0];
    		$the_first_day = $first_day_obj['day'];
    		$first_day_time = $this->calc_time_from_date($the_first_day);
    	
    		$days_passed = ceil ((time() - $first_day_time)/ (60 * 60 * 24));
            $days_passed = $days_passed-1;

    	
    		$obj_list_array = array();
    		$day_index = 0;
    		// create key val with user data
    		foreach ($obj_list as $value)
    		{
    			$day = $value['day'];
    			$day_index++;
    	
    			//echo '$day: ' . $day .  ' * ' . $the_day . '<br>';
    			$obj_tmp = array();
    			//$obj_tmp['date'] = $value['day'];
    			$obj_tmp['day'] = $day_index;
    			$obj_tmp['distance'] = round($value['distance'] / 1000);
    	
    	
    			$obj_list_array[$value['day']] = $obj_tmp;
    	
    			//	$the_day_desc = date ('Ymd',$the_time);
    			 
    		}
    	
    		for ($i = 0; $i < $days_passed; $i++)
    		{
    			$time = $first_day_time + (60 * 60 *24 * $i);
    		 
    			$day = intval(date('Ymd',$time));
    		 
    			if(isset($obj_list_array[$day]))
    			{
    				$obj_list_out[] = $obj_list_array[$day];
    			}
    			else
    			{
    				$obj_tmp = array();
    				//$obj_tmp['date'] = $value['day'];
    				$obj_tmp['day'] = $i;
    				$obj_tmp['distance'] = 0;
    	
    				$obj_list_out[] = $obj_tmp;
    	
    			}
   
    			 
    	
   			}
    	}
   		else
		{
			$the_first_day = null;
		}
    	
    	$r = array();
    	$r['obj_list_out'] = $obj_list_out;
    	$r['initial_date'] = $the_first_day;
    			 
    	//echo '$first_day_of_week_time: ' . date('Y-m-d H:i:s',$first_day_of_week_time) . '<br><br>';die;
    	//print_r($r);die;
    	return $r;
    	
    	
    	
    	
    	
    	/*
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	$obj_list_out_1 = array();
    	

    	
    	
    	if(sizeof($obj_list) > 0)
    	{
    		$first_day_obj = $obj_list[0];
    		$the_first_day = $first_day_obj['day'];
    		$day_index = 0;
    		
    		foreach ($obj_list as $value)
    		{
    			$day = $value['day'];
    			$the_day = intval(substr($day,6,2));
    			$day_index++;
    				
    			//echo '$day: ' . $day .  ' * ' . $the_day . '<br>';
    			$out_obj_tmp = array();
    			//$out_obj_tmp['day'] = $the_day;
    			$out_obj_tmp['day'] = $day_index;
    			$out_obj_tmp['distance'] = round($value['distance'] / 1000);
    		
    	
    			$obj_list_out_1[] = $out_obj_tmp;
    	
    				
    			//	$the_day_desc = date ('Ymd',$the_time);
    				
    	
    		}
    		
    		
    		$obj_list_out = array();
    		$days_passed = ceil ((time() - $the_first_day)/ (60 * 60 * 24));
    		$accum_users = 0;
    		
    		for ($i = 0; $i < $days_passed; $i++)
    		{
   		 		$obj_list_out_tmp = array();
    		 
    			if(isset($obj_list_out_1[$i]))
    			{
    				$accum_users += $obj_list_out_1[$i];
    		
    			}
    			 
    			$obj_list_out_tmp['day'] = $i;
    			$obj_list_out_tmp['count'] = $accum_users;
    			 
    			$obj_list_out[] = $obj_list_out_tmp;
    			 
    		
    		}
    		
    	}
    	else 
    	{
    		$the_first_day = null;
    	}
    	
    	$r = array();
    	$r['obj_list_out'] = $obj_list_out;
    	$r['initial_date'] = $the_first_day;
    	 
    	//echo '$first_day_of_week_time: ' . date('Y-m-d H:i:s',$first_day_of_week_time) . '<br><br>';die;
    	
    	return $r;
    	*/
    }
   
    
    function get_collection_name()
    {
    	return 'stats_all_users_day';
    }
   
    function get_obj_by_day($day)
    {
    	$where = array();
    	$where['day'] = $day;
    	
    
    	$obj_list = $this->mongo_db->where($where)
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
     	
    function save_or_update_obj($location_obj)
    {
    	
    	$distance = $location_obj['distace'];
    	$zooz = $location_obj['zooz'];
    	//$created_time = $location_obj['db_insert_time'];
    	$created_time = round($location_obj['loc_timestamp'] / 1000);
    	
    	//echo '$created_time: ' . $created_time . '<br>';
    	
    		
    	//$created_time = date( 'Y-m-d H:i:s', $created_time);
    		
    	$day = date( 'Ymd', $created_time);
    	$day = intval($day);
    	
    
    	
    	// check if obj exists for this month
    	$obj = $this->get_obj_by_day($day);
    	
    	if($obj['_id'] > 0)
    	{
    		// update 
    		if($distance != null)
    		{
    			$data['distance'] = $obj['distance'] + $distance;
    		}
    		
    		if($zooz != null)
    		{
    			$data['zooz'] = $obj['zooz'] + $zooz;
    		}
    		
    		$where = array();
    		$where['_id'] = $obj['_id'];
    		 
    		$updated = $this->mongo_db->where($where)
    		->set($data)
    		->update($this->get_collection_name());
    	}
    	else 
    	{
    		// save new
    		$this->load->model('sequence_model');
    		$id = $this->sequence_model->get_sequence($this->get_collection_name());
    		
    		$obj['_id'] = $id;
    		
    		
    		if($distance > 0)
    		{
    			$obj['distance'] = $distance;
    		}
    		else
    		{
    			$obj['distance'] = 0;
    		}
    		
    		if($zooz > 0)
    		{
    			$obj['zooz'] = $zooz;
    		}
    		else 
    		{
    			$obj['zooz'] = 0;
    		}
    		
    		
    		$obj['day'] = $day;
    		
    		$result_id = $this->mongo_db->insert($this->get_collection_name(), $obj);

    	}
    	
    	
    
    }
}
?>