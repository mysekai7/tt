
//配置（可扩展）

//根目录

var object = "";
var isMsie = /msie/.test(navigator.userAgent.toLowerCase());
var isFf = /firefox/.test(navigator.userAgent.toLowerCase());
function initObj(form,url,cWidth,cHeight,intLeft,intTop)
{
	object = new PObject(form,url,cWidth,cHeight,intLeft,intTop);
}

function PObject(form,url,cWidth,cHeight,intLeft,intTop)
{
	
	this.form = form;
	this.url = url;
	this.cWidth = cWidth;
	this.cHeight = cHeight;
	this.intLeft = intLeft;
	this.intTop = intTop;
	this.showPanel();
}

PObject.prototype = {
	showPanel:function() {
		
		var sWidth = parseInt(document.body.offsetWidth);
		var ofHeight = parseInt(document.body.offsetHeight);	
		var scHeight = parseInt(document.body.scrollHeight);
		cWidth = this.cWidth<100?100:this.cWidth;
		cHeight = this.cHeight<30?30:this.cHeight;	
		intLeft = this.intLeft || (document.body.clientWidth-cWidth)/2;
		intTop = this.intTop || (scHeight-document.documentElement.scrollTop-cHeight)/2+document.documentElement.scrollTop;
		//intTop = this.intTop+parseInt(document.documentElement.scrollTop);
		sHeight = Math.max(ofHeight,scHeight,screen.availHeight-100)+(isMsie?30:20);	
		
		//alert(document.body.clientWidth);
		bgObj = document.createElement("div");
		bgObj.setAttribute('id','MM_bgDiv');
		bgObj.style.position = "absolute";
		bgObj.style.zIndex = "100";	
		bgObj.style.background = "#777";
		bgObj.style.filter = "progid:DXImageTransform.Microsoft.Alpha(style=1,opacity=30,finishOpacity=30)";
		bgObj.style.opacity = "0.3";
		bgObj.style.left = isMsie?document.body.style.padding:"0";
		bgObj.style.top = "0";
		bgObj.style.width = "100%";
		bgObj.style.height = sHeight + "px";
		document.body.appendChild(bgObj);
		
		panelObj=document.createElement("div");
		panelObj.setAttribute('id','panelObjDiv');
		panelObj.style.position="absolute";
		panelObj.style.zIndex="101";
		panelObj.style.top=intTop+"px";
		panelObj.style.left=intLeft+"px";
		panelObj.style.background="#F5FBFF";
		panelObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=1,opacity=50,finishOpacity=50)";
		panelObj.style.opacity="0.6";
		panelObj.style.width=(cWidth+19)+"px";
		panelObj.style.height=(cHeight+19)+"px";
		document.body.appendChild(panelObj);
		
		panContentObj=document.createElement("div");
		panContentObj.setAttribute('id','panContentObjDiv');
		panContentObj.style.position="absolute";
		panContentObj.style.zIndex="102";
		panContentObj.style.top=(intTop+8)+"px";
		panContentObj.style.left=(intLeft+8)+"px";
		panContentObj.style.width=cWidth+"px";
		panContentObj.style.height=cHeight+"px";
		panContentObj.style.background="#fff";
		panContentObj.style.border="1px solid #CCCCCC";
		
		panCloseObj = document.createElement("div");
		panCloseObj.setAttribute('id', 'panCloseDiv');
		panCloseObj.style.width = "100%";
		panCloseObj.style.height = "25px";
		panCloseObj.style.background = "#dddddd";
		panCloseObj.innerHTML = "<table width=\"100%\"><tr><td align=\"right\"><a href=\"#\" onclick=\"closePanel()\">[ 关闭 ]</a></td></tr></table>";
		panContentObj.appendChild(panCloseObj);
		
		document.body.appendChild(panContentObj);
		
		panContentObj.appendChild(this.createIframe());
		
	},
	createIframe:function() {
		conIframeObj=document.createElement("iframe");
		conIframeObj.setAttribute('id','objIframe');
		conIframeObj.setAttribute('name','objIframe');
		conIframeObj.frameBorder="0";
		conIframeObj.width=this.cWidth+"px";
		conIframeObj.height=(this.cHeight-25)+"px";
		conIframeObj.scrolling="no";
		conIframeObj.src=this.url;
		
		return conIframeObj;
	}
	
}
function request(url, args, succFunc, waitFunc) 
{
	if(window.XMLHttpRequest){
		this.ro = new XMLHttpRequest();
	} else if(window.ActiveXObject){
		this.ro = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var ro = this.ro;
	this.ro.onreadystatechange= function(){
		if (ro.readyState == 4) {
			//alert(ro.responseText);
			if(succFunc) eval(succFunc+"('"+ro.responseText+"')");
			closePanel();
		}
		else if(waitFunc) eval(waitFunc);
	}
	ro.open("post",url);
	ro.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ro.send(args);
}
function doSubmit(form,succFunc,waitFunc)
{
	var args = [];
	var eles = $(form).elements;
	while(args.length<eles.length) {
		args[args.length] = encodeURIComponent(eles[args.length].name)+"="+encodeURIComponent(eles[args.length].value);
	}
	request('/panel_applet.php',args.join("&"),succFunc,waitFunc);
}
function closePanel()
{
	parent.document.body.removeChild(parent.document.getElementById('MM_bgDiv'));
	parent.document.body.removeChild(parent.document.getElementById('panelObjDiv'));
	parent.document.body.removeChild(parent.document.getElementById('panContentObjDiv'));
}
function cb1(param)
{
	alert("回调函数cb1被调用，服务器返回："+param);
}
/**
* rewrite the function $()
*/
function $()
{
	var elements = new Array();
	for(var i=0;i<arguments.length;i++)
	{
		var element = arguments[i];
		if(typeof element == "string")
			element = document.getElementById(element);
		if(arguments.length == 1)
			return element;
		elements.push(element);
	}
	
	return elements;
}
//-----------------------------------------------------------------------------
