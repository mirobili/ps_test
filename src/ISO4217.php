<?php

namespace App;


class ISO4217{
		
	private $data ;
	private static $instance;
	
	private function __construct(){
		$this->data = json_decode(file_get_contents("ISO4217.json"), true);
	}
	
	public function generate_json_util(){
		
		$file = 'ISO4217.csv';
		$csv = array_map('str_getcsv', file($file));
		array_walk($csv, function(&$a) use ($csv) {
			$a = array_combine($csv[0], $a);
		});
		  
		foreach($csv as $cc){
		  $aa[$cc['Code']]=$cc; 
		}
		
		file_put_contents('currenciy_info.json', json_encode($aa));
	}
	
	public static function get_instance(){
		
		if(!self::$instance){
			self::$instance = new ISO4217();
		}
		return self::$instance;
	}

	public function get_data(){			
		return $this->data;
	}	
	
	public function get($currency){			
		return $this->data[$currency]??['','','',''];
	}	
}
	

