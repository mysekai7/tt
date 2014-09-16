//get方式
function loadajax(url, func)
{
    var http_request;
    try { http_request = new XMLHttpRequest(); }
    catch(e) {
        try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch(e) {
            try { http_request = new ActiveXObject('Msxml2.XMLHTTP'); }
            catch(e) {}
        }
    }

    if(http_request){

        http_request.onreadystatechange=function(){
            if(http_request.readyState==4){
                if(http_request.status==200){
                    func(http_request.responseText);
                }
            }
        };
        http_request.open("GET",url,true);
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(null);
    }
}

//POST方式
function ajax(url, data, func)
{
    var http_request;
	if(window.ActiveXObject){
		try{
			http_request=new ActiveXObject("Msxml2.XMLHTTP");
		}catch(execption){
			try{
				http_request=new ActiveXObject("Microsoft.XMLHTTP");
			}catch(execption){
				http_reqeust=false;
			}
		}
	}else{
		http_request=new XMLHttpRequest();
		if(http_request.overrideMimeType){
			http_request.overrideMimeType="text/xml";
		}
	}

	if(http_request){
		http_request.onreadystatechange=function(){
            if(http_request.readyState==4){
                if(http_request.status==200){
                    func(http_request.responseText);
                }
            }
        };
		http_request.open("POST",url,true);
		http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
		http_request.setRequestHeader("If-Modified-Since",0);
		http_request.send(data);
	}else{
		alert("Error");
	}
}