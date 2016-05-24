

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Init_block_chain_report_status_update extends CI_Controller {


	public function __construct()
	{
		parent::__construct();

	}

	public function index($batch_token=null)
	{
		log_message('debug', ' ******  init_block_chain_report_status_update start  ******');
		log_message('debug', 'init_block_chain_report_status_update $post parms: ' . json_encode($_POST));
		
		
		if($batch_token == $this->config->item('batch_token'))
		{
			$this->load->model('blockchain_code_model');
			$blockchain_code_obj = $this->blockchain_code_model->create_and_save_obj();
				
			if(!($blockchain_code_obj['_id'] >0))
			{
				log_message('debug', 'error creating code');
			}
			else
			{
				$code = $blockchain_code_obj['code'];
				$response = $this->block_chain_report_model->send_initiate_block_chain_report_status_update($code);
			
				log_message('debug', 'response from block chain server: ' . $response);
			
			}
			
			
			log_message('debug', 'init_block_chain_report_status_update $response: ' . json_encode($response));
			log_message('debug', ' ******  init_block_chain_report_status_update end  ******');
			
			die(json_encode($response));
		}
		
		
		
		
		
		
		
		
		
		
		
		

	}

	

}
