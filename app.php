<?php

require 'vendor/autoload.php';

require_once 'ENV.php';

// use App\BinProvider;
// use App\BinProviderMock;
// use App\BinService;
// use App\CommissionService;
// use App\ConvertService;
// use App\CountryService;
// use App\RatesProvider;
// use App\RatesProviderMock;
// use App\RatesService;
// use App\Utils;
// use App\Exception;
use App\CurrencyInformationService;


use App\WithdrawBusinessProcessor;
use App\WithdrawPrivateProcessor;
use App\DepositPrivateProcessor;
use App\DepositBusinessProcessor;

use App\ProcessorFactory;
use App\CurrencyService;
use App\Buffer;
use App\ISO4217;

/**********************************/

function my_log($message){
    error_log(date('Y-m-d H:i:s'). ' ' . $message ."\n",'3','error_log.log');
}

function trace($var){
    print_r($var);
    echo "\n";
}

function get_env($key){
    return ENV[$key]?? throw new Exception('ENV key not found');
}

function ISO4217(){
    return $currency_data = ISO4217::get_instance()->get_data();
}


function ceil_float($value, $places=0){
    $tmp = pow(10,$places);
    return ceil( $value*$tmp ) / $tmp ;
}

function get_currency_decimals($currency){

    $data = ISO4217();
    $dec =  $data[$currency]['D'] ?? '';

    return $dec;
}

function ceil_currency($amount, $currency){

    $d= get_currency_decimals($currency);
    return ceil_float($amount, $d);
}

function format_currency($amount, $currency){

    $d= get_currency_decimals($currency);
    $amount= ceil_float($amount, $d);
    return number_format($amount, $d, '.', '');
}

function commission_rates($key){

    $rates= get_env('commission_rates');
    return $rates[$key] ?? throw new Exception('Commission key not found');
}




/******************************************************/

// $input="
// 2014-12-31,4,private,withdraw,1200.00,EUR
// 2015-01-01,4,private,withdraw,1000.00,EUR
// 2016-01-05,4,private,withdraw,1000.00,EUR
// 2016-01-05,1,private,deposit,200.00,EUR
// 2016-01-06,2,business,withdraw,300.00,EUR
// 2016-01-06,1,private,withdraw,30000,JPY
// 2016-01-07,1,private,withdraw,1000.00,EUR
// 2016-01-07,1,private,withdraw,100.00,USD
// 2016-01-10,1,private,withdraw,100.00,EUR
// 2016-01-10,2,business,deposit,10000.00,EUR
// 2016-01-10,3,private,withdraw,1000.00,EUR
// 2016-02-15,1,private,withdraw,300.00,EUR
// 2016-02-19,5,private,withdraw,3000000,JPY
// ➜  php script.php input.csv
// 0.60
// 3.00
// 0.00
// 0.06
// 1.50
// 0
// 0.70
// 0.30
// 0.30
// 3.00
// 0.00
// 0.00
// 8612

// ";



function process_transaction($csv){

    try{

        list($date, $uid, $user_type, $action, $amount, $currency) = explode(',', trim($csv) );

        $currency = trim($currency);
        $factory= new ProcessorFactory();
        $processor= $factory->get_processor($date, $uid, $user_type, $action, $amount, $currency );
        $commission = $processor->process($date, $uid, $user_type, $action, $amount, $currency );

        //	trace("$commission = \$processor->process($date, $uid, $user_type, $action, $amount, $currency );");
        //trace($commission);
        return $commission;

    }catch(Exception $e){

        //error_log(date('Y-m-d H:i:s').' '.$e->getMessage()."\n",'3','error_log.log');

        $error_message = $e->getMessage();
        my_log($error_message);

        trace($error_message);
    }
}

function process_input($input=''){
    $output=[];
    foreach(explode("\n",trim($input))as $csv){
       $output[]= process_transaction($csv);
    }

    return implode("\n", $output);
}
