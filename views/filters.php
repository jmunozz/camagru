<!DOCTYPE html>
<div class="scroll scroll-horizontal scroll-left">
	<img alt="" src="assets/img/scroll-left.png"/>
</div>
<ul>

<?php
for($i = 0; $i < count($filters); $i++) {
	echo '<li>
			<img id="filter_' . $i . '" class="filters" src="' . FILTERS_DIR . $filters[$i]['path'] . '" />
		</li>';
}
?>

</ul>
<div class="scroll scroll-horizontal scroll-right">
	<img alt="" src="assets/img/scroll-right.png"/>
</div>




<script>

	function addScroll() {

		function addScrollLeft() {
			var scroll = this;
			var stop = setInterval(function() {
				scroll.parentNode.querySelector("ul").scrollLeft -= 10;
			}, 50);

			scroll.addEventListener("mouseout", function() {
				clearInterval(stop)
			});
		}

		function addScrollRight() {
			
			var scroll = this;
			var stop = setInterval(function() {
				scroll.parentNode.querySelector("ul").scrollLeft += 10;
			}, 50);

			scroll.addEventListener("mouseout", function() {
				clearInterval(stop)
			});
		}

		var scroll = document.querySelectorAll(".scroll-horizontal");
		scroll[0].addEventListener("mouseover", addScrollLeft);
		scroll[1].addEventListener("mouseover", addScrollRight);
	}

	addScroll();
</script>
