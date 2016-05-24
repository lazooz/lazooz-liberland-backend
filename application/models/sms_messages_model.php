<?php
class Sms_messages_model extends CI_Model {

	
	/*
	 
======================================================================
object example	  
======================================================================

{

  "_id" : , 7
  "created_time" : 7 ,
  "to" : 7 ,
  "from" : 7 ,
  "body" : 7 ,
  "status" : 7 ,  // created , process , sent , error
  "sent_time" : 7 ,
  "gw_response" : 7 


}


======================================================================

	  
	 */
 
    function __construct()
    {
        parent::__construct();
 		$this->load->library('mongo_db');
 		
 		require_once ('../application/libraries/twilio-php/Services/Twilio.php');
 		
 		$account_sid = $this->config->item('sms_twilio_account_sid');
    	$auth_token = $this->config->item('sms_twilio_auth_token');
    	$from_number = $this->config->item('sms_twilio_from_number');
/*
        $http = new Services_Twilio_TinyHttp(
            'https://api.twilio.com',
            array('curlopts' => array(
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            )));

        $this->client = new Services_Twilio($account_sid, $auth_token, "2010-04-01", $http);
*/
  	  	$this->client = new Services_Twilio($account_sid, $auth_token);
		 
    }
    
    function get_collection_name()
    {
    	return 'sms_messages';
    }
    
    
 	function send_friends_recommend_sms($user_id,$name,$to,$token,$download_link,$pesonal_message)
    {
    	$body = $pesonal_message . " La'Zooz app with a friend recommendation benefit. " . $download_link .  ' token: ' . $token . '. '; 
    		//			". join La'Zooz google community to download the App https://plus.google.com/u/0/communities/116028422996838948960";
    	
    	$obj = $this->insert_new_obj($to,$body,$user_id);
    	
    	$this->send_sms_obj_to_gw($obj);
		    	
    }

    
    
    
    function send_registration_sms($to,$token)
    {
    	$body = "La'Zooz app registration token: " . $token;
    	
    	$obj = $this->insert_new_obj($to,$body);
    	
    	$this->send_sms_obj_to_gw($obj);
		    	
    }
    
    function get_obj_by_id($id)
    {
    	$id = intval($id);
    	 
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
    
    function send_sms_obj_to_gw($obj)
    {
    	
    	$destination_number = $obj['to'];
    	
    	
    	$destination_number = str_replace('+','',$destination_number);
        if(substr($destination_number,0,9) == '972546778')
            //if(false)
        {
            $obj['gw'] = 'twilio';
            $this->send_sms_to_twilio($obj);
        }
    	elseif(substr($destination_number,0,3) == '972')
    	//if(false)
    	{           
    		$obj['gw'] = 'smartsms';
    		$this->send_sms_to_smartsms($obj);
    	}
    	elseif(substr($destination_number,0,4) == '7916')
    	//elseif(false)
    	{
    	  //moscow
    	  $obj['gw'] = 'clickatell';
    	  $this->send_sms_to_clickatell($obj);

    	}
        else {
            $obj['gw'] = 'twilio';
            $this->send_sms_to_twilio($obj);
        }
        /*
    	else
    	{
    	    $obj['gw'] = 'twilio';
    		$this->send_sms_to_twilio($obj);

    	}
        */

    		
    }

    
    function send_sms_to_smartsms($obj)
    {
    	$smart_sms_username = $this->config->item('smart_sms_username');
    	$smart_sms_password = $this->config->item('smart_sms_password');
    	
    	$destination_number = $obj['to'];
    	//$destination_number = substr($destination_number,3,strlen($destination_number) - 3);
    	
    	
    	
    	$from_number = $this->config->item('sms_twilio_from_number');
    	$from_number = str_replace("+","",$from_number);
    	
    	$smart_sms_url = "http://www.smartsms.co.il/member/http_sms_xml_api.php?function=singles";
    	
    	$body = $obj['body'];
    	
    	$body = str_replace("'","\'",$body);
    	
    	$xml = new SimpleXMLElement("<Request></Request>");
    	$xml->addChild("UserName",$smart_sms_username);
    	$xml->addChild("Password",$smart_sms_password);
    	$xml->addChild("Time",0);
    	$singles = $xml->addChild("Singles");
    	
    	
    	$single = $singles->addChild("Single");
    	$single->addChild("Message", $body);
    	$single->addChild("DestinationNumber", $destination_number);
    	$single->addChild("SourceNumber", $from_number);
    	$single->addChild("ClientReference", $obj['_id']);
    	
    	//echo $xml->asXML();die;
    	
    	try {
    		
    		$ch = curl_init();
    		curl_setopt($ch,CURLOPT_URL, $smart_sms_url);
    		curl_setopt($ch,CURLOPT_POST, 1);
    		curl_setopt($ch,CURLOPT_POSTFIELDS, "xml=". urlencode($xml->asXML()));
    		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    		$smart_sms_response = curl_exec($ch);
    		curl_close($ch);
    		//echo $smart_sms_response;
    		$obj['status'] = 'sent';
    		$obj['gw_response'] = $smart_sms_response;
    		
    	 
    	} catch (Exception $e)
    	{
    		//echo $e->getMessage();
    	
    		$obj['gw_response'] = $e->getMessage();
    		$obj['status'] = 'error';
    	}
    	 
    	 
    	$this->update_message_status($obj);
    	
    	
    }
    
    function encode_text_for_unicode_message($data)
    {
    	$mb_hex = '';
    	for($i = 0 ; $i<mb_strlen($data,'UTF-8') ; $i++){
    		$c = mb_substr($data,$i,1,'UTF-8');
    		$o = unpack('N',mb_convert_encoding($c,'UCS-4BE','UTF-8'));
    		$mb_hex .= sprintf('%04X',$o[1]);
    	}
    	return $mb_hex;
    	
    }
    
    function send_sms_to_clickatell($obj)
    {
    	$clickatell_username = $this->config->item('clickatell_username');
    	$clickatell_sms_password = $this->config->item('clickatell_sms_password');
    	 
    	$destination_number = $obj['to'];
    	 
    	 
    	$from_number = $this->config->item('sms_twilio_from_number');
    	$from_number = str_replace("+","",$from_number);

    	$body = $obj['body'];
    
    	$body = substr($body,0,130);
    	$body = $this->encode_text_for_unicode_message($body);
    	 
    	$clickatell_url = "https://api.clickatell.com/http/sendmsg" . 
    						"?user=" . $clickatell_username . 
    						"&password=" . $clickatell_sms_password . 
    						"&api_id=3505323" . 
    						"&to=" . $destination_number . 
    						"&text=" . $body . 
    						"&unicode=1" . 
    						"&concat=2";
    	
    	try {
    
    		$clickatell_sms_response = file_get_contents($clickatell_url);
    		
    		//echo $clickatell_sms_response;

    		$obj['status'] = 'sent';
    		$obj['gw_response'] = $clickatell_sms_response;
    
    
    	} catch (Exception $e)
    	{
    		//echo $e->getMessage();
    		 
    		$obj['gw_response'] = $e->getMessage();
    		$obj['status'] = 'error';
    	}
    
    
    	$this->update_message_status($obj);
    	 
    	 
    }
    
    
    function send_sms_to_twilio($obj)
    {

    	try {

    	$sms = $this->client->account->messages->sendMessage(
          	$obj['from'] , 
            "+" . $obj['to'],
            $obj['body']
        );
/*
            $sms=$this->client->account->messages->create(array(
                'To' => "+".$obj['to'],
                'From' => "+18559762579",
                'Body' => $obj['body'],

            ));
*/
        $obj['status'] = 'sent';
    	
   	//} catch (Services_Twilio_RestException $e)
        } catch (Exception $e)
    	{
    		//echo $e->getMessage();
            echo $e->getMessage();
    		$obj['gw_response'] = $e->getMessage();
    		$obj['status'] = 'error';
    	}
    	
    	
    	$this->update_message_status($obj);
    	
    	
    	
    }
    
    function send_test_sms_to_twilio()
    {
    	 
    	try {
    		 
    		$url = $this->config->item('base_url') . 'twilio_callback?aa=ii';
    		
    		$sms = $this->client->account->messages->sendMessage(
    				'+18559762579' ,
    				"+972525656245",
    				'ya man test'
    			
    		);
    
    		//echo $sms->MessageSid;
    		 
    	} catch (Exception $e)
    	{
    		echo $e->getMessage();
    
    		
    	}
    	 
    	 
    	
    	 
    	 
    	 
    }
    
    
    function get_sms_messages_from_twilio()
    {
    	 
    	try {
    		 
    	$messages = $this->client->account->messages->getIterator(0, 1, array(   
		)); 
 
		foreach ($messages as $message) { 
			echo $message->body; 
		}
    
    		$obj['status'] = 'sent';
    		 
    	} catch (Exception $e)
    	{
    		//echo $e->getMessage();
    
    		$obj['gw_response'] = $e->getMessage();
    		$obj['status'] = 'error';
    	}
    	 
    	 
    	$this->update_message_status($obj);
    	 
    	 
    	 
    }
    
    
    
    function update_message_status($obj)
    {
    	$data['status'] = $obj['status'];
    	$data['gw'] = $obj['gw'];
    	$data['sent_time'] = new MongoDate();
    	$data['gw_response'] = $obj['gw_response'];
    	
    	$updated = $this->mongo_db->where('_id', $obj['_id'])
									->set($data)
									->update($this->get_collection_name());
									
									
    	
    }
    
    function insert_new_obj($to,$body,$user_id = null)
    {
    	
    	$from = $this->config->item('sms_twilio_from_number');
    	
    	$this->load->model('sequence_model');
    	$id = $this->sequence_model->get_sequence($this->get_collection_name());
    	
    	$obj['_id'] = $id;
    	$obj['created_time'] = new MongoDate();
    	$obj['to'] = $to;
    	$obj['from'] = $from;
    	$obj['body'] = $body;
    	$obj['status'] = 'created';
    	$obj['sent_time'] = null;
    	$obj['gw_response'] = null;
    	
    	if($user_id != null)
    	{
    		$obj['user_id'] = $user_id;
    	}
    	
    	$result = $this->mongo_db->insert($this->get_collection_name(), $obj);
    	
    	if ($result != $id)
    	{
    		$obj['save_message'] = 'db_error_insert';
    	}
    	else
    	{
    		$obj['save_message'] = 'insert_success';
    	}
    	
    	
    	return $obj;
    	
    	
    }
    

   
}
?>