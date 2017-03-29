<?php

define('ERROR_VALUES', serialize(array('login', 'mot de passe', 'mail')));

Class Signin {

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
		if (!isset($_POST['submit']) || !$_POST['submit'])
			$this->defaut();
		else if ($_POST['submit'])
			$this->traitement();
	}

	private function defaut() {
		include ('views/head.php');
		include ('views/signin.php');
		include ('views/footer.php');
	}

	private function traitement() {

		$login = $_POST['login'];
		$pwd = $_POST['pwd'];
		$email = $_POST['email'];

		if (!$login || !$pwd || !$email) {
			$this->_data = 'Attention tous les champs ne sont pas remplis'.PHP_EOL;
			$this->defaut();
			return;
		}
		if (($i = 0) ||!Check::_field($login) || !(++$i) || !Check::_field($pwd) 
		|| !(++$i) || !Check::_mail($email)) {
			$this->_data = 'Le champ '.unserialize(ERROR_VALUES)[$i].' est invalide'.PHP_EOL;
			$this->defaut();
			return;
		}
		if ($this->isAlreadyUsed('email', $email)) {
			$this->_data = 'Attention cette adresse mail est déjà utilisée'.PHP_EOL;
			$this->defaut();
			return;
		}
		if ($this->isAlreadyUsed('login', $login)) {
			$this->_data = 'Attention ce login est déjà utilisé'.PHP_EOL;
			$this->defaut();
			return;
		}
		if (!$this->createUser($email, $login, $pwd)) {
			$this->_data = 'L\'inscription a échouée.'.PHP_EOL;
			$this->defaut();
			return;
		}
		header('Location: '.$this->url_base.'/validate?mail='.$email);
		return;
	}

	private function isAlreadyUsed($field, $value) {
		if (!$this->bdd_obj->get_elem_by('users', $field, $value))
			return FALSE;
		return TRUE;
	}

	private function createUser($email, $login, $pwd) {
		require_once('models/Mail_model.php');
		$code = $this->getRandomCode();
		Mail::init($this->url_base);
		Mail::sendCodeToUser($code, $email, $login);
		return ($this->bdd_obj->insert_user($login, hash('whirlpool', $pwd), $email, $code));		
	}


	private function getRandomCode() {
		return uniqid('');
	}

}
?>
