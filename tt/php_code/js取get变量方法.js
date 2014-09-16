function $_GET(name) {
	var getArray = {};
	var get = location.search;
	gets = get.substr(1).split('&');
	for (i in gets) {
		t = gets[i].split('=');
		getArray[t[0]] = t[1];
	}
	return getArray[name];
}