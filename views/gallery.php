<div id="gallery_toolbar">
	<label>Pictures Size</label>
	<input id="range" type="range" />
</div>
<div id="gallery">

<?php
	//print_r($images);
	foreach($gallery as $image) {
?>
		<div id="<?php echo $image['img_id']; ?>" class="picture_card">
			<div class="picture_info">
				<span class="picture_info_date"><?php echo $image['date']; ?></span>
				<span class="picture_info_author"><? echo $image['user']; ?></span>
			</div>
			<div class="picture_info picture_info_title">
				<span><?php echo $image['titre']; ?></span>
			</div>
			<img src="<?php echo $this->url_base.'/'.$image['path'] ?>"/>
			<div class="picture_social">
				<div>
					<span class="nb"><?php echo $image['nb_comment']; ?></span>
					<img  class ="comment" src="<?php echo $this->url_base; ?>/assets/img/comment.png" />
				</div>
				<div>
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
