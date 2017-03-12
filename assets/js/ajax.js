 function getXMLHttpRequest() {
	var xhr = null;

	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest(); 
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	return xhr;
};

var xhr = null; // put xhr to null in callback !!

function sendRequest (type, request, callback) {
	if (xhr && xhr.readyState != 4)
		return null;
	xhr = getXMLHttpRequest();
	if (!xhr)
		return null;
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if (xhr.status != 200 && xhr.status != 0)
				return null;
			else {
				if (type == "XML")
					callback(xhr.responseXML);
				else 
					callback(xhr.responseText);
			}
		}
	}
	request();
};

function setRequest(method, path, args) {
	return function() {
		if (method == "POST") {
			xhr.open(method, path, true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(args)
		}
		else {
			xhr.open(method, path + '?' + args, true);
			xhr.send(args);
		}
	};
};
