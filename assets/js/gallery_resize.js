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

function add_comment(ev, id_image) {
	if (ev.charCode == 13) {
		sendRequest('text', setRequest('POST', 'gallery/get_comments',
		'id_image=' + id_image),
			function (response) {
				display_comment(id_image, response);
			});*/
		console.log(id_image);
	}
		
}


function comment() {

	function display_comment(id_image, response) {
		var com = {login: '', date: '', text: ''};
		var tab = response.getElementsByTagName('comment');
		var img = document.getElementById(id_image);
		console.log(tab);
		console.log(img);
		var div_comment = document.createElement('div');
		div_comment.setAttribute('class', 'div_comment');
		img.insertBefore(div_comment, img.children[3]);
		for (var i = 0; i < tab.length; i++) {
			console.log(tab[i].getElementsByTagName('user_login')[0].innerHTML);
			com.login = tab[i].getElementsByTagName('user_login')[0].innerHTML;
			com.date = tab[i].getElementsByTagName('date')[0].innerHTML;
			com.text = tab[i].getElementsByTagName('text')[0].innerHTML;
			console.log(com);
			p = document.createElement('p');
			p.innerHTML = '<bold>'+com.date+'</bold>'+'<br />'+com.login+
			':  '+com.text;
			div_comment.appendChild(p);
		}
			var ev;
			p = document.createElement('input');
			p.setAttribute("type", "text");
			p.setAttribute("placeholder", "Add a comment");
			p.onkeypress = function(ev) {
				add_comment(ev, id_image);
			};
			div_comment.appendChild(p);
	}
		
	var comment_all = document.querySelectorAll('.comment');
	for (var i = 0; i < comment_all.length; i++) {
		comment_all[i].addEventListener("click", function() {
			var comment = this;
			var id_image = this.parentNode.parentNode.parentNode.id;
			sendRequest('XML', setRequest('POST', 'gallery/get_comments',
			'id_image=' + id_image),
			function (response) {
				display_comment(id_image, response);
			});
		});
	}
}

Resize_control();
likeSetup();
like();
comment();
