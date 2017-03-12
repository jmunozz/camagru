<!doctype html>
<html>
	<head>
	</head>
	<body>
		<span>pluay.png</span>
		<img src="assets/img/camera.png" />
	</body>
	<script>
var img = document.querySelector("img");
img.addEventListener("click", function() {
	var target = document.querySelector("span").innerHTML;
	img.src = "assets/img/"+target;
});
	</script>
</html>
