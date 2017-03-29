<?php

/*
** Interface d'utilisation de la BDD. Permet de se connecter, d'insérer events
** users, comites, d'obtenir tableau de tableaux associatifs avec fields values.
*/
class Bdd {
	static $err_init = 'init_error: ';
	public $log;
	public $dbh;
	public $last_statement_return;

	static public function get_instance() {
		require_once('config/infos.php');
		try {
			$dbh = new PDO($DB_HOSTNAME, $DB_USER, $DB_PWD, $DB_OPTIONS);
		}
		catch (PDOException $e) {
			self::$err_init = 'Impossible de se connecter à la BDD: '.
			$e->getMessage().PHP_EOL;
			return (NULL);
		}
		return (new Bdd($dbh));
	}

	public function __construct($dbh) {
		$this->dbh = $dbh;
	}

# Insert a user in the BDD.

	public function insert_user($login, $pwd, $email, $code) {
		$query =
		"INSERT into users (login, pwd, email, code)
		VALUES ('".$login."', '".$pwd."', '".$email."', '".$code."')";
		$err_log = 'impossible d\'insérer l\'utilisateur';
		$succ_log = 'l\'utilisateur a été inséré';
		return ($this->do_query($query, $err_log, $succ_log));
	}

# Update some user field  in the BDD by id.

	public function update_user($id, $field, $value) {

	$query = 'UPDATE users SET '.$field.'=\''.$value.'\' WHERE id='.$id;
	$this->do_statement($query);
	return($this->last_statement_return);
	}

# Get id of last row inserted.

	public function get_last_id() {
		$query = 'SELECT @@identity';
		return($this->do_statement($query));
	}

# Delete an image from id.

	public function delete_image($args) {
		$query = 'DELETE FROM images WHERE id = :id AND id_user = :id_user';
		$this->do_statement($query, $args);
		return($this->last_statement_return);
	}

# Get path of an image from id.

	public function get_path_image($id) {
		$query = 'SELECT path FROM images WHERE id='.$id;
		return ($this->do_statement($query));
	}

# Insert an image in the BDD. Informations are contained in tab $image.

	public function insert_image($image) {
		$query = 
			'INSERT into images (id_user, type, name, path, date)
			VALUES (:id_user, :type, :name, :path, NOW())';
		$this->do_statement($query, $image);
		return($this->last_statement_return);
	}


# Give a table to the function and fields. Return their values or NULL if a problem occurs.

	public function get_table_field($table, ...$fields) {

		$field_str = '';
		$field_nb = count($fields);
		for ($i = 0; $i < $field_nb - 1; $i++)
			$field_str = $field_str.$fields[$i].', ';
		$field_str = $field_str.$fields[$i];
		$query = 'SELECT '.$field_str.' FROM '.$table;
		return ($this->do_statement($query));
	}

# Give a table, a unique field and a value and retrieve the element.

	public function get_elem_by($table, $field, $value) {

		$query = 'SELECT * FROM '.$table.' WHERE '.$field.'=\''.$value.'\'';
		return ($this->do_statement($query));
	}

# Give a table and an id. Return all fields for this id or NULL if a problem occurs.

	public function get_elem_by_id($table, $id) {

		$query = "SELECT * FROM ".$table." WHERE id=".$id;
		return ($this->do_statement($query));
	}


# Execute a query, update the $log.

	private function do_query($query, $err_log, $succ_log) {
		try {
			$this->dbh->query($query);
		}
		catch (PDOException $e) {
			$this->log = $this->log.$err_log.
			$e->getMessage().PHP_EOL;
			return (FALSE);
		}
		$this->log = $this->log.$succ_log.PHP_EOL;
		return (TRUE);
	}

# Prepare and execute a statement, update the $log.

	public function do_statement($query, $args = NULL) {
		try {
			$statement = $this->dbh->prepare($query);
			if ($args)
				$statement->execute($args);
			else
				$statement->execute();
			$this->last_statement_return = $statement->rowCount();
			try {
				$result = $statement->fetchAll();
			}
			catch (PDOException $e) {
				$result = NULL;
			}
		}
		catch (PDOException $e) {
			echo 'Problème dans la requête: '.$e->getMessage().PHP_EOL;
			return (NULL);
		}
		return ($result);
	}

#Return how many rows affected by last statement.

	public function last_statement_return() {
		return ($this->last_statement_return);
	}
}
if (($this->bdd_obj = Bdd::get_instance()) == NULL) {
	$this->bdd = NULL;
}
else {
	$this->bdd = $this->bdd_obj->dbh;
}
?>
