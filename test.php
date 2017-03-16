<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="/test.css" />
	</head>
	<body>
		<div class="test">
			<div id='1' class="subdiv" draggable="true">
			</div>
		</div>
		<div class='menu'>
			<div id='2' class='elem' draggable="true"></div>
			<div id='3' class='elem' draggable="true"></div>
			<div id='4' class='elem' draggable="true"></div>
		</div>
	</body>
	<script>
	var div  = document.body.children[0];
	var sub  = document.body.children[0].children[0];
	var elems = document.getElementsByClassName('elem')
	
	for (var i = 0; i < elems.length; i++) {
		elems[i].addEventListener('dragstart', function(a) {
		console.log('popo', a.target.id);
		a.dataTransfer.setData('text', a.target.id);
		});
	}
	
	
	div.addEventListener('dragover', function(a) { // a = evenement, this=div
		a.preventDefault();
	});
	
	
	div.addEventListener('drop', function(a) {
		a.preventDefault();
		var id = a.dataTransfer.getData('text')
		console.log(id);
		var elem = document.getElementById(id);
		console.log(elem);
		this.appendChild(elem);
		elem.style.position = 'absolute';
		elem.style.left = a.offsetX + 'px';
		elem.style.top = a.offsetY + 'px';
	});
	</script>
</html>
