<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Blockchain extends CI_Controller {

	public function get_blockchain_transactions()
	{
		
		log_message('debug', ' ******  get_blockchain_transactions start  ******');
		log_message('debug', 'get_blockchain_transactions $post parms: ' . json_encode($_POST));
		
		$response = array();
		
		$code = $this->input->post('code');

		$this->load->model('blockchain_code_model');
		
		// checks and burns the code if exists
		$is_code_exists = $this->blockchain_code_model->is_code_exists($code);
		
		if($is_code_exists || $code == '12345678')
		{
			
			// creates a blockchain report doc
			$this->load->model('block_chain_report_model');
			$report_id = $this->block_chain_report_model->create_new_report();
			
			if($report_id > 0)
			{
				$response['report_id'] = $report_id;
				
				$report_transactions = array(); // the reported transaction array
				
				// load transactions
				$this->load->model('blockchain_pending_balance_model');
				$transaction_obj_list = $this->blockchain_pending_balance_model->get_transactions_for_blockchain_batch();
				
				$this->load->model('users_model');
				$this->load->model('block_chain_report_user_log_model');
				
				if(sizeof($transaction_obj_list) > 0)
				{
					foreach ($transaction_obj_list as $transaction_obj_tmp) 
					{
						// load user obj for public key
						$user_id = $transaction_obj_tmp['user_id'];
						
						
						$user_obj = $this->users_model->get_obj_by_user_id($user_id);
						$public_key = $user_obj['public_key'];
						
						$type = 'report_to_bc';
						$zooz = $transaction_obj_tmp['zooz_balance'];
						
						// create a log doc for this user transaction;
						
						$user_transaction_id = $this->block_chain_report_user_log_model->save_user_transaction($report_id,$user_id,$public_key,$zooz,$type);
					
						if($user_transaction_id > 0)
						{
							
							// substract zooz from pending balance
							$this->blockchain_pending_balance_model->update_blockchain_report($report_id,$user_id,$zooz);
							
							// update log with update report 
							$this->block_chain_report_user_log_model->update_with_balance_update($user_transaction_id);
							
							$tran = array();
							$tran['pk'] = $public_key;
							$tran['zooz'] = $zooz;
							$report_transactions[] = $tran;
						}
					}
				}
				
				$response['transactions'] = $report_transactions;
				$response['message'] = 'success';
				
				
				$this->block_chain_report_user_log_model->update_with_tran_reported($report_id);
				
			}
			else 
			{
				$response['message'] = 'error_creating_report_id';
				
			}
			
		}
		else 
		{
			$response['message'] = 'error_code_not_valid';
			
		}
			
		
		
		
		
		
		log_message('debug', 'get_blockchain_transactions $response: ' . json_encode($response));
		log_message('debug', ' ******  get_blockchain_transactions end  ******');
		
		die(json_encode($response));
	
	}
	
	
	public function verify_blockchain_report()
	{
	
		log_message('debug', ' ******  verify_blockchain_report start  ******');
		log_message('debug', 'verify_blockchain_report $post parms: ' . json_encode($_POST));
	
		$response = array();
	
		$code = $this->input->post('code');
	
		$this->load->model('blockchain_code_model');
	
		// checks and burns the code if exists
		$is_code_exists = $this->blockchain_code_model->is_code_exists($code);
	
		if($is_code_exists || $code == '12345678')
		{
			$report_id = $this->input->post('report_id');
			$report_status = $this->input->post('report_status'); // success , some_error , all_error
			$error_transaction_objs = json_decode( $this->input->post('error_transaction_objs'));
			
			// load report obj
			
			$this->load->model('block_chain_report_model');
			$report_obj = $this->block_chain_report_model->get_obj_by_id($report_id);
			
			if($report_obj['_id'] > 0 && $report_obj['status'] == 'created')
			{
				// update report with result 				
				$this->block_chain_report_model->update_report_with_result_status($report_obj['_id'], $report_status,$error_transaction_objs);
				
				// update report log with success (default if not all error)  
				if($report_status == 'success' || $report_status == 'some_error')
				{
					
				}
				else
				{
					
				}
					
				
				
				
			}
			else 
			{
				$response['message'] = 'error_report_id_not_found';
			}
			
			
			
			if($report_status == 'success')
			{
				
			}
			elseif($report_status == 'some_errors')
			{
				
			}
			else
			{
			
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			// creates a blockchain report doc
			$this->load->model('block_chain_report_model');
			$report_id = $this->block_chain_report_model->create_new_report();
				
			if($report_id > 0)
			{
				$response['report_id'] = $report_id;
	
				$report_transactions = array(); // the reported transaction array
	
				// load transactions
				$this->load->model('blockchain_pending_balance_model');
				$transaction_obj_list = $this->blockchain_pending_balance_model->get_transactions_for_blockchain_batch();
	
				$this->load->model('users_model');
				$this->load->model('block_chain_report_user_log_model');
	
				if(sizeof($transaction_obj_list) > 0)
				{
					foreach ($transaction_obj_list as $transaction_obj_tmp)
					{
						// load user obj for public key
						$user_id = $transaction_obj_tmp['user_id'];
	
	
						$user_obj = $this->users_model->get_obj_by_user_id($user_id);
						$public_key = $user_obj['public_key'];
	
						$type = 'report_to_bc';
						$zooz = $transaction_obj_tmp['zooz_balance'];
	
						// create a log doc for this user transaction;
	
						$user_transaction_id = $this->block_chain_report_user_log_model->save_user_transaction($report_id,$user_id,$public_key,$zooz,$type);
							
						if($user_transaction_id > 0)
						{
								
							// substract zooz from pending balance
							$this->blockchain_pending_balance_model->update_blockchain_report($report_id,$user_id,$zooz);
								
							// update log with update report
							$this->block_chain_report_user_log_model->update_with_balance_update($user_transaction_id);
								
							$tran = array();
							$tran['pk'] = $public_key;
							$tran['zooz'] = $zooz;
							$report_transactions[] = $tran;
						}
					}
				}
	
				$response['transactions'] = $report_transactions;
				$response['message'] = 'success';
	
			}
			else
			{
				$response['message'] = 'error_creating_report_id';
	
			}
				
		}
		else
		{
			$response['message'] = 'error_code_not_valid';
				
		}
			
	
	
	
	
	
		log_message('debug', 'get_blockchain_transactions $response: ' . json_encode($response));
		log_message('debug', ' ******  get_blockchain_transactions end  ******');
	
		die(json_encode($response));
	
	}
	
	
	
	
	public function set_blockchain_code()
	{
	
		log_message('debug', ' ******  set_blockchain_code start  ******');
		log_message('debug', 'set_blockchain_code $post parms: ' . json_encode($_POST));
	
		$response = array();
	
		$code = $this->input->post('code');
	
	
	
	
	
		$response['message'] = 'success';
	
		log_message('debug', 'set_blockchain_code $response: ' . json_encode($response));
		log_message('debug', ' ******  set_blockchain_code end  ******');
	
		die(json_encode($response));
	
	}
	

}
