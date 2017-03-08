<?php

/*
** Fournit des instructions d'installation de la BDD.
*/

class Model_bdd {

	public $dbh;
	public $DB_NAME; 
	public $DB_TABLES;
	public $SET_TABLES;
	public $log;

# Try to connect with the server from infos included in 'db_infos.php'. 
# If success, create an object to manipulate BDD.

	static public function connect_host() {
		try {
			include (basename(__DIR__).'/'.'db_infos.php');
			$dbh = new PDO($DB_HOST, $DB_USER, $DB_PWD, $DB_OPTIONS);
		} catch (PDOException $e) {
			$this->log = $this->log.'La connexion au serveur SQL a échouée'.
			$e->getMessage().PHP_EOL;
			return (NULL);
		}
		return (new Model_bdd($dbh, $DB_NAME, $DB_TABLES, $SET_TABLES));
	}

# Fill the instance variables with infos from "db_infos.php".

	public function __construct ($dbh, $DB_NAME, $DB_TABLES, $SET_TABLES) {
		$this->dbh =  $dbh;
		$this->DB_NAME = $DB_NAME;
		$this->DB_TABLES =  $DB_TABLES;
		$this->SET_TABLES = $SET_TABLES;
		$this->log = $this->log.'Connexion au serveur effectuée'.PHP_EOL;
	}

# Query to use $BD_NAME as default bd. If $BD_NAME does not exist, return FALSE.

	public function use_bd() {
		$query = 'USE '.$this->DB_NAME;
		try {
			$this->dbh->query($query);

}
		catch (PDOException $e) {
			$this->log = $this->log.'Impossible de rejoindre '.$this->DB_NAME.' : '.
			$e->getMessage().PHP_EOL;
			return (FALSE);
		}
		$this->log = $this->log.'Positionnement sur la BDD '.$this->DB_NAME.
		' effectué'.PHP_EOL;
		return (TRUE);
	}

# After trying to use the BD, check if the required tables are present.

	public function check_bd() {

		if (!$this->use_bd())
			return FALSE;
		$query = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
		WHERE TABLE_SCHEMA =:table_schema AND TABLE_NAME =:table_name';
		$statement = $this->dbh->prepare($query);
		foreach ($this->DB_TABLES as $table) {
			try {
				$statement->execute(array('table_schema' => $this->DB_NAME,
				'table_name' => $table));
			} catch (PDOException $e) {
				$this->log = $this->log.'Impossible de checker la table '.
				$table.'  de la BDD'.$e->getMessage().PHP_EOL;
				return (FALSE);
			}
			if (!($ret = $statement->fetch())) {
				$this->log = $this->log.'La table '.$table.' n\'existe pas'.PHP_EOL;
				return (FALSE);
			}
		}
		$this->log = $this->log.'Toutes les tables sont présentes'.PHP_EOL;
		return (TRUE);
	}

# Set a new BDD. If a BDD already exists, drop it and erase gallery content.

	public function set_bd() {

		$query = 'DROP DATABASE IF EXISTS '.$this->DB_NAME.';
		CREATE DATABASE IF NOT EXISTS '.$this->DB_NAME;
		try {
			$this->dbh->query($query);
		} catch (PDOException $e) {
			$this->log = $this->log. 'Impossible de créer la base '.$e->getMessage().PHP_EOL;
			return (FALSE);
		}		
		$images = glob('gallery/*');
		foreach ($images as $image) {
			if (is_file($image))
				unlink($image);
		}
		$this->log = 'Gallerie supprimée'.PHP_EOL;
		if (!$this->use_bd())
			return (FALSE);
		try {
			$this->dbh->query($this->SET_TABLES);
		} catch (PDOException $e) {
			$this->log = $this->log.'Impossible de créer les tables '.$e->getMessage().PHP_EOL;
			return (FALSE);
		}
		$this->log = $this->log.'Tables crées'.PHP_EOL;
		return (TRUE);
	}

# Set a administrator profile in the users table.

	public function set_admin($login, $pwd, $email) {
		if (!$this->use_bd())
			return FALSE;
		$query = sprintf("INSERT INTO users (login, pwd, email, valid)
				VALUES ('%s', '%s', '%s', '1')", $login, hash('whirlpool', $pwd), $email);
		try {
			$this->dbh->query($query);
		}
		catch (PDOException $e) {
			$this->log = $this->log.'Impossible de créer un compte administrateur '.
			$e->getMessage().PHP_EOL;
			return FALSE;
		}
		$this->log = $this->log.'Compte administraeur créé'.PHP_EOL;
		return TRUE;
	}
}
