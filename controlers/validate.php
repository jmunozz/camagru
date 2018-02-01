<?php

Class Validate {

	public $bdd;
	public $bdd_obj;
	public $_data;

	/*
	** Roots user to finishing registration.
	*/
	public function index() {
		require_once('models/bdd_model.php');
		require_once('models/check_model.php');

		// If no mail in querystring, display default page.
		if (!isset($_GET['mail']) || !$_GET['mail']) {
			$this->defaut(null);
			return;
		}

		// If no code in querystring, display default page.
		if (!isset($_GET['code']) || !$_GET['code']) {
			$this->defaut($_GET['mail']);
			return;
		}

		// Validate registration.
		else
			$this->complete_registration($_GET['code'], $_GET['mail']);
	}

	/*
	** Display Mail and Code fields or only Code field.
	*/
	public function defaut($mail) {
		include ('views/head.php');
		include ('views/validate.php');
		include ('views/footer.php');
	}

	/*
	** Display message on success page.
	*/
	public function display_message($message) {
		$success_message = $message;
		include ('views/head.php');
		include ('views/success.php');
		include ('views/footer.php');
	}

	/*
	**
	*/
	public function pwd($mail, $code) {
		include ('views/head.php');
		include('views/header.php');
		include ('views/pwd_init2.php');
		include ('views/footer.php');
	}

	/*
	** Complete registration or display default page with error log.
	*/
	public function complete_registration($code, $mail) {

		// Security if someone calls this functions directly(not rooted from index).
		if(!isset($code) || !isset($mail)) {
			$this->index();
			return;
		}

		// Get user infos from mail.
		$user = $this->bdd_obj->get_elem_by('users', 'email', $mail);
		$user = $user ? $user[0] : null;

		// Code format is not valid.
		if (!Check::_field($code)) {
			$this->_data = 'Ce code est invalide'.PHP_EOL;
		}

		// User has not been found.
		else if (!$user) {
			$this->_data = 'Cette adresse mail n\'existe pas'.PHP_EOL;
		}

		// User has already completed registration.
		else if ($user['valid']) {
			$this->_data = 'Ce compte est déjà actif'.PHP_EOL;
		}

		// User code is wrong.
		else if ($user['code'] !== $code) {
			$this->_data = 'Le code entré n\'est pas valide'.PHP_EOL;
		}

		// Everything is ok.
		else {

			// Code is set to 0. Valid is set to 1. Logs are print if fails.
			if (!$this->bdd_obj->update_user($user['id'], 'code', 0) || 
				!$this->bdd_obj->update_user($user['id'], 'valid', 1))
					$this->_data = $this->bdd_obj->log;

			// Everything is ok.
			else {
				$message = 'Bravo !' . $user['login'] . PHP_EOL . 'Ton inscription est validée';
				$this->display_message($message);
				return;
			}
		}
		// Back to default page.
		$this->defaut($mail);
		return;
	}

	
	/*
	** Handle password change.
	*/
	public function password_change() {

		require_once('models/check_model.php');
		require_once('models/bdd_model.php');

		// First time, user arrives on page.
		$mail = !isset($_GET['mail']) ? null : $_GET['mail'];
		$code = !isset($_GET['code']) ? null : $_GET['code'];
		
		// Second time, user submits a new password.
		$mail = !isset($_POST['mail']) ? $mail : $_POST['mail'];
		$code = !isset($_POST['code']) ? $code : $_POST['code'];
		$pwd = !isset($_POST['pwd']) ? null : $_POST['pwd'];

		$user = $this->bdd_obj->get_elem_by('users', 'email', $mail);

		// If we have missing email, code, invalid user, wrong user_code, display login page.
		if (!$code || !$mail || !$user || $user[0]['code'] !== $code)
				header('Location: /login');
		
		// If pwd is not submitted yet (first time), display pwd_init page.
		else if (!$pwd)
			$this->pwd($mail, $code);

		// If pwd is not valid, display pwd_init page.
		else if (!Check::_field($pwd)) {
			$this->_data = 'Le mot de passe est incorrect' . PHP_EOL;
			$this->pwd($mail, $code);
		}

		// pwd is set and valid. Make change and display message.
		else {
			$secure_pwd = hash('whirlpool', $pwd);
			$user_id = $user[0]['id'];
			$user_login = $user[0]['login'];

			$update_succes = $this->bdd_obj->update_user($user_id, 'pwd', $secure_pwd);
			$message = ($update_succes === 0 || $update_succes) ?
				'Votre mot de passe a bien été modifié' :
				'Le mot de passe n\'a pas pu etre modifié, adressez-vous au support';
			$this->display_message($message);
		}
	}
}

?>
