<?php
class Block_chain_report_model extends CI_Model {






	function __construct()
	{
		parent::__construct();
		$this->load->library('mongo_db');
			
			
	}

	function get_collection_name()
	{
		return 'block_chain_report';
	}
	
	
	function send_initiate_block_chain_report($code)
	{

		$block_chain_server_secret = $this->config->item('block_chain_server_secret');
		$block_chain_server_ip = $this->config->item('block_chain_server_ip');
		
		$url = "https://" . $block_chain_server_ip . "/initiate_block_chain_report";
		
		$post = array();
		
		$post['sectet'] = $block_chain_server_secret; 
		$post['code'] = $code;
		
		$postvars = http_build_query($post);
		
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
		curl_setopt($ch,CURLOPT_TIMEOUT, 20);
		$response = curl_exec($ch);
		//print "curl response is:" . $response;
		curl_close ($ch);
		
		return $response;
	}
	
	
	function send_initiate_block_chain_report_status_update($code)
	{
		$block_chain_server_secret = $this->config->item('block_chain_server_secret');
		$block_chain_server_ip = $this->config->item('block_chain_server_ip');
		
		$url = "https://" . $block_chain_server_ip . "/initiate_block_chain_report_status_update";
		
		$post = array();
		
		$post['sectet'] = $block_chain_server_secret;
		$post['code'] = $code;
		
		$postvars = http_build_query($post);
		
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
		curl_setopt($ch,CURLOPT_TIMEOUT, 20);
		$response = curl_exec($ch);
		//print "curl response is:" . $response;
		curl_close ($ch);
		
		return $response;
	}
	 

	function create_new_report()
	{
		// save new
		$this->load->model('sequence_model');
		$id = $this->sequence_model->get_sequence($this->get_collection_name());
		 
		$obj['_id'] = $id;
		$obj['created'] = new MongoDate();
		$obj['status'] = 'created'; // created , id_sent, success , some_error , all_error
		$obj['report_result_time'] = null;
		$obj['result_data'] = null;
		
		 
		$new_insert_id = $this->mongo_db->insert($this->get_collection_name(), $obj);
		
		$obj['_id'] = $new_insert_id;
		
		return $obj;
		
	}
	
	function update_report_status($status)
	{
		$data = array();
		
		$data['status'] = $status;
			
		$where = array();
		$where['_id'] = $id;
			
		$updated = $this->mongo_db->where($where)
		->set($data)
		->update($this->get_collection_name());
	
	
	}
	
	function update_report_with_result_status($id,$status,$error_transaction_objs)
	{
		$data['status'] = $status;
		$data['error_transaction_objs'] = $error_transaction_objs;
		 
		$where = array();
		$where['_id'] = $id;
		 
		$updated = $this->mongo_db->where($where)
		->set($data)
		->update($this->get_collection_name());
		
		
	}
	
	function get_obj_by_id($id)
	{
		 
		$obj_list = $this->mongo_db->where(array("_id" => $id  ))
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

}
?>