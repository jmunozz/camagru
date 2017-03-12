<div class="container j-center">
	<label>Taille des images</label>
	<input id="range" type="range" />
</div>
<div class="page container gallery">

<?php
	//print_r($images);
	foreach($images as $image) {
?>
		<div id="<?php echo $image['img_id']; ?>" class="container column img">
			<div class="opac">
			</div>
			<div class="container img_infos">
				<span><small><?php echo $image['date']; ?></small></span>
				<span><?php echo $image['titre']; ?></span>
				<span><small><? echo $image['user']; ?></small></span>
			</div>
			<img class="photo" src="<?php echo $this->url_base; ?>/assets/img/camera.png"/>
			<div class="container icon">
				<div class="container">
					<span class="nb"><?php echo $image['nb_comment']; ?><span>
					<img src="<?php echo $this->url_base; ?>/assets/img/comment.png" />
				</div>
				<div class="container">
					<span class="nb"><?php echo $image['nb_like']; ?></span>
					<img class="like" src="<?php echo $this->url_base; ?>/assets/img/red_like.png" />
				</div>
			</div>
		</div>
<?php }
?>
<script>

	var url_base = document.URL.split('/')[3];

	function includeJs(file) {
		script = document.createElement("script");
		script.setAttribute('src', file);
		script.setAttribute('type', 'text/javascript');
		document.body.appendChild(script);
	}

	includeJs('assets/js/ajax.js');
	includeJs('assets/js/gallery_resize.js');


</script>
