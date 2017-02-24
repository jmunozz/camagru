<?php
$url_base = basename(__DIR__);
$controlers_list = array('install.php', 'home.php');

# Intègre tous les controlers en se protégeant contre l'upload de fichiers infectés.

$controlers = glob('controlers/*.php'); // glob à partir du dossier courant.
foreach($controlers as $controler) {
	if (in_array(basename($controler), $controlers_list)) {
		require_once($controler);
	}
}
$path = $_GET['path'];
$url = explode('/', $path);

if (!$url[0] || $url[0] == 'index.php' ) {
	$home->index();
}
else  {
	if (!class_exists($url[0])) {
		header('HTTP/1.O 404 Not Found');
		require('views/404_view.php');
		exit();
	}
	else {
		$class = new $url[0]();
		if ($url[1]) {
			if (method_exists($url[0], $url[1])) {
				if ($url[2])
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
