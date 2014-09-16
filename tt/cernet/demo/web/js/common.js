var Class = {
	create: function() {
		return function() {
			this.initialize.apply(this, arguments);
		}
	}
}

/**
* check which browser is used
*
* return: string
*/
function checkBrowser()
{
	var agentArr = ['msie','firefox'];
	var agent = navigator.userAgent;
	for(var i=0;i<agentArr.length;i++) {
		var reg = new RegExp(agentArr[i]);
		if(reg.test(agent.toLowerCase())) return agentArr[i];
	}
	return false;
}
/**
* check if some browser is used
*
* return: boolean
*/
function isBrowser(type)
{
	var reg = new RegExp(type.toLowerCase());
	var agent = navigator.userAgent;
	return reg.test(agent.toLowerCase());
}
/**
* rewrite function $()
* 
* author: GuoLei
* date:   2008-09-17
*/
function $()
{
	var elements = new Array();
	for(var i=0;i<arguments.length;i++) {
		var element = arguments[i];
		if(typeof element == "string")
			element = document.getElementById(element) || document.getElementsByName(element)[0];
		if(arguments.length == 1)
			return element;
		elements.push(element);
	}
	return elements;
}

/**
* 删除数组中重复的值
* 返回：array
*/
Array.prototype.unique = function()
{
    var i = 0, j = 0;
    while (undefined !== this[i]) {
        j = i + 1;
        while(undefined !== this[j]) {
            if (this[i] === this[j]) this.splice(j, 1);
            ++j;
        }
        ++i;
    }
    return this;
}
/**
* 判断数组中是否有重复的值
* 返回: boolean
*/
Array.prototype.isUnique = function()
{
    var i = 0, j = 0;
    while (undefined !== this[i]) {
        j = i + 1;
        while(undefined !== this[j]) {
            if (this[i] === this[j]) return false;
            ++j;
        }
        ++i;
    }
    return true;
}

//选择全部
function __all(form)
{
	//alert("abc");
	try{
	if(typeof form == 'string') var form = document.forms[form];
	for (var i=0;i<form.elements.length;i++)
	{
		var e = form.elements[i];
		if (e.name != 'chkall')
			e.checked = form.chkall.checked;
	}
	}catch(e){}
}


//确定选择
function doActions(formId,url)
{
	//检查是否有选项
	var flag = false;
	for(var i=0;i<cks.length;i++)
		if(cks[i].checked) flag = true;
	if(!flag) {
		window.alert("请至少选择一个记录");
		return;
	}
	if(!window.confirm("您确定要执行操作吗？")) 
		return;
	form = document.forms[formId];
	form.action = url;
	form.submit();
}


/**
* 将地址复制到剪贴板 （当前只适用于IE）
* 
* author: GuoLei
* date: 2008-09-11
*/
function copyToClipBoard(href)
{
	try {
		var clipBoardContent="";
		//clipBoardContent+=document.title;
		clipBoardContent+="";
		if(typeof href == "undefined")
			clipBoardContent+=this.location.href;
		else
			clipBoardContent+=href;
		window.clipboardData.setData("Text",clipBoardContent);
		alert("复制成功，请粘贴到你的QQ/MSN上推荐给你的好友");
	}catch(e) {
		alert("您的浏览器不支持“复制到剪贴板功能”，请使用键盘快捷键(Ctrl+C)来完成");
	}
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

/**
* 检测输入字数
* 
* author: GuoLei
* date: 2008-09-25
*/
function chkLength(obj,mx,mi,msg1,msg2)
{
	mx = mx||10;
	mi = mi||1;
	if($(obj).value.match(/^\s+$/) || $(obj).value.length < mi) {alert(msg2);return false;}
	else if($(obj).value.length > mx) {alert(msg1);return false;}
	return true;
}

/**
* 标签检测函数
* 要求：标签用空格或者逗号（半角或全角）分割
*      标签数最多tagNum个
*      每个字数不超过perTag个字
* author: GuoLei
* date: 2008-09-24
*/
function checkTag(obj,tagNum,perTag)
{
	var tag = obj.value.replace(/\s*$/,"");
	if(!tag.match(/^[\w\u4e00-\u9fa5]+((\s?|,?|，?)?[\w\u4e00-\u9fa5]+)*$/)) {
		alert("标签格式错误！");
		return false;
	}
	var tags = tag.replace(/[\s,，]/g,",").split(",");
	if(!tags.isUnique()) { alert("标签有重复"); return false; }
	tagNum = tagNum|8;
	perTag = perTag|20;
	if(tags.length > tagNum) { alert("标签数不能超过"+tagNum+"个"); return false; }
	for(var i=0;i<tags.length;i++)
		if(tags[i].length > perTag) { alert("每个标签不能超过"+perTag+"个字"); return false; }
	return true;
}

/**
* 选取标签
*/
function assignTag(e)
{
	var tag = $("tag").value;
	if(tag.length+e.innerHTML.length > 20) {alert("标签不能超过20个字"); return;}
	var tags = tag.replace(/[\s,，]/g,",").split(",");
	for(var i=0;i<tags.length;i++)
		if(tags[i]==e.innerHTML) {alert("标签重复"); return;}
	$("tag").value += (tag.length>0?" ":"")+e.innerHTML;
}
