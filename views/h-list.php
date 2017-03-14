<!DOCTYPE html>
		<div class="h-list">
			<div class="h-scroll">
				<img height="100%" alt="" src="assets/img/scroll-left.png"/>
			</div>
			<ul>
<?php
				foreach($h_list as $elem) {
					echo '<li><img src="'.$elem['path'].'" /></li>';
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

			var h_selected = null;

			function setHSelected(new_selected) {
			if (h_selected){
				h_selected.classList.remove("selected");
			}
			h_selected = new_selected;
			console.log(h_selected);
			if (h_selected && !h_selected.classList.contains("selected"))
				h_selected.classList.add("selected");
			}

			function addClick() {
			var li = document.querySelectorAll(".h-list ul li");
			console.log(li);
			li.forEach(function(li) {
			li.addEventListener("click", function() {
			setHSelected(this);});
			});
			}
			addScroll();
			addClick();
		</script>
