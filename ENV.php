<?php

	
	const ENV = [

		'commission_rates' => [
		  	  'private_deposit'  => 0.0003  // 0.03%
			, 'business_deposit' => 0.0003 // 0.03%
			
			, 'private_withdraw' => 0.003 // 0.3%
			, 'business_withdraw'=> 0.005  // 0.5%
		],
		
		'transaction_processors'=> [

			'withdraw'=>[
				'business' => 'App\WithdrawBusinessProcessor',
				'private'  => 'App\WithdrawPrivateProcessor',
				],
			'deposit'=>[
				
				'private'  => 'App\DepositPrivateProcessor',
				'business' => 'App\DepositBusinessProcessor',
				]
		],
		'private_free_weekly_withdraw_euro' => 1000,
		'private_free_weekly_withdraws' => 3,
		// 'debug'=>'1'
	];
	
