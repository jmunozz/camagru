<?php

Class Register {

	public $d_data = '';
	public $url_base;
	public $bdd;
	public $bdd_obj;

	public function __construct($url_base) {
		$this->url_base = $url_base;
	}

	public function index() {
		if (!isset($_POST['submit']) || !$_POST['submit']) {
			$this->view();
			return;
		}
		if (!$_POST['login'] || !$_POST['pwd'] || !$_POST['mail']) {
			$this->_data = 'Tous les champs ne sont pas remplis'.PHP_EOL;
			$this->view();
			return;
		}
		if (!($user = $this->verification($_POST['login'], $_POST['pwd']))){
			$this->view();
			return;
		}
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['user_login'] = $user['login'];
		header('Location: '.$this->url_base);
		return;
	}

	public function view() {
		include ('views/head.php');
		include ('views/header.php');
		include ('views/register.php');
		include ('views/footer.php');
	}
}
