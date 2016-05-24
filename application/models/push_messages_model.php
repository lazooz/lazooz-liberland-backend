<?php
class Push_messages_model extends CI_Model {

	 
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
		 
    }
    
    function get_collection_name()
    {
    	return 'client_push_messages';
    }
    
    
    function get_last_notification_id()
    {
    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_current_sequence($this->get_collection_name());
    	
    	return $id;
    }
    
    function get_global_messages($from_number,$from_time)
    {
    	
    	$obj_list = $this->mongo_db
    	->where(array("is_global" => 'yes'))
    	->where_gt('created_time', $from_time)
    	->where_gt('_id', $from_number)
    	->order_by(array('_id' => 'ASC'))
    	->limit(100)
    	->get($this->get_collection_name());
    	
    	return $obj_list;
    }
    
    function get_private_messages($from_number,$user_id)
    {
    	//echo $user_id;die;
    	$where = array();
    	$where['is_global'] = 'no';
    	$where['user_id'] = $user_id;
    	
    	
    	
    	$obj_list = $this->mongo_db
    	->where($where)
    	->where_gt('_id', $from_number)
    	->order_by(array('_id' => 'ASC'))
    	->limit(100)
    	->get($this->get_collection_name());
    	
    	//print_r($obj_list);die;
    	 
    	return $obj_list;
    }
    
    
    function create_and_save_new_message($title,$body,$type,$is_popup,$is_notification,$is_global = 'yes',$user_id = null)
    {
    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	 
    	$user_id = intval($user_id);

        log_message('debug', ' ******  create_and_save_new_message  ******'.$user_id);

    	
    	$obj['_id'] = $id;
    	$obj['created_time'] = new MongoDate();
    	$obj['title'] = $title;
    	$obj['body'] = $body;
    	$obj['type'] = $type;//out_data
    	$obj['is_popup'] = $is_popup;
    	$obj['is_notification'] = $is_notification;
    	$obj['is_global'] = $is_global;
    	$obj['user_id'] = $user_id;
    	
    	$obj['status'] = 'active'; // active , not_active
    	
    	$result = $this->mongo_db->insert($this->get_collection_name(), $obj);
    	 
    	return $obj;
    	 
    }

    function create_and_save_new_message_2($title,$body,$type,$is_popup,$is_notification,$is_global = 'yes',$user_id = null)
    {

        $this->load->model('sequence_model');
        $id = $this->sequence_model->get_sequence($this->get_collection_name());

        $user_id = intval($user_id);

        log_message('debug', ' ******  create_and_save_new_message_2  ******'.$user_id);


        $obj['_id'] = $id;
        $obj['created_time'] = new MongoDate();
        $obj['title'] = $title;
        $obj['body'] = $body;
        $obj['type'] = $type;//out_data
        $obj['is_popup'] = $is_popup;
        $obj['is_notification'] = $is_notification;
        $obj['is_global'] = $is_global;
        $obj['user_id'] = $user_id;

        $obj['status'] = 'active'; // active , not_active


        $data2 = new stdClass();
        $data2->users=array($user_id);
        $data2->android = array(
            'collapseKey' => 'Optional',
            'data'=> array(
                'message'=>$body,
                'title'=>$title,
                'event' => 'LiveData',
                'is_notification' => $is_notification,
                'is_popup' => $is_popup,
            ));
        $data2->ios = array(
        'badge' => 0,
        'alert'=> 'msg',
        'sound'=>'soundname');

       // echo json_encode($obj);


        $options = array(
            'http' => array(
                'method'  => 'POST',
                'content' => json_encode( $data2 ),
                'header'=>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"
            )
        );
        log_message('debug', 'create_and_save_new_message_2 d2 ' .json_encode( $data2 ));


        $url = "http://52.19.29.83:8000/send/";

        $context  = stream_context_create( $options );
        $result = file_get_contents( $url, false, $context );
        $response = json_decode( $result );

        log_message('debug', 'create_and_save_new_message_2  ' . $response);

        //$result = $this->mongo_db->insert($this->get_collection_name(), $obj);

        return $obj;

    }
    
    
    
    function get_all_messages()
    {
    	$where = array();
    	
    	$obj_list = $this->mongo_db->where()
    	->order_by(array('_id' => 'DESC'))
    	->get($this->get_collection_name());
    	
    	
    	return $obj_list;
    }
    
    
}
?>