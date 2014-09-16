/**
* This is a uniform interface page by which the functions you needed can be loaded.
* 
* author: GuoLei
* date: 2008-11-25
*/
var aScripts = document.getElementsByTagName('script');
for(var i=0;i<aScripts.length;i++) {
	if(aScripts[i].src.indexOf(jsLib+'form.js')>-1) {
		var urlPara = aScripts[i].src.split('?')[1].split('=')[1];
		if(urlPara) document.write('<script type="text/javascript" src="'+jsLib+'form/'+urlPara+'.js"></script>');
	}
}
