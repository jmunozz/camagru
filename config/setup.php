<?php


# Try to connect with the server from infos included in `config/database.php` 
# If success, create an object to manipulate BDD.

function connect_host() {
	include('config/database.php');
	try {
		return new PDO($DB_HOST, $DB_USER, $DB_PWD, $DB_OPTIONS);
	} catch (PDOException $e) {		
		echo 'La connexion au serveur SQL a échouée: '. $e->getMessage() . PHP_EOL;
		return false;
	}
}

# Set a new DB. If a DB already exists, DROP IT. Set Tables and Admin.
function set_db($dbh) {
	include('config/database.php');

	// Create Database
	$query1 = 'DROP DATABASE IF EXISTS ' . $DB_NAME . '; CREATE DATABASE IF NOT EXISTS ' . $DB_NAME;
	$error1 = 'Impossible de créer la base';

	// Use Database
	$query2 = 'USE ' . $DB_NAME;
	$error2 = 'Impossible de rejoindre ' . $DB_NAME;

	// Create Tables
	$query3 = $SET_TABLES;
	$error3 = 'Impossible de créer les tables';

	// Create Admin
	$pwd = 'admin';
	$login = 'admin';
	$email = 'admin@admin.com';
	$query4 = sprintf("INSERT INTO users (login, pwd, email, valid) 
		VALUES ('%s', '%s', '%s', '1')", $login, hash('whirlpool', $pwd), $email);
	$error4 = 'Impossible to create admin';

	return (execute_query($dbh, $query1, $error1) && 
	 			execute_query($dbh, $query2, $error2) &&
	 				execute_query($dbh, $query3, $error3) &&
	 					execute_query($dbh, $query4, $error4) &&
	 						insert_all_filters($dbh));

}


# Insert all filters in images Table.
function insert_all_filters($dbh) {


	$all_filters_filenames = array(
		array('name' => 'Argent', 'filename' => 'argent_1.png'),
		array('name' => 'Bernie 1', 'filename' => 'bernie_1.png'),
		array('name' => 'Bernie 2', 'filename' => 'bernie_2.png'),
		array('name' => 'De Gaulles 1', 'filename' => 'degaulles_1.png'),
		array('name' => 'Fillon 1', 'filename' => 'fillon_1.png'),
		array('name' => 'Hamon 1', 'filename' => 'hamon_1.png'),
		array('name' => 'Kebab', 'filename' => 'kebab.png'),
		array('name' => 'Kim 1', 'filename' => 'kim_1.png'),
		array('name' => 'Kim 2', 'filename' => 'kim_2.png'),
		array('name' => 'Macron 1', 'filename' => 'macron_1.png'),
		array('name' => 'Melanchon 1', 'filename' => 'melanchon_1.png'),
		array('name' => 'Melanchon 2', 'filename' => 'melanchon_2.png'),
		array('name' => 'Phillipot 1', 'filename' => 'phillipot_1.png'),
		array('name' => 'Strauss Kahn 1', 'filename' => 'strausskahn_1.png'),
		array('name' => 'Strauss Kahn 2', 'filename' => 'strausskahn_2.png'),
		array('name' => 'Trump 1', 'filename' => 'trump_1.png'),
		array('name' => 'Trump 2', 'filename' => 'trump_2.png')
	);

	$has_failed = false;

	$q = "INSERT INTO images (id_user, type, name, path, date) 
				VALUES ('%d', '%d', '%s', '%s', NOW())";

	foreach ($all_filters_filenames as $filter) {
		$query = sprintf($q, 0, FILTER_TYPE, $filter['name'], $filter['filename']);
		$error = 'Impossible to add filter: ' . $filter['name'];
		if (!execute_query($dbh, $query, $error)) 
			$has_failed = true;
	}
	return !$has_failed;

}

# Execute with a given query, PDO and error message. Return true/false
function execute_query($dbh, $query, $error) {

	try {
		$dbh->query($query);
	} catch (PDOException $e) {
		echo $error . ': ' . $e->getMessage() . PHP_EOL;
		return false;
	}
	return true;
}

# Use all previous functions.
function install() {
	$dbh = connect_host();
	if ($dbh && set_db($dbh)) {
		return true;
	} else {
		echo "Installation failed." . PHP_EOL;
		return false;
	}
}
