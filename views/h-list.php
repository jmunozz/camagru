<!DOCTYPE html>
		<div class="h-list">
			<div class="h-scroll">
				<img height="100%" alt="" src="assets/img/scroll-left.png"/>
			</div>
			<ul>
<?php
				for($i = 0; $h_list[$i]; $i++) {
					echo '<li><img id="filter_'.$i.'" class="filters" src="'.$h_list[$i]['path'].'" /></li>';
				}
?>
			</ul>
			<div class="h-scroll">
				<img height="100%" alt="" src="assets/img/scroll-right.png"/>
			</div>
		</div>
		<script>
			function addScroll() {

				function addScrollLeft() {
				var scroll = this;
				var stop = setInterval(function() {
				scroll.parentNode.querySelector("ul").scrollLeft -= 10;
				}, 50);

				scroll.addEventListener("mouseout", function() {
				clearInterval(stop)});
				}

				function addScrollRight() {
				var scroll = this;
				var stop = setInterval(function() {
				scroll.parentNode.querySelector("ul").scrollLeft += 10;
				}, 50);

				scroll.addEventListener("mouseout", function() {
				clearInterval(stop)});
				}

				var scroll = document.querySelectorAll(".h-scroll");
				scroll[0].addEventListener("mouseover", addScrollLeft);
				scroll[1].addEventListener("mouseover", addScrollRight);
			}

			addScroll();
		</script>
