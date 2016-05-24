<?php
class Sequence_model extends CI_Model {


    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
		 
    }
    
    function get_current_sequence($collection)
    {
    	$obj_list = $this->mongo_db->where(array("_id" => $collection ))
    	->get('sequence');
    		
    	//print_r($obj_list);die;
    	
    	//echo sizeof($obj_list);
    		
    	if(sizeof($obj_list) > 0)
    	{
    		$obj = $obj_list[0];
    	
    		if(is_nan(@$obj['seq']))
    		{
    			$obj['seq'] = 0;
    		}
    	
    		
    		 
    	}
    	else
    	{
    		$obj = array();
    		$obj['seq'] = 0;
    	
    	}
    	return $obj['seq'];
    		
    }
    
    function get_sequence($collection)
    {
    	$is_error = false;
    	
    	 $q = array('findandmodify' => 'sequence',
		 		'query' => array('_id' => $collection),
		 		'update' => array('$inc' => array('seq' => 1)),
		 		'new' => TRUE);
    	 

    	 $result = $this->mongo_db->command($q);
    	

    	//Array ( [value] => Array ( [_id] => users [seq] => 3 ) [lastErrorObject] => Array ( [updatedExisting] => 1 [n] => 1 ) [ok] => 1 )
    			
    	if(isset($result['value']['seq']))
    	{
    		$seq = $result['value']['seq'];
    		
    		if($result['ok'] != 1)
    		{
    			$is_error = true;
    		}
    	}
    	else
    	{
    		$result = $this->mongo_db->insert('sequence', array('_id'=>$collection,'seq'=>1));
    		
    		$seq = 1;
    		
    		if($result != $collection)
    		{
    			$is_error = true;
    		}
    	}
    	
    	
    	if($is_error)
    	{
    		die('db_sequence_error');
    	}
    	
    	return $seq;
    	
    }

   
}
?>