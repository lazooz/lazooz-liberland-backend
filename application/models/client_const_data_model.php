<?php
class Client_const_data_model extends CI_Model {


 
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
		 
    }
    
    function get_collection_name()
    {
    	return 'client_const_data';
    }
    
    
    
    
    function get_obj_by_key($key)
    {
    	$where = array();
    	$where['key'] = $key;
    
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
    
    function get_value_by_key($key)
    {
    	
    	$obj = $this->get_obj_by_key($key);
    
    	if(isset($obj['value']))
    	{
    		$value =$obj['value'];
    	}
    	else 
    	{
    		$value = null;
    	}
    	
    	
    	return $value ; 
    
    }   
    
    
    function save_or_update_obj($key,$value)
    {
    	 
    	
    	$obj = $this->get_obj_by_key($key);
    	 
    	if($obj['_id'] > 0)
    	{
    		// update
    		$data['value'] = $value;
    
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
    		$obj['key'] = $key;
    		$obj['value'] = $value;
    
    		$result_id = $this->mongo_db->insert($this->get_collection_name(), $obj);
    
    	}
    	 
    	 
    
    }
    
    
    
    
    
    
}
?>
