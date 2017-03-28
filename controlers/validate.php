<?php

Class Validate {
	
	public $bdd;
	public $bdd_obj;
	public $url_base;
	public $_data;

	public function __construct($url_base) {
		$this->url_base = $url_base;
	}

	public function index() {
		require_once('models/bdd_model.php');
		require_once('models/check_model.php');
		if (!isset($_GET['mail']) || !$_GET['mail']) {
			$this->defaut(null);
			return;
		}
		if (!isset($_GET['code']) || !$_GET['code']) {
			$this->defaut($_GET['mail']);
			return;
		}
		else
			$this->traitement();
	}

	public function defaut($mail) {
		include ('views/head.php');
		include ('views/validate.php');
		include ('views/footer.php');
	}
}

?>
