/**
* Declare some global variables and functions.
* Note: All pages of the site shoule include this script.
* Author: glzone
* date: 2008-11-22
*/
var jsRoot = '/js_new/';
var jsLib = jsRoot+'lib/';
var jsMod = jsRoot+'mod/';
var debug = false;
/**
* Define this object to return a constructor in which initializing function is called.
* The class using this object should define an initializing function with name 'initialize'.
* author:
* date: 2008-09-17
*/
var Class = {
	create: function() {
		return function() {
			this.initialize.apply(this, arguments);
		}
	}
}

/**
* Rewrite function $() and you may use it to get the first element by name.
* Note: Don't use it with prototype.
* author: glzone
* date: 2008-09-17
*/
function $() {
	var elements = new Array();
	for(var i=0;i<arguments.length;i++) {
		var element = arguments[i];
		if(typeof element == "string")
			element = document.getElementById(element) || document.getElementsByName(element)[0];
		if(arguments.length == 1)
			return element;
		elements.push(element);
	}
	return elements;
}


/**
* Define a function to debug during development.
* Note: This function is temporary.
* author: glzone
* date: 2008-11-24
*/

window.onerror = function(msg, url, line) {
	if(debug && this.onerror.num++ < this.onerror.max) {
		alert('ERROR: '+ msg + '\n' + url + ':' + line);
		return true;
	}
}
onerror.max = 3;
onerror.num = 0;