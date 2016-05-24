<?php
class Friend_recommend_requests_model extends CI_Model {

	
	/*
	 
======================================================================
object example	  
======================================================================

{
  "_id" : 6,
  "recommending_user_id" : 10,
  "new_user_id" : 12,
  "created_time" : ISODate("2014-07-13T07:55:42Z"),
  "name" : "Gil",
  "cellphone" : "+972525656245",
  "token_md" : "a2c08e082b4f67aad9e010a4b11943506268e2d2",
  "status" : "created", // used used_phone_exists
  "tries" : 0,
  "used_time" : ISODate("2014-07-13T07:59:53Z")
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
    	return 'friend_recommend_requests';
    }
    
    function get_obj_by_recommending_user_id_and_cellphone($recommending_user_id,$cellphone)
    {
    	$where = array();
    	$where['recommending_user_id'] = $recommending_user_id;
    	$where['cellphone'] = $cellphone;
    	 
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
    	 
    	return $obj ;
    	 
    }
    
    function is_obj_exists_by_recommending_user_id_and_cellphone($recommending_user_id,$cellphone)
    {
    	$obj = $this->get_obj_by_recommending_user_id_and_cellphone($recommending_user_id,$cellphone);
    	
    	if($obj['_id'] > 0)
    	{
    		$r = true;
    	}
    	else 
    	{
    		$r = false;
    	}
    	
    	return $r;
    	
    }
    
    function get_send_recommendation_case($obj)
    {
    	
    	if($obj['_id'] == 0)
    	{
    		$r = 'create';
    	}
    	else
    	{
    		if(!isset($obj['send_count']))
    		{
    			$obj['send_count'] = 1;
    		}
    		
    		if($obj['send_count'] < 5 && intval($obj['new_user_id'] ) == 0)
    		{
    			$r = 'again';
    		}
    		else 
    		{
    			$r = 'dont';
    		}
    	}
    	
    	return $r;
    	
    	
    }
    
    function update_for_send_request_again($obj)
    {
    	$id = intval($obj['_id']);
    	$send_count = intval($obj['send_count']) + 1;
    	
    	$this->load->helper('string');
    	//$token =  random_string('alpha', 8);
    	$token =  rand(100000000,999999999);
    	 
    	$this->load->library('encrypt');
    	$token_md = $this->encrypt->sha1($token);
    	
    	
    	$data['status'] = 'created';
    	$data['send_count'] = $send_count;
    	$data['token_md'] = $token_md;
    	$data['tries'] = 0;
    	$data['used_time'] = null;
    	
    	$updated = $this->mongo_db->where('_id', $id)
    	->set($data)
    	->update($this->get_collection_name());
    	
    	$obj['token'] = $token;
    	$obj['save_message'] = 'insert_success';
    	 
    	return $obj;
    	
    	
    }
    
	function create_and_save_request($recommending_user_id,$name,$cellphone)
    {
    	$recommending_user_id = intval($recommending_user_id);
    	
    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	
    	$this->load->helper('string');
    	//$token =  random_string('alpha', 8);
    	$token =  rand(100000000,999999999);
    	
    	$this->load->library('encrypt');
    	$token_md = $this->encrypt->sha1($token);
    	
    	$obj['_id'] = $id;
    	$obj['recommending_user_id'] = $recommending_user_id;
    	$obj['new_user_id'] = null;
    	$obj['created_time'] = new MongoDate();
    	$obj['name'] = $name;
    	$obj['cellphone'] = $cellphone;
    	$obj['token_md'] = $token_md;
    	$obj['status'] = 'created';
    	$obj['tries'] = 0;
    	$obj['send_count'] = 1;
    	$obj['used_time'] = null;
    	
    	$result = $this->mongo_db->insert($this->get_collection_name(), $obj);
    	
    	if ($result != $id)
    	{
    		$obj['save_message'] = 'db_error_insert';
    	}
    	else
    	{
    		$obj['save_message'] = 'insert_success';
    	}
    	
    	$obj['token'] = $token;
    	
    	return $obj;
    	
    }
    
    function get_obj_by_user($recommending_user_id)
    {
    	$where = array();
    	$where['recommending_user_id'] = $recommending_user_id;
    	
    	$obj_list = $this->mongo_db->where($where)
    							->get($this->get_collection_name());
    	return $obj_list ;
    	
    }
    
    
    
    function get_recommended_contacts_that_arent_users_by_user($recommending_user_id)
    {
    	$where = array();
    	$where['recommending_user_id'] = $recommending_user_id;
    	$where['status'] = 'created';
    	 
    	$obj_list = $this->mongo_db->where($where)
    	->get($this->get_collection_name());
    	
    	
    	$response_objs = array();
    	
    	if(sizeof($obj_list) > 0)
    	{
    		foreach ($obj_list as $obj_tmp)
    		{
    			$response_objs[] = $obj_tmp['cellphone'];
    			
    		}
    	}
    	
    	return $response_objs ;
    	 
    }
    
    
    
    function calc_number_of_recommendation_for_recommending_user($recommending_user_id)
    {
    	$where = array();
    	$where['recommending_user_id'] = $recommending_user_id;
    	$where['status'] = 'used';
    	
    	//$count = $this->mongo_db->where('recommending_user_id', $recommending_user_id)
    	$count = $this->mongo_db->where($where)
 								->count($this->get_collection_name());
												
		return $count;
    }
    
    function is_phone_exists_for_recommending_user($obj)
    {
    	$recommending_user_id = $obj['recommending_user_id'];
    	$cellphone = $obj['cellphone'];
    	
    	$where = array();
    	$where['recommending_user_id'] = $recommending_user_id;
    	$where['cellphone'] = $cellphone;
    	
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
    
    
 	function validate_token($new_user_id,$friend_request_token)
    {
    	$r = 'not_valid';
    	
    	$token_array = explode('-',$friend_request_token);
    	
    	if(sizeof($token_array) == 2)
    	{
	    	$token = $token_array[0]; 
   		 	$id = $token_array[1];
   		 	
   		 	//echo $token;die;
    	
	    	$this->load->library('encrypt');
   		 	$token_md = $this->encrypt->sha1($token);
    	
    		$id = intval($id);
    	
   		 	$obj_list = $this->mongo_db->where(array("_id" => $id ,"status" => "created" ))
    								->get($this->get_collection_name());
		
    		if(sizeof($obj_list) > 0)
    		{
    			$obj = $obj_list[0];
    			
    			$confirmation_backdoor_code = $this->config->item('confirmation_backdoor_code');
    			
    			if($token == $confirmation_backdoor_code && strlen($friend_request_token) > 5)
    			{
    				$is_backdoor_code = true;
    				 
    			}
    			else
    			{
    				 
    				$is_backdoor_code = false;
    			}
    			
    		
    			if($obj['token_md'] == $token_md || $is_backdoor_code)
    			{
    				if($new_user_id != $obj['recommending_user_id'] )
    				{

    					$is_phone_exists_for_recommending_user = $this->is_phone_exists_for_recommending_user($obj);
    					
	    				$r = 'valid';
   		 				// update obj status to used
   		 				
	    				if(!$is_phone_exists_for_recommending_user)
    					{
    						$data['status'] = 'used';
    					}
    					else
    					{
    						$data['status'] = 'used_phone_exists';
    					} 
    					
   	 					$data['new_user_id'] = $new_user_id;
    					$data['used_time'] = new MongoDate();
    		
    					$updated = $this->mongo_db->where('_id', $obj['_id'])
											->set($data)
											->update($this->get_collection_name());
    				}
    				else 
    				{
    					$r = 'error_recommending_user_same_as_new';
    				}
    				
    			}
    			else 
    			{
    				//todo count not valid requests
    			}
	    	}
    	}
    	
    	$obj['is_valid'] = $r;
    	
    	return $obj;
    }
    
}
?>