<?php
$DB_HOST= 'mysql:host=localhost';
$DB_NAME = 'db_camagru';
$DB_USER = 'root';
$DB_PWD = 'root';
$DB_TABLES = array('comments', 'images', 'likes', 'users');
$DB_OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
