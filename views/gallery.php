<div id="gallery_toolbar">
	<label>Pictures Size</label>
	<input id="range" type="range" />
</div>
<div id="comments_sidebar">
	<div class="comments_sidebar_title">Comments <img id="comments_sidebar_title_close" src="<?php echo $this->url_base; ?>/assets/img/cancel.png" /></div>
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
<div id="gallery_pagination">
</div>

<script src=<?php echo '"' . $this->url_base . '/assets/js/ajax.js"' ?>></script>
<script src=<?php echo '"' . $this->url_base . '/assets/js/gallery_resize.js"' ?>></script>
