<div class="container page">
	<div class="main container column">
		<div class="container b2">
			<div id="camera">
				<img src="assets/img/camera.png" />
			</div>
			<div id="camera-menu" class="container column j-center">
				<img id="activate_cam" src="assets/img/play.png" />
				<img id="take-picture" src="assets/img/camera.png" />
				<img id="send" src="assets/img/upload.png" />
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

function includeJs(file) {
	script = document.createElement("script");
	script.setAttribute('src', file);
	script.setAttribute('type', 'text/javascript');
	document.body.appendChild(script);
}

includeJs('assets/js/ajax.js');


</script>
<script>

function previewImage() {

	var camera = document.getElementById("camera");
	var file_contain = document.getElementById("file");
	var reader = new FileReader();

	function displayStandard() {
		camera.children[0].src = 'assets/img/camera.png';
	}

	function displayBuffering() {
		camera.children[0].src = 'assets/img/loading.gif';
	}

	function readFile() {
		var file = file_contain.files[0];
		if (file) {
			reader.addEventListener("load", displayImage);
			reader.readAsDataURL(file);
		}
		displayStandard();
	}

	function displayImage(){
		camera.children[0].src = reader.result;
	}

	file_contain.addEventListener("change", function() {
		displayBuffering();
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
		img.height = elem.style.height.slice(0, -2);
		img.width = elem.style.width.slice(0, -2);
		img.src = elem.style.getPropertyValue('background-image').slice(5).slice(0, -2);
		img.y = elem.style.getPropertyValue('top').slice(0, -2);
		img.x = elem.style.getPropertyValue('left').slice(0, -2);
		filterTabImg.push(img);
		console.log(img);
	});
	return filterTabImg;
}

// take 

function buildObjectSent(img, filters) {
	var Object = {};
	var i = 0;
	Object.img = img.src;
	filters.forEach(function(elem) {
		Object['filter_' + i++] = JSON.stringify(elem);
	});
	console.log(Object);
	return Object;
}

function AddPictureGallery() {
}

function sendPicture() {

	var img = document.getElementById('preview');
	var imgFilters = convertDivtoImg(getFilterTab());
	var files = buildObjectSent(img, imgFilters);
	sendRequest('text', setRequest('POST', 'home/sendPicture', files),
	AddPictureGallery);
	console.log(img);
	console.log(imgFilters);
}


function Video() {
	var camera = document.getElementById("camera");
	var play_button = document.getElementById("activate_cam");
	var file_button = document.getElementById("activate_file");
	var pic_button = document.getElementById('take-picture');
	var video;
	var canvas;
	var preview;
	var is_stream = false;

	function clearCanvas() {
		canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
	}

	function setVideo() {
		var video = document.createElement("video");
		var preview = document.createElement("img");
		var canvas = document.createElement("canvas");
		var test = document.createElement('div');
		test.setAttribute('class', 'test');

		video.setAttribute("id", "video");
		preview.setAttribute("id", "preview");
		canvas.setAttribute("id", "canvas");
		camera.prepend(preview);
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

// Draw each filters on the canvas. (Not used).

	function  drawFilters() {
		var filters = convertDivtoImg(getFilterTab());
		console.log(filters);
		filters.forEach(function(elem) {
			canvas.getContext('2d').drawImage(elem, 0, 0, elem.width, elem.height);
		});
	}

	function takepicture() {
		canvas.width = video.videoWidth;
		canvas.height = video.videoHeight;
		canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
		var data = canvas.toDataURL('image/png');
		preview.setAttribute('src', data);
		getValidateBar();
	}

	function addVideo() {
		setVideo();
		video = document.querySelector('#video');
		canvas = document.querySelector('#canvas');
		preview = document.querySelector('#preview');
		activateCamera();
		pic_button.addEventListener('click', takepicture);
	}

	function removeVideo() {
		camera.removeChild(camera.children[2]);
		camera.removeChild(camera.children[1]);
		camera.removeChild(camera.children[0]);
		pic_button.removeEventListener('click', takepicture);
	}

	function removeImage() {
		camera.removeChild(camera.children[0]);
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
}

previewImage();
Video();

</script>
