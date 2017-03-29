<?php

Class Mail {

	static public $url_base;

	static public function init($url_base) {

		self::$url_base = $url_base;
	}

	static public function sendCodeToUser($code, $email, $login) {

		$objet = 'Camagru: Validation de votre compte';
		$body = '<h1>Félicitations '.$login.' pour ton inscription !</h1>';
		$body .= '<p>Ton code de validation est : '.$code.' .</p>';
		$body .= '<br /> ';
		$body .= '<p>Tu peux valider directement ton inscription à cette adresse: ';
		$body .= $_SERVER['HTTP_HOST'].self::$url_base.'/validate?mail='.
			$email.'&code='.$code.'</p>';
		self::sendMail($email, $objet, $body);
	}

	static public function sendPwdCodeToUser($code, $email, $login) {
		$objet = 'Camagru: Ré-initialisation de votre mot de passe';
		$body = '<p>Pour ré-initialiser votre mot de passe, cliquez sur le 
		lien suivant</p>';
		$body .= '<a href= "'.$_SERVER['HTTP_HOST'].self::$url_base.
			'/validate/init?mail='.$email.'&code='.$code.'">ici</a>';
		self::sendMail($email, $objet, $body);
	}

	static public function sendMail($email, $objet, $body) {

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\b";
		$headers .= 'From: Camagru';
		if (!mail($email, $objet, $body, $headers))
			echo 'mail cant be delivered to '.$email;
	}
}

?>
