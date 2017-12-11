<?php

// In php5, defined Constants can be only scalars. In php7 arrays are allowed.
define('ERROR_VALUES', serialize(array('login', 'mot de passe', 'mail')));


Class Signin {

	public $bdd;
	public $bdd_obj;
	public $url_base;
	public $_data;

	public function __construct($url_base) {
		$this->url_base = $url_base;
	}

	
	/*
	** Root to the right method.
	*/
	public function index() {
		require_once('models/bdd_model.php');
		require_once('models/check_model.php');

		// Display default page.
		if (!isset($_POST['submit']) || !$_POST['submit'])
			$this->defaut();

		// Display next step.
		else if ($_POST['submit'])
			$this->traitement();
	}

	/*
	** Display default page.
	*/
	private function defaut() {
		include ('views/head.php');
		include ('views/signin.php');
		include ('views/footer.php');
	}

	

	private function traitement() {

		$login = $_POST['login'];
		$pwd = $_POST['pwd'];
		$email = $_POST['email'];

		// All fields are not filled. Display default page.
		if (!$login || !$pwd || !$email) {
			$this->_data = 'Attention tous les champs ne sont pas remplis'.PHP_EOL;
			$this->defaut();
			return;
		}

		// For every field, if check is not valid, display default page.
		if (($i = 0) ||!Check::_field($login) 
			|| !(++$i) || !Check::_field($pwd) 
			|| !(++$i) || !Check::_mail($email)) {
				$this->_data = 'Le champ '.unserialize(ERROR_VALUES)[$i].' est invalide'.PHP_EOL;
				$this->defaut();
				return;
		}

		// If email is already used, display default page.
		if ($this->isAlreadyUsed('email', $email)) {
			$this->_data = 'Attention cette adresse mail est déjà utilisée'.PHP_EOL;
			$this->defaut();
			return;
		}

		// If login is already used, display default page.
		if ($this->isAlreadyUsed('login', $login)) {
			$this->_data = 'Attention ce login est déjà utilisé'.PHP_EOL;
			$this->defaut();
			return;
		}

		// If createUser method fails for whatever reason, display default page.
		if (!$this->createUser($email, $login, $pwd)) {
			$this->_data .= 'L\'inscription a échouée.'.PHP_EOL;
			$this->defaut();
			return;
		}

		// Will redirect user to step 2 (code validation);
		header('Location: '.$this->url_base.'/validate?mail='.$email);
		return;
	}

	private function isAlreadyUsed($field, $value) {
		if (!$this->bdd_obj->get_elem_by('users', $field, $value))
			return FALSE;
		return TRUE;
	}

	

	/*
	** Create an inactive user in ddb from email, login and password.
	*/
	private function createUser($email, $login, $pwd) {

		$code =	$this->getRandomCode();
		$secure_pwd = hash('whirlpool', $pwd);

		require_once('models/Mail_model.php');	
		Mail::init($this->url_base);
		Mail::sendCodeToUser($code, $email, $login);

		return $this->bdd_obj->insert_user($login, $secure_pwd, $email, $code);
	}


	private function getRandomCode() {
		return uniqid('');
	}

}
?>
