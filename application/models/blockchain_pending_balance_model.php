<?php
class Blockchain_pending_balance_model extends CI_Model {

	
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
    	return 'block_chain_pending_balance';
    }
   
    
    function get_transactions_for_blockchain_batch()
    {
    	$block_chain_minimum_zooz_for_report = $this->config->item('block_chain_minimum_zooz_for_report');
    	
    	$obj_list = $this->mongo_db->where($where)
    	->where_lt('zooz_balance' , $block_chain_minimum_zooz_for_report) // only above 5 zooz balance
    	->get($this->get_collection_name());
    	
    
    	return $obj_list;
    }
    
    function get_obj_by_user_id($user_id)
    {
    	$where = array();
    	$where['user_id'] = $user_id;
    	 
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
    
    
    function update_blockchain_report($report_id,$user_id,$zooz_balance_reported)
    {
    	$obj = $this->get_obj_by_user_id($user_id);
    	
    	if(!($obj['_id'] > 0))
    	{
    		$this->save_or_update_balance_for_user($user_id,0);
    		$obj = $this->get_obj_by_user_id($user_id);
    	}
    	
    	$data['zooz_balance'] = $obj['zooz_balance'] - $zooz_balance_reported;
    	$data['last_blockchain_updated'] = new MongoDate();
    	$data['zooz_reported_to_blockchain'] = $obj['zooz_reported_to_blockchain'] + $zooz_balance_reported;
    	$data['last_report_id'] = $report_id;
    	
    	$where = array();
    	$where['_id'] = $obj['_id'];
    	
    	$updated = $this->mongo_db->where($where)
    	->set($data)
    	->update($this->get_collection_name());
    	
    }
    
    
    function save_or_update_balance_for_user($user_id,$zooz_balance)
    {
    	$obj = $this->get_obj_by_user_id($user_id);
    	
    	if($obj['_id'] > 0)
    	{
    		// update 
    		$data['zooz_balance'] = $obj['zooz_balance'] + $zooz_balance;
    		$data['last_updated'] = new MongoDate();;
    		
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
    		$obj['last_updated'] = new MongoDate();
    		$obj['zooz_balance'] = $zooz_balance;
    		$obj['zooz_reported_to_blockchain'] = 0;
    		$obj['last_blockchain_updated'] = null;
    		$obj['last_report_id'] = null;
    		
    		 
    		$result = $this->mongo_db->insert($this->get_collection_name(), $obj);
    		
    		
    	}
    	
    	
    	
    	
    } 
    
   	
   	
}
?>