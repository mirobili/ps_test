<?php

namespace App;

class CommissionService{ 

	function __construct(private $CountryService ){
		
	}
	
	function get_country_commission($country_code){
		return $this->CountryService->isEu($country_code) ? 0.01 : 0.02;
	}

}
