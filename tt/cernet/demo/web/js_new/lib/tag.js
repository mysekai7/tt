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

/**
* 标签检测函数
* 要求：标签用空格或者逗号（半角或全角）分割
*      标签数最多tagNum个
*      每个字数不超过perTag个字
* 注意：此函数依赖于函数Array.prototype.isUnique
* author: glzone
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
