<?php

Class Settings {
	public $url_base;
	public $model;
	public $_data = null;

	public function __construct($url_base) {
		$this->url_base = $url_base;
		require_once('models/settings_model.php');
		$this->model = new Settings_model($url_base);
	}

	public function index()  {
		$this->display();
	}

	public function display() {

		include ('views/head.php');
		include ('views/header.php');
		include ('views/settings.php');
		include ('views/footer.php');
	}
}
?>
