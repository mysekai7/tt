/************************************************************************

2008-6-27
xuezx

公共函数↓

************************************************************************/
function getChildNodes(parNode,strName)//返回指定名称节点
{
	var objChilds=parNode.childNodes;
	var j=0;
	var objArr=[];
	for(var i=0;i<objChilds.length;i++)
	{
		if(objChilds[i].nodeName==strName)
		{
			objArr[j]=objChilds[i];
			j++;
		}
	}
	return objArr;
}

function getNodeValue(nodeObj)
{	
	return nodeObj.text==undefined?nodeObj.textContent:nodeObj.text	;
}

function initMenu(intX)
{
	var url="http://"+location.host+location.pathname;	
	$$("#tabs ul li").each(function(s){
	if(s.down("a").href==url){
			s.className="current";
			try{$("tabs").previous(".titleBg").down("h1").update(s.down("span").innerHTML)}catch(e){}
		}
	});
}

/************************************************************************

code by esen at 2008/04/07
supported wyxqjq@163.com

功能：去掉接连虚线框↓
***********************************************************************
Event.observe(window,"load",function()	{
		var objs=$$("a");
		for(var i=0;i<objs.length;i++)
		objs[i].observe("focus",function(ev){Event.element(ev||window.event).blur();});	
});
*/



/************************************************************************

code by esen at 2008/03/24
supported wyxqjq@163.com

公共函数↓

************************************************************************/
function addEvent(obj,ev,myFun)
{
	try{obj.attachEvent(ev,myFun)}catch(e){}
	try{obj.addEventListener(ev.replace(/^on/,""),myFun, true)}catch(e){};
}

function these()
{
	var returnV=null;
	for(var i=0;i<arguments.length;i++)
	{
		var cc=arguments[i];
		try{returnV=cc();break;}catch(e){}
	}
	return returnV;
}
function $Tag(str,obj)//返回指定标签
{	
	if(obj){
		return 	obj.getElementsByTagName(str);
	}else
	{
		return document.getElementsByTagName(str);
	}
}

function objCoord(obj)//返回节点对象坐标
{
	var intLeft = 0;
	var intTop  = 0;
	var intWidth=parseInt(obj.offsetWidth);
	var intHeight=parseInt(obj.offsetHeight);
	while (obj)
	{ 
		intLeft += parseInt(obj.offsetLeft); 
		intTop  += parseInt(obj.offsetTop);
		obj = obj.offsetParent; 
	}  
	return {left:intLeft, top:intTop,width:intWidth,height:intHeight};
}

function setAtt(obj,attName,attValue)//给节点增加属性
{
	document.createAttribute(attName);		
	obj.setAttribute(attName,attValue);
}
function exchange(imgUrl1,imgUrl2)//设置交换图片
{
	var imgs=$Tag("img");
	for(var i=0;i<imgs.length;i++)
	{
		if(imgs[i].src==imgUrl1)
		{

			setAtt(imgs[i],"outUrl",imgUrl1);
			setAtt(imgs[i],"ovUrl",imgUrl2);
			addEvent(imgs[i],"onmouseout",
				function(ev)
				{
					var obj=evObj(ev);			
					obj.src=obj.getAttribute("outUrl");
				});
			addEvent(imgs[i],"onmouseover",
				function(ev)
				{
					var obj=evObj(ev);
					obj.src=obj.getAttribute("ovUrl");
				});
			imgs[i].style.cursor="pointer";
		}
	}
}
function mCoord(ev)//返回鼠标坐标
{
	 if(ev.pageX || ev.pageY)
	 {
		return {x:ev.pageX, y:ev.pageY};
	 }
	 return {
		  x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
		  y:ev.clientY + document.body.scrollTop  - document.body.clientTop
	 };
}



function evObj(ev)//返回触发事件的对象
{
	return ev.srcElement?ev.srcElement:ev.currentTarget;
}

function getPosition(e){
 var intLeft = 0;
 var intTop  = 0;
 var intWidth=parseInt(e.offsetWidth);
 var intHeight=parseInt(e.offsetHeight);
 while (e){ 
  intLeft += parseInt(e.offsetLeft); 
  intTop  += parseInt(e.offsetTop);
  e     = e.offsetParent; 
 }  
 return {left:intLeft, top:intTop,width:intWidth,height:intHeight};
}
var menu001=document.createElement("div");
	menu001.setAttribute('id','objMenu');
	menu001.style.position="absolute";
	menu001.style.top="0px";
	menu001.style.left="0px";
	
function setMsg(obj,str)
{
	
	var objpos=getPosition(obj)
	menu001.style.top=(objpos.top+18)+"px";
	menu001.style.left=(objpos.left-100)+"px";
	menu001.innerHTML=str;
	obj.parentNode.parentNode.appendChild(menu001);	
	setTimeout("msgShow()",100);
}
function msgShow()
{	
	if(menu001.style.display!="")menu001.style.display="";	
}
function msgHide()
{	
	if(menu001.style.display!="none")menu001.style.display="none";	
}
addEvent(document,"onmouseup",msgHide);

