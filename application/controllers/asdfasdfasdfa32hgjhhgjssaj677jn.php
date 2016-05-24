<?php
//phpinfo();

class asdfasdfasdfa32hgjhhgjssaj677jn extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

	}



	public function index()
	{

		$user_id = 1;
		
		$this->load->model('users_model');
		$user_obj = $this->users_model->get_obj_by_user_id($user_id);
		
		$user_obj['zooz_distance_balance'] = ($user_obj['zooz_distance_balance']);
		echo $user_obj['zooz_distance_balance'];
		
		if(is_nan($user_obj['zooz_distance_balance']))
		{
			echo 'asdfasdf';
		}

	}
}