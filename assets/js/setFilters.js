function SetFilters(container, elems, sizeUp, sizeDown) {

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
			'background-image': 'url(' + elem.src + ')',
			'position':'absolute'
			}
		);
		newElem.draggable = true;
		newElem.classList.add('cloned');
		newElem.id = 'on_' + ElemOnCount++;
		dragElem(newElem);
		selectElem(newElem);
		return (newElem);
	}

	function dragElem(elem) {
		elem.addEventListener('dragstart', function(e) {
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
			e.preventDefault();
		});

		function dropOnContainer(e) {
			e.preventDefault();
			console.log(e);
			if (!ElemOnDrag)
				return;
			var elem  = document.getElementById(ElemOnDrag.id);
			if (elem.parentNode.id != container.id) {
				elem = cloneElement(elem);
				this.appendChild(elem);
			}
			var offset = e.dataTransfer.getData('offset');
			console.log(ElemOnDrag);
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
console.log(sizeUp, sizeDown, container, elems);
SetFilters(container, elems, sizeUp, sizeDown);
