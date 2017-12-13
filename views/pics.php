<!DOCTYPE html>


<div class="scroll scroll-vertical scroll-top">
	<img alt="" src="assets/img/scroll-top.png"/>
</div>
<ul>
<?php
foreach($v_list as $img) {
	echo '<li>
		<img data-id="'. $img['id'] . '" src="' . $img['path'] . '" />
		</li>';
}
?>
</ul>
<div class="scroll scroll-vertical scroll-bottom">
	<img alt="" src="assets/img/scroll-bottom.png"/>
</div>








<script>

	/*
	** Scroll top/bottom when mouse hovers scroll buttons.
	*/
	function addScroll() {


		const scroll_buttons = document.querySelectorAll(".scroll-vertical");
		const scroll_top_button = scroll_buttons[0];
		const scroll_bottom_button = scroll_buttons[1];

		function addScrollTop(event) {

			const stop = setInterval(function() {
				scroll_top_button.parentNode.querySelector("ul").scrollTop -= 10;
			}, 50);

			scroll_top_button.addEventListener("mouseout", function() {
				clearInterval(stop);
			});
		}

		function addScrollBottom(event) {

			const stop = setInterval(function() {
				scroll_bottom_button.parentNode.querySelector("ul").scrollTop += 10;
			}, 50);

			scroll_bottom_button.addEventListener("mouseout", function() {
				clearInterval(stop);
			});
		}

 		scroll_top_button.addEventListener("mouseover", addScrollTop);
		scroll_bottom_button.addEventListener("mouseover", addScrollBottom);
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

	/*
	** Switch class and event to newly selected pic.
	*/
	function setVSelected(new_selected) {

		const is_different = !new_selected.classList.contains("selected");

		// Remove class and event linked to old selected pic.
		if (v_selected) {
			v_selected.classList.remove("selected");
			window.removeEventListener('keydown', del);
			v_selected = null;
		}

		// Link class and event to new selected pic if different from last.
		if (is_different) {
			v_selected = new_selected;
			v_selected.classList.add("selected");
			window.addEventListener('keydown', del);
		}


	}

	// Will trigger setVSelected when clicked.
	function addClick() {
		var li = document.querySelectorAll("#pics ul li");
		li.forEach(function(li) {
			li.addEventListener("click", function() {
				setVSelected(li);
			});
		});
	}

	addScroll();
	addClick();
</script>
