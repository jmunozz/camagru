<?php
Class Home {
	public $url_base;

	public function __construct($url_base) {
		$this->url_base = $url_base;
	}
	
	public function index() {
		if (!$_SESSION['user_id'] || !$_SESSION['user_login']) {
			header('location: '.$this->url_base.'/login');
			return;
		}
		else 
		{
$imgs = array(array('path' => 'assets/img/camera.png'), array('path' => 'assets/img/camera.png'), array('path' => 'assets/img/camera.png'), array('path' => 'assets/img/camera.png'), array('path' => 'assets/img/camera.png'));

			include('views/head.php');
			include('views/header.php');
			include('views/home.php');
			include('views/footer.php');
		}
	}
}
?>
