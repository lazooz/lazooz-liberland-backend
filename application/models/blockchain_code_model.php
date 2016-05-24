<?php
class Blockchain_code_model extends CI_Model {

	
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
    	return 'blockchain_code';
    }
    

    function is_code_exists($code)
    {
    	$obj = $this->get_obj_by_code($code);
    	
    	if($obj['_id'] > 0)
    	{
    		$r = true;
    		$this->update_code_as_used($obj['_id']);
    	}
    	else 
    	{
    		$r = false;
    	}
    	return $r;
    }
    
    
    
    function get_obj_by_code($code)
    {
    	$where = array();
    	$where['code'] = $code;
    	$where['status'] = 'created';
    	
    	$time = time()- 60 * 60 * 25; // last 25 hours
    	$time = new MongoDate($time);
    	 
    	 
    	$obj_list = $this->mongo_db->where($where)
    	->where_lt('db_insert_time' , $time)
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
    
    
    function update_code_as_used($id)
    {
    	
    	$data['status'] = 'used';
    		
    	$updated = $this->mongo_db->where('_id', $id)
											->set($data)
											->update($this->get_collection_name());
    	
    } 
    
   	
   	
   	
   	function create_and_save_obj()
    {
    	$this->load->helper('string');
    	
    	$code = random_string('alnum', 64);
		    	
    	$this->load->model('sequence_model');
   		$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	
  		$obj['_id'] = $id;
  		$obj['code'] = $code;
    	$obj['db_insert_time'] = new MongoDate();
    	$obj['status'] = 'created';
    	
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
}
?>