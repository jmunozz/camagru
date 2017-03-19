<?php

Class Home_model {
	private $bdd_obj;
	private $bdd;
	private $img_id = null;
	public $occ = 0;

	static public function user_is() {
		if ($_SESSION['user_id'] && $_SESSION['droits'])
			return ($_SESSION['droits']);
	}

	private function getRandomId() {
		$str = '';
		for ($i = 0; $i < 10 ; $i++) {
			$str = $str.(string)rand(0, 9);
		}
		return($str);
	}
	
	public function extractImage($tab) {
		foreach($tab as $key => $value) {
			if (!strcmp($key, 'img')){
				return $value;
			}
		}
		return null;
	}

	public function extractFilters($tab) {
		$filters = [];
		foreach($tab as $key => $value) {
			if (!strncmp($key, 'filter_', 7)) {
				array_push($filters, $value);
			}
		}
		return $filters;
	}

	public function resizeImage($height, $width) {}

	public function saveImage($resource, $name) {
		require_once('models/bdd_model.php');
		echo $name;
		$this->bdd_obj->insert_image(array('id_user' => $_SESSION['user_id'], 'type'
		=> 1, 'name' => $name, 'path' => 'assets/gallery/img_'.$this->img_id.'.png'));
		imagepng($resource, 'assets/gallery/img_'.$this->img_id.'.png');
		$this->freeImage($resource);
		$this->removeTempImage();
	}
	
	public function freeImage($resource){
		imagedestroy($resource);
	}

	public function getTempImage($img) {
		$this->occ++;
		$data = base64_decode($img);
		$img_id = $this->getRandomId();
		$ret = file_put_contents('assets/gallery/tmp_'.$img_id.'.png', $data);
		echo $ret;
		echo mime_content_type('assets/gallery/tmp_'.$img_id.'.png');
		$this->img_id = $img_id;
	}

	public function removeTempImage() {
		unlink('assets/gallery/tmp_'.$this->img_id.'.png');
		$this->img_id = NULL;
	}

#copy filter into resource image and reisze.

	public function copyFilterIntoImage($img, $filter_obj) {

		$filter = imagecreatefrompng('assets/gallery/filters/'.$filter_obj->src);
		echo (imagesy($img));

		$f_height = imagesy($filter);
		$f_width = imagesx($filter);
		if (!imagecopyresampled($img, $filter, $filter_obj->x, $filter_obj->y, 0, 0,
			round($filter_obj->width), round($filter_obj->height), 
			$f_width, $f_height))
			echo 'ECHEC';
		else
			echo 'SUCCESS';
	}

	public function createImage($tab) {

		$image_data = $tab['img'];
		$filters = $this->extractFilters($tab);
		$name = isset($tab['name']) ? $tab['name'] : 'Inconnu';
		echo 'name'.$name;

		$this->getTempImage($image_data);
		$image = imagecreatefrompng('assets/gallery/tmp_'.$this->img_id.'.png');
		foreach($filters as $filter) {
			$obj = json_decode($filter);
			$this->copyFilterIntoImage($image, $obj);
		}
		$this->saveImage($image, $name);
		echo 'occ:'.$this->occ;
	}

}
