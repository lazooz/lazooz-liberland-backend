
<?php
class Suspicious_users_model extends CI_Model {

	 
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
		 
    }
    
    function get_collection_name()
    {
    	return 'suspicious_users';
    }
    
    
    function create_and_save_obj($user_id,$payload_obj,$base_payload_obj)
    {
    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	 
    	$this->load->model('users_model');
    	$user_obj = $this->users_model->get_obj_by_user_id($user_id);
    	
    	
    	
    	
    	$obj['_id'] = $id;
    	$obj['created_time'] = new MongoDate();
    	$obj['user_id'] = $user_id;
    	$obj['cellphone'] = $user_obj['cellphone'];
    	$obj['user_obj'] = $user_obj;
    	$obj['payload_obj'] = $payload_obj;
    	$obj['base_payload_obj'] = $base_payload_obj;
    	 
    	$result = $this->mongo_db->insert($this->get_collection_name(), $obj);

    	return $obj;
    	 
    }
    
    function get_all_obj_for_admin_screen()
    {
    	$obj_list = $this->mongo_db
    	->order_by(array('_id' => 'DESC'))
    	->limit(1000)
    	->get($this->get_collection_name());
    	
    	
    	return $obj_list;
    }
    
}
?>