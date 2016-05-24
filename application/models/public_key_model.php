<?php
class Public_key_model extends CI_Model {

	
 
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
		 
    }
    
    function get_collection_name()
    {
    	return 'public_key';
    }
    
    
    function create_and_save_new_public_key($user_id,$public_key)
    {
    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	
    	$obj['_id'] = $id;
    	$obj['created_time'] = new MongoDate();
    	$obj['user_id'] = $user_id;
    	$obj['public_key'] = $public_key;
    	$obj['balance'] = 0;
    	$obj['balance_updated_time'] = null;
    	
    	$result = $this->mongo_db->insert($this->get_collection_name(), $obj);	
    	
    	return $obj;
    	
    }
    
    function is_public_key_exists_for_user($user_id,$public_key)
    {
    	$obj = $this->get_obj_by_user_id_and_public_key($user_id,$public_key);

    	if($obj['_id'] > 0)
    	{
    		$is_exists = true;
    	}
    	else 
    	{
    		$is_exists = false;
    	}

    	return $is_exists;
    			
    }
    
    
  	function get_obj_by_user_id_and_public_key($user_id,$public_key)
    {
    	
    	$obj_list = $this->mongo_db->where(array("_id" => $user_id,"public_key" => $public_key  ))
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
    
}
?>