/*
** Transform an iterator into an array.
*/
function intoArray(something) {
	var array = [];
	for(var i = 0; i < something.length; i++) {
		array.push(something[i]);
	}
	return array;
}




function Resize_control(range, images) {

	if (images)
		var orig_size = images[0].clientWidth;

	var resize = function() {
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
	});
}


/*
** Get pictures liked for a user.
*/
function likeSetup() {


	const callback = (response) => {
		var imgs = response.getElementsByTagName('like')[0].children;
		// All liked pictures heart are display in yellow.
		for (var i = 0; i < imgs.length; i++) {
			var elem = document.getElementById(imgs[i].id).children[3].children[1].children[1];
			elem.src = 'assets/img/yellow_like.png';
		}
	};

	sendRequest("XML", setRequest('POST', 'gallery/get_user_likes', {'get_user_likes':'ok'}), callback);
}




function like() {
	var likes = document.querySelectorAll('.like');
	for (var i = 0; i < likes.length; i++) {
	likes[i].addEventListener("click", function() {
			var like = this;
			sendRequest("text", setRequest('POST', 'gallery/like',
			{'id_image': this.parentNode.parentNode.parentNode.id}),
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


	let current_picture_comment_icon = null;
	let current_picture = null;

	function add_comment(ev, image, input, div_comment) {

		if (ev.charCode == 13) {
			sendRequest('XML', setRequest('POST', 'gallery/add_comment',
			{'id_image':image.id, 'text':input.value}), function (response) {
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
					alert('Please login to write comment');
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
		p.innerHTML = 
		'<span class="comments_content_date">' + obj.date + '</span>' + 
		'<br />' + '<span class="comments_content_author">' + obj.login + 
		':  ' + '</span><span class="comments_content">' + obj.text + '</span>';
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

	function close_comments_sidebar(e) {

		e.preventDefault;

		const comments_sidebar = document.getElementById("comments_sidebar");
		const comments_sidebar_children = intoArray(comments_sidebar.children);

		comments_sidebar.style.right = "-500px";
		setTimeout(function() {
			comments_sidebar.style['transition-duration'] = '0s';
			// We empty Comments Sidebar from comments
			comments_sidebar_children.forEach(function(elem) {
				if (!elem.classList.contains('comments_sidebar_title') ) {
					elem.parentNode.removeChild(elem);
				}
			});
		}, 1000);
		// Replace event by an Openning Event.
		current_picture_comment_icon.removeEventListener('click', close_comments_sidebar);
		current_picture_comment_icon.addEventListener('click', open_comments_sidebar);
		current_picture_comment_icon = null;
		current_picture = null;
	}

	function open_comments_sidebar(e) {


		const open = () => {

			current_picture_comment_icon = this;
			current_picture = this.parentNode.parentNode.parentNode;

			// Backend Call to retreive comments
			sendRequest('XML', setRequest('POST', 'gallery/get_comments',
			{'id_image':current_picture.id}), function (response) {

				// Open Comments SideBar
				const comments_sidebar = document.getElementById("comments_sidebar");
				const body = document.body;
				const top = (window.pageYOffset || body.scrollTop)  - (body.clientTop || 0);

				comments_sidebar.style.top = top + "px";
				comments_sidebar.style['transition-duration'] = '1s';
				comments_sidebar.style.right = "0";
				comments_sidebar.style.width = "25%";


				// Append every comment in response to the Comments SideBar
				const tab = response.getElementsByTagName('comment');
				for (var i = 0; i < tab.length; i++) {
					const com = xml_to_com_obj(tab[i]);
					const com_dom = com_obj_to_dom(com);
					comments_sidebar.appendChild(com_dom);
				}

				// Append comment input to the Comments SideBar
				comments_sidebar.appendChild(input_comment(current_picture, comments_sidebar));

				// Replace this event by a Closing event.
				current_picture_comment_icon.removeEventListener('click', open_comments_sidebar);
				current_picture_comment_icon.addEventListener('click', close_comments_sidebar);		
			});
		};

		e.preventDefault();
		if(current_picture || current_picture_comment_icon) {
			close_comments_sidebar(e);
			setTimeout(open, 1000);
		}
		else 
			open();
		


		


	}

	function add_event() {

		// Add Closing Event to Closing Icon.
		const close_icon = document.getElementById('comments_sidebar_title_close');
		close_icon.addEventListener('click', close_comments_sidebar);	

		// Add Openning Event to all comments Icons.	
		var comment_all = document.querySelectorAll('.comment');
		for (var i = 0; i < comment_all.length; i++) {
			comment_all[i].addEventListener("click", open_comments_sidebar);
		}
	}

	add_event();
}


function Pagination() {


	function create_pagination (total_pages) {

		const URL = document.URL;
		const gallery_pagination = document.getElementById('gallery_pagination');

		for (var i = 0; i < total_pages; i++) {
			const page_number = document.createElement('a');
			page_number.href = URL.split('?')[0] + '?page=' + i;
			page_number.innerHTML = i; 
			gallery_pagination.appendChild(page_number);
		}
	}

	function get_total_pages() {

		const callback = (response)  => {
			const total_pages = response.getElementsByTagName('total_pages')[0].innerHTML;
			create_pagination(total_pages);
		};

		// Ajax Call to Backend
		sendRequest("XML", setRequest('POST', 'gallery/get_total_pages', {'total_pages':'ok'}), callback);

	}

	get_total_pages();
}


Resize_control(document.getElementById('range'), document.querySelectorAll('.picture_card'));
Pagination();
likeSetup();
like();
comment();
