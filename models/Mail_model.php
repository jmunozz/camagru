<?php

Class Mail {

	static public $url_base;

	
	/*
	** Format Code Validation email and send to user.
	*/
	static public function sendCodeToUser($code, $email, $login) {

		$url = 	$_SERVER['HTTP_HOST'] . self::$url_base . '/validate?mail=' . $email . '&code=' . $code;

		$subject = 	'Camagru: Validation de votre compte';

		$body = 	'<h1>Félicitations ' . $login . ' pour ton inscription !</h1>' .
					'<p>Ton code de validation est : '. $code .' .</p>' .
					'<br /> ' .
					'<p>Tu peux valider directement ton inscription à cette addresse:' .
					' <a href="' . $url . '">' . $url . '</a></p>';


		self::sendMail($email, $subject, $body);
	}


	/*
	** Format Forgiven Password email and send to user.
	*/ 
	static public function sendPwdCodeToUser($code, $email, $login) {

		$url = $_SERVER['HTTP_HOST'] . '/validate/password_change?mail=' . $email . '&code=' . $code;

		$subject =	'Camagru: Ré-initialisation de votre mot de passe';

		$body = 	'<p>Pour ré-initialiser votre mot de passe, cliquez sur le lien suivant:</p>' .
					'<a href= "'. $url . '">' . $url . '</a>';

		self::sendMail($email, $subject, $body);
	}

	
	/*
	** Take email address, subject, body and send email. Return Bool.
	*/
	static public function sendMail($email, $subject, $body) {

		// Headers must be separated by PHP_EOL.
		$additional_headers = 
			'MIME-Version: 1.0' . PHP_EOL .
			'Content-type: text/html; charset=utf-8' . PHP_EOL .
			'From: Camagru';

		return mail($email, $subject, $body, $additional_headers);
	}
}

?>
