<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);
session_name('em');
session_start();
$url_base = '/'.basename(__DIR__);
$controlers_list = array('settings.php', 'install.php', 'home.php', 'signin.php', 'login.php', 'add.php', 'gallery.php', 'validate.php');
# Intègre tous les controlers en se protégeant contre l'upload de fichiers infectés.

$controlers = glob('controlers/*.php'); // glob à partir du dossier courant.
foreach($controlers as $controler) {
	if (in_array(basename($controler), $controlers_list)) {
		require_once($controler);
	}
}
if (isset($_GET['path'])) {
	$path = $_GET['path'];
	$url = explode('/', $path);
}
else
	$url = NULL;

if (!$url || $url[0] == 'index.php' ) {
	$home = new Home($url_base);
	$home->index();
}
else  {
	if (!class_exists($url[0])) {
		header('HTTP/1.O 404 Not Found');
		require('views/404_view.php');
		exit();
	}
	else {
		$class = new $url[0]($url_base);
		if (isset($url[1]) && $url[1]) {
			if (method_exists($url[0], $url[1]) && is_callable(array($class, $url[1]))) {
				if (isset($url[2]) && $url[2])
					call_user_func(array($class, $url[1]), $url[2]);
				else
					call_user_func(array($class, $url[1]));
			}
			else {
				header('HTTP/1.O 404 Not Found');
				require('views/404_view.php');
				exit();
			}
		}
		else
			call_user_func(array($class, 'index'));
	}
}
?>
