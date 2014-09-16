/**
* create xmlHttpRequest object
* param:
* return: object
*
function createXmlHttp(url,succ,wait,fail,dis)
{
	var oRequest = new httpRequest(url,succ,wait,fail,dis);
}
*/
/**
* define a xmlHttpRequest class
* param:  url,object
* return: 
* author: GuoLei
* date:   2008-10-14
*/
function httpRequest() { this.ro = null; this.initRequest(); }
httpRequest.prototype = {
	initRequest: function() {
		var self = this;
		if(this.ro != null) return this.ro;
		try {
			this.ro = new XMLHttpRequest();
		}catch(e) {
			this.ro = new ActiveXObject("Microsoft.XMLHTTP");
		}
		this.ro.onreadystatechange = function() {
			if (self.ro.readyState == 4) {
				clearInterval(self.tl);
				if(self.ro.status == 200)
					self.success();
				else self.failure();
			}else 
				self.loading();
			
		}
	
	},
	request: function(url,obj) {
		obj.method = obj.method || 'get';
		url = obj.param?url+'?'+obj.param:url;
		try {
			this.ro.open(obj.method,url,true);
			if(obj.method == 'post')
				this.ro.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			//this.param = this.param || obj.param;alert(this.param);
			this.s = obj.onSuccess;
			this.l = obj.onLoading;
			this.f = obj.onFailure;
			if(obj.timeout) {
				this.t = 1000*obj.timeout + (new Date()).getTime();
				this.tl = setInterval(this.listen,500);
			}
			//alert(this.tl);
			this.ro.send(this.param?this.param:null);
		}catch(e) {}
		
	},
	success: function() {
		//alert(this.tl);
		if(typeof this.s == 'function')
			(this.s)(this.ro);
		else return;
	},
	loading: function() {
		if(typeof this.l == 'stirng')
			eval(this.l)();
		else if(typeof this.l == 'function')
			(this.l)();
		else return;
	},
	failure: function() {
		if(typeof this.f == 'stirng')
			eval(this.f)();
		else if(typeof this.f == 'function')
			(this.f)(this.ro);
		else return;
	},
	serialize: function(fm) {
		//alert('xxxxxxxxx');
		var res = [];
		var eles = $(fm).elements;
		for(var i=0;i<eles.length;i++)
			res[i] = eles[i].name+'='+eles[i].value;
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
		//alert(this.serialize(test));
		document.write(unescape(this.serialize(test)));
	}
	
}

/**
* pack a function with interfaces provided by class httpRequest 
* 
* author: GuoLei
* date:   2008-09-17
*/
function getAjaxResponse(url, data, method, form, button)
{
	var o = new httpRequest();
	o.serialize('regform');
	o.request(url,{
			  method: method,
			  param: data,
			  timeout: 5000,
			  onSuccess: function(res) {
				  $('login_info').innerHTML='信息成功提交，提交的信息为：'+res.responseText;
				  $('login_info').className = 'msgDiv';
				  $('login_info').ondblclick = function() {
					  window.document.location.reload();
				}
			  },
			  onLoading: function() {
				  if(button)
					 $(button).disabled = true;
			  }
	});
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