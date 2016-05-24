<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Register extends CI_Controller {

	public function index()
	{
		
		log_message('debug', ' ******  register_user start  ******');
		log_message('debug', 'register_user $post parms: ' . json_encode($_POST));
		

		$cellphone = $this->input->post('cellphone');
        $accountname = $this->input->post('accountname');

		$this->load->model('contacts_model');

         $is_email_valid = $this->contacts_model->is_email_valid($accountname);
         if ($is_email_valid == false)
          $accountname = null;



		$cellphone = $this->contacts_model->clean_cellphone($cellphone);

		$is_cellphone_valid = $this->contacts_model->is_cellphone_valid($cellphone);


		if($is_cellphone_valid)
		{

			$this->load->model('registration_requests_model');

			// save a new registration request
			$obj = $this->registration_requests_model->create_and_save_request($cellphone,$accountname);

			if($obj['save_message'] != 'insert_success')
			{
				$response['message'] = $obj['save_message'];
			}
			else
			{

				// send a new sms message
				$this->load->model('sms_messages_model');

				$this->sms_messages_model->send_registration_sms($cellphone,$obj['token']);

				$response['message'] = 'success';
				$response['registration_request_id'] = $obj['_id'];

			}


		}
		else if($is_email_valid)
		{
		    $this->load->model('registration_requests_model');

			// save a new registration request
            log_message('debug', ' ******  register_user 1  ******');
			$obj = $this->registration_requests_model->create_and_save_request(null,$accountname);

			if($obj['save_message'] != 'insert_success')
			{
				$response['message'] = $obj['save_message'];
			}
			else
			{


				$response['message'] = 'success';
				$response['registration_request_id'] = $obj['_id'];

			}


		}
        else
        {
          $response['message'] = 'error_cell_not_valid';
        }



		log_message('debug', 'register_user $response: ' . json_encode($response));
		log_message('debug', ' ******  register_user end  ******');

		die(json_encode($response));

	}


	public function validation()
	{

		log_message('debug', ' ******  register_validation start  ******');
		log_message('debug', 'register_validation $post parms: ' . json_encode($_POST));
		
		$response = array();
		$response['message'] = 'error';
		
		$registration_request_id = $this->input->post('registration_request_id');
		$registration_request_token = $this->input->post('registration_request_token');
		$public_key = $this->input->post('public_key');

		$friend_request_token = $this->input->post('registration_request_recommendation_token');
		

		$this->load->model('registration_requests_model');
		
		$validate_token_response = $this->registration_requests_model->validate_token($registration_request_id,$registration_request_token);



		if ($validate_token_response['is_valid'] == 'valid')
		{
			//create new user

			$cellphone = $validate_token_response['cellphone'];
            $accountname = $validate_token_response['accountname'];
			
			$this->load->model('users_model');

			// check if user exists on db
            if ($cellphone != null)
            {
			 $user_obj = $this->users_model->get_obj_by_cellphone($cellphone);

            }
            else
            {
                $user_obj = $this->users_model->get_obj_by_accountname($accountname);
            }

			$this->load->model('public_key_model');

			if($user_obj['_id'] > 0)
			{
				$is_new_user = 'no';
				$user_obj = $this->users_model->create_and_update_secret($user_obj,$public_key);

				$is_public_key_exists_for_user = $this->public_key_model->is_public_key_exists_for_user($user_obj['_id'],$public_key);

				if(!$is_public_key_exists_for_user)
				{
					$this->public_key_model->create_and_save_new_public_key($user_obj['_id'],$public_key);

				}
                $this->load->model('users_model');
                $obj_accountname = $this->users_model->get_obj_by_accountname($accountname);
                $obj_cellphone = $this->users_model->get_obj_by_cellphone($cellphone);
                if (($obj_cellphone['_id'] > 0)&& ($obj_accountname['_id'] > 0) && ($obj_cellphone['_id']!=$obj_accountname['_id']))
                {

                   $user_obj = $this->users_model->merge_account($obj_cellphone,$obj_accountname);
                }


			}
			else
	        {

	            $user_obj = $this->users_model->get_obj_by_accountname($accountname);
                if($user_obj['_id'] > 0)
                {
                  $is_new_user = 'no';
                  $user_obj = $this->users_model->create_and_update_secret($user_obj,$public_key);
                  $user_obj = $this->users_model->update_user_cellphone($user_obj,$cellphone);

				   $is_public_key_exists_for_user = $this->public_key_model->is_public_key_exists_for_user($user_obj['_id'],$public_key);
			      {
					$this->public_key_model->create_and_save_new_public_key($user_obj['_id'],$public_key);

				   }

                }
                else
                {

			    	$is_new_user = 'yes';
			    	$user_obj = $this->users_model->create_and_save_new_user($cellphone,$public_key,$accountname);

			     	$this->public_key_model->create_and_save_new_public_key($user_obj['_id'],$public_key);




				// handle friend recommendation
			    	if(!($friend_request_token === false) || $friend_request_token != null)
			    	{
				    	$this->load->model('friend_recommend_requests_model');

				    	$validate_token_response = $this->friend_recommend_requests_model->validate_token($user_obj['_id'],$friend_request_token);


				    	if(isset($validate_token_response['recommending_user_id']))
				    	{
				    		$recommending_user_id = $validate_token_response['recommending_user_id'];
				    	}
				    	else
				    	{
				    		$recommending_user_id = null;
				    	}


				    	if($validate_token_response['is_valid'] == 'valid')
				    	{
				     		$response['recommendation_response_message'] = 'success';


						// check if to validate recommending user and give recommendation reward to recommender
					    	if($recommending_user_id > 0)
					     	{
							// give the recommending user reward zooz
					    		$this->users_model->update_zooz_balance_with_friend_reward($recommending_user_id);


						     	$number_of_recommendation = $this->friend_recommend_requests_model->calc_number_of_recommendation_for_recommending_user($recommending_user_id);

							// check recommender activation_status
						    	if($number_of_recommendation >=3)
						    	{
							    	$user_recommending_obj = $this->users_model->get_obj_by_user_id($recommending_user_id);

							    	if($user_recommending_obj['_id'] > 0 && $user_recommending_obj['activation_status'] != 'activated')
							     	{
								    	$this->users_model->update_activation_status_to_activated($user_recommending_obj['_id']);
							     	}

						     	}


				    	    }
				         	else
					        {
					     	$response['recommendation_response_message'] = 'token_not_valid';
				    	   }
				     }
                } //else
                }//else

				// handle friend recommendation end

			}//if



			if($user_obj['_id'] > 0)
			{
				$response['user_id'] = $user_obj['_id'];
				$response['user_secret'] = $user_obj['user_secret'];
				$response['is_new_user'] = $is_new_user;
                $response['message'] = 'success';
                if ($cellphone == null)
                {
    				$response['message'] = 'success_email';
                    }



			}
			else 
			{
				$response['message'] = 'error_db';
			}
			

		}
		else 
		{
			sleep(2);
		}
		
		log_message('debug', 'register_validation $response: ' . json_encode($response));
		log_message('debug', ' ******  register_validation end  ******');
		
		die(json_encode($response));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */