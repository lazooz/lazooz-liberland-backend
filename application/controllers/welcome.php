<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	// lazooz.b-buzzy.com:8080/welcome
	public function index()
	{
		//phpinfo();die;
		$user_id = 39;
		$time_from = time() - 60 * 60 * 24 * 2;
		$time_to = time();
		
		$time_from = 1405503206;
		$time_to = 1405503207;
		
		$this->load->model('location_payload_model');
		$this->location_payload_model->calc_user_distance_and_zooz_sum_for_time($user_id,$time_from,$time_to);
		
		die;
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		$connection = new MongoClient();
		$collection = $connection->db->users;
		//zooz_distance_balance
		
		$out = $collection->aggregate(
		
    	array(
        //'$match' => array('activation_status' => array('$eq' => 'active')),
        '$match' => array('activation_status' =>  'not_activated')
    	),
    	array(
        '$group' => array(
    		'_id' => array('activation_status' => '$activation_status'),
            'pop' => array('$sum' => '$zooz_distance_balance' )
        )
    )
    
);

//print_r($out['result']);
print_r($out);
		
		
		
		
		
		
		
		
		
		
		die;
		
		$query = array( "_id" => array( '$gt' => 1 ) ); //note the single quotes around '$gt'
		$cursor = $collection->find( $query );

		while ( $cursor->hasNext() )
		{
    		var_dump( $cursor->getNext() );
		}

		die;

		
		$this->load->library('mongo_db');
		
		$db = $this->mongo_db->_connect();
		
		$collection = $db->users;
		
		$document = $collection->findOne();
		var_dump( $document );
		die;


		
		$q = array('
		db.orders.aggregate( [
   		{
     		$group: {
        	_id: null,
        	total: { $sum: "$price" }
     		}
   		}
		] )');
		
		$this->mongo_db->command();
		
		



		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		$this->load->model('zip_model');
		echo $this->zip_model->get_content_from_file('c:/Temp/123.zip','123.TXT');
		
		die;
		
	$zip = zip_open("c:/Temp/123.zip");

	if ($zip) {
    while ($zip_entry = zip_read($zip)) {
        echo "Name:               " . zip_entry_name($zip_entry) . "\n";
        echo "Actual Filesize:    " . zip_entry_filesize($zip_entry) . "\n";
        echo "Compressed Size:    " . zip_entry_compressedsize($zip_entry) . "\n";
        echo "Compression Method: " . zip_entry_compressionmethod($zip_entry) . "\n";

        if (zip_entry_open($zip, $zip_entry, "r")) {
            echo "File Contents:\n";
            $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            echo "$buf\n";

            zip_entry_close($zip_entry);
        }
        echo "\n";

    }

    zip_close($zip);

}

		die;
		
		
		
		
		
		//echo 'start<br>';
		
/*		
	// Config  
$dbhost = '127.0.0.1';  
$dbname = 'db';  
  
// Connect to test database  
$m = new Mongo("mongodb://$dbhost");  
$db = $m->$dbname;  
  
// select the collection  
$collection = $db->users;  


$document["id"] = 2332;
$document["name"] = 'sharona';

 $collection->insert($document);die;
 */
/*  
// pull a cursor query  
$cursor = $collection->find();  

foreach($cursor as $document) {  
 print_r($document);  
}  

*/		
/*		
		
		$this->load->model('users_model');
		$this->users_model->insert_new_user();
		die;
		
		
		 $this->load->model('sequence_model');
		 $this->sequence_model->get_sequence('users');
		 
		 die;
		
		
		
		 $this->load->library('mongo_db');
		 
	
            
			
		// $users = $this->mongo_db->db->users->find();
		 
		// print_r($users);
		
		$users = $this->mongo_db->where(array(
    'name' => 'sharon'))->get('users');
		print_r($users);

	
		
		 echo '<br><br>end';
		*/
		
		
		//$this->load->view('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */