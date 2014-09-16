/**
* 检测输入字数
* 说明：中文与英文在javascript中都只占一个字符
* 
* author: GuoLei
* date: 2008-09-25
*/
function chkLength(obj,mx,mi,msg1,msg2)
{
	mx = mx||10;
	mi = mi||1;
	if($(obj).value.length > mx) {alert(msg1);return false;}
	else if($(obj).value.match(/^\s+$/) || $(obj).value.length < mi) {alert(msg2);return false;}
	return true;
}
