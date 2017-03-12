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

	public function insert_user($nom, $pre, $mail, $pwd, $co, $dr) {
		$co = ($co) ? "', '".$co : "";
		$co_n = ($co) ? ", comite_id" : "";
		$query =
		"INSERT into em_users (nom, prenom, mail, pwd".$co_n.", droits, date)
		VALUES ('".$nom."', '".$pre."', '".$mail."', '".$pwd.$co."', '".$dr."',
		NOW())";
		$err_log = 'Impossible d\'insérer l\'utilisateur';
		$succ_log = 'L\'utilisateur a été inséré';
		return ($this->do_query($query, $err_log, $succ_log));
	}

# Insert a envent in the BDD.

	public function insert_event($ti, $ca, $co, $li, $da, $de, $pu) {

		$query =
		"INSERT into em_events
		(titre, lieu, categorie, comite_id, date, description, publique)
		VALUES (".$ti.", ".$li.", ".$ca.",
		'".$co."', '".$da."', ".$de.", '".$pu."')";
		$err_log = 'Impossible d\'insérer l\'évènement';
		$succ_log = 'L\'évènement '.$ti.' a été inséré';
		return ($this->do_query($query, $err_log, $succ_log));
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

# Give a table and an id. Return all fields for this id or NULL if a problem occurs.

	public function get_elem_by_id($table, $id) {

		$query = "SELECT * FROM ".$table." WHERE id=".$id;
		return ($this->do_statement($query));
	}

# Formated request to get all information on events for display.

	public function get_events() {
		$query =
		'SELECT titre, lieu, categorie, em_comites.name AS comite, 
		DAY(date) AS jour, MONTHNAME(date) AS mois, 
		DATE_FORMAT(date, \'%Hh%i\') AS heure, description, publique
		FROM em_events
		INNER JOIN em_comites
		ON em_comites.id = em_events.comite_id
		WHERE DATE(date) >= CURRENT_DATE()
		ORDER BY date';
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
