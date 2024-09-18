<?php


namespace App;


class CurrencyService{
	
	private $instance;
	
	public function get_instance(){
		
		if(!isset(self::$instance)){
			self::$instance= new CurrencyService;
		}
		
		
		
		return self::$instance;
		
	}
	
	function get_currency_decimals($currency){
		
		$data = ISO4217();
		$dec =  $data[$currency]['D'] ?? '';
		
		return $dec;
	}

	function ceil_currency($amount, $currency){
		
		$d= $this->get_currency_decimals($currency);
		return $this->ceil_float($amount, $d);
	}

	function format_currency($amount, $currency){
		
		$d= $this->get_currency_decimals($currency);
		$amount= $this->ceil_float($amount, $d);
		return number_format($amount, $d, '.', '');
	}
	
	function ceil_float($value, $places=0){
		$tmp = pow(10,$places);
		return ceil( $value*$tmp ) / $tmp ;
	}
	
		/*********************************/
	function rates(){
			
		// EUR:USD - 1:1.1497, EUR:JPY - 1:129.53
			
		$rates = [
			 'USD'=> 1.1497 ,
			 'EUR'=> 1 ,
			 'JPY'=> 129.53,
		];
			
		return $rates;
	}

	function get_rate($currency){
		return $this->rates()[$currency]??'1';
	}

	function toEuro($amount, $currency){
		return $amount / $this->get_rate($currency);
	}

	function fromEuro($amount, $currency){
		return $amount * $this->get_rate($currency);
	}
}