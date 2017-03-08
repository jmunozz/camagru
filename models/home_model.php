<?php

Class Home_model {

	static public function user_is() {
		if ($_SESSION['user_id'] && $_SESSION['droits'])
			return ($_SESSION['droits']);
	}
}
