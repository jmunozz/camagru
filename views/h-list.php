<!DOCTYPE html>
		<div class="h-list">
			<div class="h-scroll">
				<img height="100%" alt="" src="assets/img/scroll-left.png"/>
			</div>
			<ul>
<?php
				foreach($imgs as $img) {
					echo '<li><img src="'.$img['path'].'" /></li>';
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

			var selected = null;

			function setSelected(new_selected) {
			if (selected){
				selected.classList.remove("selected");
			}
			selected = new_selected;
			console.log(selected);
			if (selected && !selected.classList.contains("selected"))
				selected.classList.add("selected");
			}

			function addClick() {
			var li = document.querySelectorAll(".h-list ul li");
			console.log(li);
			li.forEach(function(li) {
			li.addEventListener("click", function() {
			setSelected(this);});
			});
			}
			addScroll();
			addClick();
		</script>
