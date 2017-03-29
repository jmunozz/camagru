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
			$this->traitement($_GET['code'], $_GET['mail']);
	}

	public function defaut($mail) {
		include ('views/head.php');
		include ('views/validate.php');
		include ('views/footer.php');
	}

	public function success($login, $ret) {
		$success_message = 'Bravo '.$login.' !'.PHP_EOL.$ret;
		include ('views/head.php');
		include ('views/success.php');
		include ('views/footer.php');
	}

	public function pwd($mail, $code) {
		include ('views/head.php');
		include('views/header.php');
		include ('views/pwd_init2.php');
		include ('views/footer.php');
	}

	public function traitement($code, $mail) {
		$user = $this->bdd_obj->get_elem_by('users', 'email', $mail);
		$user = $user ? $user[0] : null;
		if (!Check::_field($code)) {
			$this->_data = 'Ce code est invalide'.PHP_EOL;
		}
		else if (!$user) {
			$this->_data = 'Cette adresse mail n\'existe pas'.PHP_EOL;
		}
		else if ($user['valid']) {
			$this->_data = 'Ce compte est déjà actif'.PHP_EOL;
		}
		else if ($user['code'] !== $code) {
			$this->_data = 'Le code entré n\'est pas valide'.PHP_EOL;
		}
		else {
			if (!$this->bdd_obj->update_user($user['id'], 'code', 0) || 
					!$this->bdd_obj->update_user($user['id'], 'valid', 1))
				$this->_data = $this->bdd_obj->log;
			else {
				$ret = 'Ton inscription est validée';
				$this->success($user['login'], $ret);
				return;
			}
		}
		$this->defaut($mail);
		return;
	}

	private function change_pwd($id_user, $pwd) {
		if (!$this->bdd_obj->update_user($id_user, 'pwd', $pwd) ||
				!$this->bdd_obj->update_user($id_user, 'code', 0))
			$ret = 'Le mot de passe n\'a pas pu etre modifié, adressez-vous au support';
		else
			$ret = 'Votre mot de passe a bien été modifié';
		return ($ret);
	}

	public function init() {
		require_once('models/check_model.php');
		require_once('models/bdd_model.php');
		$mail = !isset($_GET['mail']) ? NULL : $_GET['mail'];
		$code = !isset($_GET['code']) ? NULL : $_GET['code'];
		$mail = !isset($_POST['mail']) ? $mail : $_POST['mail'];
		$code = !isset($_POST['code']) ? $code : $_POST['code'];
		$pwd = !isset($_POST['pwd']) ? NULL : $_POST['pwd'];

		if (!$code || !$mail || !($user =  $this->bdd_obj->get_elem_by('users',
		'email', $mail)) || $user[0]['code'] !== $code)
			header('Location: '.$this->url_base.'/login');
		else if (!$pwd)
			$this->pwd($mail, $code);
		else if (!Check::_field($pwd)) {
			$this->_data = 'Le mot de passe est incorrect'.PHP_EOL;
			$this->pwd($mail, $code);
		}
		else {
			$ret = $this->change_pwd($user[0]['id'], hash('whirlpool', $pwd));
			$this->success($user[0]['login'], $ret);
		}
	}
}

?>
