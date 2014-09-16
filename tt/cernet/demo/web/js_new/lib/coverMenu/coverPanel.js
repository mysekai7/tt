//根目录
var object = "";
var isMsie = /msie/.test(navigator.userAgent.toLowerCase());
var isFf = /firefox/.test(navigator.userAgent.toLowerCase());
function initCover(url,cWidth,cHeight,intLeft,intTop)
{
	object = new coverPanel(url,cWidth,cHeight,intLeft,intTop);
}

var coverPanel = Class.create();
coverPanel.prototype = {
	initialize:function(url,cWidth,cHeight,intLeft,intTop) {
		this.url = url;
		this.cWidth = (typeof cWidth == 'string'?parseInt(cWidth):cWidth);
		this.cHeight = (typeof cHeight == 'string'?parseInt(cHeight):cHeight);
		this.intLeft = (typeof intLeft == 'string'?parseInt(intLeft):intLeft);
		this.intTop = (typeof intTop == 'string'?parseInt(intTop):intTop);
		this.showPanel();
	},
	showPanel:function() {
		
		var sWidth = parseInt(document.body.offsetWidth);
		var ofHeight = parseInt(document.body.offsetHeight);	
		var scHeight = parseInt(document.body.scrollHeight);
		//alert('document.body.offsetWidth: '+document.body.offsetWidth+'\r\n document.body.offsetHeight: '+document.body.offsetHeight+'\r\n document.body.scrollWidth: '+document.body.scrollWidth+'\r\n document.body.scrollHeight: '+document.body.scrollHeight+'\r\n document.body.clientWidth: '+document.body.clientWidth+'\r\n document.body.clientHeight: '+document.body.clientHeight);
		
		//alert(document.documentElement.clientHeight);
		//alert(document.documentElement.scrollTop);
		//面板内容的宽度和高度
		cWidth = this.cWidth<100?100:this.cWidth;
		cHeight = this.cHeight<30?30:this.cHeight;	
		intLeft = this.intLeft || (document.documentElement.clientWidth-cWidth)/2;
		intTop = this.intTop || document.documentElement.scrollTop+(document.documentElement.clientHeight-cHeight)/2;
		//intTop = this.intTop+parseInt(document.documentElement.scrollTop);
		sHeight = Math.max(ofHeight,scHeight,screen.availHeight-100);	
		
		bgObj = document.createElement("div");
		bgObj.setAttribute('id','MM_bgDiv');
		bgObj.style.position = "absolute";
		bgObj.style.zIndex = "100";	
		bgObj.style.background = "#777";
		bgObj.style.filter = "progid:DXImageTransform.Microsoft.Alpha(style=1,opacity=30,finishOpacity=30)";
		bgObj.style.opacity = "0.3";
		bgObj.style.left = isMsie?document.body.style.padding:"0";
		bgObj.style.top = "0";
		bgObj.style.width = "100%";    // 随着窗口大小变化而变化
		bgObj.style.height = sHeight + "px";
		document.body.appendChild(bgObj);
		
		panelObj=document.createElement("div");
		panelObj.setAttribute('id','panelObjDiv');
		panelObj.style.position="absolute";
		panelObj.style.zIndex="5";
		panelObj.style.top=intTop+"px";
		panelObj.style.left=intLeft+"px";
		panelObj.style.background="#cccccc";
		panelObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=1,opacity=50,finishOpacity=50)";
		panelObj.style.opacity="0.6";
		panelObj.style.width=(cWidth+19)+"px";
		panelObj.style.height=(cHeight-8)+"px";
		document.body.appendChild(panelObj);
		//alert(cHeight);
		panContentObj=document.createElement("div");
		panContentObj.setAttribute('id','panContentObjDiv');
		panContentObj.style.position="absolute";
		panContentObj.style.zIndex="102";
		panContentObj.style.top=(intTop+8)+"px";
		panContentObj.style.left=(intLeft+8)+"px";
		panContentObj.style.width=cWidth+"px";
		panContentObj.style.height=(cHeight-19-8)+"px";
		panContentObj.style.background="#fff";
		panContentObj.style.border="1px solid #CCCCCC";
		
		/*panCloseObj = document.createElement("div");
		panCloseObj.setAttribute('id', 'panCloseDiv');
		panCloseObj.style.width = "100%";
		panCloseObj.style.height = "25px";
		panCloseObj.style.background = "#dddddd";
		panCloseObj.innerHTML = "<table width=\"100%\"><tr><td align=\"right\"><a href=\"#\" onclick=\"closePanel();return false;\">[ 关闭 ]</a></td></tr></table>";
		panContentObj.appendChild(panCloseObj);
		*/
		document.body.appendChild(panContentObj);
		
		panContentObj.appendChild(this.createIframe());
		
	},
	createIframe:function() {
		conIframeObj=document.createElement("iframe");
		conIframeObj.setAttribute('id','objIframe');
		conIframeObj.setAttribute('name','objIframe');
		conIframeObj.frameBorder="0";
		conIframeObj.width=this.cWidth+"px";
		conIframeObj.height=(this.cHeight-27)+"px";
		conIframeObj.scrolling="no";
		conIframeObj.src=this.url;
		
		return conIframeObj;
	}
	
}

function closePanel()
{
	parent.document.body.removeChild(parent.document.getElementById('MM_bgDiv'));
	parent.document.body.removeChild(parent.document.getElementById('panelObjDiv'));
	parent.document.body.removeChild(parent.document.getElementById('panContentObjDiv'));
}
