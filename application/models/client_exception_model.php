<?php
class Client_exception_model extends CI_Model {

	
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
    	return 'client_exception_model';
    }
    
	
    
   	function save_obj($obj)
    {
      	$this->load->model('sequence_model');
   		$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	
  		$obj['_id'] = $id;
    	$obj['db_insert_time'] = new MongoDate();

    	
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