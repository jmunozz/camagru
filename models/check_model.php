<?php

Class Check {

# Renvoie TRUE si champ valide, FALSE sinon.

	static public function _field($field, $length = '+') {
	
		$regex = sprintf('/^[A-Z0-9._\+-]%s$/i', $length);
		return (self::match($field, $regex));
	}

# Renvoie TRUE si mail valide, FALSE sinon.

	static public function _mail($mail) {
		if (filter_var($mail, FILTER_VALIDATE_EMAIL) == FALSE)
			return (FALSE);
		return (TRUE);
	}

# Renvoie TRUE si le format DATETIME est valide, FALSE sinon.

	static public function _datetime($datetime) {
		$regex = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}$/';
		return (self::match($datetime, $regex));
	}

# Renvoie TRUE si la variable est un INT, FALSE sinon.

	static public function _int($int) {
	if (filter_var($int, FILTER_INT) === FALSE)
			return (FALSE);
		return (TRUE);
	}

# Attention cette fonction renvoie TRUE si match ou FALSE si match pas ou si erreur.

	static private function match($str, $regex) {
		if (!($ret = preg_match($regex, $str)) || $ret == FALSE)
			return (FALSE);
		else
			return (TRUE);
	}
}
