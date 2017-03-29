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
			$gallery = $gallery_model->get_user_images($_SESSION['user_id']);
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
		$image = $model->createImage($_POST);
		header('Content-Type: text/xml');
		echo '<image>';
		echo '<src>assets/gallery/img_'.$image[0].'.png</src>';
		echo '<id>'.$image[1].'</id>';
		echo '</image>';
	}

	public function deleteImage() {
		include ('models/home_model.php');
		$model = new Home_model();
		if ($model->deleteImage($_POST['id'], $_SESSION['user_id']))
			echo 'TRUE';
		else
			echo 'FALSE';
	}

}
?>
