<?php

Class Home_model {
	private $bdd_obj;
	private $bdd;
	private $img_id = null;
	private $bdd_id = null;

	static public function user_is() {
		if ($_SESSION['user_id'] && $_SESSION['droits'])
			return ($_SESSION['droits']);
	}

# Return a random ID of 10 numbers.

	private function getRandomId() {
		$str = '';
		for ($i = 0; $i < 10 ; $i++) {
			$str = $str.(string)rand(0, 9);
		}
		return($str);
	}

# get image value from $_POST. If not found, return null;

	public function extractImage($tab) {
		foreach($tab as $key => $value) {
			if (!strcmp($key, 'img')){
				return $value;
			}
		}
		return null;
	}

# get tab of filters from $_POST. If no filter, return empty tab.

	public function extractFilters($tab) {
		$filters = [];
		foreach($tab as $key => $value) {
			if (!strncmp($key, 'filter_', 7)) {
				array_push($filters, $value);
			}
		}
		return $filters;
	}

# call delete_image from bdd_model. Return TRUE or FALSE.

	public function deleteImage($id, $user_id) {
		require_once('models/bdd_model.php');
		if (!($path = $this->bdd_obj->get_path_image($id))) 
			return FALSE;
		$path = $path[0][0];
		if ($this->bdd_obj->delete_image($id, $user_id)) {
			unlink($path);
			return TRUE;
		}
		else
			return FALSE;
	}


	public function saveImage($resource, $name) {
		require_once('models/bdd_model.php');
		$this->bdd_obj->insert_image(array('id_user' => $_SESSION['user_id'], 'type'
		=> 1, 'name' => $name, 'path' => 'assets/gallery/img_'.$this->img_id.'.png'));
		$id = $this->bdd_obj->get_last_id();
		$this->bdd_id = $id[0][0];
		imagepng($resource, 'assets/gallery/img_'.$this->img_id.'.png');
		$this->freeImage($resource);
		$this->removeTempImage();
	}
	
	public function freeImage($resource){
		imagedestroy($resource);
	}

	public function getTempImage($img) {
		$data = base64_decode($img);
		$img_id = $this->getRandomId();
		$ret = file_put_contents('assets/gallery/tmp_'.$img_id.'.png', $data);
		$this->img_id = $img_id;
	}

	public function removeTempImage() {
		unlink('assets/gallery/tmp_'.$this->img_id.'.png');
	}

# Copy filter into resource image and reisze.

	public function copyFilterIntoImage($img, $filter_obj) {

		$filter = imagecreatefrompng('assets/gallery/filters/'.$filter_obj->src);
		$f_height = imagesy($filter);
		$f_width = imagesx($filter);

		imagecopyresampled($img, $filter, $filter_obj->x, $filter_obj->y, 0, 0,
		round($filter_obj->width), round($filter_obj->height), $f_width, $f_height);
	}

	public function createImage($tab) {

		$image_data = $tab['img'];
		$filters = $this->extractFilters($tab);
		$name = isset($tab['name']) ? $tab['name'] : 'Inconnu';

		$this->getTempImage($image_data);
		$image = imagecreatefrompng('assets/gallery/tmp_'.$this->img_id.'.png');
		foreach($filters as $filter) {
			$obj = json_decode($filter);
			$this->copyFilterIntoImage($image, $obj);
		}
		$this->saveImage($image, $name);
		return (array($this->img_id, $this->bdd_id));
	}
}
