<?php


namespace App;


class Buffer{
	
	private  $buffer = [];
	private static $instance ;
	
	private function __construct(){}
	
	public static function get_instance(){
		
		if( !isset(self::$instance)){
			self::$instance = new Buffer;
		}
		
		return self::$instance;
	}
	
	function set($uid, $week, $week_draw, $current_weekly_amount ){
		
		$this->buffer[$uid]=['week'=>$week, 'week_draw'=>$week_draw, 'current_weekly_amount'=>$current_weekly_amount];
	}
	
	function get($uid){
		return isset($this->buffer[$uid]) ? $this->buffer[$uid] : ['week'=>'', 'week_draw'=>0, 'current_weekly_amount'=>0] ;
	}
}