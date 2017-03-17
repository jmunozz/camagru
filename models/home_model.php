<?php

Class Home_model {
	private $img_id = null;

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

	public function saveImage($resource) {
		imagepng($resource, 'assets/gallery/img_'.$this->img_id);
		$this->freeImage($resource);
		$this->removeTempImage();
	}
	
	public function freeImage($resource){
		imagedestroy($resource);
	}

	public function getTempImage($img) {
		$data = base64_decode($img);
		$img_id = $this->getRandomId();
		file_put_contents('assets/gallery/tmp_'.$img_id, $data);
		$this->img_id = $img_id;
		return (imagecreatefrompng('assets/gallery/tmp_'.$this->img_id));
	}

	public function removeTempImage() {
		unlink('assets/gallery/tmp_'.$this->img_id);
		$this->img_id = NULL;
	}

#copy filter into resource image and reisze.

	public function copyFilterIntoImage($img, $filter_obj) {
		$filter = imagecreatefrompng('assets/gallery/filters/'.$filter_obj->src);
		$f_height = imagesy($filter);
		$f_width = imagesx($filter);
		echo $f_width.'    et    '.$f_height;
		if (!imagecopyresized($img, $filter, $filter_obj->x, $filter_obj->y, 0, 0,
			$f_width, $f_height, $filter_obj->width, $filter_obj->height))
			echo 'ECHEC';
		else
			echo 'SUCCESS';
	}

}
