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
 * Package: jet.i18n
 *
 * Need package:
 * jet.core.js
 * 
 */
 
/**
 * 5.[Javascript core]: i18n 国际化扩展
 */
Jet().$package(function(J){
	
	/**
	 * i18n 名字空间
	 * 
	 * @namespace
	 * @name i18n
	 */
	J.i18n = J.i18n || {};
	
	J.extend(J.i18n, 
		
	/**
	 * @lends i18n
	 * 
	 */		
	{
		
		/**
		 * 引用所有语言包的对象
		 * 
		 * @memberOf i18n
		 * @name langs
		 * @type {Object}
		 * 
		 */
		langs: {
			"en-us": {
				number:"ddd,ddd.dd",
				currency:"$ddd,ddd.dd",
				date: "MM/dd/yyyy",
				longDate:"yyyy year MM month dd day",
				time: "hh:mm:ss",
				text: {
					helloWorld:"Hello Word!",
					test: "test",
					about: "Jet Lib, Developer: Jetyu!"
				}
				
			}
		},

		/**
		 * 当前语言
		 * 
    	 * @memberOf i18n
		 * @name currentLang
		 * @type {Object}
		 */
		currentLang: null,
		
		/**
		 * 添加语言包对象
		 * 
		 * @param {Object} langObj 要添加的语言包对象
		 * 
		 * @example
		 * Jet().$package(function(J){
		 * 	// 添加一个名字为"zh-cn"中文语言包
		 * 	J.i18n.add({
		 * 		"zh-cn":{
		 * 			// 数字
		 * 			number:"ddd,ddd.dd",
		 * 			// 货币
		 * 			currency:"￥ddd,ddd.dd元",
		 * 			// 日期
		 * 			date: "yyyy-MM-dd",
		 * 			// 长日期
		 * 			longDate:"yyyy年MM月dd日",
		 * 			// 时间
		 * 			time: "hh小时mm分ss秒",
		 * 			// 文本
		 * 			text: {
		 * 				helloWorld:"你好,世界!",
		 * 				test: "测试",
		 * 				onlyDefaultHas: "只有默认语言才有的...",
		 * 				about: "Jet 框架, 开发者: 于涛!"
		 * 			}	
		 * 		}
		 * 	});
		 * };
		 */
		add: function(langObj){
			return J.extend(J.i18n.langs, langObj);
		},
		
		/**
    	 * 设置默认语言
		 * 
		 * @param {String} lang 语言包的名称
		 * 
		 * @example
		 * Jet().$package(function(J){
		 * 	// 将名字为"zh-cn"语言包设置为默认
		 * 	J.i18n.setDefault("zh-cn");
		 * };
		 */
		setDefault: function(lang){
			this.defaultLang = J.i18n.langs[lang];
			return this.defaultLang;
		},
		
		/**
    	 * 设置为当前语言
		 * 
		 * @param {String} lang 语言包的名称
		 * @param {Boolean} isSetDefault 是否同时设置为默认的语言
		 * 
		 * @example
		 * Jet().$package(function(J){
		 * 	// 将名字为"zh-cn"语言包设置为当前语言
		 * 	J.i18n.set("zh-cn");
		 * };
		 */
		set: function(lang, isSetDefault){
			this.currentLang = J.i18n.langs[lang];
			if(!this.defaultLang){
				this.defaultLang = this.currentLang;
			}
			this.text = this.text || {};
			return J.extend(this.text, this.defaultLang, this.currentLang.text);
		},
		
		/**
    	 * 按当前语言输出数字
    	 * 
		 * @param {Mixed} 数字
		 * @returns {String} 返回按当前语言输出的数字的字符串形式
		 */
		number:function(s){

	        if(/[^0-9\.]/.test(s)){
	        	return "invalid value";
	        }
	        s = s.replace(/^(\d*)$/,"$1.");
	        s = (s+"00").replace(/(\d*\.\d\d)\d*/,"$1");
	        s = s.replace(".",",");
	        var re = /(\d)(\d{3},)/;
	        while(re.test(s)){
	        	s = s.replace(re,"$1,$2");
	        }
	        s = s.replace(/,(\d\d)$/,".$1");
	        return s.replace(/^\./,"0.");
		},
		
		/**
    	 * 按当前语言输出日期
    	 * 
		 * @param {Mixed} 日期
		 * @returns {String} 返回按当前语言输出的日期字符串
		 * 
		 * @example
		 * Jet().$package(function(J){
		 * var d = new Date();
		 * 	// 安当前语言的格式输出时间字符串
		 * 	J.out(J.i18n.date(d));
		 * };
		 * 
		 */
		date: function(date){
			return J.formatDate(date, J.i18n.currentLang.date);
		},
		
		/**
    	 * 按当前语言输出长日期
    	 * 
		 * @param {Mixed} 长日期
		 * @returns {String} 返回按当前语言输出的长日期字符串
		 */
		longDate: function(date){
			return J.formatDate(date, J.i18n.currentLang.longDate);
		},
		
		/**
    	 * 按当前语言输出时间
    	 * 
		 * @param {Mixed} 时间
		 * @returns {String} 返回按当前语言输出的时间字符串
		 */
		time: function(time){
			return J.formatDate(date, J.i18n.currentLang.time);
		},
		
		/**
    	 * @ignore
		 * @lends i18n
		 */
		toString: function(){
			return "";
		}
	
	
	});
	
	//将第一个设为默认
	for(var p in J.i18n.langs){
		J.i18n.set(p);
		break;
	}

});















