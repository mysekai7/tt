/**
*Ajax请求统一入口
*author: GuoLei
*
*date: 2008-10-21
*/
//document.write('<link rel=\"stylesheet\" type=\"text/css\" href=\"/ajax/lib/ajax.css\" />\n');	
document.write('<script type="text/javascript" src="/js/httpRequest.js"></script>\n');	

var ajaxApp = {

	/**
	 * 变量定义
	 * 
	 */
	msgTimer:null,
	msgDelay:50000,
	getway:"/interface/sublogin.php",

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
	post:function(RequestUrl, RequestData, SuccessCall, FailureCall, WaitingCall,DisabledBtn, RequestForm)
	{
		ajaxApp.request(RequestUrl, RequestData, SuccessCall, FailureCall, WaitingCall,DisabledBtn, "post", RequestForm);
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
	get:function(RequestUrl, RequestData, SuccessCall, FailureCall, WaitingCall,DisabledBtn, RequestForm)
	{
		ajaxApp.request(RequestUrl, RequestData, SuccessCall, FailureCall, WaitingCall,DisabledBtn, "get", RequestForm);
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
	request:function(RequestUrl, RequestData, SuccessCall, FailureCall, WaitingCall, DisabledBtn, Method, RequestForm)
	{
		//alert("debug:request start...");
		
		if(typeof SuccessCall != 'function') SuccessCall = ajaxApp.onSuccess;
		if(typeof WaitingCall != 'function') WaitingCall = ajaxApp.onWaiting;
		if(typeof FailureCall != 'function') FailureCall = ajaxApp.onFailure;
		
		if(!Method || typeof Method == 'undefined') Method = 'get';

		var xmlHttp = new httpRequest();
		if(RequestForm) xmlHttp.serialize(RequestForm);
		xmlHttp.request('/Ajax6_beta.class.php',{
						method:'post',
					    param:'',
					    timeout: 5000,
					    onSuccess: function(res) {
							$('login_info').innerHTML='信息成功提交，提交的信息为：'+res.responseText;
							$('login_info').className = 'msgDiv';
							$('login_info').ondblclick = function() {
								window.document.location.reload();
							}
					    },
						onLoading: function() {
							
						},
						onFailure: function() {
							
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
		ajaxApp.message("请求失败...", ajaxApp.msgType.ERROR);
	},


	/**
	 * Ajax请求默认成功处理
	 * 
	 * 参数: responseText   string	远程请求返回数据,如:["A":1,"B":2]
	 * 返回值:void
	 */
	onSuccess:function(responseText)
	{
		ajaxApp.message("请求成功...", ajaxApp.msgType.SUCCESS);
	},


	/**
	 * Ajax请求默认等待处理
	 * 
	 * 参数: response   string	远程请求响应对象request
	 * 返回值:void
	 */
	onWaiting:function(dataText)
	{
		ajaxApp.message("请稍候...",ajaxApp.msgType.LOADING);
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
		if (msgtype != ajaxApp.msgType.LOADING)
		{
			$("appdiv").ondblclick = function(){ 
				ajaxApp.hide();
				return true; 
			};
		}
		if (ajaxApp.msgTimer)clearTimeout(ajaxApp.msgTimer);
		$("appdiv").style.display = 'block';
		$("appdiv").innerHTML = msg;
		if (!isNaN(ajaxApp.msgDelay))
		{
			ajaxApp.msgTimer = setTimeout('ajaxApp.hide()', ajaxApp.msgDelay);
		};
		//ajaxApp.msgDelay = 5000;
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
		ajaxApp.msgDelay = 100000000;

		cont = '<div class="debug"><pre>'+msg+'</pre></div>';
		$("appdiv").style.display = 'block';
		$("appdiv").innerHTML = cont;
		$("appdiv").ondblclick = function(){ 
			ajaxApp.hide();
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
var ajaxAppWO = window.onload;
window.onload = function()
{
	try{
	ajaxAppWO();
	}catch(e){}
	window.onscroll = function()
	{
		$("appdiv").style.top = document.body.scrollTop;
	}
}