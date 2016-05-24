<?php
class Client_report_issue_model extends CI_Model {


 
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
		 
    }
    
    function get_collection_name()
    {
    	return 'client_report_issue';
    }
    
    
    
    
    function get_obj_for_screen()
    {
    	
    	$obj_list = $this->mongo_db
    	->order_by(array('_id' => 'DESC'))
    	->limit(500)
    	//->where($where)
    	->get($this->get_collection_name());
    	
    	$obj_list_r = array();
    	
    	if(sizeof($obj_list))
    	{
    		$this->load->model('users_model');
    		
    		foreach ($obj_list as $obj_list_tmp)
    		{
    			//print_r($obj_list_tmp);
    			
    			if(!isset($obj_list_tmp['cellphone']))
    			{
    				$user_id = intval($obj_list_tmp['user_id']);
    				$user_obj = $this->users_model->get_obj_by_user_id($user_id);
    				$obj_list_tmp['cellphone'] =  $user_obj['cellphone'];
    				
    			//	echo $obj_list_tmp['cellphone'] . '<br>';
    				
    			}
    		
    			$obj_list_r[] = $obj_list_tmp;
    		}
    		
    	}
    	
    	
    	return $obj_list_r;
    
    }

    
    
    function save_obj($user_id,$subject,$desc,$cellphone,$client_current_build_num,$server_version)
    {
    	 
   		// save new
   		$this->load->model('sequence_model');
   		$id = $this->sequence_model->get_sequence($this->get_collection_name());
    
   		$obj['_id'] = $id;
   		$obj['user_id'] = $user_id;
   		$obj['created'] = new MongoDate();
   		$obj['subject'] = $subject;
   		$obj['desc'] = $desc;
   		
   		$obj['cellphone'] = $cellphone;
   		$obj['current_build_num'] = $client_current_build_num;
   		$obj['server_version'] = $server_version;
   		
    
   		$result_id = $this->mongo_db->insert($this->get_collection_name(), $obj);
   		
   		return $result_id;
    
    }
    
    
    
    
    
    
}
?>
