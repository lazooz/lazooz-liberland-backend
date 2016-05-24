<?php
class User_distance_accumulative_daily_model extends CI_Model {

	
	/*
	 
======================================================================
object example	  
======================================================================



======================================================================

	  
	 */
 
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
		 
    }
    
    function get_collection_name()
    {
    	return 'user_distance_accumulative_daily';
    }
    
    
	function get_obj_by_user_id_and_time($user_id,$time)
    {
    	$where = array();
    	$where['user_id'] = $user_id;
    	$where['time'] = $time;
    	
    	
    	$obj_list = $this->mongo_db->where($where)
    							->get($this->get_collection_name())
    							->limit(1);
    							
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
    
    function save_or_update_obj($user_id,$time,$zooz,$distance)
    {
    	$obj = $this->get_obj_by_user_id_and_time($user_id,$time);
    	
    	if($obj['_id'] > 0)
    	{
    		$this->update_obj_by_id($obj['_id'],$zooz,$distance);
    		
    	}
    	
    	
    }
    
    
    function update_obj_by_id($id,$zooz,$distance)
    {
    	$data = array();
   		$data['zooz'] = $zooz;
   		$data['distance'] = $distance;
   		$data['last_update'] = new MongoDate();

   		$updated = $this->mongo_db->where('_id', $id)
						->set($data)
						->update($this->get_collection_name());
    }
    
    
    function create_and_save_new_obj($user_id,$time,$zooz,$distance)
    {
    	
    	$time = strtotime(date( 'Y-m-d', time() ) . ' 00:00:00');

    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	
    	$obj['_id'] = $id;
    	$obj['user_id'] = $user_id;
    	$obj['time'] = new MongoDate($time);
    	$obj['last_update'] = new MongoDate();
    	$obj['zooz'] = $zooz;
    	$obj['distance'] = $distance;
    	
    	$result = $this->mongo_db->insert($this->get_collection_name(), $obj);	
    	
    	return $obj;
    	
    }
    
     
   
}
?>
