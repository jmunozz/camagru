<?php

define('ERROR_VALUES', serialize(array('nom', 'prénom', 'mot de passe', 'mail')));

Class Signin {

	public $bdd;
	public $bdd_obj;
	public $url_base;

	public function __construct($url_base) {
		$this->url_base = $url_base;
	}

	public function index() {
		require_once('models/bdd_model.php');
		require_once('models/check_model.php');
		if (!$_POST['submit'])
			$this->defaut();
		else if ($_POST['submit'])
			$this->traitement();
	}

	private function defaut($alert = NULL) {
		$comite = $this->bdd_obj->get_table_field('em_comites', 'id', 'name');
		include ('views/head.php');
		include ('views/signin.php');
		include ('views/footer.php');
	}

	private function traitement() {

		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$pwd = $_POST['pwd'];
		$email = $_POST['email'];

		if (!$nom || !$prenom || !$pwd || !$email) {
			$alert = 'Attention tous les champs ne sont pas remplis'.PHP_EOL;
			$this->defaut($alert);
			return;
		}
		if (($i = 0) ||!Check::_field($nom) || !(++$i) || !Check::_field($prenom) 
		|| !(++$i) || !Check::_field($pwd) || !(++$i) || !Check::_mail($email)) {
			$alert = 'Le champ '.unserialize(ERROR_VALUES)[$i].' est invalide'.PHP_EOL;
			$this->defaut($alert);
			return;
		}
		if (!($already = $this->bdd_obj->get_table_field('em_users', 'mail'))) {
			$alert = 'Impossible de vérifier l\'existence de cette adresse'.PHP_EOL;
			$this->defaut($alert);
			return;
		}
		foreach($already as $a) {
			if ($a['mail'] == $email) {
				$alert = 'Attention cette adresse mail est déjà utilisée'.PHP_EOL;
				$this->defaut($alert);
				return;
			}
		}
		if ($this->bdd_obj->insert_user($nom, $prenom, $email,$pwd,  $comite, 'membre')
		== FALSE) {
			$alert = $this->bdd_obj->log;
			$this->defaut($alert);
			return;
		}
		$this->success();
		return;
	}

	private function success() {
		$success_message = 'Vous vous êtes bien enregistré';
		include ('views/head.php');
		include ('views/success.php');
		include ('views/footer.php');
	}

}
?>
