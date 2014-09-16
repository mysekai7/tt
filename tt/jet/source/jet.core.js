/**	
 * JET (Javascript Extension Tools) 
 * Copyright (c) 2009, KDV.cn, All rights reserved.
 * http://code.google.com/p/j-et/
 *
 * @version	1.0
 * @author	Kinvix(<a href="mailto:Kinvix@gmail.com">Kinvix@gmail.com</a>)
 * 
 */

/**	
 * @description
 * Package: jet
 *
 * Need package:
 * no.
 * 
 */

/**
 * 1.[JET core]: JET 微内核
 */
;(function(){
	var version = "1.0",
		mark = "JetMark",
		topNamespace = this,
		
		// 将顶级命名空间中可能存在的 Jet 对象引入
		Jet = topNamespace.Jet,
		
		VERSIONS = {},
		PACKAGES = {},
		
		DEBUG = {
			NO_DEBUG: 0,
			SHOW_ERROR: 1,
			SHOW_WARNING: 2,
			SHOW_INFO: 3,
			SHOW_ALL: 4
		},
		
		option = {
			debug: DEBUG.SHOW_ALL
		},
		
		/**
		 * @ignore
		 */
		out = function(msg, type){
			msg = String(msg);
			type = type || 3;
			if(type < option.debug){
				if(this.console){
					if(this.console.out){
						this.console.out(msg, type);
					}else{
						alert(msg+" - 消息类型["+type+"]");
					}
					
				}
			}
			return msg;
		};
		

	try{
		// 判断Jet名字空间是否已经存在
		if(typeof Jet === "undefined" || (Jet.mark && Jet.mark === mark)){
			
			// 如果已经有Jet对象则记录已有的信息
			if(Jet){
				VERSIONS = Jet.VERSIONS;
				PACKAGES = Jet.PACKAGES;
			}
			
			/**
			 * 【Jet 对象原型】
			 * 
			 * @class Jet
			 * @constructor Jet
			 * @global
			 * 
			 * @since version 1.0
			 * @description Jet 对象原型的描述
			 * 
			 * @param {Number} ver 要使用的 Jet 的版本号，当前是1.0
			 * @param {Boolean} isCreateNew 是否创建一个新的 Jet 实例，默认为 false 不创建新的 Jet 实例，只返回同一版本在全局中的唯一一个实例，注意：除非特殊需要，否则一般不要创建新的 Jet 实例
			 * @return {Object} 返回对应版本的 Jet 对象
			 * 
			 * @example
			 * //代码组织方式一(传统)：
			 * var J = new Jet();
			 * J.out(J.version);	//输出当前Jet的版本
			 * 
			 * @example
			 * //代码组织方式二(推荐)：
			 * Jet().$package(function(J){
			 * 	J.out(J.version);	//输出当前Jet的版本
			 * };
			 * //注：此种方式可以利用匿名函数来防止变量污染全局命名空间，尤其适合大型WebApp的构建！
			 * 
			 * @example
			 * //范例：
			 * Jet().$package("tencent.qqweb", function(J){
			 * 	var $ = J.dom.id,
			 * 	$D = J.dom,
			 * 	$E = J.event,
			 * 	$H = J.http;
			 * 	this.name = "腾讯WebQQ";
			 * 	J.out(this.name);
			 * };
			 * 
			 */
			Jet = function(ver, isCreateNew){
				var J = this;

				if(isCreateNew){
					// 如果是第一次执行则初始化对象
					this._init();
				}else{
					if(ver){
						ver = String(ver);
						try{
							if(Jet.VERSIONS[ver]){
								J = Jet.VERSIONS[ver];
							}else{
								J = Jet.VERSIONS[Jet.DEFAULT_VERSION];
								throw new Error("没有找到 JET version " + ver + ", 所以返回默认版本 JET version " + Jet.DEFAULT_VERSION + "!");
							}
						}catch(e){
							//J.out(e.fileName+";"+e.lineNumber+","+typeof e.stack+";"+e.name+","+e.message, 2);
							J.out("A.错误：[" + e.name + "] "+e.message+", " + e.fileName+", 行号:"+e.lineNumber+"; stack:"+typeof e.stack, 2);
						}
					}else{
						J = Jet.VERSIONS[Jet.DEFAULT_VERSION];
					}
				}
				return J;
			};
			
			Jet.prototype = {
				/**
				 * 当前 Jet 的版本号，此版本是 1.0 <br/>
				 * Version 1.0
				 * 
				 * @description {Num} 当前 Jet 的版本号！
				 * @constant
				 * @type Number
				 */
				version: version,
				
				DEBUG: DEBUG,
				
				/**
				 * Jet 配置
				 * @ignore
				 */
				option: option,
				
				/**
				 * Jet 的初始化方法
				 * initialize method
				 * 
				 * @private
				 * @param {Object} o config 对象
				 */
				_init: function(){
					this.constructor = Jet;
					//return true;
				},
			
				/**
				 * 创建一个命名空间，创建的命名空间将会在 window 根命名空间下。
				 * Create a new namespace, the top namespace is window.
				 * 
				 * @since version 1.0
				 * @description 可以一次性连续创建命名空间
				 * 
				 * @param {String} name 命名空间名称
				 * @returns {Object} 返回对最末命名空间的引用
				 * 
				 * @example
				 * //在全局环境中创建tencent.qqweb名字空间, $namespace完成的操作相当于在全局环境中执行如下语句：
				 * //var tencent = {};
				 * //tencent.qqweb = {};
				 * 
				 * J.$namespace("tencent.qqweb");
				 * 
				 * //注：Jet的$namespace方法与其他JS框架的namespace的方法不同，其他框架如YUI是在其YAHOO对像下创
				 * //建命名空间，而Jet的$namespace测试直接在顶级命名空间window的下边直接创建命名空间。
				 * 
				 */
				$namespace: function(name) {
					var i,
						ni,
						nis = name.split("."),
						ns = topNamespace;

					for(i = 0; i < nis.length; i=i+1){
						ni = nis[i];
						ns[ni] = ns[ni] || {};
						ns = ns[nis[i]];
					}

					return ns;
				},
	
				/**
				 * 创建一个 Javascript 代码包
				 * 
				 * @param {String} name 要创建的包的名字空间
				 * @param {Function} func 要创建的包的包体
				 * @returns {Mixed} 返回任何自定义的变量
				 * 
				 * @example
				 * //创建一个匿名package包：
				 * Jet().$package(function(J){
				 * 	//这时上下文对象this指向全局window对象
				 * 	alert("Hello world! This is " + this);
				 * };
				 * 
				 * @example
				 * //创建一个名字为tencent.kinvix的package包：
				 * Jet().$package("tencent.kinvix", function(J){
				 * 	//这时上下文对象this指向window对象下的tencent.kinvix对象
				 * 	alert("Hello world! This is " + this);
				 * };
				 * 
				 * 
				 * 
				 */
				$package: function(){
					var name = arguments[0],
						func = arguments[arguments.length-1],
						ns = topNamespace,
						returnValue;
						if(typeof func === "function"){
							if(typeof name === "string"){
								ns = this.$namespace(name);
								if(Jet.PACKAGES[name]){
									//throw new Error("Package name [" + name + "] is exist!");
								}else{
							   		Jet.PACKAGES[name] = {
										isLoaded: true,
										returnValue: returnValue
									};
								}
							}
							ns.packageName = name;
							returnValue = func.call(ns, this);
						}else{
							throw new Error("Function required");
						}
	
				},
				
				/**
				 * 检查一个 Javascript 模块包是否已经存在
				 * 
				 * @param {String} name 包名
				 * @return {Object} 如果已加载则返回包对象，否则返回 undefined
				 * 
				 * @example
				 * //创建一个匿名package包：
				 * Jet().$package(function(J){
				 * 	// 输出undefined
				 * 	J.out(J.checkPackage("tencent.kinvix"));
				 * };
				 * 
				 * 
				 * @example
				 * //创建一个名字为tencent.kinvix的package包：
				 * Jet().$package("tencent.kinvix", function(J){
				 * 	//这时上下文对象this指向window下的tencent.kinvix对象
				 * 	alert("Hello world! This is " + this);
				 * };
				 * 
				 * Jet().$package(function(J){
				 * 	// J.checkPackage("tencent.kinvix")结果返回的将是tencent.kinvix的引用
				 * 	var kinvix = J.checkPackage("tencent.kinvix");
				 * 	if(kinvix){
				 * 		J.out("tencent.kinvix包已加载...");
				 * 	}
				 * };
				 * 
				 */
				checkPackage: function(name){
					return Jet.PACKAGES[name];
				},
				
				/**
				 * 标准化 Javascript 的核心输出方法，注意：在不同的Javascript嵌入宿主中会覆盖此方法！
				 * 
				 * @method out
				 * @function
				 * 
				 * @param {String} msg 要输出的信息
				 * @param {Number} type 输出信息的类型
				 * @return {String} msg 返回要输出的信息
				 * 
				 * @example
				 * //创建一个匿名package包：
				 * Jet().$package(function(J){
				 * 	// 向Jet的控制台输出信息,在不同的js宿主中具体实现细节会不同,但不会影响out方法的使用;
				 * 	J.out("Hello, world!");
				 * };
				 * 
				 */
				out: out,
				
				startTime: +new Date(),
				
				/**
				 * 关于 Jet
				 * 
				 * @return {String} 返回 Jet 的 about 信息
				 */
				about: function(){
					return this.out("JET (Javascript Extend Tools)\nversion: " + this.version + "\n\nCopyright (c) 2009, KDV.cn, All rights reserved.", 3);
				},
				
				/**
				 * Jet 对象转化为字符串的方法
				 * 
				 * @ignore
				 * @return {String} 返回 Jet 对象串行化后的信息
				 */
				toString: function(){
					return "JET version " + this.version + " !";
				}
			};

			/**
			 * Jet 版本库对象
			 * 
			 * @ignore
			 * @type Object
			 */
			Jet.VERSIONS = VERSIONS;
			
			/**
			 * 记录加载的包的对象
			 * 
			 * @ignore
			 * @type Object
			 */
			Jet.PACKAGES = PACKAGES;

			/**
			 * 创建一个当前版本 Jet 的实例
			 * 
			 * @ignore
			 * @type Object
			 */
			Jet.VERSIONS[version] = new Jet(version, true);
		
			/**
			 * Jet 默认版本的版本号，默认将会是最后一个加载的Jet版本
			 * 
			 * @constant
			 * @type Number
			 */
			Jet.DEFAULT_VERSION = version;
			/**
			 * Jet 对象验证标记
			 * 
			 * @ignore
			 * @description 用于验证已存在的Jet对象是否是本框架某子版本的Jet对象
			 * @type String
			 */
			Jet.mark = mark;
			
			// 让顶级命名空间的 Jet 对象引用新的 Jet 对象
			topNamespace.Jet = Jet;
		}else{
			throw new Error("\"Jet\" name is defined in other javascript code !!!");
		}
	}catch(e){
		// 微内核初始化失败，输出出错信息
		out("JET 微内核初始化失败! " + "B.错误：[" + e.name + "] "+e.message+", " + e.fileName+", 行号:"+e.lineNumber+"; stack:"+typeof e.stack, 1);
	}
})();