<?php
class Contacts_model extends CI_Model {

	
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
 		$this->load->model('users_model');
		 
    }
    
    function get_collection_name()
    {
    	return 'contacts';
    }
    
	function get_obj_by_user($user_id)
    {
    	$where = array();
    	$where['user_id'] = $user_id;
    	
    	$obj_list = $this->mongo_db->where($where)
    							->get($this->get_collection_name());
    	return $obj_list ;
    							
    }
    
    function get_contact_count_for_user($user_id)
    {
    	$where = array();
    	$where['user_id'] = $user_id;
    	 
    	$count = $this->mongo_db->where($where)
    							->count($this->get_collection_name());
    	
    	return $count ;
    }
    
	function get_obj_by_cellphone_int($user_id,$cellphone_int)
    {
    	$where = array();
    	$where['user_id'] = $user_id;
    	$where['cellphone_int'] = $cellphone_int;
    	
    	
    	$obj_list = $this->mongo_db->where($where)
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
    
    
    function is_cellphone_exists_for_user($obj)
    {
    	$user_id = $obj['user_id'];
    	$cellphone_int = $obj['cellphone_int'];
    	
    	$where = array();
    	$where['user_id'] = $user_id;
    	$where['cellphone_int'] = $cellphone_int;
    	
    	//$count = $this->mongo_db->where('recommending_user_id', $recommending_user_id)
    	$count = $this->mongo_db->where($where)
    							->where_ne('_id' , $obj['_id'])
 								->count($this->get_collection_name());
 								
		if($count > 0)
		{
			$r = true;
		} 				
		else 
		{
			$r = false;
		}

		return $r; 
    }
  
    function clean_cellphone($cellphone)
    {
    	$cellphone = str_replace ('-','',$cellphone);
		$cellphone = str_replace (' ','',$cellphone);
		$cellphone = str_replace ('+','',$cellphone);
		$cellphone = str_replace ('.','',$cellphone);
		$cellphone = str_replace ('_','',$cellphone);
		$cellphone = str_replace ('(','',$cellphone);
		$cellphone = str_replace (')','',$cellphone);
		
		return $cellphone;
    }
    
    function is_cellphone_valid($cellphone)
    {

    	$is_valid = true;

    	$cellphone_int = filter_var($cellphone, FILTER_SANITIZE_NUMBER_INT);

    	if(substr($cellphone,0,1) == '0')
    	{
    		$cellphone_int = '0' . $cellphone_int;
    	}
    	else
    	{
    		$cellphone_int = '' . $cellphone_int;
    	}

    	if($cellphone_int != $cellphone || strlen($cellphone) < 7 || strlen($cellphone) >  20)
    	{
    		$is_valid = false;
    	}

    	return $is_valid;
    }

    function is_email_valid($email)
    {

    	$is_valid = false;
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          $is_valid = true;
        }
    	return $is_valid;
    }
    
    
    function calc_and_save_contact_user_id($obj)
    {
    	$contact_user_obj = $this->users_model->get_obj_by_cellphone($obj['cellphone_int']);
    	
    	$contact_user_id = $contact_user_obj['_id'];
    	
    	if($contact_user_id > 0)
    	{
    		$data['contact_user_id'] = $contact_user_id;
    		
    		$updated = $this->mongo_db->where('_id', $obj['_id'])
											->set($data)
											->update($this->get_collection_name());
    	}
    	
    	return $contact_user_obj;
    } 
    
   	function save_obj($obj)
    {
    	$current_obj = $this->get_obj_by_cellphone_int($obj['user_id'],$obj['cellphone_int']);
    	
    	if(!($current_obj['_id'] > 0))
    	{
	    	$this->load->model('sequence_model');
   		 	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	
  		  	$obj['_id'] = $id;
    		$obj['db_insert_time'] = new MongoDate();
    		$obj['contact_user_id'] = null;
    	
    		$result = $this->mongo_db->insert($this->get_collection_name(), $obj);
    	
    		if ($result != $id)
    		{
    			$obj['save_message'] = 'db_error_insert';
    		}
    		else
    		{
    			$obj['save_message'] = 'insert_success';
    		}
    	}
    	else 
    	{
    		$obj = $current_obj;
    		$obj['save_message'] = 'contact_exists';
    		
    	}
    	
    	return $obj;
    	
    	
    }
}
?>
