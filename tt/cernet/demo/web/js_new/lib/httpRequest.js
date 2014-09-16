document.write('<script type="text/javascript" src="/js_new/common.js"></script>');
/**
* Define an XMLHttpRequest class
* note: This class should be used with common.js, or it doesn't work.
* param:  url,object
* author: glzone
* date:   2008-10-14
*/
var httpRequest = Class.create();
httpRequest.prototype = {
	initialize: function() {
		var self = this;
		this.ro = null;
		if(this.ro != null) return this.ro;
		try { this.ro = new XMLHttpRequest(); }
		catch(e) {
			try { this.ro = new ActiveXObject("Microsoft.XMLHTTP"); }
			catch(e) {
				try { this.ro = new ActiveXObject('Msxml2.XMLHTTP'); }
				catch(e) {}
			}
		}
		this.ro.onreadystatechange = function() {
			if (self.ro.readyState == 4) {
				if(self.tl) clearInterval(self.tl);
				if(self.ro.status == 200) self.success();
				else self.failure();
			}else self.loading();
		}
	},
	request: function(url,obj) {
		obj.method = obj.method || 'get';
		url = obj.param?url+(url.indexOf('?')>-1?'&':'?')+obj.param:url;
		try {
			this.ro.open(obj.method,url,true);
			if(obj.method == 'post')
				this.ro.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			//this.param = this.param || obj.param;alert(this.param);
			this.s = obj.onSuccess;
			this.l = obj.onLoading;
			this.f = obj.onFailure;
			this.ele = obj.ele;
			if(obj.timeout) {
				this.t = 1000*obj.timeout + (new Date()).getTime();
				this.tl = setInterval(this.listen,500);
			}
			this.ro.send(this.param?this.param:null);
		}catch(e) {}
	},
	success: function() {
		if(typeof this.s == 'string') eval(this.s)(this.ro,this,ele);
		if(typeof this.s == 'function') (this.s)(this.ro,this.ele);
		else return;
	},
	loading: function() {
		if(typeof this.l == 'string') eval(this.l)(this.ro,this,ele);
		else if(typeof this.l == 'function') (this.l)(this.ele);
		else return;
	},
	failure: function() {
		if(typeof this.f == 'string') eval(this.f)(this.ro,this,ele);
		else if(typeof this.f == 'function') (this.f)(this.ro,this.ele);
		else return;
	},
	serialize: function(fm) {
		var res = [];
		var eles = $(fm).elements;
		for(var i=0;i<eles.length;i++)
			res[i] = encodeURIComponent(eles[i].name)+'='+encodeURIComponent(eles[i].value);
		this.param = res.join('&');
	},
	listen: function() {
		if(this.tl)
			if((new Date()).getTime()>this.t && this.ro.readyState!=4) {
				this.ro.abort();
				clearInterval(this.tl);
			}
	},
	alert: function(test) {
		document.write(unescape(this.serialize(test)));
	}
	
}
