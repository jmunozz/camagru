<?php

Class Settings_Model {

	static public $url_base;

	static public function init($url_base) {
		self::$url_base = $url_base;
	}

}

?>
