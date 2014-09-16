// code for user
document.write('<script type="text/javascript" src="/js_new/lib/application.js"></script>\n');	
document.write('<link rel=\"stylesheet\" type=\"text/css\" href=\"/ajax/js/ajax.css\" />\n');	

//design attribute

//用户注册
function createUserOnSubmit(thisValue) 
{
	//var PostData = Form.serialize("regform");
	jsApp.post("Ajax2:createUser",'',successMessage,"","","FormSubmit",'regform');
}

//自定义提示
function successMessage(thisValue) 
{
	//直接反馈提示信息
	jsApp.message(thisValue,jsApp.msgType.INFO);

}

//用户注册
function createUserOnSubmit2(thisValue) 
{
	//var PostData = Form.serialize("regform");
	jsApp.post("Ajax2:createUser2",'',successMessage2,"","","FormSubmit2",'regform');
}

//自定义提示
function successMessage2(thisValue) 
{
	//根据返回结果进行显示，返回结果为数组、对象等
	eval("returnValue="+thisValue);
	if(returnValue.result != true)
	{
		jsApp.message(returnValue.message,jsApp.msgType.ERROR);

	}
	else
	{
		//alert(returnValue.message);
		//var forMessage = returnValue.message;
		for(var prop  in returnValue.message) 
		{
			alert(prop+":"+returnValue.message[prop]);
			
		}
		jsApp.message("成功提交数据",jsApp.msgType.SUCCESS);

	}
	//alert(returnValue.result);
	//alert(returnValue[1]);

	//jsApp.message(returnValue,jsApp.msgType.INFO);

}