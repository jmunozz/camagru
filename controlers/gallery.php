<?php

Class Gallery {
	public $url_base;
	public $model;

	public function __construct($url_base) {
		$this->url_base = $url_base;
		require_once('models/gallery_model.php');
		$this->model = new Gallery_model($url_base);
	}

	public function index()  {
		$this->display();

	}

	public function get_user_likes() {
		if (!isset($_SESSION['user_id']) || !$_SESSION['user_id'] ||
		!isset($_POST['get_user_likes']) || $_POST['get_user_likes'] !== 'ok')
			$this->display();
		else
		{
			$likes = $this->model->get_user_likes($_SESSION['user_id']);
			header('Content-Type: text/xml');
			echo '<like>';
				foreach ($likes as $like) {
					echo '<image id="'.$like['id_image'].'" />';
				}
			echo '</like>';
		}
	}

	public function get_comments() {
		if (!isset($_POST['id_image']) || !$_POST['id_image'])
			$this->display();
		else {
			$comments = $this->model->get_comments($_POST['id_image']);
			header('Content-Type: text/xml');
			echo '<comments>';
			foreach ($comments as $comment) {
				echo '<comment>';
				echo '<user_login>'.$comment['user_login'].'</user_login>';
				echo '<date>'.$comment['date'].'</date>';
				echo '<text>'.$comment['text'].'</text>';
				echo '</comment>';
			}
			echo '</comments>';
		}
	}

	public function like() {
		if (!isset($_SESSION['user_id']) || !$_SESSION['user_id'] || !isset(
		$_POST['id_image']) || !$_POST['id_image'])
			$this->display();
		else
		{
			$ret = $this->model->add_like($_SESSION['user_id'],
			$_POST['id_image']);
			echo $ret;
		}
	}
	public function comment() {
	}

	public function display() {
		$images = $this->model->get_all_images();
		$images = $this->model->transform_null($images);
		include ('views/head.php');
		include ('views/header.php');
		include ('views/gallery.php');
		include ('views/footer.php');
	}
}
?>
