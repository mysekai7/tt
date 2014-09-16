var bgObj,panelObj,panContentObj,conIframeObj,sHeight

function showPanel(importFileUrl,cWidth,cHeight,intTop,intLeft)
{
	
	/*
		弹出面版效果
		importFileUrl:嵌入文件路径
		cWidth:面板宽度
		cHeight:面板高度
		intTop弹出面板上边距
		intLeft弹出面板左边距
		Txt传参
		Code传参
		btn：false不显示默认关闭按钮，true显示默认关闭按钮
		注:在嵌入文件中调用window.top.objImg.onclick(),可关闭面板.
	*/	
	var sWidth;	
	sWidth=parseInt(document.body.offsetWidth);
	var ofHeight=parseInt(document.body.offsetHeight);	
	var scHeight=parseInt(document.body.scrollHeight);
	cWidth=cWidth<100?100:cWidth;
	cHeight=cHeight<30?30:cHeight;	
	intLeft=intLeft==0?(sWidth-cWidth-20)/2:intLeft;
	intTop=intTop==0?100:intTop;
	intTop=intTop+parseInt(document.documentElement.scrollTop);
	sHeight=Math.max(ofHeight,scHeight,screen.availHeight-100)+20;	
	
	bgObj=document.createElement("div");
	bgObj.setAttribute('id','bgDiv');
	bgObj.style.position="absolute";
	bgObj.style.zIndex="2";	
	bgObj.style.top="0";
	bgObj.style.background="#777";
	bgObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=1,opacity=30,finishOpacity=30)";
	bgObj.style.opacity="0.3";
	bgObj.style.left="0";
	bgObj.style.width="100%";
	bgObj.style.height=sHeight + "px";
	document.body.appendChild(bgObj);
	
	panelObj=document.createElement("div");
	panelObj.setAttribute('id','panelObjDiv');
	panelObj.style.position="absolute";
	panelObj.style.zIndex="3";
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
	panContentObj.style.zIndex="4";
	panContentObj.style.top=(intTop+8)+"px";
	panContentObj.style.left=(intLeft+8)+"px";
	panContentObj.style.width=cWidth+"px";
	panContentObj.style.height=cHeight+"px";
	panContentObj.style.background="#fff";
	panContentObj.style.border="1px solid #CCCCCC";		
	document.body.appendChild(panContentObj);	
	
	conIframeObj=document.createElement("iframe");
	conIframeObj.setAttribute('id','objIframe');
	conIframeObj.setAttribute('name','objIframe');
	//conIframeObj.src=importFileUrl;
	/*
	if(importFileUrl.indexOf("?")>0){
		conIframeObj.src=importFileUrl+"&url="+location.href.substring(0,location.href.indexOf("/",10));
	}else{
		conIframeObj.src=importFileUrl+"?url="+location.href.substring(0,location.href.indexOf("/",10));
	}*/
	conIframeObj.frameBorder="0";
	conIframeObj.width=cWidth+"px";
	conIframeObj.height=cHeight+"px";
	conIframeObj.scrolling="no";
	panContentObj.appendChild(conIframeObj);
	conIframeObj.src=importFileUrl;
	
}
String.prototype.toNum=function()
{
	return parseInt(this.replace("px",""));
}
function Pclose()
{
	//alert(window.parent.document.body.id);
	panContentObj.removeChild(conIframeObj);
	document.body.removeChild(panContentObj);
	document.body.removeChild(panelObj);
	document.body.removeChild(bgObj);			
	conIframeObj=null;
	panContentObj=null;
	panelObj=null;
	bgObj=null;	
	window.focus();	
}

function P2close(src)
{
	//alert(window.parent.document.body.id);
	panContentObj.removeChild(conIframeObj);
	document.body.removeChild(panContentObj);
	document.body.removeChild(panelObj);
	document.body.removeChild(bgObj);			
	conIframeObj=null;
	panContentObj=null;
	panelObj=null;
	bgObj=null;	
	showPanel(src,580,490,50,0);
	//window.focus();	
}

function setH(intH)
{	

	panelObj.style.height=(intH+19) + "px";
	panContentObj.style.height=intH + "px";
	conIframeObj.height=intH + "px";	
	var panelObjH=panelObj.style.top.toNum()+panelObj.style.height.toNum();
	var bgObjH=bgObj.style.height.toNum();
	if (bgObjH<panelObjH)
	{
		bgObj.style.height=panelObjH + "px";
	}
	if(bgObjH>sHeight&&bgObjH>panelObjH)
	{
		bgObj.style.height=Math.max(sHeight,panelObjH)
	}	
	
}