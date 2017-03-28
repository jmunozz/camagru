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
			$_data = 'Le champ '.unserialize(ERROR_VALUES)[$i].' est invalide'.PHP_EOL;
			$this->defaut();
			return;
		}
		if (!($already = $this->bdd_obj->get_table_field('users', 'email'))) {
			$_data = 'Impossible de vérifier l\'existence de cette adresse'.PHP_EOL;
			$this->defaut();
			return;
		}
		foreach($already as $a) {
			if ($a['email'] == $email) {
				$this->_data = 'Attention cette adresse mail est déjà utilisée'.PHP_EOL;
				$this->defaut();
				return;
			}
		}
		if (!$this->createUser($email, $login, $pwd)) {
			$this->_data = 'L\'inscription a échouée.'.PHP_EOL;
			$this->defaut();
			return;
		}
		header('Location: '.$this->url_base.'/validate?mail='.$email);
		return;
	}

	private function createUser($email, $login, $pwd) {
		$code = $this->getRandomCode();
		$this->sendCodeToUser($code, $email, $login);
		return ($this->bdd_obj->insert_user($login, $pwd, $email, $code));		
	}

	private function sendCodeToUser($code, $email, $login) {
		$objet = 'Camagru: Validation de votre compte';
		$body = '<h1>Félicitations '.$login.' pour ton inscription !</h1>';
		$body .= '<p>Ton code de validation est : '.$code.' .</p>';
		$body .= '<br /> ';
		$body .= '<p>Tu peux valider directement ton inscription à cette adresse: ';
		$body .= $_SERVER['HTTP_HOST'].$this->url_base.'/validate?mail='.$email.'&code='.$code.'</p>';
		$headers = "MIME-Version: 1.0" . "\r\n";
	    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\b";
		$headers .= 'From: Camagru';
		if (!mail($email, $objet, $body, $headers))
			echo 'mail cant be delivered to '.$email;
	}

	private function getRandomCode() {
		return uniqid('');
	}

}
?>
