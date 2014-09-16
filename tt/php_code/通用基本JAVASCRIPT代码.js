//防止被框架调用
if (top.location != location){ top.location.href = self.location; }

//获取浏览器类型
var domType = '';
if (document.all) {
	domType = "ie4";
} else if (document.getElementById) {
	domType = "std";
} else if (document.layers) {
	domType = "ns4";
}

//依浏览器类型调用不同方法
var objects=new Array();
function $(idname, forcefetch) {
	if (forcefetch || typeof(objects[idname]) == "undefined") {
		switch (domType) {
			case "std": {
				objects[idname] = document.getElementById(idname);
			}
			break;

			case "ie4": {
				objects[idname] = document.all[idname];
			}
			break;

			case "ns4": {
				objects[idname] = document.layers[idname];
			}
			break;
		}
	}
	return objects[idname];
}

//各种全面问题
function externallinks(){
	//解决标准问题
	if (!document.getElementsByTagName){ return; }
	var anchors = document.getElementsByTagName("a");
	for (var i=0; i<anchors.length; i++) {
		var anchor = anchors[i];
		if (anchor.getAttribute("href") && anchor.getAttribute("rel") == "_blank"){
			anchor.target = "_blank";
		}
	}


	//IE PNG显示问题修正
	var iearVersion = navigator.appVersion.split("MSIE");
	var ieversion = parseFloat(iearVersion[1]);
	if ((ieversion >= 5.5) && (ieversion < 7) && (document.body.filters)){
	   for(var i=0; i<document.images.length; i++){
	      var img = document.images[i];
	      var imgName = img.src.toUpperCase();
	      if (imgName.substring(imgName.length-3, imgName.length) == "PNG"){
		 var imgID = (img.id) ? "id='" + img.id + "' " : "";
		 var imgClass = (img.className) ? "class='" + img.className + "' " : "";
		 var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
		 var imgStyle = "display:inline-block;" + img.style.cssText;
		 if (img.align == "left") imgStyle = "float:left;" + imgStyle;
		 if (img.align == "right") imgStyle = "float:right;" + imgStyle;
		 if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle;
		 var strNewHTML = "<span " + imgID + imgClass + imgTitle
		 + " style=\"margin:2px 0 2px 0;" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
		 + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
		 + "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>";
		 img.outerHTML = strNewHTML;
		 i = i-1;
	      }
	   }
	}
}
window.onload = externallinks;

//滚动公告
function startmarquee(lh,speed,delay,index){
	var t;
	var p=false;
	var o=$("marqueebox"+index);
	o.innerHTML+=o.innerHTML;
	o.onmouseover=function(){p=true}
	o.onmouseout=function(){p=false}
	o.scrollTop = 0;
	function start(){
		t=setInterval(scrolling,speed);
		if(!p) o.scrollTop += 2;
	}
	function scrolling(){
		if(o.scrollTop%lh!=0){
			o.scrollTop += 2;
			if(o.scrollTop>=o.scrollHeight/2) o.scrollTop = 0;
		}else{
			clearInterval(t);
			setTimeout(start,delay);
		}
	}
	setTimeout(start,delay);
}

//支持Firefox收藏功能
function addfavorite(url,name){
	if (document.all){
		window.external.addFavorite(url,name);
	}else if(window.sidebar){
		window.sidebar.addPanel(name,url, "");
	}
}

//检测浏览器是否支持Cookie
function checkCookie(){
	//判断cookie是否开启
	var cookieEnabled=(navigator.cookieEnabled)? true : false;

	//如果浏览器不是ie4+或ns6+
	if (typeof navigator.cookieEnabled=="undefined" && !cookieEnabled){
		document.cookie="testcookie";
		cookieEnabled=(document.cookie=="testcookie")? true : falsedocument.cookie="";
	}

	//如果没有开启
	if(cookieEnabled){
		return true;
	}else{
		return false;
	}
}

//增加Cookie
function addCookie(name,value,expireHours){
	var cookieString=name+"="+escape(value);
	//判断是否设置过期时间
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime+expireHours*3600*1000);
		cookieString=cookieString+"; expire="+date.toGMTString();
	}
	document.cookie=cookieString;
}

//获取Cookie值
function getCookie(name){
	var strCookie=document.cookie;
	var arrCookie=strCookie.split("; ");
	for(var i=0;i<arrCookie.length;i++){
		var arr=arrCookie[i].split("=");
		if(arr[0]==name){
			return unescape(arr[1]);
		}
	}
	return false;
}

//删除Cookie
function deleteCookie(name){
	var date=new Date();
	date.setTime(date.getTime()-10000);
	document.cookie=name+"=; expire="+date.toGMTString();
}

//获取光标位置
function getCaret(ZysrID){
	var txb = $(ZysrID);
	var pos = 0;
	txb.focus();

	var s = txb.scrollTop;
	var r = document.selection.createRange();
	var t = txb.createTextRange();
	t.collapse(true);
	t.select();

	var j = document.selection.createRange();
	r.setEndPoint("StartToStart",j);

	var str = r.text;
	var re = new RegExp("[\\n]","g");
	str = str.replace(re,"");
	pos = str.length;
	r.collapse(false);
	r.select();
	txb.scrollTop = s;

	return pos;
}

//设置光标位置
function setCaret(id,pos){
	var textbox = $(id);
	var r = textbox.createTextRange();
	r.collapse(true);
	r.moveStart('character',pos);
	r.select();
}

//在光标处插入
function insertAtCursor(id,text){//IE下
	if(document.selection){
		$(id).focus();
		sel = document.selection.createRange();
		sel.text = text;
	}else if($(id).selectionStart||$(id).selectionStart == "0") {//firefox下
		//获得光标位置
		var startPos = $(id).selectionStart;
		var endPos = $(id).selectionEnd;
		$(id).value = $(id).value.substring(0,startPos) + text + $(id).value.substring(endPos,$(id).value.length);
	}else{
		$(id).value += text;
	}
}

//获取选择文本
function getSelectText(id){
	var text="";
	if(document.selection){//IE下
		$(id).focus();
		text = document.selection.createRange().text;
	}else if($(id).selectionStart||$(id).selectionStart == "0") {//firefox下
		//获得光标位置
		var startPos = $(id).selectionStart;
		var endPos = $(id).selectionEnd;
		text = $(id).value.substring(startPos,endPos);
	}
	return text;
}