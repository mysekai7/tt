/**
* Initialize an object to login without freshing the page. 
* param: url,object
* return: 
* author: glzone
* date: 2008-10-14
*/
var passport = Class.create();
passport.prototype = {
	initialize: function() {
		this.rootDomain = '1cm.mobi';
		this.signCook = 'REG1101';
		this.errCook = 'LoginError';
		this.timeHandle = null;
		this.msg = {'login':'通行证 | {**}，已登录！<img border="0" src="/images/letter.gif"> | <a href="#" onclick="passport.logout()">退出</a>',
			        'logout':'&nbsp;&nbsp; 用户名 <input type="text" name="email">&nbsp;&nbsp;密&nbsp;&nbsp;码 <input type="text" name="passwd">&nbsp;&nbsp;<input type="hidden" name="subcode" value="1101"><input type="submit" name="_login" value=" 登 录 " class="button" id="_login" onClick="passport.doListen()" />',
			        'error':''};
		this.tarFm = null;
		this.msgContainer = null;
		window.p = this;
	},
	listenCookie: function() {
		var sCookie = this.getCookie(this.signCook);
		if(sCookie=='Y') {
			this.msg['login'] = this.msg['login'].replace(/\{\*.*\*\}/g,decodeURI(this.getCookie('eol_name')));
			this.showMsg('login');
			$(this.msgContainer).className = 'msgDiv';
		}else if(this.getCookie('LoginError')) {
			if(!this.msg.error) this.msg['error'] = this.getCookie('LoginError');
			this.showMsg('error');
			$(this.msgContainer).className = 'msgDiv';
		}
	},
	doListen: function() {
		this.tarFm = this.tarFm || '1';
		//alert(Passport.tarFm);
		this.timeHandle = setInterval('this.p.listenCookie()',500);
	},
	showMsg: function(signal) {
		if(this.timeHandle) clearInterval(this.timeHandle);
		if(typeof this.msg[signal] == 'string') {
			if(typeof this.msgContainer == 'string') $(this.msgContainer).innerHTML = this.msg[signal].replace(/\{\*.*\*\}/g,decodeURI(this.getCookie('eol_name')));
			else if(typeof this.msgContainer == 'array') {
				for(var i=0;i<this.msgContainer.length;i++)
					this.msgContainer[i].innerHTML = this.msg[signal];
			}
			
		}else {
			//$('loginSpan'+Passport.tarFm).innerHTML = Passport.msg || $('loginSpan'+Passport.tarFm).innerHTML+'&nbsp;<font color="red">'+decodeURI(Passport.getCookie('LoginError')).replace(/\+/g,'')+'</font>';
			//alert(decodeURI(this.getCookie('LoginError')).replace(/\+/g,''));
			(this.msg[signal])();
		}
	},
	isLogin: function(el) {
		if(this.getCookie(this.signCook)=='Y') {
			if($(el)) $(el).innerHTML = this.msg['login'].replace(/\{\*.*\*\}/g,decodeURI(this.getCookie('eol_name')));
			else this.showMsg('login');
			$(this.msgContainer).className = 'msgDiv';
		}/*else {
			if($(el)) $(el).innerHTML = this.msg.logout;
			else {alert('xxxxxxxxxx');this.showMsg('logout');}
		}*/
		
	},
	logout: function() {
		$('logoutform').submit();
		this.showMsg('logout');
		$(this.msgContainer).className = '';
		this.msg = null;
		this.delCookie(this.signCook,'/',this.rootDomain);
		this.delCookie(this.errCook,'/',this.rootDomain);
	},
	getCookie: function(cookie_name) {
		var aCookie = document.cookie.split(";");
		try {
			for (var i=0; i<aCookie.length; i++) {
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
