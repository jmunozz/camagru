<div class="container page">
	<div class="main container column">
		<div class="container b2">
			<div id="camera">
				<img src="assets/img/camera.png" />
			</div>
			<div id="camera-menu" class="container column j-center">
				<img id="activate_cam" src="assets/img/play.png" />
				<img id="take-picture" src="assets/img/camera.png" />
				<label for="file">
					<img id="activate_file" src="assets/img/dots.png" />
				</label>
				<input type="file" id="file">
				<img id="sizeUp" src="assets/img/plus.png" />
				<img id="sizeDown" src="assets/img/minus.png" />
			</div>
		</div>
		<div class="container column b2">
<?php
include ('views/h-list.php');
?>
		</div>
	</div>
	<div class="side j-center container b2">
<?php
include ('views/v-list.php');
?>
		</div>
	</div>
</div>
<script src="assets/js/setFilters.js"></script>
<script>

var url_base = document.URL.split('/')[3];
console.log(url_base);



function includeJs(file) {
	script = document.createElement("script");
	script.setAttribute('src', file);
	script.setAttribute('type', 'text/javascript');
	document.body.appendChild(script);
}

includeJs('assets/js/ajax.js');


</script>
<script>

var ratio = null;
var is_stream = false;

function intoArray(something) {
	var array = [];
	for(var i = 0; i < something.length; i++) {
		array.push(something[i]);
	}
	return array;
}

function previewImage() {

	var camera = document.getElementById("camera");
	var file_contain = document.getElementById("file");
	var reader = new FileReader();

	function displayStandard() {
		camera.children[0].src = 'assets/img/camera.png';
		camera.children[0].id = '';
	}

	function readFile() {
		var file = file_contain.files[0];
		if (file) {
			reader.addEventListener("load", displayImage);
			reader.readAsDataURL(file);
		}
		else 
			displayStandard();
	}

	function displayImage(){
		console.log(camera.children[0]);
		camera.children[0].src = reader.result;
		camera.children[0].id = 'file_preview';
	}

	file_contain.addEventListener("change", function() {
		readFile();
	});
}

function getFilterTab() {
	var filters = [];
	var children = document.getElementById('camera').children;
	for (var i = 0; i < children.length; i++) {
		if (children[i].classList.contains('cloned'))
			filters.push(children[i]);
	}
	return (filters);
}

function convertDivtoImg(filterTab)
{
	var filterTabImg = [];

	var img;
	filterTab.forEach(function(elem){
		img = {};
		img.height = elem.style.height.slice(0, -2) * ratio;
		img.width = elem.style.width.slice(0, -2) * ratio;
		img.src = elem.style.getPropertyValue('background-image').slice(5).slice(0, -2);
		img.src = img.src.slice(img.src.lastIndexOf('/') + 1);
		img.y = elem.style.getPropertyValue('top').slice(0, -2) * ratio;
		img.x = elem.style.getPropertyValue('left').slice(0, -2) * ratio;
		filterTabImg.push(img);
		console.log(img);
	});
	return filterTabImg;
}

// take 

function buildObjectSent(img, filters, name) {
	var Object = {};
	var i = 0;
	Object.img = img.src.slice(img.src.indexOf(',') + 1);
	Object.name = name;
	filters.forEach(function(elem) {
		Object['filter_' + i++] = JSON.stringify(elem);
	});
	console.log(Object);
	return Object;
}

function AddPictureGallery(response) {
	var src = response.getElementsByTagName('src')[0];
	var id  = response.getElementsByTagName('id')[0];
	console.log(id, src);
	var li  = document.createElement('li');
	var img  = document.createElement('img');
	var v_list = document.getElementById('v-list');
	img.setAttribute('src', src.innerHTML);
	img.setAttribute('data-id', id.innerHTML);
	li.appendChild(img);
	v_list.children[1].prepend(li);
	li.addEventListener("click", function() {
	setVSelected(this);})

}

function addDefaultImgFile() {
	img = document.createElement('img');
	img.src = 'assets/img/camera.png';
	document.getElementById('camera').append(img);
}

function sendPicture() {

	var img = is_stream ? document.getElementById('preview') :
	document.getElementById('file_preview');
	var imgFilters = convertDivtoImg(getFilterTab());
	var name = document.getElementById('name_bar').children[0].value;
	var files = buildObjectSent(img, imgFilters, name);
	sendRequest('XML', setRequest('POST', 'home/sendPicture', files),
	AddPictureGallery);
	resetImage();
}

