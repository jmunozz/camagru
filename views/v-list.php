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

			var v_selected = null;

			function setVSelected(new_selected) {
			if (v_selected){
				v_selected.classList.remove("selected");
			}
			v_selected = new_selected;
			console.log(v_selected);
			if (v_selected && !v_selected.classList.contains("selected"))
				v_selected.classList.add("selected");
			}

			function addClick() {
			var li = document.querySelectorAll(".v-list ul li");
			console.log(li);
			li.forEach(function(li) {
			li.addEventListener("click", function() {
			setVSelected(this);});
			});
			}
			addScroll();
			addClick();
		</script>
