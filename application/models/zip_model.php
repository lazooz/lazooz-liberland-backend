<?php
class Zip_model extends CI_Model {

 
    function __construct()
    {
        parent::__construct();
		 
    }
    
    
    function get_content_from_file($path,$ziped_file_name)
    {
	    $zip = zip_open($path);

		if ($zip) 	
		{
   	 		while ($zip_entry = zip_read($zip)) 
    		{
    			log_message('debug', 'zip_entry_name($zip_entry): ' . zip_entry_name($zip_entry));
    			
		    	if($ziped_file_name == zip_entry_name($zip_entry))
   			 	{
   	 				if (zip_entry_open($zip, $zip_entry, "r")) 
    				{
    					$content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
    			        zip_entry_close($zip_entry);
       		 		}
    			}
	   	 		else 
   	 			{
    				$content = null;
    			}
    		}

	    	zip_close($zip);

		}
		
		return $content;
    }
    
  
}
?>