<?php

namespace App;



class ProcessorFactory{
	
	private $transaction_processors;
	
	public function __construct(){
		$this->transaction_processors = get_env('transaction_processors');
	}
	
	public function get_processor($date, $uid, $user_type, $action, $amount, $currency){
		
		if(isset($this->transaction_processors[$action][$user_type])){
			return new $this->transaction_processors[$action][$user_type];
		}
		
		throw new Exception("Transaction processor not found for $action $user_type");
	}	
}
