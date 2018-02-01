<?php

Class Login {

	public $_data = '';
	public $bdd;
	public $bdd_obj;

	public function index() {

		if (!isset($_POST['submit']) || !$_POST['submit']) {
			$this->view();
			return;
		}
		if (!$_POST['login'] || !$_POST['pwd']) {
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
		header('Location: /');
		return;
	}

	private function verification($login, $pwd) {


		require_once('models/bdd_model.php');
		if ($this->bdd == NULL) {
			$this->_data = Bdd::$err_init;
			return (FALSE);
		}
		$users = $this->bdd_obj->get_table_field('users', 'id',
		'login', 'pwd', 'valid');
		foreach ($users as $user) {
			if ($user['login'] == $login) {
				if ($user['valid'] && $user['pwd'] == hash('whirlpool',$pwd))
					return ($user);
				else if (!$user['valid']) {
					$this->_data = 'Veuillez confirmer votre inscription'.PHP_EOL;
					return (FALSE);
				}
				else {
					$this->_data = 'Mot de passe invalide'.PHP_EOL;
					return (FALSE);
				}
			}
		}
		$this->_data = 'Adresse mail invalide'.PHP_EOL;
		return (FALSE);
	}

	public function out(){
		$_SESSION['user_id'] = '';
		$_SESSION['user_login'] = '';
		header('Location: /');
	}

	public function init() {
		$success = NULL;
		require_once('models/bdd_model.php');
		if (!isset($_POST['mail']))
			;
		else if (!$_POST['mail']) 
			$this->_data = 'Le champ mail est vide';
		else if (!($user = $this->bdd_obj->get_elem_by('users', 'email', 
		$_POST['mail']))) 
			$this->_data = 'Cette adresse mail est invalide';
		else {
			$code = uniqid('');
			echo $code;
			if (!$this->bdd_obj->update_user($user[0]['id'], 'code', $code))
				$this->_data = 'Opération impossible';
			else {
				require_once('models/Mail_model.php');
				Mail::init($this->url_base);
				Mail::sendPwdCodeToUser($code , $user[0]['email'], $user[0]['login']);
				$success = TRUE;
			}
		}
		$this->init_view($success);
		return;
	}

	public function view() {
		include ('views/head.php');
		include ('views/header.php');
		include ('views/login.php');
		include ('views/footer.php');
	}

	public function init_view($success) {
		include ('views/head.php');
		include ('views/header.php');
		if (!$success)
			include ('views/pwd_init.php');
		else  {
			$success_message = 'Un email vient de vous être envoyé';
			include ('views/success.php');
		}
		include ('views/footer.php');
	}
}

?>
