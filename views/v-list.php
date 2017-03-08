<!DOCTYPE html>
		<div class="v-list">
			<div class="scroll">
				<img height="100%" alt="" src="assets/img/scroll-top.png"/>
			</div>
			<ul>
<?php
				foreach($imgs as $img) {
					echo '<li><img src="'.$img['path'].'" /></li>';
				}
?>
			</ul>
			<div class="scroll">
				<img height="100%" alt="" src="assets/img/scroll-bottom.png"/>
			</div>
		</div>
		<script>
			function addScroll() {

				function addScrollTop() {
				var scroll = this;
				var stop = setInterval(function() {
				scroll.parentNode.querySelector("ul").scrollTop -= 10;
				}, 50);

				scroll.addEventListener("mouseout", function() {
				clearInterval(stop)});
				}

				function addScrollBottom() {
				var scroll = this;
				var stop = setInterval(function() {
				scroll.parentNode.querySelector("ul").scrollTop += 10;
				}, 50);

				scroll.addEventListener("mouseout", function() {
				clearInterval(stop)});
				}

				var scroll = document.querySelectorAll(".scroll");
				scroll[0].addEventListener("mouseover", addScrollTop);
				scroll[1].addEventListener("mouseover", addScrollBottom);
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
			var li = document.querySelectorAll(".v-list ul li");
			console.log(li);
			li.forEach(function(li) {
			li.addEventListener("click", function() {
			setSelected(this);});
			});
			}
			addScroll();
			addClick();
		</script>
