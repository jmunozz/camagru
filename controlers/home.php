<?php
Class Home {
	public $url_base;

	public function __construct($url_base) {
		$this->url_base = $url_base;
	}
	
	public function index() {
		if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_login']) ||
			!$_SESSION['user_id'] || !$_SESSION['user_login']) {
			header('location: '.$this->url_base.'/login');
			return;
		}
		else 
		{
			include ('models/gallery_model.php');
			$gallery_model = new Gallery_model($this->url_base);
			$gallery = $gallery_model->get_all_images();
			$filters = $gallery_model->get_all_filters($_SESSION['user_id']);
			$h_list = $filters;
			$v_list = $gallery;
			include('views/head.php');
			include('views/header.php');
			include('views/home.php');
			include('views/footer.php');
		}
	}

	public function sendPicture() {
		include ('models/home_model.php');
		$model = new Home_model();
		print_r($_POST);
		$model->createImage($_POST);
	}
}
?>
