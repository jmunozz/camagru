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


function comment() {

	function add_comment(ev, image, input, div_comment) {
		console.log(ev.charCode);
		if (ev.charCode == 13) {
			sendRequest('XML', setRequest('POST', 'gallery/add_comment',
			'id_image=' + image.id + '&text=' + input.value), function (response) {
				if (response) {
					var com = com_obj_to_dom(xml_to_com_obj(
					response.getElementsByTagName('comment')[0]));
					div_comment.insertBefore(com, div_comment.lastChild);
					image.querySelector('.comment').parentNode.
					children[0].innerHTML =
					parseInt(image.querySelector('.comment').parentNode.
					children[0].innerHTML) + 1;
					input.value = '';
					div_comment.scrollTop = div_comment.scrollHeight;
					
				}
				else {
					alert(response);
					console.log(image.id);
					console.log(input);
				}
			});
		}
	}

	function add_div_comment(image) {
		var div_comment = document.createElement('div');
		div_comment.setAttribute('class', 'div_comment');
		image.insertBefore(div_comment, image.children[3]);
		return (div_comment);
	}

	function xml_to_com_obj(xml_line) {
		var com = {login: '', date: '', text: ''};
		com.login = xml_line.getElementsByTagName('user_login')[0].innerHTML;
		com.date = xml_line.getElementsByTagName('date')[0].innerHTML;
		com.text = xml_line.getElementsByTagName('text')[0].innerHTML;
		return (com);
	}

	function com_obj_to_dom(obj) {
		p = document.createElement('p');
		p.innerHTML = '<bold>' + obj.date + '</bold>' + 
		'<br />' + obj.login + ':  ' + obj.text;
		return (p);
	}

	function input_comment(image, div_comment) {
		input  = document.createElement('input');
		input.setAttribute("type", "text");
		input.setAttribute("placeholder", "Add a comment");
		input.addEventListener('keypress', function(ev) {
			input = this;
			add_comment(ev, image, input, div_comment);
		});
		return(input);
	}

	function remove_div_event(e, image, target) {
		e.preventDefault;
		if (image.querySelector('.div_comment'))
			image.removeChild(image.querySelector('.div_comment'));
		target.removeEventListener('click', remove_div_event);
		target.addEventListener('click', com_event);
	}

	function com_event(e) {
		var target = this;
		var image = this.parentNode.parentNode.parentNode;

		e.preventDefault();
		sendRequest('XML', setRequest('POST', 'gallery/get_comments',
		'id_image=' + image.id), function (response) {
			var div_comment = add_div_comment(image);
			var tab = response.getElementsByTagName('comment');
			for (var i = 0; i < tab.length; i++) {
				var com = xml_to_com_obj(tab[i]);
				var com_dom = com_obj_to_dom(com);
				div_comment.appendChild(com_dom);
			}
			div_comment.appendChild(input_comment(image, div_comment));
			target.removeEventListener('click', com_event);
			target.addEventListener('click', function(e) {
				target = this;
				remove_div_event(e, image, target)
			});
		});
	}

	function add_event() {
		var comment_all = document.querySelectorAll('.comment');
		for (var i = 0; i < comment_all.length; i++) {
			comment_all[i].addEventListener("click", com_event);
		}
	}

	add_event();
}

Resize_control();
likeSetup();
like();
comment();
