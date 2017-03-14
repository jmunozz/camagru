<?php

Class Gallery_model {
	public $url_base;
	public $bdd;
	public $bdd_obj;

	public function __construct($url_base) {
		$this->url_base = $url_base;
		include ('models/bdd_model.php');
	}

	public function get_all_images() {
		$query = "SELECT images.id AS 'img_id',name AS 'titre',
			users.login AS 'user', date, path, sub.nb_like AS 'nb_like',
			sub2.nb_comment as 'nb_comment' FROM images 
			INNER JOIN users 
			ON users.id =images.id_user 
			LEFT OUTER JOIN (SELECT COUNT(likes.id) AS 'nb_like',
			likes.id_image FROM likes GROUP BY id_image) sub 
			ON sub.id_image = images.id 
			LEFT OUTER JOIN (SELECT COUNT(comments.id) AS 'nb_comment',
			comments.id_image FROM comments GROUP BY id_image) sub2 
			ON sub2.id_image = images.id";
		return ($this->bdd_obj->do_statement($query));
	}

	public function get_all_filters($id_user) {
		$query = 
			'SELECT * FROM images
			WHERE type = 2
			AND (id_user = 0 OR id_user = :id_user)';
		$args = array('id_user' => $id_user);
		return ($this->bdd_obj->do_statement($query, $args));
	}


	public function get_user_likes($id_user) {
		$query = "SELECT id_image FROM likes WHERE id_user = :id_user";
		$args = array('id_user' => $id_user);
		return ($this->bdd_obj->do_statement($query, $args));
	}

	public function add_comment($id_user, $id_image, $text) {
		$query = 
			'INSERT INTO comments (id_user, id_image, text, DATE)
			VALUES(:id_user , :id_image , :text, NOW())';
		$args = array('id_user' => $id_user, 'id_image' => $id_image, 'text'
		=> $text);
		$this->bdd_obj->do_statement($query, $args);
		return ($this->bdd_obj->last_statement_return());
	}

	public function get_comments($id_image) {
		$query = 
				'SELECT users.login AS \'user_login\', DATE AS \'date\', text FROM comments
				INNER JOIN users
				ON users.id = comments.id_user
				WHERE comments.id_image = :id_image';
		$args = array('id_image' => $id_image);
		return ($this->bdd_obj->do_statement($query, $args));
	}

	public function add_like($id_user, $id_image) {
		$query = 'DELETE FROM likes WHERE id_user = :id_user AND id_image = :id_image';
		$query_add = 'INSERT INTO likes (id_user, id_image) VALUES (:id_user, :id_image)';
		$args = array('id_user' => $id_user, 'id_image' => $id_image);
		$this->bdd_obj->do_statement($query, $args);
		$ret = $this->bdd_obj->last_statement_return();
		if (!$ret) {
			$this->bdd_obj->do_statement($query_add, $args);
			return ('add');
		}
		else
			return ('delete');
	}

	public function transform_null($images) {
		for($i = 0; isset($images[$i]);  $i++) {
			if (!$images[$i]['nb_like'])
				$images[$i]['nb_like'] = 0;
			if (!$images[$i]['nb_comment'])
				$images[$i]['nb_comment'] = 0;
		}
		return ($images);
	}
}





