<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="test.css" />
	</head>
	<body>
		<div>
			<input id="plus" type='button' value="plus"/>
			<input id="moins" type='button' value="moins"/>
	</div>
		<div id='tab' class="test">
		</div>
		<div class='menu'>
			<div id='_2' class='elem' draggable="true"></div>
			<div id='_3' class='elem' draggable="true"></div>
			<div id='_4' class='elem' draggable="true"></div>
		</div>
	</body>

<script>



function SetFilters(container, elems) {

	var ElemOnCount = 0;
	var ElemOnDrag = null;
	var SelectElem = {};

	function onSelectedElems(callback) {
		console.log(SelectElem);
		var keys = Object.keys(SelectElem);
		var elem;
		for (var i = 0; i < keys.length; i++) {
			elem = SelectElem[keys[i]];
			callback(elem);
		}
	}

	function sizeUpSelectElem(e) {
		onSelectedElems(function(elem) {
			elem.style.width = elem.clientWidth + 10 + 'px';
			elem.style.height = elem.clientHeight + 10 + 'px';
		});
	}
	
	function sizeDownSelectElem(e) {
		onSelectedElems(function(elem) {
			elem.style.width = elem.clientWidth - 10 + 'px';
			elem.style.height = elem.clientHeight - 10 + 'px';
		});
	}

	function setResize(plus, moins) {
		plus.addEventListener('click', sizeUpSelectElem);
		moins.addEventListener('click', sizeDownSelectElem);
	}

	function deleteElem(e) {
		e.preventDefault;
		if (e.keyCode == 8) {
			onSelectedElems(function(elem) {
				console.log(elem);
				delete SelectElem[elem.id];
				elem.parentNode.removeChild(elem);
			});
		}
	}

	function selectElem(elem) {

		function removeBorder(e) {
			e.preventDefault;
			elem.style.border = '';
			delete SelectElem[this.id];
			this.removeEventListener('click', removeBorder);
			this.removeEventListener('click', deleteElem);
			this.addEventListener('click', addBorder);
		}

		function addBorder(e) {
			var elem = this;
			e.preventDefault;
			elem.style.border = 'solid yellow 2px';
			SelectElem[this.id] = this;
			this.removeEventListener('click', addBorder);
			this.addEventListener('click', removeBorder);
		}

		elem.addEventListener('click', addBorder);

	}

	function createElement(type, style) {
		var newElement = document.createElement(type);
		keys = Object.keys(style);
		for (var i = 0; i < keys.length ; i++) {
			newElement.style[keys[i]] = style[keys[i]];
		}
		return newElement;
	}

	function cloneElement(elem) {
		var style = window.getComputedStyle(elem);
		var newElem = createElement('div',
			{
			'height':style.getPropertyValue('height'),
			'width':style.getPropertyValue('width'),
			'background-color':style.getPropertyValue('background-color'),
			'position':'absolute'
			}
		);
		newElem.draggable = true;
		newElem.id = 'on_' + ElemOnCount++;
		dragElem(newElem);
		selectElem(newElem);
		return (newElem);
	}

	function dragElem(elem) {
		elem.addEventListener('dragstart', function(e) {
		console.log(e);
		ElemOnDrag =
		{
			'id':e.target.id,
			'offsetX':e.offsetX,
			'offsetY':e.offsetY
		};
		e.dataTransfer.setData('text', e.target.id);
		});
		elem.addEventListener('drop', function(e) {
			e.preventDefault;
		});
	}

	function setElems() {
		for (var i = 0; i < elems.length; i++) {
			dragElem(elems[i]);
		}
	}

	function setContainer(container) {
	
		container.addEventListener('dragover', function(e) {
			e.preventDefault();
		});

		container.addEventListener('drop', function(e) {
			console.log(e);
			e.preventDefault();
			if (ElemOnDrag) { 
				var elem = document.getElementById(ElemOnDrag.id);
				if (elem.parentNode.id != 'tab') {
					elem = cloneElement(elem);
					this.appendChild(elem);
				}
				var offset = e.dataTransfer.getData('offset');
				console.log(ElemOnDrag);
				elem.style.left = (e.clientX - ElemOnDrag.offsetX) + 'px';
				elem.style.top = (e.clientY - ElemOnDrag.offsetY) + 'px';
				ElemOnDrag = null;
			}
		});
	}
	
	var plus = document.getElementById('plus');
	var moins = document.getElementById('moins');

	setResize(plus, moins);
	setContainer(container);
	setElems();
	window.addEventListener('keydown', deleteElem);
}

var container  = document.body.children[1];
var elems = document.getElementsByClassName('elem');
SetFilters(container, elems);

</script>
</html>
