function SetFilters(container, elems, sizeUp, sizeDown) {

	var ElemOnCount = 0;
	var ElemOnDrag = null;
	var SelectElem = {};

	function onSelectedElems(callback) {
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
		if (!document.getElementsByClassName('selected').length)
		{	
			if (e.keyCode == 8) {
				onSelectedElems(function(elem) {
					delete SelectElem[elem.id];
					elem.parentNode.removeChild(elem);
				});
			}
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
		console.log(elem);
		var newElem = createElement('img',
			{
			'height':style.getPropertyValue('height'),
			'position':'absolute'
			}
		);
		newElem.src = elem.src
		newElem.draggable = true;
		newElem.classList.add('cloned');
		newElem.id = 'on_' + ElemOnCount++;
		dragElem(newElem);
		selectElem(newElem);
		return (newElem);
	}

	function dragElem(elem) {
		elem.addEventListener('dragstart', function(e) {
		console.log('dragstart');
		ElemOnDrag =
			{
				'id':e.target.id,
				'offsetX':e.offsetX,
				'offsetY':e.offsetY
			};
		});
	}

	function setElems() {
		for (var i = 0; i < elems.length; i++) {
			dragElem(elems[i]);
		}
	}

	function setContainer(container) {
	
		container.addEventListener('dragover', function(e) {
			console.log('element dragged over')
			e.preventDefault();
		});

		function dropOnContainer(e) {
			console.log("element dropped");
			e.preventDefault();
			if (!ElemOnDrag)
				return;
			var elem  = document.getElementById(ElemOnDrag.id);
			if (elem.parentNode.id != container.id) {
				elem = cloneElement(elem);
				this.appendChild(elem);
			}
			var offset = e.dataTransfer.getData('offset');
			elem.style.left = (e.offsetX - ElemOnDrag.offsetX) + 'px';
			elem.style.top = (e.offsetY - ElemOnDrag.offsetY) + 'px';
			ElemOnDrag = null;
		}	
		
		container.addEventListener('drop', dropOnContainer);
	}
	


	setResize(sizeUp, sizeDown);
	setElems();
	setContainer(container);
	window.addEventListener('keydown', deleteElem);
}

var sizeUp = document.getElementById('sizeUp');
var sizeDown = document.getElementById('sizeDown');
var container = document.getElementById('camera');
var elems = document.getElementsByClassName('filters');
SetFilters(container, elems, sizeUp, sizeDown);
