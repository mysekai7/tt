document.write('<script type="text/javascript" src="/js_new/lib/httpRequest.js"></script>');

var jsApp = {
	/**
	 * 变量定义
	 * 
	 */
	msgTimer:null,
	msgDelay:50000,
	//getway:"http://login.1cm.mobi/ajax/js.php?Mod=",
	getway:"/ajax/js.php?Mod=",

	/**
	 * 以POST方式请求
	 * 
	 * 参数: RequestMod   string    远程请求对象方法,如:HelloWorld:Print
	 * 参数: RequestData  string    远程请求参数,如:
	 * 参数: SuccessCall  function  远程请求成功回调函数,如:onSuccess
	 * 参数: FailureCall  function  远程请求失败回调函数,如:onFailure
	 * 参数: WaitingCall  function  远程请求等待回调函数,如:onWaiting
	 * 参数: Async        boolean   远程异步请求,如:true
	 * 返回值:void
	 */
	post:function(RequestMod, RequestData, SuccessCall, FailureCall, WaitingCall, DisabledBtn, formId)
	{
		jsApp.request(RequestMod, RequestData, SuccessCall, FailureCall, WaitingCall,DisabledBtn, "post", formId);
	},


	/**
	 * 以GET方式请求
	 * 
	 * 参数: RequestMod   string    远程请求对象方法,如:HelloWorld:Print
	 * 参数: RequestData  string    远程请求参数,如:
	 * 参数: SuccessCall  function  远程请求成功回调函数,如:onSuccess
	 * 参数: FailureCall  function  远程请求失败回调函数,如:onFailure
	 * 参数: WaitingCall  function  远程请求等待回调函数,如:onWaiting
	 * 参数: Async        boolean   远程异步请求,如:true
	 * 返回值:void
	 */
	get:function(RequestMod, RequestData, SuccessCall, FailureCall, WaitingCall,DisabledBtn)
	{
		jsApp.request(RequestMod, RequestData, SuccessCall, FailureCall, WaitingCall,DisabledBtn, "get");
	},


	/**
	 * Ajax请求
	 * 
	 * 参数: RequestMod   string    远程请求对象方法,如:HelloWorld:Print
	 * 参数: RequestData  string    远程请求参数,如:
	 * 参数: SuccessCall  function  远程请求成功回调函数,如:onSuccess
	 * 参数: FailureCall  function  远程请求失败回调函数,如:onFailure
	 * 参数: WaitingCall  function  远程请求等待回调函数,如:onWaiting
	 * 参数: Async        boolean   远程异步请求,如:true<默认>,false
	 * 参数: Method       string   远程请求方法,如:get<默认>,post
	 * 返回值:void
	 */
	request:function(RequestMod, RequestData, SuccessCall, FailureCall, WaitingCall, DisabledBtn, Method, formId)
	{
		//alert("debug:request start...");
		var xmlRequest = new httpRequest();
		
		if(typeof SuccessCall != 'function') SuccessCall = jsApp.onSuccess;
		if(typeof WaitingCall != 'function') WaitingCall = jsApp.onWaiting;
		if(typeof FailureCall != 'function') FailureCall = jsApp.onFailure;
		
		if(!Method || typeof Method == 'undefined') Method = 'get';
		if(Method == 'post' && typeof formId != 'undefined') xmlRequest.serialize(formId);

		xmlRequest.request(jsApp.getway + RequestMod, {
			method: Method,
			param: RequestData,
			onSuccess: function(response) {
				jsApp.hide();
				SuccessCall(response.responseText);
				jsApp.disable(DisabledBtn);
			},
			onFailure: function(response) {
				jsApp.hide();
				FailureCall(response.responseText);
				jsApp.disable(DisabledBtn);
			},
			onLoading: function() {
				jsApp.disable(DisabledBtn,true);
				WaitingCall();
			}
		});
	},
	
	/**
	 * Ajax-Updater请求
	 * 
	 * 参数: Element      string    客户端对象,如:toUpdate
	 * 参数: RequestMod   string    远程请求对象方法,如:HelloWorld:Print
	 * 参数: RequestData  string    远程请求参数,如:
	 * 参数: SuccessCall  function  远程请求成功回调函数,如:onSuccess
	 * 参数: FailureCall  function  远程请求失败回调函数,如:onFailure
	 * 参数: WaitingCall  function  远程请求等待回调函数,如:onWaiting
	 * 参数: Async        boolean   远程异步请求,如:true<默认>,false
	 * 参数: Method       string   远程请求方法,如:get<默认>,post
	 * 返回值:void
	 */
	updater:function(Element, RequestMod, RequestData, SuccessCall, FailureCall, WaitingCall,DisabledBtn, Method, formId)
	{
		//alert("debug:request start...");
		var xmlRequest = new httpRequest();
		
		if(typeof SuccessCall != 'function') SuccessCall = jsApp.onSuccess;
		if(typeof WaitingCall != 'function') WaitingCall = jsApp.onWaiting;
		if(typeof FailureCall != 'function') FailureCall = jsApp.onFailure;
		
		if(!Method || typeof Method == 'undefined') Method = 'get';

		//alert("debug:request start...");

		xmlRequest.request(jsApp.getway + RequestMod, {
			method: Method,
			param: RequestData,
			onSuccess: function(response) {
				jsApp.hide();
				try{
					if($(Element).value != response.responseText) $(Element).value = response.responseText;
				}catch(e){e.description}
					SuccessCall(response.responseText);
					jsApp.disable(DisabledBtn);
			},
			onFailure: function(response) {
				jsApp.hide();
				FailureCall(response.responseText);
				jsApp.disable(DisabledBtn);
			},
			onLoading: function() {
				jsApp.disable(DisabledBtn,true);
				WaitingCall(Element);
			}
		});
	},
	
	/**
	 * Ajax请求默认错误处理
	 * 
	 * 参数: responseText   string	远程请求返回数据,如:["Code":1000,"Message":"连接失败"]
	 * 返回值:void
	 */
	onFailure: function(responseText)
	{
		jsApp.message("请求失败...", jsApp.msgType.ERROR);
	},


	/**
	 * Ajax请求默认成功处理
	 * 
	 * 参数: responseText   string	远程请求返回数据,如:["A":1,"B":2]
	 * 返回值:void
	 */
	onSuccess:function(responseText)
	{
		jsApp.message(responseText, jsApp.msgType.SUCCESS);
	},


	/**
	 * Ajax请求默认等待处理
	 * 
	 * 参数: response   string	远程请求响应对象request
	 * 返回值:void
	 */
	onWaiting:function(dataText)
	{
		jsApp.message("请稍候...",jsApp.msgType.LOADING);
	},

	/**
	 * Ajax请求显示信息
	 * 
	 * 参数: 信息 类型loading warning error 持续性true false
	 * 返回值:void
	 */
	msgType:{"INFO":"info", "LOADING":"loading", "ERROR":"error", "SUCCESS":"success"},
	message:function(msg, msgtype)
	{
		if (msgtype) msg = '<div class="'+msgtype+'">'+msg+'</div>';
		if (msgtype != jsApp.msgType.LOADING)
		{
			$("appdiv").ondblclick = function(){ 
				jsApp.hide();
				return true; 
			};
		}
		if (jsApp.msgTimer)clearTimeout(jsApp.msgTimer);
		$("appdiv").style.display = 'block';
		$("appdiv").innerHTML = msg;
		if (!isNaN(jsApp.msgDelay))
		{
			jsApp.msgTimer = setTimeout('jsApp.hide()', jsApp.msgDelay);
		};
		//jsApp.msgDelay = 5000;
	},

	/**
	 * Ajax请求隐藏信息
	 * 
	 * 参数: 
	 * 返回值:void
	 */  
	hide:function()
	{
		if ($("appdiv"))
		{
			$("appdiv").style.display = 'none';
			$("appdiv").onmousedown = null;
		}
	},

	/**
	 * Ajax请求隐藏
	 * 
	 * 参数: 
	 * 返回值:void
	 */
	disable:function (DisabledBtn,Status)
	{
		if(typeof DisabledBtn != 'undefined')
		{
			if(typeof Status != 'boolean')Status = false;
			$(DisabledBtn).disabled = Status;
		}
	},

	/**
	 * Ajax请求显示信息
	 * 
	 * 参数:
	 * 返回值:
	 */
	debug:function(msg)
	{
		jsApp.msgDelay = 100000000;

		cont = '<div class="debug"><pre>'+msg+'</pre></div>';
		$("appdiv").style.display = 'block';
		$("appdiv").innerHTML = cont;
		$("appdiv").ondblclick = function(){ 
			jsApp.hide();
			return true; 
		};
	},
	
//==========================以下内容暂时不实现===============================
	//浏览历史处理(是否需要)
	history:
	{
		//后退
		back:function()
		{

		},
		//前进
		forward:function()
		{

		}
	}//end history
}
var jsAppWO = window.onload;
window.onload = function()
{
	try{
	jsAppWO();
	}catch(e){}
	window.onscroll = function()
	{
		$("appdiv").style.top = document.body.scrollTop;
	}
}