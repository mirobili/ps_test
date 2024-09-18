<?php

namespace App;

use Exception;
use function PHPUnit\Framework\throwException;

class Utils
{

    function ceil_float($value, $places=0){

        if($places<0){

            throw new Exception("places should be greater than or equal 0");
        }

        $tmp = pow(10,$places);
        return ceil( $value*$tmp ) / $tmp ;
    }


    function trace($var){

        print_r($var);
        echo "\n";
    }




}