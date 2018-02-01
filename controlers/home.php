<?php
Class Home {

	public function index() {
		if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_login']) ||
			!$_SESSION['user_id'] || !$_SESSION['user_login']) {
			header('location: /login');
			return;
		}
		else 
		{
			include ('models/gallery_model.php');
			$gallery_model = new Gallery_model();
			$gallery = $gallery_model->get_user_images($_SESSION['user_id']);
			$filters = $gallery_model->get_all_filters($_SESSION['user_id']);

			if (!$filters) $filters = [];
			if (!$gallery) $gallery = [];
			$v_list = $gallery;
			include('views/head.php');
			include('views/header.php');
			include('views/home.php');
			// include('views/footer.php');
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
