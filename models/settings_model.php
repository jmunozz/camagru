<?php

Class Settings_Model {

	public $url_base;
	public $bdd;
	public $bdd_obj;
	public $user_infos = null;

	public function __construct($url_base) {
		$this->url_base = $url_base;
		include ('models/bdd_model.php');
	}

	public function get_user_infos($user_id) {
		if (!$this->user_infos) {
			$user_id = $_SESSION['user_id'];
			$query = "SELECT * FROM users WHERE id=" . $user_id;
			$this->user_infos = $this->bdd_obj->do_statement($query)[0];
		}
		return $this->user_infos;

	}

	public function get_user_name($user_id) {
		return $this->get_user_infos($user_id)['login'];
	}

	public function get_user_password($user_id) {
		return $this->get_user_infos($user_id)['pwd'];
	}

	public function get_user_email($user_id) {
		return $this->get_user_infos($user_id)['email'];
	}

	public function get_user_comment_tag($user_id) {
		// return $this->get_user_infos($user_id)['comment_tag'];
		return true;
	}


	public function set_new_settings($user_id, $new_user_name, $new_user_email, $new_user_comment_tag) {
		$query = " UPDATE `db_camagru`.`users` SET `login` = '" . $new_user_name ."', `comment_tag` = ". $new_user_comment_tag .", `email` = '" . $new_user_email . "' WHERE `users`.`id` =" . $user_id;
		$this->bdd_obj->do_statement($query);
		return true;
	}

	public function set_new_password($user_id, $set_new_password) {
		$query = " UPDATE `db_camagru`.`users` SET `pwd` = '" . $set_new_password . "' WHERE `users`.`id` =" . $user_id;
		$this->bdd_obj->do_statement($query);
		return true;
	}
}

?>
