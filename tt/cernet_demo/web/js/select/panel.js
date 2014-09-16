/**
*title: 弹出面板选择菜单
*author: GuoLei
*date:	2008-09-05
*
*/

//js根目录
var root = "/js/select/";
//php调用文件路径
var gateWay = "/ajax/js.php?Mod=";
var object = "";
/**
* 作用：初始化对象函数（主页面调用）
* 参数：调用对象，数据接收对象，模块名称，面板标题，菜单级数，单选/多选，面板初始数据文件路径，提交到的文件路径，回调js函数
*/
function initObj(btnObj,target,app,title,deep,trigger,url,sbmtUrl,callFunc)
{
	object = new PObject(btnObj,target,app,title,deep,trigger,url,sbmtUrl,callFunc);
}
/**
* 定义对象的方法
* 
*/
var PObject = Class.create();
PObject.prototype = {
	initialize:function(btnObj,target,app,title,deep,trigger,url,sbmtUrl,callFunc) {
		//应用名称
		this.app = app;
		this.btnObj = btnObj;
		//面板模板文件
		this.popSrc = "panel_"+app+".html";
		//初始化面板
		this.initPanel(target,title,deep,trigger,url,sbmtUrl,callFunc);
		//弹出面板
		this.showDiv();
		//复制对象用于单个调用
		object = this;
	},
	/**
	* panel对象构造方法
	* 
	*/
	initPanel:function(target,title,deep,trigger,url,sbmtUrl,callFunc) {
		var self = this;
		
		self.panel = new Object();
		self.panel.title = title;
		self.panel.deep = deep;
		self.panel.trigger = trigger;
		self.panel.url = url;
		self.panel.target = target;
		
		self.panel.sbmtUrl = sbmtUrl;
		self.panel.callFunc = callFunc;
		self.panel.execCode = "";
		
		self.panel.maxSelect = 5;
		/**
		* 绘制弹出面板
		* 
		*/
		self.panel.drawPanel = function() {
			var _url,flType,_target,_nFile;
			self.request(root+self.popSrc, "", function(o) {
				//绘制或加载模板
				if(self.panel.deep == 1)
					$("divContent").innerHTML = "<div id=\"level1\" style=\"height:150px; border: 1px dashed #000; background-color:#ebecee; overflow: auto;\"></div>";
				else
					$("divContent").innerHTML = o.responseText;
				//alert(self.panel.deep);
				//alert(root+self.app+"/0.js");
				/*
				for(var i=0;i<self.panel.deep;i++)
				{
					if(self.panel.url && self.panel.url.length>1)
						_url = gateWay+self.panel.url[i];
					else
					{
						if(i == 0)
							_url = root+self.app+"/0.js";
						else
							_url = 
					}
					
					flType = i==self.panel.deep-1?self.panel.trigger?"flCheck":"flClick":"flSelect";
					_target = "level"+(i+1);
					
					self.panel.execCode += "self.request(\""+_url+"\", \"\", function(o) { var data = self.panel.parses(o.responseText); self.panel."+flType+"(\""+_target+"\", data); if($(\""+_target+"\").id.substr($(\""+_target+"\").id.length-1)<self.panel.deep) { $(\""+_target+"\").onchange=self.panel.onChange; _nFile=self.panel.nextfile(o.responseText)} else { self.panel.initCheck(); self.panel.initAll();}});";
				}
				//alert(self.panel.execCode);
				eval(self.panel.execCode);
				*/
				self.panel.initNext($("level1"));
				$("level"+parseInt(self.panel.deep)).className = self.panel.trigger?"":"mainContent";
				
				//初始化
				$("smt").style.display = self.panel.trigger?"block":"none";
				self.panel.initTent();
				if(self.app == "month") self.panel.flClick("level2",[['1','1月'],['2','2月'],['3','3月'],['4','4月'],['5','5月'],['6','6月'],['7','7月'],['8','8月'],['9','9月'],['10','10月'],['11','11月'],['12','12月']]);
								
			});
			
		}
		/**
		* panel对象onchange方法
		* 
		*/		
		self.panel.onChange = function(event) {
			var e = window.event || event;
			var ele = e.srcElement ? e.srcElement : e.target;
			self.panel.assignNext(ele);
			
		}
		/**
		* 数据解析方法
		* 返回数组
		*/		
		self.panel.parses = function(data) {
			if(data.indexOf('@')==-1 && data.indexOf('^')==-1)
				return [];
			var d = data.split('@');
			var nd = [];
			
			for(var i=0; i<d.length;i++) {
				
				nd[nd.length] = d[i].split('^');
			}
			return nd;
		}
		/**
		* 下一个级别判断
		* 
		*/
		self.panel.nextlevel = function(ele) {
			return parseInt(ele.id.substr(ele.id.length-1))+1;
		}
		/**
		* 获取下级菜单id
		* 
		*/
		self.panel.nextid = function(ele) {
			return "level"+(parseInt(ele.id.substr(ele.id.length-1))+1);
		}
		/**
		* 获取下级菜单数据文件名
		* 
		*/
		self.panel.nextfile = function(data) {
			
			return data.substr(0,data.indexOf("^"))+".js";
		}
		/**
		* 初始化下一级菜单
		* 
		*/
		self.panel.initNext = function(ele,nFile) {
			var el = ele;
			var _url = "";
			var execCode = "";
			if(typeof ele != "undefined") {
				var thislevel = parseInt(ele.id.substr(ele.id.length-1));
				if(self.panel.url && self.panel.url.length>1) {
					_url = gateWay+self.panel.url[thislevel-1];
				} else {
					if(!nFile)
						_url = root+self.app+"/0.js";
					else
						_url = root+self.app+"/"+nFile;
				}
				flType = thislevel==self.panel.deep?self.panel.trigger?"flCheck":"flClick":"flSelect";
				_target = "level"+thislevel;
				if(self.app != "month")
					execCode = "self.request(\""+_url+"\", \"\", function(o) { var data = self.panel.parses(o.responseText); self.panel."+flType+"(\""+_target+"\", data); if("+thislevel+"<self.panel.deep) { $(\""+_target+"\").onchange=self.panel.onChange; el=$(self.panel.nextid(el)); _nFile=self.panel.nextfile(o.responseText); self.panel.initNext(el,_nFile);} else { self.panel.initCheck(); self.panel.initAll();} });";
				else
					execCode = "self.request(\""+_url+"\", \"\", function(o) { var data = self.panel.parses(o.responseText); self.panel."+flType+"(\""+_target+"\", data); if("+thislevel+"<self.panel.deep) { el=$(self.panel.nextid(el)); _nFile=self.panel.nextfile(o.responseText); self.panel.initNext(el,_nFile);} else { self.panel.initCheck(); self.panel.initAll();} });";
				eval(execCode);
			}
			return;
		}
		/**
		* 从属菜单赋值
		* 
		*/
		self.panel.assignNext = function(ele) {
			var el = ele;
			var _url = "";
			if(typeof ele.value != "undefined") {
				if(self.panel.url && self.panel.url.length>1) {
					var nLevel = self.panel.nextlevel(el);
					_url = gateWay+self.panel.url[nLevel-1];
					if(_url.indexOf('&')>-1) {
						var tmp = _url.split("&");
						_url = tmp[0]+"&id="+el.value;
					}
				} else
					_url = root+self.app+"/"+el.value+".js";
				self.request(_url, "", function(o) {
					var execCode = "";
					var level = parseInt(el.id.substr(el.id.length-1));
					var flType = level==self.panel.deep-1?self.panel.trigger?"flCheck":"flClick":"flSelect";
					execCode += "var data = self.panel.parses(o.responseText); var nextid = self.panel.nextid(el); self.panel."+flType+"(nextid, data); el=$(self.panel.nextid(el)); self.panel.initCheck(); self.panel.initAll(); self.panel.assignNext(el); ";
					eval(execCode);
				});
			}
			return;
		}
		/**
		* 生成下拉框选项菜单
		* 
		*/
		self.panel.flSelect = function(ele, data) {
			var el = $(ele);
			el.options.length = 0;
			for(var i=0; i<data.length; i++) {
				option = document.createElement("OPTION"); 
				option.text = data[i][1];
				option.value = data[i][0];
				el.options[i] = option;
			}
		}
		/**
		* 生成复选框选项菜单
		* 
		*/
		self.panel.flCheck = function(ele, data) {
			var el = $(ele);
			el.innerHTML = "";
			var html = "";
			for(var i=0; i<data.length; i++) {
				html += "<nobr><input type=\"hidden\" name=\"hd\" value=\""+data[i][1]+"\"><input type=\"checkbox\" id=\"ck_"+data[i][0]+"\"  name=\"ck\" value=\"" + data[i][0] + "\" onclick=\"object.panel.checkOne(this)\">"+data[i][1]+"&nbsp;</nobr>";  
			}
			el.innerHTML = html;
		}
		/**
		* 生成单击选项菜单
		* 
		*/
		self.panel.flClick = function(ele, data) {
			var el = $(ele);
			el.innerHTML = "";
			var html = "";
			for(var i=0; i<data.length; i++) {
				html += "<a href=\"javascript:void(0);\" onclick=\"javascript:object.doSelect('"+self.panel.target+"','"+data[i][0]+"','"+data[i][1]+"');\">"+data[i][1]+"</a>";  
			}
			el.innerHTML = html;
		}
		/**
		* 复选框全选
		* 
		*/
		self.panel.checkAll = function(obj) {
			var ck = document.getElementsByName("ck");
			if(self.panel.maxSelect) {
				if(obj.checked && ck.length>self.panel.maxSelect) {
					alert('最多选'+self.panel.maxSelect+'个选项');
					obj.checked = false;
					return false;
				}
			}
			for(var i=0;i<ck.length;i++) {
				ck[i].checked = obj.checked;
			}
			self.panel.assignTent();
			
		}
		/**
		* 选中单独复选框
		* 
		*/
		self.panel.checkOne = function(el) {
			var ck = document.getElementsByName("ck");
			//alert(el.checked);
			if(self.panel.maxSelect>0) {
				var j=1;
				for(var i=0;i<$("tent").childNodes.length;i++) {
					if(j>self.panel.maxSelect-1 && el.checked) { 
						alert('最多选'+self.panel.maxSelect+'个选项'); el.checked = false; return false;
					}else if($("tent").childNodes[i].id.indexOf('sp_')>-1)
						j++;
				}
			}
			var hd = document.getElementsByName("hd");
			for(var i=0;i<ck.length;i++) {
				if(!ck[i].checked) break;
			}
			$("checkAll").checked = ck[i]?ck[i].checked:true;
			
			//tent.style.display = "block";
			self.panel.assignTent();
		}
		/**
		* 初始化暂存选中数据的div
		* 
		*/
		self.panel.initTent = function() {
			var tent = $("tent");
			tent.innerHTML = "";
			if($(self.panel.target).value) {
				var tars = $(self.panel.target).value.split(",");
				var tarTexts = $($(self.panel.target).id+"_text").value.split(",");
				var nobr;
				for(var i=0;i<tars.length;i++) {
					nobr = document.createElement("nobr");
					nobr.setAttribute("id","sp_"+tars[i]);
					nobr.setAttribute("title","点击删除");
					nobr.style.cursor = "pointer";
					nobr.innerHTML = tarTexts[i]+"&nbsp;|&nbsp;";
					tent.appendChild(nobr);
					nobr.onclick = function(event){
						var e = window.event || event;
						var ele = e.srcElement ? e.srcElement : e.target;
						
						var ckele = $("ck_"+ele.id.substr(ele.id.indexOf("_")+1));
						if(ckele)
							ckele.checked = false;
						self.panel.initAll();
						tent.removeChild(this);
					}
				}
			}
		}
		/**
		* 向暂存选中数据的div中添加或删除数据
		* 
		*/
		self.panel.assignTent = function() {
			var ck = document.getElementsByName("ck");
			var hd = document.getElementsByName("hd");
			var tent = $("tent");
			for(var i=0;i<ck.length;i++) {
				var tmpele = $("sp_"+ck[i].value);
				if(ck[i].checked && !tmpele) {
					var nobr = document.createElement("nobr");
					nobr.setAttribute("id","sp_"+ck[i].value);
					nobr.setAttribute("title","点击删除");
					nobr.style.cursor = "pointer";
					nobr.innerHTML = hd[i].value+"&nbsp;|&nbsp;";
					tent.appendChild(nobr);
					nobr.onclick = function(event){
						var e = window.event || event;
						var ele = e.srcElement ? e.srcElement : e.target;
						var ckele = $("ck_"+ele.id.substr(ele.id.indexOf("_")+1));
						if(ckele)
							ckele.checked = false;
						self.panel.initAll();
						tent.removeChild(this);
					}
				} else if(!ck[i].checked && tmpele) {
					tent.removeChild(tmpele);
				}
			}
		}
		/**
		* 初始化复选框选中状态
		* 
		*/
		self.panel.initCheck = function() {
			var tent = $("tent");
			var nobr = tent.getElementsByTagName("nobr");
			var ck = document.getElementsByName("ck");
			//alert(tent.innerHTML);
			for(var i=0;i<ck.length;i++) {
				//alert(nobr[i].id.substr(nobr[i].id.indexOf("_")+1)+" "+nobr[i].innerHTML);
				if(tent.innerHTML.indexOf("sp_"+ck[i].value)>0)
					ck[i].checked = true;
			}
		}
		/**
		* 初始化全选框状态
		* 
		*/
		self.panel.initAll = function() {
			var ck = document.getElementsByName("ck");
			for(var i=0;i<ck.length;i++)
				if(!ck[i].checked) break;
			$("checkAll").checked = ck[i]?ck[i].checked:true;
		}
	},
	/**
	* 发送异步请求
	* 
	*/
	request:function(url, args, callback) {
		if(window.XMLHttpRequest) {
            this.ro = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
            this.ro = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var ro = this.ro;
		this.ro.onreadystatechange= function(){
            if (ro.readyState == 4) {
                callback(ro);
            }
        }
        this.ro.open("GET", url+args, true);
		/*if(method == "POST")
		{
			this.ro.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			this.ro.send(args);
		}
		else*/
        this.ro.send('');
	},
	/**
	* 弹出面板
	* 
	*/
	showDiv:function() {
		var self = this;
		var objDiv = $("_bgDiv");
		if (objDiv) {  
		  	var DWidth=document.body.clientWidth;
		  	var DHeight=document.body.clientHeight;
		  	var el = self.getClientRect();
		  	var x = el.Left+2;
		  	var y = el.Top + el.Height + 3;
		  
		  	$("codeTitle").innerHTML = self.panel.title;	
		  	$("divContent").innerHTML = "";
			
		  	self.hideBody();
			$("_bgDiv").style.display = "block";
	
		  	if(x+objDiv.clientWidth > DWidth)
		  	{
			 	x -= (x+objDiv.clientWidth - DWidth);
		  	}
		  	if(y+objDiv.clientHeight > DHeight)
		  	{
			 	y -= (y+objDiv.clientHeight - DHeight);
		  	}
		  	objDiv.style.left = x + "px";
		  	objDiv.style.top  = y + "px";
		  	
			self.panel.drawPanel();
		}
		
	},
	/**
	* 隐藏面板
	* 
	*/
	hideDiv:function() {
		$("_bgDiv").style.display = "none";
		this.showBody();	
		//alert(object.app);
		object = null;
		$("checkAll").checked = false;
		//alert(object);
	},
	/**
	* 显示主页面
	* 
	*/
	showBody:function() {
		document.body.removeChild($('MM_bgDiv'));
	},
	/**
	* 隐藏主页面
	* 
	*/
	hideBody:function() {
		var bgObj=document.createElement("div"); 
	    bgObj.setAttribute('id','MM_bgDiv'); 
	    bgObj.style.position="absolute"; 
	    bgObj.style.top="0"; 
	    bgObj.style.background="#cccccc"; 
	    bgObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75"; 
	    bgObj.style.opacity="0.6"; 
	    bgObj.style.left="0"; 
	    bgObj.style.width="100%"; 
	    bgObj.style.height=document.body.scrollHeight + 200 + "px";
	    bgObj.style.zIndex = "5"; ; 
	    document.body.appendChild(bgObj);
	},
	/**
	* 获取触发对象的位置及尺寸
	* 
	*/
	getClientRect:function() {
		var ol,ot,ow,oh;
		var self = this;
		var el = self.btnObj;
		ol = 0;
		ot = 0;
		while (el!=null) {
			ot += el.offsetTop;
			ol += el.offsetLeft;
			el=el.offsetParent;
		}
		ow = parseInt(self.btnObj.offsetWidth);
		oh = parseInt(self.btnObj.offsetHeight);
		return{
			Left: ol,
			Top: ot,
			Width: ow,
			Height: oh
		};
	},
	/**
	* 提交触发事件
	* 
	*/
	doSelect:function() {
		var self = this;
		if(self.panel.trigger)
		{
			var tent = $("tent");
			
			var resCode = [];
			var resName = [];
			var nobr = tent.getElementsByTagName("nobr");
			var _hd = document.getElementsByName("_hd");
			
			if(self.panel.sbmtUrl && self.panel.sbmtUrl.length>0)
			{
				for(var i=0;i<nobr.length;i++)
				{
					//alert(nobr[i].id.substr(nobr[i].id.indexOf("_")+1)+" "+nobr[i].innerHTML);
					resCode.push("arg"+i+"="+nobr[i].id.substr(nobr[i].id.indexOf("_")+1));
					
				}
				var arg = "&"+resCode.toString().replace(/,/g,'&');
				//alert("?Mod="+this.panel.sbmtUrl[1]+arg);
				self.request("/ajax/js.php", "?Mod="+self.panel.sbmtUrl+arg, function(o) {
					//var txt = o.responseText.replace(/"|'/g,'\"');
					eval(self.panel.callFunc+"('"+o.responseText.replace(/"|'/g,'\"')+"')");
																 
				});
			}
			else{
				for(var i=0;i<nobr.length;i++)
				{
					//alert(nobr[i].id.substr(nobr[i].id.indexOf("_")+1)+" "+nobr[i].innerHTML);
					resCode.push(nobr[i].id.substr(nobr[i].id.indexOf("_")+1));
					resName.push(nobr[i].innerHTML.replace(/&nbsp;\|&nbsp;/,""));
					
				}
				$(self.panel.target).value = resCode.toString();
				$(self.panel.target+"_text").value = resName.toString();
			}
		}
		else {
			if(self.panel.sbmtUrl && self.panel.sbmtUrl.length>1) {
				var arg = "&arg="+arguments[1];
				self.request("/ajax/js.php", "?Mod="+self.panel.sbmtUrl+arg, function(o) {
					//var txt = o.responseText.replace(/"|'/g,'\"');
					eval(self.panel.callFunc+"('"+o.responseText.replace(/"|'/g,'\"')+"')");									 
				});
			}else {
				if(self.app != "month") {
					$(arguments[0]).value = arguments[1];
					$($(arguments[0]).id+"_text").value = arguments[2];
				}else {
					$(arguments[0]).value = $("level1").value+","+arguments[1];
					$($(arguments[0]).id+"_text").value = $("level1").value+"年"+arguments[2];
				}
			}
		}
		self.hideDiv();
	},
	doAny:function() {
		$(this.panel.target).value = "";
		$(this.panel.target+"_text").value = "";
		this.hideDiv();
	},
	alert:function(str) {
		alert(str);
	}
	
}
/**
* rewrite the function $()
*/
function $()
{
	var elements = new Array();
	for(var i=0;i<arguments.length;i++)
	{
		var element = arguments[i];
		if(typeof element == "string")
			element = document.getElementById(element);
		if(arguments.length == 1)
			return element;
		elements.push(element);
	}
	
	return elements;
}
//-----------------------------------------------------------------------------
