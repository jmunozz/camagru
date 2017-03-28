<?php
$DB_TYPE ='mysql:';
$DB_HOST= $DB_TYPE.'host=localhost';
$DB_NAME = 'db_camagru';
$DB_HOSTNAME = $DB_HOST.';dbname='.$DB_NAME;
$DB_USER = 'root';
$DB_PWD = 'root';
$DB_TABLES = array('comments', 'images', 'likes', 'users');
$SET_TABLES =  'CREATE TABLE users (
								id INT PRIMARY KEY AUTO_INCREMENT,
								login VARCHAR(255) NOT NULL,
								pwd VARCHAR(255) NOT NULL,
								email VARCHAR(255) NOT NULL,
								code VARCHAR(255),
								valid BOOLEAN DEFAULT 0);
				CREATE TABLE images (
								id INT PRIMARY KEY AUTO_INCREMENT,
								id_user INT NOT NULL,
								type INT,
								name VARCHAR(255) NOT NULL,
								path VARCHAR(255) NOT NULL,
								date DATETIME);
				CREATE TABLE comments (
								id INT PRIMARY KEY AUTO_INCREMENT,
								id_user INT NOT NULL,
								id_image INT NOT NULL,
								text VARCHAR(512) NOT NULL,
								DATE DATETIME);
				CREATE TABLE likes (
								id INT PRIMARY KEY AUTO_INCREMENT,
								id_user INT NOT NULL,
								id_image INT NOT NULL);';
$DB_OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
?>
