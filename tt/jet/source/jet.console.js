/**
 * JET (Javascript Extension Tools)
 * Copyright (c) 2009, KDV.cn, All rights reserved.
 * Code licensed under the BSD License:
 * http://developer.kdv.cn/jet/license.txt
 *
 * @fileOverview Jet!
 * @version	1.0
 * @author	Kinvix(<a href="mailto:Kinvix@gmail.com">Kinvix@gmail.com</a>)
 * @description
 *
 */

/**
 * @description
 * Package: jet.console
 *
 * Need package:
 * jet.core.js
 * jet.string.js
 * jet.http.js
 *
 */


/**
 * 10.[Browser part]: console 控制台
 */
Jet().$package(function(J){
	var $ = J.dom.id,
		$D = J.dom,
		$E = J.event,
		$H = J.http;



	var _open=window.open;
	var open=function(sURL, sName, sFeatures, bReplace){
		if(sName == undefined){
			sName="_blank";
		};
		if(sFeatures == undefined){
			sFeatures="";
		};
		if(bReplace == undefined){
			bReplace=false;
		};

		var win=_open(sURL, sName, sFeatures, bReplace);
		if(!win){
			J.out("天啦！你的机器上竟然有软件拦截弹出窗口耶~~~");
			return false;
		}

		return true;
	};
	window.open = open;







	J.config={
		debugLevel: 1
	};





	/**
	 * Jet 控制台，用于显示调试信息以及进行一些简单的脚本调试等操作。可以配合 J.debug J.runtime 来进行数据显示和调试.
	 *
	 * @type console
	 * @namespace
	 * @name console
	 */
	J.console = {
		/**
		 * 在console里显示信息
		 *
		 * @param {String} msg 信息
		 * @param {Number} type 信息类型, 可以参考 J.console.TYPE <br/> TYPE:{<br/>
		 *            &nbsp;&nbsp;&nbsp; DEBUG:0,<br/> &nbsp;&nbsp;&nbsp; ERROR:1,<br/>
		 *            &nbsp;&nbsp;&nbsp; WARNING:2,<br/> &nbsp;&nbsp;&nbsp; INFO:3,<br/>
		 *            &nbsp;&nbsp;&nbsp; PROFILE:4<br/> }<br/>
		 *
		 * @example
		 * J.console.print("这里是提示信息",J.console.TYPE.ERROR)
		 */
		print : function(msg, type){
			if(J.console.log){
				J.console.log((type === 4 ? (new Date() + ":") : "") + msg);
			}
		}
	};

	/**
	 * 数据监控和上报系统
	 *
	 * @ignore
	 * @type J.Report
	 */
	J.Report = {
		/**
		 * 数据分析上报接口
		 *
		 * @param {string} source 数据来源
		 * @param {number} type 数据返回结果,<br/> <br/>1 加载完成 <br/>2 加载失败 <br/>3 数据异常
		 *            无法解释/截断 <br/>4 速度超时 <br/>5 访问无权限 <br/> 对应的转义符是 %status%
		 *
		 * @param {string} url 请求的数据路径
		 * @param {number} time 响应时间
		 * @ignore
		 */
		receive : J.emptyFunc,

		/**
		 * 添加监控规则,
		 *
		 * @param {String} url 需要监控的url
		 * @param {String} reportUrl 出现异常后上报的地址 上报地址有几个变量替换 <br/>%status% 数据状态
		 *            <br/>%percent% 统计百分比 <br/>%url% 监听的url地址,自动encode
		 *            <br/>%fullUrl% 监听的完整的url地址，包括这个地址请求时所带 <br/>%source% js处理来源
		 *            <br/>%time% 请求花掉的时间 <br/>%scale% 比例,通常是指 1:n 其中的 n 就是 %scale%
		 *
		 * <br/>
		 * @example
		 * J.Report.addRule("http://imgcache.qq.com/data2.js","http://imgcache.qq.com/ok?flag1=3234&flag2=%status%&1=%percent%&flag4=123456");
		 * @ignore
		 */
		addRule : J.emptyFunc
	};





	J.extend(J.console,
	/**
	 * @lends console
	 */
	{
		/**
		 * 是否进行了初始化
		 *
		 * @type Boolean
		 */
		_isCreated : false,

		/**
		 * console表现模板
		 *
		 * @type String
		 */
		_html :    '<div id="ConsoleBoxHead" class="consoleBoxHead">\
						<button id="ConsoleCloseButton" class="consoleCloseButton">x</button>\
						<h5 class="title">Console</h5>\
					</div>\
					<ul id="ConsoleOutput" class="consoleOutput"></ul>\
					<div class="consoleInputBox">\
						&gt;<input id="ConsoleInput" class="consoleInput" />\
					</div>',

		/**
		 * 提示框是否打开了
		 *
		 * @type Boolean
		 */
		_opened : false,

		//日志记录对象
		_log_record: [],

		_cmd_history:[],
		_cmd_last_index:0,

		/**
		 * 信息类型常量，一共五种类型<br/> <br/> DEBUG : 0 <br/> ERROR : 1 <br/> WARNING : 2
		 * <br/> INFO : 3 <br/> PROFILE : 4 <br/>
		 *
		 * @type Object
		 */
		TYPE : {
			DEBUG : 0,
			ERROR : 1,
			WARNING : 2,
			INFO : 3,
			PROFILE : 4
		},

		/**
		 * 样式类
		 *
		 * @type
		 */
		_typeInfo : [["log_debug_type", "√"], ["log_error_type", "x"], ["log_warning_type", "!"], ["log_info_type", "i"], ["log_profile_type", "└"]],

		/**
		 * 显示console
		 */
		show : function() {
			if (!this._isCreated) {
				this._create();
			}
			this._opened = true;

			this._main.style.display = "block";

			//输入焦点过来
			window.setTimeout(J.bind(this.focusCommandLine, this), 0);
		},

		/**
		 * 隐藏console
		 */
		hide : function() {
			J.console._main.style.display = "none";
			J.console._opened = false;

		},

		/**
		 * 开启console
		 */
		enable : function() {
			J.option.console = true;
			this.show();

		},

		/**
		 * 关闭console
		 */
		disable : function() {
			J.option.console = false;
			this.hide();

		},

		/**
		 * 初始化控制台
		 *
		 * @ignore
		 */
		_init : function() {
			this.print = this.out;
			// 快捷键开启
			$E.on(document, "keydown", J.bind(this.handleDocumentKeydown, this));
			if (J.option.console) {
				this.show();
			}
		},
		_create:function(){


			$H.loadCss(J.path+"assets/jet.css");
			this._main = document.createElement("div");

			this._main.id="JetConsole";
			this._main.style.display="none";
			this._main.className = "consoleBox";
			this._main.innerHTML = this._html;
			//alert(window.document.body)
			window.document.body.appendChild(this._main);


			this._headEl = $("ConsoleBoxHead");
			this._inputEl = $("ConsoleInput");
			this._closeButtonEl = $("ConsoleCloseButton");
			this._outputEl = $("ConsoleOutput");

			// 如果存在拖拽类
			if (J.dragdrop) {
				J.dragdrop.registerDragdropHandler(this._headEl, this._main);
			}


			// 绑定方法
			$E.on(this._inputEl, "keyup", J.bind(this._execScript,this));
			//$E.on(this._inputEl, "keypress", J.bind(this._execScript,this));
			$E.on(this._closeButtonEl, "click", this.hide);
			// 输入焦点过来
			// $E.on(this._main, "dblclick", this.focusCommandLine.bind(this));



			if(J.option.debug > J.DEBUG.NO_DEBUG){
				this.setToDebug();
			}else{
				this.setToNoDebug();
			}
			this._isCreated = true;
			this.out("Welcome to JET(Javascript Extension Tools)...", this.TYPE.INFO);


		},

		handleDocumentKeydown: function(e){
			switch(e.keyCode){
				//case 74:	// J 键:74
				case 192:	// `~键:192
					if(e.ctrlKey){

						this.toggleShow();
						e.preventDefault();
					}
					break;
				default: break;
			}
		},

		focusCommandLine: function(){
			this._inputEl.focus();
		},

		toggleShow:function(){
			if(this._opened){
				this.hide();

				//J.option.debug = J.DEBUG.NO_DEBUG;
			}else{
				this.show();
				//J.option.debug = J.DEBUG.SHOW_ALL;

			}

		},

		/**
		 * 控制台记录信息
		 *
		 * @param {String} msg 要输出的信息
		 * @param {Number} type 要输出的信息的类型，可选项
		 * @return {String} 返回要输出的信息
		 */
		outConsoleShow:function(msg, type){
			this.outConsole(msg, type);

			if ((!this._opened) && J.option.console) {
				this.show();
			}
		},

		/**
		 * 向控制台输出信息并显示
		 *
		 * @param {String} msg 要输出的信息
		 * @param {Number} type 要输出的信息的类型，可选项
		 * @return {String} 返回要输出的信息
		 */
		outConsole: function(msg, type) {
			type = type || 3;
			this.log(msg, type);

			if(type < J.option.debug){
				var _item = document.createElement("li");
				this._outputEl.appendChild(_item);

				var _ti = J.console._typeInfo[type] || J.console._typeInfo[0];
				_item.className = _ti[0];
				_item.innerHTML = '<span class="log_icon">' + _ti[1] + '</span>' + msg;

				this._outputEl.scrollTop = this._outputEl.scrollHeight;
			}
		},

		/**
		 * 向控制台输出信息的方法
		 *
		 * @param {String} msg 要输出的信息
		 * @param {Number} type 要输出的信息的类型，可选项
		 * @return {String} 返回要输出的信息
		 */
		out:function(){
		},


		setToDebug:function(){
			this.out = this.outConsoleShow;
		},

		setToNoDebug:function(){
			this.out = this.outConsole;
		},

		log: function(msg, type){

			this._log_record.push([msg,type]);
		},

		/**
		 * 清空log
		 */
		clear : function() {
			J.console._outputEl.innerHTML = "";
		},

		/**
		 * 执行脚本
		 */
		_execScript : function(e) {
			switch(e.keyCode){
				case 13:
					this._cmd_history.push(J.console._inputEl.value);
					this._cmd_last_index=this._cmd_history.length;
					break;
				case 38://上一命令
					if(this._cmd_history.length==0)return;
					var s="";
					if(this._cmd_last_index>0){
						this._cmd_last_index--;
						s=this._cmd_history[this._cmd_last_index];
					}else{
						this._cmd_last_index=-1;
					}
					J.console._inputEl.value=s;
					return;
				case 40://下一命令
					if(this._cmd_history.length==0)return;
					var s="";
					if(this._cmd_last_index<this._cmd_history.length-1){
						this._cmd_last_index++;
						s=this._cmd_history[this._cmd_last_index];
					}else{
						this._cmd_last_index=this._cmd_history.length;
					}
					J.console._inputEl.value=s;
					return;
				default:
					return;
			}
			// 控制台命令
			switch (J.console._inputEl.value) {
				case "help" :
					var _rv = "&lt;&lt; Console Help &gt;&gt;<br/>\
								help  : 控制台帮助<br/>\
								clear : 清空控制台输出<br/>\
								hide  : 隐藏控制台，或者使用 Ctrl + `(~) 快捷键"
					J.console.out(_rv, 3);
					break;
				case "clear" :
					J.console.clear();
					break;
				case "hide" :

					J.console.hide();
					break;
				default :
					var _rv = '<span style="color:#ccff00">' + J.console._inputEl.value + '</span><br/>';
					try {
						_rv += (eval(J.console._inputEl.value) || "").toString().replace(/</g, "&lt;").replace(/>/g, "&gt;")
						J.console.out(_rv, 0);
					} catch (e) {
						_rv += e.description;
						J.console.out(_rv, 1);
					}
			}

			J.console._inputEl.value = "";
		}
	});








	var topNamespace = this,
		query = J.string.mapQuery(window.location.search);

	if(query.console){
		if(query.console == "firebug"){

			if(topNamespace.console){
				topNamespace.console.out = function(msg){
					topNamespace.console.log(msg);
				};
				J.console = topNamespace.console;

			}else{
				// http://getfirebug.com/releases/lite/1.2/firebug-lite.js
				$H.loadScript(J.path+"firebug/firebug-lite.js",{
					onSuccess : function(){
						firebug.env.height = 220;
						// http://getfirebug.com/releases/lite/1.2/firebug-lite.css
						firebug.env.css = "../../source/firebug/firebug-lite.css";

						topNamespace.console.out = function(msg){
							topNamespace.console.log(msg);
						};
						J.console = topNamespace.console;

						J.out("...控制台开启");
						J.out("...测试成功");
					}
				});
			}
		}
		else if(query.console == "true"){
			$E.onDomReady(function(){
				J.console._init();
				J.console.show();
			});

			J.console=J.extend(J.console,{
				'log':J.emptyFunc,
				'info':J.emptyFunc,
				'warn':J.emptyFunc,
				'dir':J.emptyFunc
			});
		}
	}else{
		J.console={
			'log':J.emptyFunc,
			'info':J.emptyFunc,
			'warn':J.emptyFunc,
			'dir':J.emptyFunc,
			'out':J.emptyFunc
		};
	}












	/**
	 * runtime处理工具静态类
	 *
	 * @namespace runtime处理工具静态类
	 * @name runtime
	 */
	J.runtime = (function() {
		/**
		 * 是否debug环境
		 *
		 * @return {Boolean} 是否呢
		 */
		function isDebugMode() {
			return (J.config.debugLevel > 0);
		}

		/**
		 * log记录器
		 *
		 * @ignore
		 * @param {String} msg 信息记录器
		 */
		function log(msg, type) {
			var info;
			if (isDebugMode()) {
				info = msg + '\n=STACK=\n' + stack();
			} else {
				if (type == 'error') {
					info = msg;
				} else if (type == 'warn') {
					// TBD
				}
			}
			J.Debug.errorLogs.push(info);
		}

		/**
		 * 警告信息记录
		 *
		 * @param {String} sf 信息模式
		 * @param {All} args 填充参数
		 */
		function warn(sf, args) {
			log(write.apply(null, arguments), 'warn');
		}

		/**
		 * 错误信息记录
		 *
		 * @param {String} sf 信息模式
		 * @param {All} args 填充参数
		 */
		function error(sf, args) {
			log(write.apply(null, arguments), 'error');
		}

		/**
		 * 获取当前的运行堆栈信息
		 *
		 * @param {Error} e 可选，当时的异常对象
		 * @param {Arguments} a 可选，当时的参数表
		 * @return {String} 堆栈信息
		 */
		function stack(e, a) {
			function genTrace(ee, aa) {
				if (ee.stack) {
					return ee.stack;
				} else if (ee.message.indexOf("\nBacktrace:\n") >= 0) {
					var cnt = 0;
					return ee.message.split("\nBacktrace:\n")[1].replace(/\s*\n\s*/g, function() {
						cnt++;
						return (cnt % 2 == 0) ? "\n" : " @ ";
					});
				} else {
					var entry = (aa.callee == stack) ? aa.callee.caller : aa.callee;
					var eas = entry.arguments;
					var r = [];
					for (var i = 0, len = eas.length; i < len; i++) {
						r.push((typeof eas[i] == 'undefined') ? ("<u>") : ((eas[i] === null) ? ("<n>") : (eas[i])));
					}
					var fnp = /function\s+([^\s\(]+)\(/;
					var fname = fnp.test(entry.toString()) ? (fnp.exec(entry.toString())[1]) : ("<ANON>");
					return (fname + "(" + r.join() + ");").replace(/\n/g, "");
				}
			}

			var res;

			if ((e instanceof Error) && (typeof arguments == 'object') && (!!arguments.callee)) {
				res = genTrace(e, a);
			} else {
				try {
					({}).sds();
				} catch (err) {
					res = genTrace(err, arguments);
				}
			}

			return res.replace(/\n/g, " <= ");
		}

		return {
			/**
			 * 获取当前的运行堆栈信息
			 *
			 * @param {Error} e 可选，当时的异常对象
			 * @param {Arguments} a 可选，当时的参数表
			 * @return {String} 堆栈信息
			 */
			stack : stack,
			/**
			 * 警告信息记录
			 *
			 * @param {String} sf 信息模式
			 * @param {All} args 填充参数
			 */
			warn : warn,
			/**
			 * 错误信息记录
			 *
			 * @param {String} sf 信息模式
			 * @param {All} args 填充参数
			 */
			error : error,

			/**
			 * 是否调试模式
			 */
			isDebugMode : isDebugMode
		};

	})();

});














