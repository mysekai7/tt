/**
* 通用无刷新登录
* 说明：此程序用于简单部署到首页头部
* author: glzone
* date: 2008-12-19 am
*/

/**
* 以下变量需根据所应用的系统进行适当修改
*/
//域名及cookie名设置
var rootDomain = '1cm.mobi';
var loginUrl = 'http://login.'+rootDomain;
var centerUrl = 'http://center.'+rootDomain;
var signCookName = 'REG1101';
var errorCookName = 'LoginError';
//登录框html配置
var loginNo = '<form method="get" id="loginForm" action="'+loginUrl+'/interface/sublogin.php" target="loginifr" class="loginForm"><span id="loginSpan" style="padding-top:10px;">用户名 <input type="text" name="email" style="height:14px;width:60px" size="8"> 密码 <input type="password" name="passwd" size="8"  style="height:14px;width:60px" > <input type="hidden" name="subcode" value="1101"> <input type="submit" name="_login" value="登录" id="_login" onClick="Passport.doListen()"  style="height:20px;"/> <a href="'+loginUrl+'/register.php" target="_blank"">注册</a></span></form> &nbsp;<a href="#" target="_blank">帮助</a>';
var loginYes = '，已登录！ <a href="#" onclick="Passport.logout();return false;">退出</a> &nbsp;<a href="'+centerUrl+'/space.php" target="_blank">我的空间</a> &nbsp;<a href="#" target="_blank">帮助</a>';

/**
* 登录类
* 注：以下主程序代码，不能改动
*/
var Passport = {
	signCook: signCookName,
	errCook: errorCookName,
	timeHandle: null,
	infoMessage:null,
	msg: {'login':loginYes,'logout':loginNo,'error':''},
	tarFm:'',
	listenCookie: function() {
		var sCookie = Passport.getCookie(Passport.signCook);
		if(sCookie=='Y') {
			Passport.showMsg('login');
		}else if(Passport.getCookie('LoginError')) {
			if(!Passport.msg.error) Passport.msg['error'] = decodeURI(Passport.getCookie('LoginError')).replace(/\+/g,'');
			Passport.showMsg('error');
		}
	},
	doListen: function() {
		//Passport.tarFm = Passport.tarFm || '1';
		Passport.timeHandle = setInterval('Passport.listenCookie()',500);
	},
	showMsg: function(signal) {
		if(Passport.timeHandle) clearInterval(Passport.timeHandle);
		if(signal == 'login') $('loginDiv').innerHTML = '<form id="logoutform" target = "loginifr" method="post" action="'+loginUrl+'/interface/sublogout.php" class="loginForm">'+decodeURI(Passport.getCookie('eol_name'))+Passport.msg['login']+'</form>';
		else $('loginDiv').innerHTML = Passport.msg['logout']+(signal == 'error'?Passport.msg['error']:'');
	},
	isLogin: function() {
		Passport.showMsg(Passport.getCookie(Passport.signCook)=='Y'?'login':'logout');
	},
	logout: function() {
		Passport.delCookie(Passport.signCook,'/',rootDomain);
		Passport.delCookie(Passport.errCook,'/',rootDomain);
		$('logoutform').submit();
		Passport.showMsg('logout');
	},
	getCookie: function(cookie_name) {
		var aCookie = document.cookie.split(";");
		try {
			for(var i=0; i<aCookie.length; i++) {
				var aCrumb = aCookie[i].split("=");//alert(aCrumb[0]+' '+aCrumb[1]);
				aCrumb[0] = aCrumb[0].replace(/^\s+/,'').replace(/\s+$/,'');
				if (aCrumb[0] == cookie_name) return aCrumb[1].replace(/^\s+/,'').replace(/\s+$/,'');
			}
		}catch(e){ return false;}
		return false;
	},
	delCookie: function(name,path,domain) {
		document.cookie = name + "=" + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}
//简化调用函数
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

