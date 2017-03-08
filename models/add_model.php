<?php

Class Add_model {

	public static function get_date($date, $hour) {
		return ($date.' '.$hour.':00');
	}
	
	public static function get_date1($date, $hour) {
		
		$elem = explode('/', $date);
		$date = '20'.$elem[2].'-'.$elem[0].'-'.$elem[1];
		$hour = ' '.$hour.':00';
		return($date.$hour);
	}
}
