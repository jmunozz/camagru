<?php

Class Settings {
	public $url_base;
	public $model;
	public $_data = null;

	/*
	** If no session is set, this will redirect to homepage.
	*/
	private function validate_identity() {
		if (!isset($_SESSION) || !$_SESSION['user_id']) {
			header('Location:' . $this->url_base);
			exit();
		}
		return;
	}

	public function __construct($url_base) {
		$this->url_base = $url_base;
		require_once('models/settings_model.php');
		$this->model = new Settings_model($url_base);
	}

	public function index()  {
		$this->validate_identity();
		$this->display();
	}

	public function display() {
		$this->validate_identity();

		// Get all variables to be displayed.
		$user_id = $_SESSION['user_id'];
		$user_name = $this->model->get_user_name($user_id);
		$user_email =  $this->model->get_user_email($user_id);
		if (!$user_email) $user_email = '';
		$user_comment_tag = $this->model->get_user_comment_tag($user_id);

		// Display Page.
		include ('views/head.php');
		include ('views/header.php');
		include ('views/settings.php');
		include ('views/footer.php');
		exit();
	}


	/*
	** Change user password or display appropriate page.
	*/
	public function change_password() {

		// Check that user is connectd. Else redirect on login page.
		$this->validate_identity();
		
		// User make a GET on this URL. Display default page.
		if (!isset($_POST['new_password'])){
			$this->display();
		}

		$user_id = $_SESSION['user_id'];
		$new_password = $_POST['new_password'];

		// User has sent empty password on AJAX POST request.
		if (!$new_password) {
			echo 'Password must not be empty';
			exit();
		}

		// Everything is ok.
		$secure_pwd = hash('whirlpool', $new_password);
		if ($this->model->set_new_password($user_id, $secure_pwd)) {
			echo 'ok';
			exit();
		}
		// DDB update has failed.
		else {
			echo 'Something went wrong';
			exit();
		}
	}

	/*
	** Change user settings or display appropriate page.
	*/
	public function change_settings() {

		// Check that user is connectd. Else redirect on login page.
		$this->validate_identity();

		// User make a GET on this URL (else every variable should be present). Display default page.
		if (!isset($_POST['new_user_name']) 
			|| !isset($_POST['new_user_email']) 
			|| !isset($_POST['new_user_comment_tag'])) {
			$this->display();
		}

		$new_user_name = $_POST['new_user_name'];
		$new_user_email = $_POST['new_user_email'];
		$new_user_comment_tag = $_POST['new_user_comment_tag'];
		$user_id = $_SESSION['user_id'];


		// User has sent empty settings on AJAX POST request.
		if (!$new_user_name || !$new_user_email) {
			echo 'Fields must not be empty';
			exit();
		}
		// Everything is ok.
		if ($this->model->set_new_settings($user_id, $new_user_name, $new_user_email, $new_user_comment_tag)) {
			echo 'ok';
			exit();
		}
		// DDB update has failed.
		else {
			echo 'Something went wrong';
			exit();
		}
	}
}
?>
