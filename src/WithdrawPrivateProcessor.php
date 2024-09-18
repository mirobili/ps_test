<?php

namespace App;

class WithdrawPrivateProcessor{
	
	public function process($date, $uid, $user_type, $action, $amount, $currency ){
		return $this->withdraw_private($date, $uid, $user_type, $action, $amount, $currency);
	}
	
	function get_free_weekly_withdraw(){ // 1000;
		return get_env('private_free_weekly_withdraw_euro');
	}

	function get_free_withdraws_count(){
		return get_env('private_free_weekly_withdraws');
	}

	function get_buffer($uid){
	
		$buffer = Buffer::get_instance();
		return $user_buffer = $buffer->get($uid);
	}

	function set_buffer($uid, $week, $week_draw, $current_weekly_amount ){
		
		$buffer = Buffer::get_instance();
		$buffer->set($uid, $week, $week_draw, $current_weekly_amount );
	}



	private function withdraw_private($date, $uid, $user_type, $action, $amount, $currency){

		// trace("withdraw_single($date, $uid, $user_type, $action, $amount, $currency)");	
		// $currency= trim($currency); 

		// $rates_service = new RatesService(new RatesProviderMock);
		// $ConvertService= new ConvertService($rates_service);
		$CurrencyService = new CurrencyService;


		$commission_rate = commission_rates('private_withdraw'); // 0.003; // 0.3%

		$free_weekly_withdraw_euro = $this->get_free_weekly_withdraw(); // 1000;
		$free_draws = $this->get_free_withdraws_count(); // 1000;

		$user_buffer = $this->get_buffer($uid);
		$tmp_week  			   = $user_buffer['week'];
		$week_draw 			   = $user_buffer['week_draw'];
		$current_weekly_amount = $user_buffer['current_weekly_amount'];

		$week = date( 'oW', strtotime($date) ); 
		
		if($tmp_week != $week){ // starting new week 
			$tmp_week = $week;
			$current_weekly_amount = 0;
			$week_draw = 0;
		} 
		
		$week_draw++;
		
		$euro_amount = $CurrencyService->toEuro($amount,trim($currency));;
		
		// $euro_amount = toEuro($amount,trim($currency));;

		if($week_draw>$free_draws  or $current_weekly_amount >= $free_weekly_withdraw_euro ){ // after the 3rd withraw OR after 1000Euro charge for the full amount 
			
			$amount_to_charge = $amount;
			
		}else{
			
			if($current_weekly_amount + $euro_amount < $free_weekly_withdraw_euro){ //  not exceeding free limit
				
				$amount_to_charge=0;
			}else{ // New draw exceeding free limit. Charge only amount exceeding 
			
				// $euro_amount_to_charge = $current_weekly_amount + $euro_amount - $weekly_free_withdraw_euro; 
				$free_draw_remaining = ($free_weekly_withdraw_euro - $current_weekly_amount);
				$euro_amount_to_charge = $euro_amount - $free_draw_remaining; 
				$amount_to_charge = $CurrencyService->fromEuro($euro_amount_to_charge, $currency);
				// $amount_to_charge = fromEuro($euro_amount_to_charge, $currency);
			}
		}
		
		$commission = $amount_to_charge * $commission_rate ;
		/// $commission = $CurrencyService->format_currency($commission, $currency);
		$commission = format_currency($commission, $currency);

		// $current_weekly_amount += $CurrencyService->ceil_float($euro_amount, 2);
		$current_weekly_amount += ceil_float($euro_amount, 2);
		
		$this->set_buffer($uid, $week, $week_draw, $current_weekly_amount );
		//$buffer->set($uid, $week, $week_draw, $current_weekly_amount );
		
		if(isset(ENV['debug']))
			trace(
				str_pad("$action", 10 ). 
				str_pad("uid: $uid", 10 ). 
				str_pad("date: $date", 20 ). 
				"week: $week, rate: $commission_rate  weekdraw: $week_draw   ". 
				str_pad("comm: $commission $currency", 22 ). 
				str_pad("amount: $amount $currency  " , 25). 
				str_pad("current_weekly_amount: $current_weekly_amount", 35).
				str_pad("charge_amount: $amount_to_charge " , 25).
				str_pad("rate: $commission_rate", 12 ).
				str_pad("euro_amount: $euro_amount " , 20)
				
			);

		return  $commission ;
	}
}