<div class="container page">
	<div class="main container column">
		<div class="container">
			<div id="camera">
				<img src="assets/img/camera.png" />
			</div>
			<div id="camera-menu" class="container column j-center">
				<img id="activate_cam" src="assets/img/play.png" />
				<img id="take-picture" src="assets/img/camera.png" />
				<label for="file">
					<img id="activate_file" src="assets/img/upload.png" />
				</label>
				<input type="file" id="file">
			</div>
		</div>
		<div class="container column">
<?php
include ('views/h-list.php');
?>
		</div>
	</div>
	<div class="side container">
<?php
include ('views/v-list.php');
?>
		</div>
	</div>
</div>
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

	function Video() {
		var camera = document.getElementById("camera");
		var play_button = document.getElementById("activate_cam");
		var file_button = document.getElementById("activate_file");
		var pic_button = document.getElementById('take-picture');
		var video;
		var canvas;
		var preview;

		function setVideo() {
			var video = document.createElement("video");
			var preview = document.createElement("img");
			var canvas = document.createElement("canvas");

			video.setAttribute("id", "video");
			preview.setAttribute("id", "preview");
			canvas.setAttribute("id", "canvas");
			camera.append(video);
			camera.append(preview);
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
		
		function takepicture() {
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
			var data = canvas.toDataURL('image/png');
			preview.setAttribute('src', data);
			console.log('event');
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
			console.log("remettre image");
			removeVideo();
			addImage();
		});

		play_button.addEventListener("click", function() {
			removeImage();
			addVideo();
		});
	}

previewImage();
Video();

</script>
