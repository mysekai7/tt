// code for user
document.write('<script type="text/javascript" src="/js_new/lib/application.js"></script>\n');	
document.write('<link rel=\"stylesheet\" type=\"text/css\" href=\"/ajax/js/ajax.css\" />\n');	

//design attribute

//用户注册
function createUserOnSubmit(thisValue) 
{
	//var PostData = Form.serialize("regform");
	//alert("abc");
	jsApp.post("Ajax4:createUser",'',successMessage,"","","FormSubmit",'regform');
	//alert("abck");
}

//自定义提示
function successMessage(thisValue) 
{
	//alert("abck:"+thisValue);
	jsApp.message(thisValue,jsApp.msgType.INFO);

	jsApp.disable("FormSubmit",false);


}