<?php

Class Gallery {
	public $model;

	public function __construct() {
		require_once('models/gallery_model.php');
		$this->model = new Gallery_model();
		$this->PICTURES_PER_PAGE = 15;
	}



	public function index()  {
		$this->display();
	}

	
	/*
	** Return number of pages that needs to be displayed on pagination.
	*/
	public function get_total_pages() {

		// User is making a GET request. Redirect on gallery default page.
		if(!isset($_POST['total_pages'])) {
			$this->redirect();
		}
		// User is making an POST AJAX request. Return XML object with total_pages number.
		else {
			$total_pictures = $this->model->get_total_pictures();
			$total_pages = ceil($total_pictures / $this->PICTURES_PER_PAGE);
			header('Content-Type: text/xml');
			echo '<total_pages>' . $total_pages . '</total_pages>';
		}
	}


	/*
	** Return pictures_id of pictures liked by user if connected.
	*/
	public function get_user_likes() {


		// User is making a GET request. Redirect on gallery default page.
		if(!isset($_POST['get_user_likes'])) {
			$this->redirect();
		}

		// User is not connected. Return an empty XML object.
		if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
			header('Content-Type: text/xml');
			echo '<like></like>';
		}

		// Request is valid. Return XML object.
		else
		{
			$likes = $this->model->get_user_likes($_SESSION['user_id']);
			header('Content-Type: text/xml');
			echo '<like>';
			foreach ($likes as $like) {
				echo '<image id="' . $like['id_image'] . '" />';
			}
			echo '</like>';
		}
	}

	/*
	** Add comment to id_image picture.
	*/
	public function add_comment() {


		// User is making a GET request. Redirect on default gallery page.
		if(!isset($_POST['id_image']) || !isset($_POST['text'])) {
			$this->redirect();
		}

		// User is not connected. Return skip text.
		if (!isset($_SESSION['user_id']) || !$_SESSION['user_id'])
			echo 'skip';

		// Valid Ajax POST request. Add comment and return it.
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
	
	/*
	** Return comments for id_image picture.
	*/
	public function get_comments() {

		// User is making a GET request. Redirect on default gallery page.
		if (!isset($_POST['id_image']))
			$this->redirect();
		
		// User is making a valid Ajax POST request. Return id_image comments.
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

	/* 
	** Like/Dislike image_id picture.
	*/
	public function like() {

		// User is making a GET request. Redirect on default gallery page.
		if(!isset($_POST['id_image'])) {
			$this->redirect();
		}

		// User is not conncted. Return skip text.
		if (!isset($_SESSION['user_id']) || !$_SESSION['user_id'])
			echo 'skip';
		
		// User is making an Ajax POST request. Add like and return ok.
		else
		{
			$ret = $this->model->add_like($_SESSION['user_id'], $_POST['id_image']);
			echo $ret;
		}
	}
	

	/* 
	** Will display default gallery page. Cannot be called in url.
	*/
	private function display() {

		$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
		if ($page < 0) $page = 0;

		$gallery = $this->model->get_paginated_pictures($this->PICTURES_PER_PAGE, $page);
		//$gallery = $this->model->get_all_images();
		$gallery = $this->model->transform_null($gallery);
		include ('views/head.php');
		include ('views/header.php');
		include ('views/gallery.php');
		include ('views/footer.php');
		exit();
	}

	/*
	** Will redirect user on default gallery page. Cannot be called in url.
	*/
	private function redirect() {
		header('Location: /gallery');
		exit();	
	}
}
?>
