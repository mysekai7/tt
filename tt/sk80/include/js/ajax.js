// Create Ajax Request
// updated on 2010-1-25 by mysekai7


var http_request = null;

//get
function loadajax(url, process){

    //if(http_request != null) return http_request; //request once in a page
    try { http_request = new XMLHttpRequest(); }
    catch(e) {
        try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch(e) {
            try { http_request = new ActiveXObject('Msxml2.XMLHTTP'); }
            catch(e) {}
        }
    }

	if(http_request){
		//alert("Ajax引擎创建成功");
		http_request.onreadystatechange=process;
		http_request.open("GET",url,true);
		http_request.setRequestHeader("If-Modified-Since",0);
		http_request.send(null);
	}
}

//post
function ajax(url,data){

    //if(http_request != null) return http_request;
    try { http_request = new XMLHttpRequest(); }
    catch(e) {
        try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch(e) {
            try { http_request = new ActiveXObject('Msxml2.XMLHTTP'); }
            catch(e) {}
        }
    }

	if(http_request){
		http_request.onreadystatechange=process;
		http_request.open("POST",url,true);
		http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
		http_request.setRequestHeader("If-Modified-Since",0);
		http_request.send(data);
	}
}

/*
function process() {
	if(http_request.readyState==4){
		if(http_request.status==200){
			str=http_request.responseText;  //return data
		}
	}
}
*/