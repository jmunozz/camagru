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
		!isset($_POST['get_user_likes']) || $_POST['get_user_likes'] !== 'ok') {
			print_r($_POST);
			echo ($_POST['get_user_likes']);
			echo ('aaaaaa'.$_POST[1]);
			$this->display();
		}
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

	public function add_comment() {
		if (!isset($_SESSION['user_id']) || !$_SESSION['user_id'] || !isset(
			$_POST['id_image']) || !$_POST['id_image'] || !isset($_POST['text']) ||
			!$_POST['text'])
			$this->display();
		else {
			$ret = $this->model->add_comment($_SESSION['user_id'], 
			$_POST['id_image'], $_POST['text']);
			if ($ret) {
				header('Content-Type: text/xml');
				echo '<comment>';
				echo '<user_login>'.$_SESSION['user_login'].'</user_login>';
				echo '<date>'.strftime('%Y-%m-%d %k:%M').'</date>';
				echo '<text>'.$_POST['text'].'</text>';
				echo '</comment>';
			}
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
		$gallery = $this->model->get_all_images();
		$gallery = $this->model->transform_null($gallery);
				include ('views/head.php');
		include ('views/header.php');
		include ('views/gallery.php');
		include ('views/footer.php');
	}
}
?>
