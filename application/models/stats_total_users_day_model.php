
<?php
class Stats_total_users_day_model extends CI_Model {
	
	
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
 		
		 
    }
    
    function get_collection_name()
    {
    	return 'stats_total_users_day';
    }
   
    
    function get_all_data()
    {
//    	$first_day_of_year_time = mktime( 0, 0, 0, 1 , 1,  date( 'Y', time()) );
    	 
  //  	$month = intval(date('Ym',$first_day_of_year_time));
    	//$day = 20140530;
    	
    	//	echo '$day: ' . $day . '<br>';
    	
    	$obj_list = $this->mongo_db
    
    	 	->order_by(array('day' => 'ASC'))
    	//   	->limit(1)
    	->get($this->get_collection_name());
    	
    	
    	
    	//	print_r($obj_list);die;
    	
    	
    	$obj_list_out = array();
    	
    	$day_counter = 1;
    	
    	if(sizeof($obj_list) > 0)
    	{
    		$initial_obj = $obj_list[0]; 
    		
    		$initial_date = $initial_obj['day'];
    		
    		foreach ($obj_list as $value)
    		{
    			 
    			
    			
    			$out_obj_tmp = array();
    			$out_obj_tmp['day'] = $day_counter;
    			$out_obj_tmp['count'] = $value['users_count']; 
    			
    			$day_counter += 1;
    			 
    			$obj_list_out[] = $out_obj_tmp;
    			 
    		}
    	}
    	else
    	{
    		$initial_date = null;
    	}
    	 
    	$r = array();
    	$r['obj_list_out'] = $obj_list_out;
    	$r['initial_date'] = $initial_date;
    	
    	//echo '$first_day_of_week_time: ' . date('Y-m-d H:i:s',$first_day_of_week_time) . '<br><br>';die;
    	 
    	return $r;
    	
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
    
    
    function calc_stats_total_users_day()
    {
    	$this->save_or_update_obj();
    }
    
     	
    function save_or_update_obj()
    {
    	$this->load->model('users_model');
    	$active_users_this_day_count = $this->users_model->get_active_users_last_30_days(); // active user is a user that connected the last month
    	
    	
    	//$created_time = date( 'Y-m-d H:i:s', $created_time);
    	
    	$day = date( 'Ymd', time());
    	$day = intval($day);
    	
    	// check if obj exists for this month
    	$obj = $this->get_obj_by_day($day);
    	
    	if($obj['_id'] > 0)
    	{
    		// update 
			$data['users_count'] = $active_users_this_day_count;	
    		
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
    		$obj['users_count'] = $active_users_this_day_count;
    		
    		$obj['day'] = $day;
    		
    		$result_id = $this->mongo_db->insert($this->get_collection_name(), $obj);

    	}
    	
    	
    
    }
}
?>