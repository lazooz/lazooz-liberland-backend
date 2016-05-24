<?php
class Stats_users_month_model extends CI_Model {
	
	/*
	 
======================================================================
object example	  
======================================================================
{
  "_id" : 1,
  "user_id" : 1,
  "name" : "טל אלנקוה",
  "cellphone" : "025654333,317",
  "cellphone_int" : "97225654333",
  "db_insert_time" : ISODate("2014-07-20T15:40:40Z"),
  "contact_user_id" : null
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
    	return 'stats_users_month';
    }
   
    
    function get_year_to_date_data($user_id)
    {
    	$first_day_of_year_time = mktime( 0, 0, 0, 1 , 1,  date( 'Y', time()) );
    	 
    	$month = intval(date('Ym',$first_day_of_year_time));
    	//$day = 20140530;
    	
    	//	echo '$day: ' . $day . '<br>';
    	
    	$obj_list = $this->mongo_db
    	->where(array("user_id" => $user_id))
    	->where_gte('month', $month)
    	 	->order_by(array('month' => 'ASC'))
    	//   	->limit(1)
    	->get($this->get_collection_name());
    	
    	
    	
    	//	print_r($obj_list);die;
    	
    	
    	$obj_list_out = array();
    	$total_distance = 0;
    	
    	if($obj_list > 0)
    	{
    		foreach ($obj_list as $value)
    		{
    			$month = $value['month'];
    	
    			 
    			 
    			$the_month = intval(substr($month,4,2));
    	
    			//echo '$day: ' . $day .  ' * ' . $the_day . '<br>';
    			 
    			 
    			$out_obj_tmp = array();
    			$out_obj_tmp['month'] = $the_month;
    			$out_obj_tmp['distance'] = round($value['distance'] / 1000);
    			$total_distance += $out_obj_tmp['distance'];
    			 
    			$obj_list_out[] = $out_obj_tmp;
    			 
    	
    			//	$the_day_desc = date ('Ymd',$the_time);
    	
    			 
    		}
    	}
    	 
    	$r = array();
    	$r['obj_list_out'] = $obj_list_out;
    	$r['total_distance'] = $total_distance;
    	
    	//echo '$first_day_of_week_time: ' . date('Y-m-d H:i:s',$first_day_of_week_time) . '<br><br>';die;
    	 
    	return $r;
    	
    }
    
    
    
    function get_obj_by_month($month,$user_id)
    {
    	$where = array();
    	$where['user_id'] = $user_id;
    	$where['month'] = $month;
    
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
    	//$created_time = $this->mongo_db->get_php_time($location_obj['db_insert_time']);
    	$created_time = round($location_obj['loc_timestamp'] / 1000);
    	$user_id = $location_obj['user_id'];
    	
    	//$created_time = date( 'Y-m-d H:i:s', $created_time);
    	
    	$month = date( 'Ym', $created_time);
    	$month = intval($month);
    	
    	// check if obj exists for this month
    	$obj = $this->get_obj_by_month($month,$user_id);
    	
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
    		$obj['user_id'] = $user_id;
    		
    		if($distance != null)
    		{
    			$obj['distance'] = $distance;
    		}
    		else
    		{
    			$obj['distance'] = 0;
    		}
    		
    		if($zooz != null)
    		{
    			$obj['zooz'] = $zooz;
    		}
    		else
    		{
    			$obj['zooz'] = 0;
    		}
    		
    		
    		$obj['month'] = $month;
    		
    		$result_id = $this->mongo_db->insert($this->get_collection_name(), $obj);

    	}
    	
    	
    
    }
}
?>