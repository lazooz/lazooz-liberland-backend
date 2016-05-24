<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Get_blockchain_transactions extends CI_Controller {


	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		log_message('debug', ' ******  get_blockchain_transactions start  ******');
		log_message('debug', 'get_blockchain_transactions $post parms: ' . json_encode($_POST));
		
		$response = array();
		
		
		$block_chain_server_secret = $this->config->item('block_chain_server_secret');
		$block_chain_server_ip = $this->config->item('block_chain_server_ip');
		
		$request_ip_address = $this->input->ip_address();
		
		$request_secret = $this->input->post('secret');
		$code = $this->input->post('code');
		
		
		if($request_secret != $block_chain_server_secret || $request_ip_address != $block_chain_server_ip)
		{
			$response['message'] = 'credentials_not_valid';
		}
		else 
		{
			$this->load->model('blockchain_code_model');
			
			// checks and burns the code if exists
			$is_code_exists = $this->blockchain_code_model->is_code_exists($code);
			
			if(!$is_code_exists)
			{
				$response['message'] = 'error_code_not_valid';
			}
			else
			{
					
				// creates a blockchain report doc
				$this->load->model('block_chain_report_model');
				$report_id = $this->block_chain_report_model->create_new_report();
					
				log_message('debug', 'get_blockchain_transactions $report_id: ' . $report_id);
				
				if(!($report_id > 0))
				{
					$response['message'] = 'error_creating_report_id';
				}
				else
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
				
					
			}
			
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
			
		
		
		
		
		
		log_message('debug', 'get_blockchain_transactions $response: ' . json_encode($response));
		log_message('debug', ' ******  get_blockchain_transactions end  ******');
		
		die(json_encode($response));

	}

	

}
