<?php

Class Login {

	public $_data;
	public $bdd;
	public $bdd_obj;
	public $url_base;
	
	public function __construct($url_base) {
		$this->url_base = $url_base;
	}

	public function index() {
		include ('views/header.php');
		include ('views/login.php');
		include ('views/footer.php');
	}

	public function in() {

		if (!isset($_POST['submit']) || !$_POST['submit'])
			return;
		if (!$_POST['login'] || !$_POST['pwd']) {
			$this->_data = 'Tous les champs ne sont pas remplis'.PHP_EOL;
			echo $this->_data;
			return;
		}
		if (!($user = $this->verification($_POST['login'], $_POST['pwd']))){
			echo $this->_data;
			return;
		}
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['user_login'] = $user['login'];
		return;
	}

	private function verification($login, $pwd) {

		require_once('models/bdd_model.php');
		$users = $this->bdd_obj->get_table_field('db_camagru', 'id',
		'login', 'pwd', 'valid');
		foreach ($users as $user) {
			if ($user['login'] == $login) {
				if ($user['valid'] && $user['pwd'] == $pwd)
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
		header('Location: /'.$this->url_base);
	}
}

?>
