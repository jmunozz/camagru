<?php

/*
** Fournit des instructions d'installation de la BDD.
*/

class Install_model {

	public $dbh;
	public $DB_NAME; 
	public $DB_TABLES;
	public $SET_TABLES;
	public $log;

# Try to connect with the server from infos included in 'db_infos.php'. 
# If success, create an object to manipulate BDD.

	static public function connect_host() {
		include ('config/infos.php');
		try {
			$dbh = new PDO($DB_HOST, $DB_USER, $DB_PWD, $DB_OPTIONS);
		} catch (PDOException $e) {
			$this->log = $this->log.'La connexion au serveur SQL a échouée'.
			$e->getMessage().PHP_EOL;
			return (NULL);
		}
		return (new Install_model($dbh, $DB_NAME, $DB_TABLES, $SET_TABLES));
	}

# Fill the instance variables with infos from "db_infos.php".

	public function __construct ($dbh, $DB_NAME, $DB_TABLES, $SET_TABLES) {
		$this->dbh =  $dbh;
		$this->DB_NAME = $DB_NAME;
		$this->DB_TABLES =  $DB_TABLES;
		$this->SET_TABLES = $SET_TABLES;
		$this->log = $this->log.'Connexion au serveur effectuée'.PHP_EOL;
	}

# Query to use $BD_NAME as default BDD. If $BD_NAME does not exist, return FALSE.

	public function use_bd() {
		$query = 'USE '.$this->DB_NAME;
		$err_log = 'Impossible de rejoindre '.$this->DB_NAME.' : ';
		$succ_log = 'Positionnement sur la BDD '.$this->DB_NAME.' effectué';
		return ($this->do_query($query, $err_log, $succ_log));
	}

# After trying to use the BD, check if the required tables are present.

	public function check_bd() {

		if (!$this->use_bd())
			return FALSE;
		$query =
			'SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE TABLE_SCHEMA =:table_schema AND TABLE_NAME =:table_name';
		$err_log = '';
		if (($state = $this->prepare_statement($query)) == FALSE)
			return (FALSE);
		foreach ($this->DB_TABLES as $table) {
			$err_log = 'Erreur: check de la table '.$table;
			$succ_log = '';
			$args = array('table_schema' => $this->DB_NAME, 'table_name' => $table);
			if ($this->do_statement($state, $args, $err_log, $succ_log) == FALSE)
				return (FALSE);
			if (!$state->fetch()) {
					$this->log = $this->log.'La table '.$table.
					' n\'existe pas'.PHP_EOL;
					return (FALSE);
			}
		}
		$this->log = $this->log.'Toutes les tables sont présentes'.PHP_EOL;
		return (TRUE);
	}

# Set a new BDD. If a BDD already exists, drop it and erase gallery content.

	public function set_bd() {

		$query =
			'DROP DATABASE IF EXISTS '.$this->DB_NAME.';
			CREATE DATABASE IF NOT EXISTS '.$this->DB_NAME;
		$err_log = 'Impossible de créer la BDD';
		$succ_log = 'La BDD a été créée';
		if ($this->do_query($query, $err_log, $succ_log) == FALSE)
			return (FALSE);
		if ($this->set_french_bdd() == FALSE)
			return (FALSE);
		if (!$this->use_bd())
			return (FALSE);
		$query = $this->SET_TABLES;
		echo $query;
		$err_log = 'Impossible de créer les tables de la BDD';
		$succ_log = 'Les tables de la BDD ont été créés';
		return ($this->do_query($query, $err_log, $succ_log));
	}

# Set the BDD in french (month display).

	public function set_french_bdd() {
	if (!$this->use_bd())
		return (FALSE);
	$query = "SET GLOBAL lc_time_names = 'fr_FR'";
	$err_log = 'Impossible de mettre la BDD en français';
	$succ_log = 'La BDD a été mise en français';
	return ($this->do_query($query, $err_log, $succ_log));
	}

# Insert an administrator in the BDD.

	public function set_admin($nom, $pre, $mail, $pwd) {
		if (!$this->use_bd())
			return FALSE;
		$query =
		"INSERT into em_users (nom, prenom, mail, pwd, droits, date)
		VALUES ('".$nom."', '".$pre."', '".$mail."', '".$pwd."', 'admin',
		NOW())";
		$err_log = 'Impossible d\'insérer l\'administrateur';
		$succ_log = 'L\'administrateur a été inséré';
		return ($this->do_query($query, $err_log, $succ_log));
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

# Prepare a query, update the $log if necessary.

	private function prepare_statement($query, $err_log) {
		try {
			$statement = $this->dbh->prepare($query);
		}	
		catch (PDOException $e) {
			$this->log = $this->log.$err_log.
			$e->getMessage().PHP_EOL;
			return (FALSE);
		}
		return ($statement);
	}

# Execute a statement, update the $log.

	private function do_statement($statement, $args, $err_log, $succ_log) {
		try {
			$statement->execute($args);
		}
		catch (PDOException $e) {
			$this->log = $this->log.$err_log.
			$e->getMessage().PHP_EOL;
			return (FALSE);
		}
		$this->log = $this->log.$succ_log.PHP_EOL;
		return (TRUE);
	}

}
