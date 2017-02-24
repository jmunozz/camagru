<?php

Class Home {

	public function __construct() {
	}
	
	public function index() {
	echo 'home';
	}
}
global $home;
$home = new Home();
?>
