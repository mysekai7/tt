
//配置（可扩展）
/*var appArray = 
	{"city":{"title":"省市","deep":2,"trigger":0,"script":1,"url":[["/0.js","/11.js"],
																   ["Demo:listProvince","Demo:listCity&id=11"]]},
	 "speciality":{"title":"专业","deep":3,"trigger":1,"script":0,"url":[["/0.js","/110000.js","/110100.js"],
																		 []]},
	 "trade":{"title":"行业","deep":2,"trigger":1,"script":0,"url":[["/0.js","/1100.js"],
																		 []]}
	};*/
//根目录
var root = "/js/select/";
var gateWay = "/ajax/js.php?Mod=";
var object = "";
function initObj(btnObj,app,title,deep,trigger,script,url,target,sbmtUrl,callFunc)
{
	object = new PObject(btnObj,app,title,deep,trigger,script,url,target,sbmtUrl,callFunc);
}
function PObject(btnObj,app,title,deep,trigger,script,url,target,sbmtUrl,callFunc)
{
	//应用名称
	this.app = app;
	this.btnObj = btnObj;
	this.popSrc = "panel_"+app+".html";
	
	this.initPanel(title,deep,trigger,script,url,target,sbmtUrl,callFunc);
	this.showDiv();
	
}

PObject.prototype = {
	initPanel:function(title,deep,trigger,script,url,target,sbmtUrl,callFunc) {
		var self = this;
		
		self.panel = new Object();
		self.panel.title = title;
		self.panel.deep = deep;
		self.panel.trigger = trigger;
		self.panel.script = script;
		self.panel.url = url;
		self.panel.target = target;
		
		self.panel.sbmtUrl = sbmtUrl;
		self.panel.callFunc = callFunc;
		self.panel.execCode = "";

		self.panel.drawPanel = function() {
			var _url,flType,_target;
			self.request(root+self.popSrc, "", function(o) {
				if(self.panel.deep == 1)
					$("divContent").innerHTML = "<div id=\"level1\" style=\"height:150px; border: 1px dashed #000; background-color:#ebecee; overflow: auto;\"></div>";
				else
					$("divContent").innerHTML = o.responseText;
				//alert(self.panel.deep);
				//alert(root+self.app+"/0.js");
				for(var i=0;i<self.panel.deep;i++)
				{
					if(self.panel.script)
						_url = gateWay+self.panel.url[i];
					else
						_url = root+self.app+"/"+self.panel.url[i];
					
					flType = i==self.panel.deep-1?self.panel.trigger?"flCheck":"flClick":"flSelect";
					_target = "level"+(i+1);
					
					self.panel.execCode += "self.request(\""+_url+"\", \"\", function(o) { var data = self.panel.parses(o.responseText); self.panel."+flType+"(\""+_target+"\", data); if($(\""+_target+"\").id.substr($(\""+_target+"\").id.length-1)<self.panel.deep) $(\""+_target+"\").onchange=self.panel.onChange; else { self.panel.initCheck(); self.panel.initAll();} });";
				}
				//alert(self.panel.execCode);
				eval(self.panel.execCode);
				$("level"+parseInt(self.panel.deep)).className = self.panel.trigger?"":"mainContent";
				
				//初始化
				$("smt").style.display = self.panel.trigger?"block":"none";
				self.panel.initTent();
								
			});
			
		}
		self.panel.onChange = function(event) {
			var e = window.event || event;
			var ele = e.srcElement ? e.srcElement : e.target;
			self.panel.assignNext(ele);
			
		}
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
		self.panel.nextlevel = function(ele) {
			
			return parseInt(ele.id.substr(ele.id.length-1))+1;
		}
		self.panel.nextid = function(ele) {
			
			return "level"+(parseInt(ele.id.substr(ele.id.length-1))+1);
		}
		self.panel.assignNext = function(ele) {
			var el = ele;
			var _url = "";
			if(typeof ele.value != "undefined")
			{
				if(self.panel.script)
				{
					var nLevel = self.panel.nextlevel(el);
					_url = gateWay+self.panel.url[nLevel-1];
					if(_url.indexOf('&')>-1)
					{
						var tmp = _url.split("&");
						_url = tmp[0]+"&id="+el.value;
					}
				}
				else
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
		self.panel.flSelect = function(ele, data) {
			var el = $(ele);
			el.options.length = 0;
			for(var i=0; i<data.length; i++)
			{
				option = document.createElement("OPTION"); 
				option.text = data[i][1];
				option.value = data[i][0];
				
				el.options[i] = option;
			}
		}
		self.panel.flCheck = function(ele, data) {
			var el = $(ele);
			el.innerHTML = "";
			var html = "";
			for(var i=0; i<data.length; i++)
			{
				html += "<nobr><input type=\"hidden\" name=\"hd\" value=\""+data[i][1]+"\"><input type=\"checkbox\" id=\"ck_"+data[i][0]+"\"  name=\"ck\" value=\"" + data[i][0] + "\" onclick=\"object.panel.checkOne()\">"+data[i][1]+"&nbsp;</nobr>";  
			}
			el.innerHTML = html;
		}
		self.panel.flClick = function(ele, data) {
			var el = $(ele);
			el.innerHTML = "";
			var html = "";
			for(var i=0; i<data.length; i++)
			{
				html += "<a href=\"javascript:void(0);\" onclick=\"javascript:object.doSelect('"+self.panel.target+"','"+data[i][0]+"','"+data[i][1]+"');\">"+data[i][1]+"</a>";  
			}
			el.innerHTML = html;
		}
		self.panel.checkAll = function(obj) {
			var ck = document.getElementsByName("ck");
			for(var i=0;i<ck.length;i++)
			{
				ck[i].checked = obj.checked;
			}
			self.panel.assignTent();
			
		}
		self.panel.checkOne = function() {
			var ck = document.getElementsByName("ck");
			var hd = document.getElementsByName("hd");
			for(var i=0;i<ck.length;i++)
			{
				if(!ck[i].checked) break;
			}
			$("checkAll").checked = ck[i]?ck[i].checked:true;
			
			//tent.style.display = "block";
			self.panel.assignTent();
		}
		self.panel.initTent = function() {
			var tent = $("tent");
			tent.innerHTML = "";
			if($(self.panel.target).value)
			{
				var tars = $(self.panel.target).value.split(",");
				var tarTexts = $($(self.panel.target).id+"_text").value.split(",");
				var nobr;
				for(var i=0;i<tars.length;i++)
				{
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
		self.panel.assignTent = function() {
			var ck = document.getElementsByName("ck");
			var hd = document.getElementsByName("hd");
			var tent = $("tent");
			for(var i=0;i<ck.length;i++)
			{
				var tmpele = $("sp_"+ck[i].value);
				if(ck[i].checked && !tmpele)
				{
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
					
				}
				else if(!ck[i].checked && tmpele)
				{
					tent.removeChild(tmpele);
				}
			}
			
		}
		self.panel.initCheck = function() {
			var tent = $("tent");
			//alert(this.panel.type);
			var nobr = tent.getElementsByTagName("nobr");
			var ck = document.getElementsByName("ck");
			//alert(tent.innerHTML);
			for(var i=0;i<ck.length;i++)
			{
				//alert(nobr[i].id.substr(nobr[i].id.indexOf("_")+1)+" "+nobr[i].innerHTML);
				if(tent.innerHTML.indexOf("sp_"+ck[i].value)>0)
					ck[i].checked = true;
				
			}
			
		}
		self.panel.initAll = function() {
			var ck = document.getElementsByName("ck");
			for(var i=0;i<ck.length;i++)
			{
				if(!ck[i].checked) break;
			}
			$("checkAll").checked = ck[i]?ck[i].checked:true;
		}
	},
	request:function(url, args, callback) {
		if(window.XMLHttpRequest){
            this.ro = new XMLHttpRequest();
        } else if(window.ActiveXObject){
            this.ro = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var ro = this.ro;
		this.ro.onreadystatechange= function(){
            if (ro.readyState == 4)
            {
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
	showDiv:function() {
		var self = this;
		var objDiv = $("_bgDiv");
		
		if (objDiv)
		{  
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
		  	
			self.panel.drawPanel(self.app);
			
		}
		
	},
	hideDiv:function() {
		$("_bgDiv").style.display = "none";
		this.showBody();	
		//alert(object.app);
		object = null;
		$("checkAll").checked = false;
		//alert(object);
		
	},
	showBody:function() {
		document.body.removeChild($('MM_bgDiv'));
	},
	hideBody:function() {
		var bgObj=document.createElement("div"); 
	    bgObj.setAttribute('id','MM_bgDiv'); 
	    bgObj.style.position="absolute"; 
	    bgObj.style.top="0"; 
	    bgObj.style.background="#cccccc"; 
	    bgObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75)"; 
	    bgObj.style.opacity="0.6"; 
	    bgObj.style.left="0"; 
	    bgObj.style.width="100%"; 
	    bgObj.style.height=document.body.scrollHeight + 200 + "px";
	    bgObj.style.zIndex = "5"; ; 
	  
	    document.body.appendChild(bgObj);
	},
	getClientRect:function() {
		var ol;
		var ot;
		var ow;
		var oh;
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
		else
		{
			if(self.panel.sbmtUrl && self.panel.sbmtUrl.length>1)
			{
				var arg = "&arg="+arguments[1];
				self.request("/ajax/js.php", "?Mod="+self.panel.sbmtUrl+arg, function(o) {
					//var txt = o.responseText.replace(/"|'/g,'\"');
					
					eval(self.panel.callFunc+"('"+o.responseText.replace(/"|'/g,'\"')+"')");
																 
				});
			}
			else {
				$(arguments[0]).value = arguments[1];
				$($(arguments[0]).id+"_text").value = arguments[2];
			}
		}
		self.hideDiv();
	},
	alert:function(str) {
		alert(str);
	}
	
}

/*
function createIframe(L,T,W,H)
{
	
  var tmpObj=document.createElement("iframe") 
  tmpObj.setAttribute("id","MM_Iframe"); 
  tmpObj.style.position = "absolute"; 
  tmpObj.style.border = "0px"; 
  tmpObj.style.left = L;
  tmpObj.style.top = T;
  tmpObj.style.width = W; 
  tmpObj.style.height = H;
  tmpObj.style.zIndex = "9999"; 
  return tmpObj;
}

*/
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
