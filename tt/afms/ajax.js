// JavaScript Document
function do_ajax() {			
}
function ajax_get_data(id) {	
}
function file_list(path) {	
    var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Msxml2.XMLHTTP");
	}else {
		throw new Error("Ajax is not supported by this browser");
	}
	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4) {
			if (xhr.status >= 200 && xhr.status < 300) {
				document.getElementById('file_list').innerHTML = xhr.responseText;
			}
		}
	}
	xhr.open('GET','anfms.php?action=filelist&path='+path);
	xhr.setRequestHeader("If-Modified-Since","0");
	xhr.send();
}
function file_content_input(data) {
	var data;
	var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Msxml2.XMLHTTP");
	}else {
		throw new Error("Ajax is not supported by this browser");
	}
	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4) {
			if (xhr.status >= 200 && xhr.status < 300) {
				document.getElementById('file_content_input').innerHTML = xhr.responseText;
			}
		}
	}
	xhr.open('GET','anfms.php?action=inputcontent&'+data);
	xhr.setRequestHeader("If-Modified-Since","0");
	xhr.send();	
}
function file_get_content(data) {
	var data;
	var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Msxml2.XMLHTTP");
	}else {
		throw new Error("Ajax is not supported by this browser");
	}
	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4) {
			if (xhr.status >= 200 && xhr.status < 300) {
				document.getElementById('file_get_content').innerHTML = xhr.responseText;
			}
		}
	}
	xhr.open('GET','anfms.php?action=getcontent&'+data);
	xhr.setRequestHeader("If-Modified-Since","0");
	xhr.send();	
}
function file_make(file) {
	var file;
	var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Msxml2.XMLHTTP");
	}else {
		throw new Error("Ajax is not supported by this browser");
	}
	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4) {
			if (xhr.status >= 200 && xhr.status < 300) {
				document.getElementById('file_make').innerHTML = xhr.responseText;
			}
		}
	}
	xhr.open('GET','anfms.php?action=filemake&'+file);
	xhr.setRequestHeader("If-Modified-Since","0");
	xhr.send();	
}
function file_delete(file) {
	var file;
	var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Msxml2.XMLHTTP");
	}else {
		throw new Error("Ajax is not supported by this browser");
	}
	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4) {
			if (xhr.status >= 200 && xhr.status < 300) {
				document.getElementById('file_list').innerHTML = xhr.responseText;
			}
		}
	}
	xhr.open('GET','anfms.php?action=delete_file&file='+file);
	xhr.setRequestHeader("If-Modified-Since","0");
	xhr.send();	
}
function dir_delete(file) {
	var file;
	var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Msxml2.XMLHTTP");
	}else {
		throw new Error("Ajax is not supported by this browser");
	}
	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4) {
			if (xhr.status >= 200 && xhr.status < 300) {
				document.getElementById('file_list').innerHTML = xhr.responseText;
			}
		}
	}
	xhr.open('GET','anfms.php?action=delete_dir&file='+file);
	xhr.setRequestHeader("If-Modified-Since","0");
	xhr.send();	
}