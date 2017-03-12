function Resize_control() {

	var range = document.getElementById('range');
	var images = document.querySelectorAll('.img');
	if (images)
		var orig_size = images[0].clientWidth;

	var resize = function() {
		console.log(range.value);
		var i = -1;
		while (++i < images.length) {
				var size = orig_size * (2 * range.value / 100) + "px";
				images[i].style.width = size;
		}
	};
	
	range.addEventListener("mousedown", function() {
	    range.addEventListener("mousemove", resize);
		range.addEventListener("mouseup", function() {
		  range.removeEventListener("mousemove", resize);
		  });
		console.log(range.value);
	});
}

function likeSetup() {
	sendRequest("XML", setRequest('POST', 'gallery/get_user_likes', 'get_user_likes=ok'),
	function(response) {
		var imgs = response.getElementsByTagName('like')[0].children;
		for (var i = 0; i < imgs.length; i++) {
			var elem = document.getElementById(imgs[i].id).children[3].children[1].children[1];
			elem.src = 'assets/img/yellow_like.png';
		}
	});
}

function like() {
	var likes = document.querySelectorAll('.like');
	for (var i = 0; i < likes.length; i++) {
	likes[i].addEventListener("click", function() {
			var like = this;
			sendRequest("text", setRequest('POST', 'gallery/like',
			'id_image=' + this.parentNode.parentNode.parentNode.id),
			function(response) {
				if (response == 'add') {
					like.parentNode.children[0].innerHTML =
					parseInt(like.parentNode.children[0].innerHTML) + 1;
					like.src =  'assets/img/yellow_like.png';
				}
				else if (response == 'delete') {
					like.parentNode.children[0].innerHTML =
					parseInt(like.parentNode.children[0].innerHTML) - 1;
					like.src = 'assets/img/red_like.png';
				}
			});
		});
	}
}

Resize_control();
likeSetup();
like();