function resetImage() {

	var c_child = intoArray(document.getElementById('camera').children);
	c_child.forEach(function(elem) {
		if (elem.tagName == 'DIV' || elem.tagName == 'IMG') {
			elem.parentNode.removeChild(elem);
		}
	});
	if (!is_stream)
		addDefaultImgFile();
}


function Video() {
	var camera = document.getElementById("camera");
	var play_button = document.getElementById("activate_cam");
	var file_button = document.getElementById("activate_file");
	var pic_button = document.getElementById('take-picture');
	var video;
	var canvas;

	function clearCanvas() {
		canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
	}

	function setVideo() {
		var video = document.createElement("video");
		var canvas = document.createElement("canvas");
		var test = document.createElement('div');
		test.setAttribute('class', 'test');

		video.setAttribute("id", "video");
		canvas.setAttribute("id", "canvas");
		camera.prepend(video);
		camera.append(canvas);
	}

	function activateCamera() {

		navigator.getMedia = (navigator.getUserMedia || 
			navigator.webkitGetUserMedia || navigator.mozGetUserMedia ||
			navigator.msGetUserMedia);

		navigator.getMedia({video: true, audio: false}, function(stream) {
			if (navigator.mozGetUserMedia) {
				video.mozSrcObject = stream;
			} 
			else {
				var vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL.createObjectURL(stream);
			}
			video.addEventListener('play', function() {
				console.log('width', video.videoHeight, video.clientWidth);
			});
			video.play();
		}, function(err) {
			console.log("An error occured! " + err);
		});
	}

	function getValidate(bar) {
		var validate = document.createElement('img');
		validate.src = 'assets/img/validate.png';
		validate.addEventListener('click', sendPicture);
		bar.appendChild(validate);

	}

	function getCancel(bar) {
		var cancel = document.createElement('img');
		cancel.src = 'assets/img/cancel.png';
		cancel.addEventListener('click', resetImage);
		bar.appendChild(cancel);
		
	}

	function getValidateBar() {
		var bar = document.createElement('div');
		bar.setAttribute('class', 'container j-center');
		bar.setAttribute('id', 'validate_bar');
		getValidate(bar);
		getCancel(bar);
		camera.appendChild(bar);
	}

	function getNameBar() {
		var bar = document.createElement('div');
		var input = document.createElement('input');
		bar.setAttribute('id', 'name_bar');
		bar.setAttribute('class', 'container j-center');
		input.setAttribute('type', 'text');
		input.setAttribute('placeholder', 'Name');
		bar.appendChild(input);
		camera.appendChild(bar);
	}

	function takepicture() {
		var img_file;
		if (is_stream) {
			ratio = video.videoWidth / video.clientWidth;
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
			var data = canvas.toDataURL('image/png');
			var preview = document.createElement("img");
			preview.setAttribute('src', data);
			preview.setAttribute("id", "preview");
			var f = camera.children[1];
			if (f)
				camera.insertBefore(preview, f);
			else
				camera.append(preview);
		}
		else if ((img_file = document.getElementById('file_preview'))) 
			ratio = img_file.naturalHeight / img_file.clientHeight;
		else 
			return;
		getValidateBar();
		getNameBar();
	}

	function addVideo() {
		setVideo();
		video = document.querySelector('#video');
		canvas = document.querySelector('#canvas');
		activateCamera();
	}

	function removeVideo() {
		var children = intoArray(camera.children);
		children.forEach(function(elem) {
			camera.removeChild(elem);
			});
	}

	function removeImage() {
		var children = intoArray(camera.children);
		children.forEach(function(elem) {
			camera.removeChild(elem);
			});
	}

	function addImage() {
		var image = document.createElement("img");
		image.setAttribute("src", "assets/img/camera.png");
		camera.append(image);
	}


	file_button.addEventListener("click", function() {
		if (is_stream) {
			removeVideo();
			addImage();
			is_stream = false;
		}
	});

	play_button.addEventListener("click", function() {
		if (!is_stream) {
			removeImage();
			addVideo();
			is_stream = true;
		}
	});


	pic_button.addEventListener('click', takepicture);
	
}

previewImage();
Video();

</script>
