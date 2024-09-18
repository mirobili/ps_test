<?php

require_once 'app.php';
$input =  file_get_contents($argv[1]);
echo process_input( $input );



// class Tests{

	// public function testDepositCommission() {
		// $operation = ['2016-01-05', '1', 'private', 'deposit', '200.00', 'EUR'];
		// $this->assertEquals(0.06, calculateCommission($operation));
	// }

	// public function testPrivateWithdrawBelowLimit() {
		// $operation = ['2016-01-01', '4', 'private', 'withdraw', '1000.00', 'EUR'];
		// $this->assertEquals(0.00, calculateCommission($operation));
	// }

	// public function testPrivateWithdrawAboveLimit() {
		// $operation = ['2016-01-01', '4', 'private', 'withdraw', '1200.00', 'EUR'];
		// $this->assertEquals(0.60, calculateCommission($operation)); // 0.3% of 200 EUR
	// }

	// public function testCurrencyConversion() {
		// $operation = ['2016-02-19', '5', 'private', 'withdraw', '3000000', 'JPY'];
		// $this->assertEquals(8612, calculateCommission($operation)); // 0.3% after converting JPY to EUR
	// }

// }






