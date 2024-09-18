<?php

namespace App;

class WithdrawBusinessProcessor{
	
	function process($date, $uid, $user_type, $action, $amount, $currency ){
		return $this->withdraw_business($date, $uid, $user_type, $action, $amount, $currency);
	}
	
	private function withdraw_business($date, $uid, $user_type, $action, $amount, $currency){

		$business_withdraw_commission_rate = commission_rates('business_withdraw'); // 0.005 // 0.5%
		$commission = $amount * $business_withdraw_commission_rate;
		
		return format_currency($commission, $currency);
	}
}
