<?php
Class Home {
	public $url_base;

	public function __construct($url_base) {
		$this->url_base = $url_base;
	}
	
	public function index() {
		if (!$_SESSION['user_id'] || !$_SESION['user_login'])
		{
			header('location: '.$this->url_base.'/login');
			return;
		}
	}
}
?>
