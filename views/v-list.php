<!DOCTYPE html>
		<div id='v-list'  class="v-list">
			<div class="v-scroll">
				<img  alt="" src="assets/img/scroll-top.png"/>
			</div>
			<ul>
<?php
				foreach($v_list as $img) {
					echo '<li><img data-id="'.$img['id'].'" src="'
					.$img['path'].'" /></li>';
				}
?>
			</ul>
			<div class="v-scroll">
				<img alt="" src="assets/img/scroll-bottom.png"/>
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

				var scroll = document.querySelectorAll(".v-scroll");
				scroll[0].addEventListener("mouseover", addScrollTop);
				scroll[1].addEventListener("mouseover", addScrollBottom);
			}

			var v_selected = null;

			function del(e) {
				e.prevenDefault;
				e.stopPropagation;
				if (e.keyCode == 8) {
					var id = v_selected.children[0].getAttribute('data-id');
					sendRequest('text', setRequest('POST', 'home/deleteImage',
					{'id' : id}), function(response) {
						if (response == 'TRUE') {
							v_selected.parentNode.removeChild(v_selected);
							v_selected = null;
							window.removeEventListener('keydown', del);
						}
					});
				}
			}

			function setVSelected(new_selected) {
			if (new_selected.classList.contains('selected')) {
				v_selected.classList.remove("selected");
				v_selected = null;
				window.removeEventListener('keydown', del);
				return;
			}
			else if (v_selected) {
				v_selected.classList.remove("selected");
				window.removeEventListener('keydown', del);
			}
			v_selected = new_selected;
			v_selected.classList.add("selected");
			window.addEventListener('keydown', del);
			}

			function addClick() {
			var li = document.querySelectorAll(".v-list ul li");
			li.forEach(function(li) {
			li.addEventListener("click", function() {
			setVSelected(this);});
			});
			}

			addScroll();
			addClick();
		</script>
