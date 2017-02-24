<?php
global $log;

Class	install {

	public function index($page = 1) {
		require_once('config/setup.php');
		switch ($page) {
			case 1:
				self::install_1();
				break;
			case 2:
				self::install_2();
				break;
			case 3:
				self::install_3();
				break;
		}
	}

	public function install_1() {

		$log = '';
		$success = FALSE;

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
		include('views/header.php');
		include('views/install_1.php');
	}

	public function install_2() {

		$success = FALSE;
		$alert = '';
		$log = '';

		echo 'merde';
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
		include('views/header.php');
		include('views/install_2.php');
	}

	public function install_3() {
		include('views/header.php');
		include('views/install_3.php');
	}
}
?>
