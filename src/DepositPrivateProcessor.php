<?php

namespace App;

class DepositPrivateProcessor{
	
	function deposit($date, $uid, $user_type, $action, $amount, $currency){

		$deposit_commission_rate = commission_rates($user_type.'_deposit'); /// 0.0003; // 0.03%
		$commission = $amount * $deposit_commission_rate;
		return format_currency($commission, $currency);
	}

	function process($date, $uid, $user_type, $action, $amount, $currency ){
		return $commission = $this->deposit($date, $uid, $user_type, $action, $amount, $currency );
	}
}
