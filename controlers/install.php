<?php

Class	install {

	public $log ='';
	public $url_base;

	public function __construct($url_base) {
		$this->url_base ='/'.$url_base;
	}

	public function index($page = 1) {
		$this->_1();
	}

	public function _1() {

		$success = FALSE;
		
		require_once('config/setup.php');
		if (!$_POST['submit'] || ($_POST['submit'] && $_POST['action'] == 'check'))
		{
			if (($dbh = Model_bdd::connect_host()) && $dbh->check_bd())
				$success = TRUE;
		}
		else if ($_POST['submit'] && $_POST['action'] == 'reset')
		{
			if (($dbh = Model_bdd::connect_host()) && $dbh->set_bd())
				$success = TRUE;
		}
		$log = $dbh->log;
		include('views/header.php');
		include('views/install_1.php');
	}

	public function _2() {

		$success = FALSE;
		$alert = '';

		require_once('config/setup.php');
		if ($_POST['submit'] && (!$_POST['login'] || !$_POST['pwd'] || !$_POST['email']))
			$alert = 'Attention tous les champs ne sont pas remplis'.PHP_EOL;
		else if ($_POST['submit']) {
			$login = $_POST['login'];
			$pwd = $_POST['pwd'];
			$email = $_POST['email'];
			if (($dbh = Model_bdd::connect_host())
			&& $dbh->set_admin($login, $pwd, $email))
				$success = TRUE;
		}
		$log = $dbh->log;
		include('views/header.php');
		include('views/install_2.php');
	}

	public function _3() {
		include('views/header.php');
		include('views/install_3.php');
	}
}
?>
