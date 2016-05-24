<?php
class Block_chain_report_user_log_model extends CI_Model {


	function __construct()
	{
		parent::__construct();
		$this->load->library('mongo_db');
			
			
	}

	function get_collection_name()
	{
		return 'block_chain_report_user_log';
	}



	function save_user_transaction($report_id,$user_id,$public_key,$zooz,$type)
	{
		$this->load->model('sequence_model');
		$id = $this->sequence_model->get_sequence($this->get_collection_name());
		 
		$obj['_id'] = $id;
		$obj['user_id'] = $user_id;
		$obj['created'] = new MongoDate();
		$obj['report_id'] = $report_id;
		$obj['public_key'] = $public_key;
		$obj['zooz'] = $zooz;
		$obj['type'] = $type;
		$obj['status'] = 'created'; // balance_updated , tran_reported , tran_success , tran_error
		 
		$result_id = $this->mongo_db->insert($this->get_collection_name(), $obj);
		
		return $result_id;
		
	}
	
	
	function update_with_balance_update($log_id)
	{
		$data['status'] = 'balance_updated';
		 
		$where = array();
		$where['_id'] = $log_id;
		 
		$updated = $this->mongo_db->where($where)
		->set($data)
		->update($this->get_collection_name());
	}
	
	
	
	function update_with_tran_reported($report_id)
	{
		$data['status'] = 'tran_reported';
			
		$where = array();
		$where['report_id'] = $report_id;
			
		$updated = $this->mongo_db->where($where)
		->set($data)
		->update($this->get_collection_name());
	}
	
	
	

}
?>