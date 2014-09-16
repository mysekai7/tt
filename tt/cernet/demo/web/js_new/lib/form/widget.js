/**
* 复选框全（不）选
* 
* author: GuoLei
* date: 2008-11-19
*/
function checkAll(allId,itemName)
{
	var items = document.getElementsByName(itemName);
	for(var i=0;i<items.length;i++) items[i].checked = $(allId).checked;
}
/**
* 选择一个复选框
* 
* author: GuoLei
* date: 2008-11-19
*/
function checkOne(allId,itemName)
{
	var items = document.getElementsByName(itemName);
	for(var i=0;i<items.length;i++) if(!items[i].checked) break;
	$(allId).checked = items[i]?items[i].checked:true;
}


/**
* 确定选择
*
* author: GuoLei
* date: 2008-11-19
*/
function doActions(formId,url)
{
	//检查是否有选项
	var flag = false;
	for(var i=0;i<cks.length;i++)
		flag = flag || cks[i].checked;
	if(!flag) {
		window.alert("请至少选择一个记录");
		return;
	}
	if(!window.confirm("您确定要执行操作吗？")) return;
	form = document.forms[formId];
	form.action = url;
	form.submit();
}

/**
* 选中文本框，提示文字自动隐藏
* 
* author: GuoLei
* date: 2008-09-11
*/
function focusInput(obj,text)
{
	obj.value = "";
	obj.style.color = "#000000";
}

/**
* 不选文本框，提示文字自动显示
* 
* author: GuoLei
* date: 2008-09-11
*/
function blurInput(obj,text)
{
	if(obj.value === "") {
		obj.value = text?text:"请输入内容";
		obj.style.color = "#cccccc";
	}
}


