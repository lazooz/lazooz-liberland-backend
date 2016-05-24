<?php
class Mcrypt_model extends CI_Model {


	function __construct()
	{
		parent::__construct();
		
		//$this->iv = 'fedcba9876543210' . 'fedcba9876543210'; 
		$this->iv = 'fedcba9876543210' . '';
		$this->key = '0123456789abcdef'; 

			
	}
	
	
	
	function encrypt($str,$key = null) 
	{
		if($key != null)
		{
			$this->key = $key;
		}
	
		$str = mb_convert_encoding( $str, "BASE64", "UTF-8" );
		
		//$key = $this->hex2bin($key);
		$iv = $this->iv;
	
		$td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);
	
		mcrypt_generic_init($td, $this->key, $iv);
		$encrypted = mcrypt_generic($td, $str);
	
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
	
		return bin2hex($encrypted);
	}
	
	function decrypt($code,$key = null) 
	{
		//$key = $this->hex2bin($key);
		if($key != null)
		{
			$this->key = $key;
		}
		
		$code = $this->hex2bin($code);
		$iv = $this->iv;
	
		$td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);
	
		mcrypt_generic_init($td, $this->key, $iv);
		$decrypted = mdecrypt_generic($td, $code);
	
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		$decrypted = utf8_encode(trim($decrypted));
		
		$decrypted = mb_convert_encoding( $decrypted, "UTF-8", "BASE64" );
	
		return $decrypted;
		
	}
	
	protected function hex2bin($hexdata) {
		$bindata = '';
	
		for ($i = 0; $i < strlen($hexdata); $i += 2) {
			$bindata .= chr(hexdec(substr($hexdata, $i, 2)));
		}
	
		return $bindata;
	}
	


}
?>