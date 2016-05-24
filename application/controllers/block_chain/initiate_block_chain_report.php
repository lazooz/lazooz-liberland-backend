
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Initiate_block_chain_report extends CI_Controller {


	public function __construct()
	{
		parent::__construct();

	}

	public function index($batch_token=null)
	{
		log_message('debug', ' ******  initiate_block_chain_report start  ******');
		
		if($batch_token == $this->config->item('batch_token'))
		{
			// create a new code for block chain query
				
			$this->load->model('blockchain_code_model');
			$blockchain_code_obj = $this->blockchain_code_model->create_and_save_obj();
				
			if(!($blockchain_code_obj['_id'] >0))
			{
				log_message('debug', 'error creating code');
			}
			else
			{
				$code = $blockchain_code_obj['code'];
				$response = $this->block_chain_report_model->send_initiate_block_chain_report($code);
			
				log_message('debug', 'response from block chain server: ' . $response);
			
			}
				
			
			
				
		}
			
		
		log_message('debug', ' ******  initiate_block_chain_report end  ******');

	}

	

}
