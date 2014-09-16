// code for user
document.write('<script type="text/javascript" src="/js_new/lib/application.js"></script>\n');	
document.write('<link rel=\"stylesheet\" type=\"text/css\" href=\"/ajax/js/ajax.css\" />\n');	

//design attribute
var UserArr;

var JsWait = "<img src='/ajax/images/js_wait.gif' width='14' height='14' border='0' alt=''>";
var JsError = "<img src='/ajax/images/js_error.gif' width='14' height='14' border='0' alt=''>";
var JsOk = "<img src='/ajax/images/js_ok.gif' width='14' height='14' border='0' alt=''>";

//用户注册
function checkEmailOnChange(thisValue) 
{
	jsApp.updater("EmailID","Ajax3:checkEmail","id="+thisValue,'','','',"FormSubmit");
	//jsApp.updater("EmailID","User:checkEmail",thisValue,"","","","FormSubmit");

}

//自定义提示
function waitMessage(thisValue) 
{
	$(thisValue).innerHTML = JsWait;
	//alert(JsError);
}
function errorMessage(thisValue) 
{
	$(thisValue).innerHTML = JsError+" 网络连接错误";
}
function successMessage(thisValue) 
{
	alert(thisValue);
}

