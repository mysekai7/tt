/*document.write("<script type\"text/javascript\" src=\"/js/panel/lib.js\"></script>\n");
document.write("<script type\"text/javascript\" src=\"/js/panel/panel.js\"></script>\n");*/
document.write("<script type=\"text/javascript\" src=\"/ajax/lib/proto/prototype.js\"></script>\n");

var codeaProv="";
var arraCity=[];


function onchangeProv(obj)
{
	//alert(obj.value);
	codeProv = obj.value;
	arraCity=[];
	loadJs("/js/acting/old_select/data/"+codeProv+".js?id="+new Date());
	//alert("city/"+codeProv+".js?id="+new Date());
}

function showaCity()
{
	$("city").options.length = 0;
	var i = 0;
	for(var i=0;i<arraCity.length;i++) 
	{
		$("city").options[i] = document.createElement("OPTION"); 
		$("city").options[i].text = arraCity[i].name; 
		$("city").options[i].value = arraCity[i].code;
	}
	
}

function loadJs(strfile)
{	
	var html_doc = document.getElementsByTagName('head').item(0);
	var scripts=html_doc.getElementsByTagName("script");	
	if(scripts.length>0){html_doc.removeChild(scripts[scripts.length-1])}
	var js = document.createElement('script');
	js.setAttribute('language', 'javascript');
	js.setAttribute('type', 'text/javascript');
	html_doc.appendChild(js);
	js.setAttribute('src', strfile);	
}