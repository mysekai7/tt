// by GuoLei 2008/07/03

//初始化
document.write('<script type="text/javascript" src="/ajax/lib/application.js"></script>\n');	
document.write('<script type="text/javascript" src="panel/lib.js"></script>\n');	
document.write('<script type="text/javascript" src="panel/panel.js"></script>\n');	
var codeaProv="";
var arraCity=[];

function getXmlService(data) 
{
	showPanel('city.html',600,350,100,0);
}


function onchangeProv(obj,codeProv,nameProv)
{
	loadJs("data/"+codeProv+".js?id="+new Date());
}

function showaCity()
{
	var strHtml="";
	if(arraCity.length==0)
	{
		document.getElementById("floorContent").innerHTML="<div style='padding-top:60px;text-algin:center;'>【暂无数据】</div>"
		return;
	}
	
	for(var i=0;i<arraCity.length;i++)
	{
		cityName=arraCity[i].name;
		cityCode=arraCity[i].code;
		strHtml=strHtml+"<a href=\"#\" onclick=\"t('"+cityName.replace(/"/gi,"&quot;")+"','"+cityCode+"')\">"+cityName+"</a>";
	}
	document.getElementById("floorContent").innerHTML=strHtml;
}

//核心原理函数（通过Domtree追加标签的方法请求文件或数据）
function loadJs(strfile)
{
	var html_doc = document.getElementsByTagName('head').item(0);
	var scripts=html_doc.getElementsByTagName("script");	
	if(scripts.length>0){html_doc.removeChild(scripts[scripts.length-1])}
	var js = document.createElement('script');
	js.setAttribute('language', 'javascript');
	js.setAttribute('type', 'text/javascript');
	html_doc.appendChild(js)
	js.setAttribute('src', strfile);	
}


function t(strValue,strCode)
{	
	//try{window.top.getCollege(strProvince+">"+strValue,strCode)}catch(e){}
	try{window.top.getCollege(strValue,strCode)}catch(e){}
	window.top.Pclose();		
}

