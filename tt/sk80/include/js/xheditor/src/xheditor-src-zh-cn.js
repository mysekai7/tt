/*!
 * xhEditor - WYSIWYG XHTML Editor
 * @requires jQuery v1.4.2
 * 
 * @author Yanis.Wang<yanis.wang@gmail.com>
 * @site http://xheditor.com/
 * @licence LGPL(http://www.opensource.org/licenses/lgpl-license.php)
 * 
 * @Version: 1.0.0 rc1 build 100301
 */
(function($){
$.fn.xheditor=function(bInit,options)
{
	var arrSuccess=[];
	this.each(function(){
		if(this.tagName.toLowerCase()!='textarea')return;
		if(bInit)//初始化
		{
			if(!this.editor)
			{
				var tOptions=$(this).attr('xheditor');
				if(tOptions)
				{
					try{tOptions=eval('('+tOptions+')');}catch(ex){};
					options=$.extend({},tOptions,options );
				}
				var editor=new $.xheditor(this,options);
				if(editor.init())
				{
					this.editor=editor;
					arrSuccess.push(editor);
				}
				else editor=null;	
			}
		}
		else//卸载
		{
			if(this.editor)
			{
				this.editor.remove();
				this.editor=null;
			}
		}
	});
	if(arrSuccess.length==0)arrSuccess=false;
	if(arrSuccess.length==1)arrSuccess=arrSuccess[0];
	return arrSuccess;
	
}
var xCount=0,browerVer=$.browser.version,isIE=$.browser.msie,isMozilla=$.browser.mozilla,isSafari=$.browser.safari,bShowPanel=false,bClickCancel=true;
var _jPanel,_jShadow,_jCntLine,_jPanelButton;
var jsUrl;
$('script[src*=xheditor]').each(function(){
	var s=this.src;
	if(s.match(/xheditor[^\/]*\.js/i)){jsUrl=s.replace(/[\?#].*$/, '').replace(/(^|[\/\\])[^\/]*$/, '$1');return false;}
});

var specialKeys={ 27: 'esc', 9: 'tab', 32:'space', 13: 'enter', 8:'backspace', 145: 'scroll', 
          20: 'capslock', 144: 'numlock', 19:'pause', 45:'insert', 36:'home', 46:'del',
          35:'end', 33: 'pageup', 34:'pagedown', 37:'left', 38:'up', 39:'right',40:'down', 
          112:'f1',113:'f2', 114:'f3', 115:'f4', 116:'f5', 117:'f6', 118:'f7', 119:'f8', 120:'f9', 121:'f10', 122:'f11', 123:'f12' };
var itemColors=['#FFFFFF','#CCCCCC','#C0C0C0','#999999','#666666','#333333','#000000','#FFCCCC','#FF6666','#FF0000','#CC0000','#990000','#660000','#330000','#FFCC99','#FF9966','#FF9900','#FF6600','#CC6600','#993300','#663300','#FFFF99','#FFFF66','#FFCC66','#FFCC33','#CC9933','#996633','#663333','#FFFFCC','#FFFF33','#FFFF00','#FFCC00','#999900','#666600','#333300','#99FF99','#66FF99','#33FF33','#33CC00','#009900','#006600','#003300','#99FFFF','#33FFFF','#66CCCC','#00CCCC','#339999','#336666','#003333','#CCFFFF','#66FFFF','#33CCFF','#3366FF','#3333FF','#000099','#000066','#CCCCFF','#9999FF','#6666CC','#6633FF','#6600CC','#333399','#330099','#FFCCFF','#FF99FF','#CC66CC','#CC33CC','#993399','#663366','#330033'];
var arrBlocktag=[{n:'p',t:'普通段落'},{n:'h1',t:'标题1'},{n:'h2',t:'标题2'},{n:'h3',t:'标题3'},{n:'h4',t:'标题4'},{n:'h5',t:'标题5'},{n:'h6',t:'标题6'},{n:'pre',t:'已编排格式'},{n:'address',t:'地址'}];
var arrFontname=[{n:'宋体',c:'SimSun'},{n:'仿宋体',c:'FangSong_GB2312'},{n:'黑体',c:'SimHei'},{n:'楷体',c:'KaiTi_GB2312'},{n:'微软雅黑',c:'Microsoft YaHei'},{n:'Arial'},{n:'Arial Narrow'},{n:'Arial Black'},{n:'Comic Sans MS'},{n:'Courier New'},{n:'System'},{n:'Times New Roman'},{n:'Tahoma'},{n:'Verdana'}];
var arrFontsize=[{n:'xx-small',wkn:'x-small',s:'8pt',t:'极小'},{n:'x-small',wkn:'small',s:'10pt',t:'特小'},{n:'small',wkn:'medium',s:'12pt',t:'小'},{n:'medium',wkn:'large',s:'14pt',t:'中'},{n:'large',wkn:'x-large',s:'18pt',t:'大'},{n:'x-large',wkn:'xx-large',s:'24pt',t:'特大'},{n:'xx-large',wkn:'-webkit-xxx-large',s:'36pt',t:'极大'}];
var menuAlign=[{s:'左对齐',v:'justifyleft'},{s:'居中',v:'justifycenter'},{s:'右对齐',v:'justifyright'},{s:'两端对齐',v:'justifyfull'}],menuList=[{s:'数字列表',v:'insertOrderedList'},{s:'符号列表',v:'insertUnorderedList'}];
var htmlPastetext='<div>使用键盘快捷键(Ctrl+V)把内容粘贴到方框里，按 确定</div><div><textarea id="xhePastetextValue" wrap="soft" spellcheck="false" style="width:300px;height:100px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
var htmlLink='<div>链接地址: <input type="text" id="xheLinkHref" value="http://" class="xheText" /></div><div>打开方式: <select id="xheLinkTarget"><option selected="selected" value="">默认</option><option value="_blank">新窗口</option><option value="_self">当前窗口</option><option value="_parent">父窗口</option></select></div><div style="display:none">链接文字: <input type="text" id="xheLinkText" value="" class="xheText" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
var htmlImg='<div>图片文件: <input type="text" id="xheImgSrc" value="http://" class="xheText" /></div><div>替换文本: <input type="text" id="xheImgAlt" /></div><div>对齐方式: <select id="xheImgAlign"><option selected="selected" value="">默认</option><option value="left">左对齐</option><option value="right">右对齐</option><option value="top">顶端</option><option value="middle">居中</option><option value="baseline">基线</option><option value="bottom">底边</option></select></div><div>宽度高度: <input type="text" id="xheImgWidth" style="width:40px;" /> x <input type="text" id="xheImgHeight" style="width:40px;" /></div><div>边框大小: <input type="text" id="xheImgBorder" style="width:40px;" /></div><div>水平间距: <input type="text" id="xheImgHspace" style="width:40px;" /> 垂直间距: <input type="text" id="xheImgVspace" style="width:40px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
var htmlFlash='<div>动画文件: <input type="text" id="xheFlashSrc" value="http://" class="xheText" /></div><div>宽度高度: <input type="text" id="xheFlashWidth" style="width:40px;" value="480" /> x <input type="text" id="xheFlashHeight" style="width:40px;" value="400" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
var htmlMedia='<div>媒体文件: <input type="text" id="xheMediaSrc" value="http://" class="xheText" /></div><div>宽度高度: <input type="text" id="xheMediaWidth" style="width:40px;" value="480" /> x <input type="text" id="xheMediaHeight" style="width:40px;" value="400" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
var htmlTable='<div>行数列数: <input type="text" id="xheTableRows" style="width:40px;" value="3" /> x <input type="text" id="xheTableColumns" style="width:40px;" value="2" /></div><div>标题单元: <select id="xheTableHeaders"><option selected="selected" value="">无</option><option value="row">第一行</option><option value="col">第一列</option><option value="both">第一行和第一列</option></select></div><div>宽度高度: <input type="text" id="xheTableWidth" style="width:40px;" value="200" /> x <input type="text" id="xheTableHeight" style="width:40px;" value="" /></div><div>边框大小: <input type="text" id="xheTableBorder" style="width:40px;" value="1" /></div><div>表格间距: <input type="text" id="xheTableCellSpacing" style="width:40px;" value="1" /> 表格填充: <input type="text" id="xheTableCellPadding" style="width:40px;" value="1" /></div><div>对齐方式: <select id="xheTableAlign"><option selected="selected" value="">默认</option><option value="left">左对齐</option><option value="center">居中</option><option value="right">右对齐</option></select></div><div>表格标题: <input type="text" id="xheTableCaption" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
var htmlAbout='<div style="width:200px;word-wrap:break-word;word-break:break-all;"><p><span style="font-size:20px;color:#1997DF;">xhEditor</span><br />v1.0.0 rc1 build 100301</p><p>xhEditor是一个基于jQuery开发的跨平台开源迷你XHTML编辑器组件。</p><p><a href="http://xheditor.com/" target="_blank">http://xheditor.com/</a></p></div>';
var itemEmots={'default':{'name':'默认','width':25,'height':18,'line':6,'list':{'biggrin':'Big grin','smile':'Smile','titter':'Titter','lol':'Lol','call':'Call','victory':'Victory','shy':'Shy','handshake':'Handshake','kiss':'Kiss','sad':'Sad','cry':'Cry','huffy':'Huffy','mad':'Mad','tongue':'Tongue','sweat':'Sweat','shocked':'Shocked','time':'Time','hug':'Hug'}}};
var arrTools={GStart:{},GEnd:{},Separator:{},Cut:{t:'剪切 (Ctrl+X)'},Copy:{t:'复制 (Ctrl+C)'},Paste:{t:'粘贴 (Ctrl+V)'},Pastetext:{t:'粘贴文本'},Blocktag:{t:'段落标签'},Fontface:{t:'字体'},FontSize:{t:'字体大小'},Bold:{t:'加粗 (Ctrl+B)',s:'Ctrl+B'},Italic:{t:'斜体 (Ctrl+I)',s:'Ctrl+I'},Underline:{t:'下划线 (Ctrl+U)',s:'Ctrl+U'},Strikethrough:{t:'删除线 (Ctrl+S)',s:'Ctrl+S'},FontColor:{t:'字体颜色'},BackColor:{t:'背景颜色'},Removeformat:{t:'删除文字格式'},Align:{t:'对齐'},List:{t:'列表'},Outdent:{t:'减少缩进 (Shift+Tab)',s:'Shift+Tab'},Indent:{t:'增加缩进 (Tab)',s:'Tab'},Link:{t:'超链接'},Unlink:{t:'取消超链接'},Img:{t:'图片'},Flash:{t:'Flash动画'},Media:{t:'多媒体文件'},Emot:{t:'表情'},Table:{t:'表格'},Source:{t:'源代码'},Preview:{t:'预览'},Fullscreen:{t:'全屏编辑 (Esc)',s:'Esc'},About:{t:'关于 xhEditor'}};
var toolsThemes={
	mini:'GStart,Bold,Italic,Underline,Strikethrough,GEnd,Separator,GStart,Align,List,GEnd,Separator,GStart,Link,Img,About,GEnd',
	simple:'GStart,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,GEnd,Separator,GStart,Align,List,Outdent,Indent,GEnd,Separator,GStart,Link,Img,Emot,About,GEnd',
	full:'GStart,Cut,Copy,Paste,Pastetext,GEnd,Separator,GStart,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,Removeformat,GEnd,Separator,GStart,Align,List,Outdent,Indent,GEnd,Separator,GStart,Link,Unlink,Img,Flash,Media,Emot,Table,GEnd,Separator,GStart,Source,Preview,Fullscreen,About,GEnd'};
$.xheditor=function(textarea,options)
{
	var defaults={skin:'default',tools:'full',clickCancelDialog:true,linkTag:false,internalScript:false,inlineScript:false,internalStyle:true,inlineStyle:true,showBlocktag:false,forcePtag:true,upLinkExt:"zip,rar,txt",upImgExt:"jpg,jpeg,gif,png",upFlashExt:"swf",upMediaExt:"wmv,avi,wma,mp3,mid",modalWidth:350,modalHeight:220,modalTitle:true,defLinkText:'点击打开链接',layerShadow:3,emotMark:false,upBtnText:'上传',wordDeepClean:true};
	var _this=this,_text=textarea,_jText=$(_text),_jForm=_jText.closest('form'),_jTools,_jArea,_win,_jWin,_doc,_jDoc,_jTempIframe;
	var bookmark;
	var bInit=false,bSource=false,bPreview=false,bFullscreen=false,bReadonly=false,bShowBlocktag=false,sLayoutStyle='',ev;
	var editHeight=0;
	this.settings=$.extend({},defaults,options );
	var plugins=_this.settings.plugins;
	if(plugins)
	{
		var arrName=[];
		$.each(plugins,function(n){arrName.push(n);});
		toolsThemes.full=toolsThemes.full.replace('Table','Table,'+arrName.join(','));//插件接在full的Table后面
		arrTools=$.extend({},arrTools,plugins);
	}
	if(_this.settings.tools.match(/^\s*(mini|simple|full)\s*$/i))
	{
		_this.settings.tools=$.trim(_this.settings.tools);
		_this.settings.tools=toolsThemes[_this.settings.tools];
	}
	if(!_this.settings.tools.match(/(^|,)\s*About\s*(,|$)/i))_this.settings.tools+=',About';
	_this.settings.tools=_this.settings.tools.split(',');
	jsUrl=getLocalUrl(jsUrl,'abs');

	//基本控件名
	var idCSS='xheCSS_'+_this.settings.skin,idContainer='xhe'+xCount+'_container',idTools='xhe'+xCount+'_Tool',idIframeArea='xhe'+xCount+'_iframearea',idIframe='xhe'+xCount+'_iframe';
	var bodyClass='',skinPath=jsUrl+'xheditor_skin/'+_this.settings.skin+'/',arrEmots=itemEmots,emotPath=getLocalUrl(jsUrl,'rel')+'xheditor_emot/',selEmotGroup='';
	arrEmots=$.extend({},arrEmots,_this.settings.emots );
	bShowBlocktag=_this.settings.showBlocktag;
	if(bShowBlocktag)bodyClass+=' showBlocktag';
	
	var arrShortCuts=[];
	this.init=function()
	{
		//加载样式表
		if($('#'+idCSS).length==0)$('head').append('<link id="'+idCSS+'" rel="stylesheet" type="text/css" href="'+skinPath+'ui.css" />');	
		//初始化编辑器
		var cw = _this.settings.width || _text.style.width || _jText.outerWidth();
		editHeight = _this.settings.height || _jText.outerHeight();
		if(editHeight<=0)//禁止对隐藏区域里的textarea初始化编辑器
		{
			alert('当前textarea处于隐藏状态，请将之显示后再初始化xhEditor，或者直接初始化时指定height高度值');
			return false;	
		}
		if(/^[0-9\.]+$/i.test(''+cw))cw+='px';
		
		//工具栏内容初始化
		var sToolHtml='',tool,cn;
		$.each(_this.settings.tools,function(i,n)
		{
			tool=arrTools[n];
			if(n=='GStart')sToolHtml+='<span class="xheGStart"/>';
			else if(n=='GEnd')sToolHtml+='<span class="xheGEnd"/>';
			else if(n=='Separator')sToolHtml+='<span class="xheSeparator"/>';
			else if(n=='BtnBr')sToolHtml+='<br />';
			else
			{
				if(tool.c)cn=tool.c;
				else cn='xheIcon xheBtn'+n;
				sToolHtml+='<span><a href="javascript:void(0);" title="'+tool.t+'" name="'+n+'" class="xheButton xheEnabled" tabindex="-1"><span class="'+cn+'" /></a></span>';
				if(tool.s)_this.addShortcuts(tool.s,n);
			}
		});
		sToolHtml+='<br />';

		_jText.after($('<span id="'+idContainer+'" class="xhe_'+_this.settings.skin+'" style="display:none"><table cellspacing="0" cellpadding="0" class="xheLayout" style="width:'+cw+';height:'+editHeight+'px;"><tbody><tr><td id="'+idTools+'" class="xheTool" style="height:1px;"></td></tr><tr><td id="'+idIframeArea+'" class="xheIframeArea"><iframe frameborder="0" id="'+idIframe+'" src="" style="width:100%;"></iframe></td></tr></tbody></table></span>'));
		_jTools=$('#'+idTools);_jArea=$('#'+idIframeArea);
		var iframeHTML='<html><head><meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/><link rel="stylesheet" href="'+skinPath+'iframe.css"/>';
		if(_this.settings.loadCSS)
		{
			var loadCSS=_this.settings.loadCSS;
			if(is(loadCSS,'array'))for(var i in loadCSS)iframeHTML+='<link rel="stylesheet" href="'+loadCSS[i]+'"/>';
			else iframeHTML+='<link rel="stylesheet" href="'+loadCSS+'"/>';
		}
		iframeHTML+='</head><body spellcheck="false" dir="ltr" class="editMode'+bodyClass+'"></body></html>';
		_win=$('#'+idIframe)[0].contentWindow;
		_jWin=$(_win);
		try{
			_doc = _win.document;_jDoc=$(_doc);
			_doc.open();
			_doc.write(iframeHTML);
			_doc.close();
			if(isIE)_doc.body.contentEditable='true';
			else _doc.designMode = 'On';
		}catch(e){}
		setTimeout(_this.setOpts,300);
		_this.setSource();
		_win.setInterval=null;
		
		//添加工具栏
		_jTools.append(sToolHtml);
		_jTools.find('.xheButton').click(function(event)
		{
			_this.hidePanel();
			_this.focus();
			ev=event;
			var aButton=$(this);
			if(aButton.is('.xheEnabled'))_this.exec(aButton.attr('name'));
			return false;
		}).mousedown(function(){return false;});
		//初始化面板
		_jPanel=$('#xhePanel');
		_jShadow=$('#xheShadow');
		_jCntLine=$('#xheCntLine');
		if(_jPanel.length==0)
		{
			_jPanel=$('<div id="xhePanel"></div>').mousedown(function(ev){ev.stopPropagation()});
			_jShadow=$('<div id="xheShadow"></div>');
			_jCntLine=$('<div id="xheCntLine"><img src="'+skinPath+'img/spacer.gif" /></div>');
			$(document.body).append(_jPanel).append(_jShadow).append(_jCntLine);
		}
		$(document).mousedown(_this.clickCancelPanel);
		_jDoc.mousedown(_this.clickCancelPanel);
		//修正IE光标丢失
		if(isIE)
		{
			_jDoc.bind('beforedeactivate',function(){if(isIE&&!bSource)bookmark=_this.getRng();});
			_jWin.focus(function(){if(isIE&&!bSource&&bookmark){bookmark.select();bookmark=null;}});
		}
		//创建临时iframe
		_jTempIframe=$('<iframe class="xheHideArea" />').appendTo('body');
		
		//切换显示区域
		$('#'+idContainer).show();
		_jArea.css('height',editHeight-_jTools.outerHeight());
		_jText.hide();
		
		//绑定内核事件
		_jText.focus(_this.focus);
		_jForm.submit(_this.getSource).bind('reset', _this.setSource);
		var jpWin=$(window);
		jpWin.unload(_this.saveUnload).bind('beforeunload', _this.saveUnload);
		jpWin.resize(_this.fixFullHeight);
		_jWin.focus(function(){if(_this.settings.focus)_this.settings.focus();}).blur(function(){if(_this.settings.blur)_this.settings.blur();});
		if(isSafari)_jWin.click(_this.fixAppleSel);
		_jDoc.keydown(_this.checkShortcuts).keydown(_this.forcePtag).bind('mousedown click',function(ev){_jText.trigger(ev.type);});
		$('body',_jDoc).bind('paste',_this.cleanPaste);
		
		//添加用户快捷键
		var shortcuts=_this.settings.shortcuts;
		if(shortcuts)$.each(shortcuts,function(key,func){_this.addShortcuts(key,func);});
		
		xCount++;
		bInit=true;
		if(_this.settings.fullscreen)_this.toggleFullscreen();
		if(_this.settings.readonly)_this.toggleReadonly(true);
		else if(_this.settings.sourceMode)setTimeout(_this.toggleSource,20);
		return true;
	}
	this.remove=function()
	{
		if(bShowPanel)_this.hidePanel();
		//取消绑定事件
		_jText.unbind('focus',_this.focus);
		_jForm.unbind('submit',_this.getSource).unbind('reset', _this.setSource);
		var jpWin=$(window);
		jpWin.unbind('unload',_this.saveUnload).unbind('beforeunload', _this.saveUnload);
		jpWin.unbind('resize',_this.fixFullHeight);
		
		$('#'+idContainer).remove();
		_jText.show();
		bInit=false;
	}
	this.saveUnload=function(){_this.getSource();};
	this.cleanPaste=function()//清理粘贴内容中的代码
	{
		if(isIE&&!bSource)
		{
			var sHtml,tbody=_jTempIframe[0].contentWindow.document.body;
			tbody.innerHTML = '';
			tbody.createTextRange().execCommand("Paste");
			sHtml=tbody.innerHTML;
			if(sHtml.indexOf('&nbsp;')==0)sHtml=sHtml.substring(6);
			sHtml=_this.cleanWord(sHtml);
			sHtml=_this.cleanHTML(sHtml);
			_this.pasteHTML(sHtml);
			return false;
		}
	}
	this.setCSS=function(css)
	{
		try{_this._exec('styleWithCSS',css);}
		catch(e)
		{try{_this._exec('useCSS',!css);}catch(e){}}
	}
	this.setOpts=function()
	{
		if(bInit&&!bPreview&&!bSource)
		{
			_this.setCSS(false);
			try{_this._exec('enableObjectResizing',true);}catch(e){}
			//try{_this._exec('enableInlineTableEditing',false);}catch(e){}
			if(isIE)try{_this._exec('BackgroundImageCache',true);}catch(e){}
		}
	}
	this.forcePtag=function(ev)
	{
		if(bSource||bPreview||ev.keyCode!=13||ev.shiftKey||ev.ctrlKey||ev.altKey)return true;
		var pNode=_this.getParent('p,h1,h2,h3,h4,h5,h6,pre,address,div,li');
		if(_this.settings.forcePtag){if(pNode.length==0)_this._exec('formatblock','<p>');}
		else
		{
			_this.pasteHTML('<br />');
			return false;
		}
	}
	this.fixFullHeight=function()
	{		
		if(!isMozilla&&!isSafari)
		{
			_jArea.height('100%');
			if(bFullscreen)_jArea.css('height',_jArea.outerHeight()-_jTools.outerHeight());
			if(isIE)_jTools.hide().show();
		}
	}
	this.fixAppleSel=function(e)
	{
		e=e.target;
		if(e.tagName.match(/(img|embed)/i))
		{
			var sel=_this.getSel(),rng=_this.getRng();
			rng.selectNode(e);
			sel.removeAllRanges();
			sel.addRange(rng);
		}
	}
	this.focus=function()
	{
		if(!bSource)_jWin.focus();
		else $('#sourceCode',_doc).focus();
		return false;
	} 
	this.getSel=function()
	{
		return _win.getSelection ? _win.getSelection() : _doc.selection;
	}
	this.getRng=function()
	{
		var sel=_this.getSel(),rng;
		try{
			rng = sel.rangeCount > 0 ? sel.getRangeAt(0) : (sel.createRange ? sel.createRange() : _doc.createRange());
		}catch (ex){}
		if(!rng)rng = isIE ? _doc.body.createTextRange() : _doc.createRange();	
		return rng;
	}
	this.getParent=function(tag)
	{
		var rng=_this.getRng(),p;
		if(!isIE)
		{
			p = rng.commonAncestorContainer;
			if(!rng.collapsed)if(rng.startContainer == rng.endContainer&&rng.startOffset - rng.endOffset < 2&&rng.startContainer.hasChildNodes())p = rng.startContainer.childNodes[rng.startOffset];
		}
		else p=rng.item?rng.item(0):rng.parentElement();
		tag=tag?tag:'*';p=$(p);
		if(!p.is(tag))p=$(p).closest(tag);
		return p;
	}
	this.getSelect=function(format)
	{
		var sel=_this.getSel(),rng=_this.getRng(),isCollapsed=true;
		if (!rng || rng.item)isCollapsed=false
		else isCollapsed=!sel || rng.boundingWidth == 0 || rng.collapsed;
		if(format=='text')return isCollapsed ? '' : (rng.text || (sel.toString ? sel.toString() : ''));
		var sHtml;
		if(rng.cloneContents)
		{
			var tmp=$('<div></div>'),c;
			c = rng.cloneContents();
			if(c)tmp.append(c);
			sHtml=tmp.html();
		}
		else if(is(rng.item))sHtml=rng.item(0).outerHTML;
		else if(is(rng.htmlText))sHtml=rng.htmlText;
		else sHtml=rng.toString();
		if(isCollapsed)sHtml='';
		sHtml=_this.processHTML(sHtml,'read');
		sHtml=_this.formatXHTML(sHtml);
		sHtml=_this.cleanHTML(sHtml);
		return sHtml;
	}
	function is(o,t)
	{
		var n = typeof(o);
		if (!t)return n != 'undefined';
		if (t == 'array' && (o.hasOwnProperty && o instanceof Array))return true;
		return n == t;
	}
	this.pasteHTML=function(sHtml)
	{
		if(bSource||bPreview)return false;
		_this.focus();
		sHtml=_this.processHTML(sHtml,'write');
		var sel=_this.getSel(),rng=_this.getRng();
		sHtml+='<span id="_xhe_temp" />';
		if(rng.insertNode)
		{
			rng.deleteContents();
			rng.insertNode(rng.createContextualFragment(sHtml));
		}
		else
		{
			if(rng.item){_this._exec('delete');rng=_this.getRng()}
			rng.pasteHTML(sHtml);
		}

		var jc=$('#_xhe_temp',_doc),c=jc[0];
		if(isIE)
		{
			rng.moveToElementText(c);
			rng.select();
		}
		else
		{
			rng.selectNode(c); 
			sel.removeAllRanges();
			sel.addRange(rng);
		}
		jc.remove();
	}
	this.pasteText=function(text)
	{
		if(!text)text='';
		text=_this.domEncode(text);
		text = text.replace(/\r?\n/g, '<br />');
		_this.pasteHTML(text);
	}
	this.appendHTML=function(sHtml)
	{
		if(bSource||bPreview)return false;
		_this.focus();
		sHtml=_this.processHTML(sHtml,'write');
		$(_doc.body).append(sHtml);
	}
	this.domEncode=function(str)
	{
		return str.replace(/[<>]/g,function(c){return {'<':'&lt;','>':'&gt;'}[c];});
	}
	this.setSource=function(sHtml)
	{
		setTimeout(function(){_this._setSource(sHtml);},10);
	}
	this._setSource=function(sHtml)
	{
		bookmark=null;
		if(typeof sHtml!='string'&&sHtml!='')sHtml=_jText.val();
		if(bSource)$('#sourceCode',_doc).val(sHtml);
		else
		{
			if(_this.settings.beforeSetSource)sHtml=_this.settings.beforeSetSource(sHtml);
			sHtml=_this.formatXHTML(sHtml);
			sHtml=_this.cleanWord(sHtml);
			sHtml=_this.cleanHTML(sHtml);
			$(_doc.body)[0].innerHTML=_this.processHTML(sHtml,'write');
		}
	}
	this.processHTML=function(sHtml,mode)
	{
		var appleClass=' class="Apple-style-span"';
		if(mode=='write')
		{//write
			//恢复emot
			function restoreEmot(all,attr,q,emot){
				emot=emot.split(',');
				if(!emot[1]){emot[1]=emot[0];emot[0]=''}
				if(emot[0]=='default')emot[0]='';
				return all.replace(/\s+src\s*=\s*(["']?).*?\1(\s|$|\/|>)/i,'$2').replace(attr,' src="'+emotPath+(emot[0]?emot[0]:'default')+'/'+emot[1]+'.gif"'+(_this.settings.emotMark?' emot="'+(emot[0]?emot[0]+',':'')+emot[1]+'"':''));
			}
			sHtml = sHtml.replace(/<img(?:\s+[^>]*?)?(\s+emot\s*=\s*(["']?)\s*(.+?)\s*\2)(?:\s+[^>]*?)?\/?>/ig,restoreEmot);
			//保存属性值:src,href
			function saveValue(all,tag,attr,n,q,v){return all.replace(attr,attr+' _xhe_'+n+'="'+v+'"');}
			sHtml = sHtml.replace(/<(\w+(?:\:\w+)?)(?:\s+[^>]*?)?(\s+(src|href)\s*=\s*(["']?)\s*(.*?)\s*\4)(?:\s+[^>]*?)?\/?>/ig,saveValue);
				
			sHtml = sHtml.replace(/<(\/?)del(\s+[^>]*?)?>/ig,'<$1strike$2>');//编辑状态统一转为strike
			if(isMozilla)
			{
				sHtml = sHtml.replace(/<(\/?)strong(\s+[^>]*?)?>/ig,'<$1b$2>');
				sHtml = sHtml.replace(/<(\/?)em(\s+[^>]*?)?>/ig,'<$1i$2>');	
			}
			else if(isSafari)
			{
				sHtml = sHtml.replace(/("|;)\s*font-size\s*:\s*([a-z-]+)(;?)/ig,function(all,pre,sname,aft){
					var t,s;
					for(var i=0;i<arrFontsize.length;i++)
					{
						t=arrFontsize[i];
						if(sname==t.n){s=t.wkn;break;}
					}
					return pre+'font-size:'+s+aft;
				});
				sHtml = sHtml.replace(/<strong(\s+[^>]*?)?>/ig,'<span'+appleClass+' style="font-weight: bold;"$1>');
				sHtml = sHtml.replace(/<em(\s+[^>]*?)?>/ig,'<span'+appleClass+' style="font-style: italic;"$1>');
				sHtml = sHtml.replace(/<u(\s+[^>]*?)?>/ig,'<span'+appleClass+' style="text-decoration: underline;"$1>');
				sHtml = sHtml.replace(/<strike(\s+[^>]*?)?>/ig,'<span'+appleClass+' style="text-decoration: line-through;"$1>');
				sHtml = sHtml.replace(/<\/(strong|em|u|strike)>/ig,'</span>');
				sHtml = sHtml.replace(/<span((?:\s+[^>]*?)?\s+style="([^"]*;)*\s*(font-family|font-size|color|background-color)\s*:\s*[^;"]+\s*;?"[^>]*)>/ig,'<span'+appleClass+'$1>');
			}
			else if(isIE)
			{
				sHtml = sHtml.replace(/&apos;/ig, '&#39;');
				sHtml = sHtml.replace(/\s+(disabled|checked|readonly|selected)\s*=\s*[\"\']?(false|0)[\"\']?/ig, '');
			}
			sHtml = sHtml.replace(/<a(\s+[^>]*?)?\/>/,'<a$1></a>');
			
			if(!isSafari)
			{
				//style转font
				function style2font(all,tag,style,content)
				{
					var attrs='',f,s1,s2,c;
					f=style.match(/font-family\s*:\s*([^;"]+)/i);
					if(f)attrs+=' face="'+f[1]+'"';
					s1=style.match(/font-size\s*:\s*([^;"]+)/i);
					if(s1)
					{
						s1=s1[1].toLowerCase();
						for(var j=0;j<arrFontsize.length;j++)if(s1==arrFontsize[j].n||s1==arrFontsize[j].s){s2=j+1;break;}
						if(s2)
						{
							attrs+=' size="'+s2+'"';
							style=style.replace(/(^|;)(\s*font-size\s*:\s*[^;"]+;?)+/ig,'$1');
						}
					}
					c=style.match(/(?:^|[\s;])color\s*:\s*([^;"]+)/i);
					if(c)
					{
						var rgb;
						if(rgb=c[1].match(/\s*rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/i)){c[1]='#';for(var i=1;i<=3;i++)c[1]+=(rgb[i]-0).toString(16);}
						c[1]=c[1].replace(/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i,'#$1$1$2$2$3$3');
						attrs+=' color="'+c[1]+'"';
					}
					style=style.replace(/(^|;)(\s*(font-family|color)\s*:\s*[^;"]+;?)+/ig,'$1');
					if(attrs!='')
					{
						if(style)attrs+=' style="'+style+'"';
						return '<font'+attrs+'>'+content+"</font>";
					}
					else return all;
				}
				sHtml = sHtml.replace(/<(span)(?:\s+[^>]*?)?\s+style\s*=\s*"((?:[^"]*?;)*\s*(?:font-family|font-size|color)\s*:[^"]*)"(?: [^>]+)?>(((?!<\1(\s+[^>]*?)?>)[\s\S])*?)<\/\1>/ig,style2font);//最里层
				sHtml = sHtml.replace(/<(span)(?:\s+[^>]*?)?\s+style\s*=\s*"((?:[^"]*?;)*\s*(?:font-family|font-size|color)\s*:[^"]*)"(?: [^>]+)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?)<\/\1>/ig,style2font);//第2层
				sHtml = sHtml.replace(/<(span)(?:\s+[^>]*?)?\s+style\s*=\s*"((?:[^"]*?;)*\s*(?:font-family|font-size|color)\s*:[^"]*)"(?: [^>]+)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?<\/\1>)*?)<\/\1>/ig,style2font);//第3层
			}
		}
		else
		{//read
			//恢复属性值src,href
			var localUrl=_this.settings.localUrl;
			function restoreValue(all,n,q,v)
			{
				if(localUrl)v=getLocalUrl(v,localUrl);
				return all.replace(new RegExp('\\s+'+n+'\\s*=\\s*(["\']?).*?\\1([\\s/>])','ig'),' '+n+'="'+v.replace(/\$/g,'$$$$')+'"$2');
			}
			sHtml = sHtml.replace(/<(?:\w+(?:\:\w+)?)(?:\s+[^>]*?)?\s+_xhe_(src|href)\s*=\s*(["']?)\s*(.*?)\s*\2(?:\s+[^>]*?)?\/?>/ig,restoreValue);

			if(isSafari)
			{
				sHtml = sHtml.replace(/("|;)\s*font-size\s*:\s*([a-z-]+)(;?)/ig,function(all,pre,sname,aft){
					var t,s;
					for(var i=0;i<arrFontsize.length;i++)
					{
						t=arrFontsize[i];
						if(sname==t.wkn){s=t.n;break;}
					}
					return pre+'font-size:'+s+aft;
				});
				var arrAppleSpan=[{r:/font-weight:\sbold/ig,t:'strong'},{r:/font-style:\sitalic/ig,t:'em'},{r:/text-decoration:\sunderline/ig,t:'u'},{r:/text-decoration:\sline-through/ig,t:'strike'}];
				function replaceAppleSpan(all,tag,attr1,attr2,content)
				{
					var attr=attr1+attr2,newTag='';
					for(var i=0;i<arrAppleSpan.length;i++)
					{
						if(attr.match(arrAppleSpan[i].r))
						{
							newTag=arrAppleSpan[i].t;
							break;
						}
					}
					if(newTag)return '<'+newTag+'>'+content+'</'+newTag+'>';
					else return all;					
				}
				sHtml = sHtml.replace(/<(span)(\s+[^>]*?)?\s+class\s*=\s*"Apple-style-span"(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S])*?)<\/\1>/ig,replaceAppleSpan);//最里层
				sHtml = sHtml.replace(/<(span)(\s+[^>]*?)?\s+class\s*=\s*"Apple-style-span"(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?)<\/\1>/ig,replaceAppleSpan);//第2层
				sHtml = sHtml.replace(/<(span)(\s+[^>]*?)?\s+class\s*=\s*"Apple-style-span"(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?<\/\1>)*?)<\/\1>/ig,replaceAppleSpan);//第3层
			}
			sHtml = sHtml.replace(/\s+(?:_xhe_|_moz_|_webkit_)[^=]+?\s*=\s*(["']?).*?\1([\s/>])/ig,'$2');
			sHtml = sHtml.replace(/(<\w+[^>]*?)\s+class\s*=\s*(["']?)\s*(?:apple|webkit)\-.+?\s*\2([\s/>])/ig, "$1$3");
		}
		
		return sHtml;
	}
	this.getSource=function(bFormat)
	{
		var sHtml;
		if(bSource)sHtml=$('#sourceCode',_doc).val();
		else
		{
			sHtml=_this.processHTML($(_doc.body).html(),'read');
			sHtml=sHtml.replace(/^\s*(?:<(p|div)(?:\s+[^>]*?)?>)?\s*<br[^>]*>\s*(?:<\/\1>)?\s*$/ig, '');//修正Firefox在空内容情况下多出来的代码
			sHtml=_this.formatXHTML(sHtml,bFormat);
			sHtml=_this.cleanWord(sHtml);
			sHtml=_this.cleanHTML(sHtml);
			if(_this.settings.beforeGetSource)sHtml=_this.settings.beforeGetSource(sHtml);
		}
		_jText.val(sHtml);
		return sHtml;
	}	
	this.cleanWord=function(sHtml)
	{
		if(sHtml.match(/mso-|MsoNormal/i))
		{
			var deepClean=_this.settings.wordDeepClean;
			
			//格式化
			sHtml = sHtml.replace(/(<link(?:\s+[^>]*?)?)\s+href\s*=\s*(["']?)\s*file:\/\/.+?\s*\2((?:\s+[^>]*?)?\s*\/?>)/ig, '');
			
			//区块标签清理
			sHtml = sHtml.replace(/<!--[\s\S]*?-->|<!(--)?\[[\s\S]+?\](--)?>|<style(\s+[^>]*?)?>[\s\S]*?<\/style>/ig, '');
			sHtml = sHtml.replace(/<\/?\w+:[^>]*>/ig, '');
			if(deepClean)sHtml = sHtml.replace(/<\/?(span|a|img)(\s+[^>]*?)?>/ig,'');

			//属性清理
			sHtml = sHtml.replace(/(<\w+(?:\s+[^>]*?)?)\s+class\s*=\s*(["']?)\s*mso.+?\s*\2((?:\s+[^>]*?)?\s*\/?>)/ig, "$1$3");//删除所有mso开头的样式
			sHtml = sHtml.replace(/(<\w+(?:\s+[^>]*?)?)\s+lang\s*=\s*(["']?)\s*.+?\s*\2((?:\s+[^>]*?)?\s*\/?>)/ig, "$1$3");//删除lang属性
			sHtml = sHtml.replace(/(<\w+(?:\s+[^>]*?)?)\s+align\s*=\s*(["']?)\s*left\s*\2((?:\s+[^>]*?)?\s*\/?>)/ig, "$1$3");//取消align=left

			//样式清理
			sHtml = sHtml.replace(/<\w+(?:\s+[^>]*?)?(\s+style\s*=\s*(["']?)\s*(.*?)\s*\2)(?:\s+[^>]*?)?\s*\/?>/ig,function(all,attr,p,styles){
				styles=$.trim(styles.replace(/\s*(mso-[^:]+:.+?|margin\s*:\s*0cm 0cm 0pt\s*|(text-align|font-variant|line-height)\s*:\s*.+?)(;|$)\s*/ig,''));
				return all.replace(attr,deepClean?'':styles?' style="'+styles+'"':'');
			});
		}
		return sHtml;
	}
	this.cleanHTML=function(sHtml)
	{
		sHtml = sHtml.replace(/<\??xml(:\w+)?(\s+[^>]*?)?>([\s\S]*?<\/xml>)?/ig, '');
		sHtml = sHtml.replace(/<\/?(html|head|body|meta|title)(\s+[^>]*?)?>/ig, '');
		
		if(!_this.settings.linkTag)sHtml = sHtml.replace(/<link(\s+[^>]*?)?>/ig, '');
		if(!_this.settings.internalScript)sHtml = sHtml.replace(/<script(\s+[^>]*?)?>[\s\S]*?<\/script>/ig, '');
		if(!_this.settings.inlineScript)sHtml=sHtml.replace(/(<\w+)(\s+[^>]*?)?\s+on(?:click|dblclick|mousedown|mouseup|mousemove|mouseover|mouseout|mouseenter|mouseleave|keydown|keypress|keyup|change|select|submit|reset|blur|focus|load|unload)\s*=\s*(["']?).*?\3((?:\s+[^>]*?)?\/?>)/ig,'$1$2$4');
		if(!_this.settings.internalStyle)sHtml = sHtml.replace(/<style(\s+[^>]*?)?>[\s\S]*?<\/style>/ig, '');
		if(!_this.settings.inlineStyle)sHtml=sHtml.replace(/(<\w+)(\s+[^>]*?)?\s+style\s*=\s*(["']?).*?\3((?:\s+[^>]*?)?\/?>)/ig,'$1$2$4');
		
		for(var i=0;i<3;i++)sHtml=sHtml.replace(/<(strong|b|u|del|strike|s|em|i)(?:\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)([ \t\r\n]|&nbsp;))*?)<\/\1>/ig,function(all,tag,content){
				if(content.match(/&nbsp;/i))return content.replace(/ +/g,'');
				else return '';
		});//内部空白的标签: <span|b|u|s|i> &nbsp;</b>
		sHtml=sHtml.replace(/<\/(strong|b|u|strike|em|i)>((?:\s|<br\/?>|&nbsp;)*?)<\1(\s+[^>]*?)?>/ig,'$2');//连续相同标签

		sHtml = sHtml.replace(/<(p|div)(?:\s+[^>]*?)?>(((?!<\1(?: [^>]+)?>)[\s\S])+?)<\/\1>/ig,function(all,tag,content){//p内空白显示
			var temp=content.replace(/<\/?(span|strong|b|u|strike|em|i)(\s+[^>]*?)?>/ig,'');
			temp=temp.replace(/([ \t\r\n]|&nbsp;)+/ig,'');
			if(temp!='')return all;
			else return '<'+tag+'></'+tag+'>';
			});
		
		return sHtml;
	}
	this.formatXHTML=function(sHtml,bFormat)
	{
		var emptyTags = makeMap("area,base,basefont,br,col,frame,hr,img,input,isindex,link,meta,param,embed");	
		var blockTags = makeMap("address,applet,blockquote,button,center,dd,dir,div,dl,dt,fieldset,form,frameset,hr,iframe,ins,isindex,li,map,menu,noframes,noscript,object,ol,p,pre,script,table,tbody,td,tfoot,th,thead,tr,ul");
		var inlineTags = makeMap("a,abbr,acronym,applet,b,basefont,bdo,big,br,button,cite,code,del,dfn,em,font,i,iframe,img,input,ins,kbd,label,map,object,q,s,samp,script,select,small,span,strike,del,strong,sub,sup,textarea,tt,u,var");
		var closeSelfTags = makeMap("colgroup,dd,dt,li,options,p,td,tfoot,th,thead,tr");
		var fillAttrsTags = makeMap("checked,compact,declare,defer,disabled,ismap,multiple,nohref,noresize,noshade,nowrap,readonly,selected");
		var specialTags = makeMap("script,style");
		var tagReplac={'b':'strong','i':'em','s':'del','strike':'del'};
		var startTag = /^<(\w+(?:\:\w+)?)((?:\s+[\w-\:]*(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/;
		var endTag = /^<\/(\w+(?:\:\w+)?)[^>]*>/;
		var attr = /([\w-(?:\:\w+)?]+)(?:\s*=\s*(?:(?:"((?:\\.|[^"])*)")|(?:'((?:\\.|[^'])*)')|([^>\s]+)))?/g;
		var skip=0,stack=[],last=sHtml.replace(/\t*\r?\n\t*/g,''),results=Array(),lvl=-1,lastTag='body';
		stack.last = function(){return this[ this.length - 1 ];};
		while(last.length>0)
		{
			if(!stack.last()||!specialTags[stack.last()])
			{
				skip=0;
				if(last.substring(0, 4)=='<!--')
				{//注释标签
					skip=last.indexOf("-->");
					if(skip!=-1)
					{
						skip+=3;
						addHtmlFrag(last.substring(0,skip));
					}
				}
				else if(last.substring(0, 2)=='</')
				{//结束标签
					match = last.match( endTag );
					if(match)
					{
						match[0].replace(endTag,parseEndTag );
						skip = match[0].length;
					}
				}
				else if(last.charAt(0)=='<')
				{//开始标签
					match = last.match( startTag );
					if(match)
					{
						match[0].replace(startTag,parseStartTag);
						skip = match[0].length;
					}
				}
				if(skip==0)//普通文本
				{
					skip=last.indexOf('<');
					if(skip==0)skip=1;
					else if(skip<0)skip=last.length;
					addHtmlFrag(_this.domEncode(last.substring(0,skip)));
				}
				last=last.substring(skip);
			}
			else
			{//处理style和script
				last=last.replace(/^([\s\S]*?)<\/(style|script)>/i, function(all, script,tagName){
					addHtmlFrag(script);
					return ''
				});
				parseEndTag('',stack.last());
			}
		}
		parseEndTag();
		sHtml=results.join('');
		results=null;
		function makeMap(str)
		{
			var obj = {}, items = str.split(",");
			for ( var i = 0; i < items.length; i++ )obj[ items[i] ] = true;
			return obj;
		}
		function processTag(tagName)
		{
			if(tagName)
			{
				tagName=tagName.toLowerCase();
				var tag=tagReplac[tagName];
				if(tag)tagName=tag;
			}
			else tagName='';
			return tagName;
		}
		function parseStartTag( all, tagName, rest, unary )
		{
			tagName=processTag(tagName);
			if(blockTags[tagName])while(stack.last()&&inlineTags[stack.last()])parseEndTag("",stack.last());
			if(closeSelfTags[tagName]&&stack.last()==tagName )parseEndTag("",tagName);
			unary = emptyTags[ tagName ] || !!unary;
			if (!unary)stack.push(tagName);
			all=Array();
			all.push('<' + tagName);
			rest.replace(attr, function(match, name)
			{
				name=name.toLowerCase();
				var value = arguments[2] ? arguments[2] :
						arguments[3] ? arguments[3] :
						arguments[4] ? arguments[4] :
						fillAttrsTags[name] ? name : "";
				if(value)all.push(" "+name+'="'+value.replace(/(^|[^\\])"/g, '$1\\\"')+'"');
			});
			all.push((unary ? " /" : "") + ">");
			addHtmlFrag(all.join(''),tagName,true);
		}
		function parseEndTag(all, tagName)
		{
			if(!tagName)var pos = 0;
			else
			{
				tagName=processTag(tagName);
				for(var pos=stack.length-1;pos>=0;pos--)if(stack[pos]==tagName)break;
			}
			if(pos>=0)
			{
				for(var i=stack.length-1;i>=pos;i--)addHtmlFrag("</" + stack[i] + ">",stack[i]);
				stack.length=pos;
			}
		}
		function addHtmlFrag(html,tagName,bStart)
		{
			if(bFormat)
			{
				if(html.match(/^[\s\t]*$/))return;
				var bBlock=blockTags[tagName],tag=bBlock?tagName:'',tabs;
				if(bBlock)
				{
					if(bStart)lvl++;//块开始
					if(lastTag=='')lvl--;//补文本结束
				}
				else if(lastTag)lvl++;//文本开始		
				if(tag!=lastTag||bBlock)
				{
					results.push("\r\n");
					if(lvl>0){tabs=lvl;while(tabs--)results.push("\t");}
				}
				results.push(html);
				if(tagName=='br')//回车强制换行
				{
					results.push("\r\n");
					if(lvl>0){tabs=lvl;while(tabs--)results.push("\t");}
				}
				if(bBlock&&!bStart)lvl--;//块结束
				lastTag=bBlock?tagName:'';
			}
			else results.push(html);
		}
		//font转style
		function font2style(all,tag,attrs,content)
		{
			var styles='',f,s,c,style;
			f=attrs.match(/ face\s*=\s*"\s*([^"]+)\s*"/i);
			if(f)styles+='font-family:'+f[1]+';';
			s=attrs.match(/ size\s*=\s*"\s*(\d+)\s*"/i);
			if(s)styles+='font-size:'+arrFontsize[(s[1]>7?7:(s[1]<1?1:s[1]))-1].n+';';
			c=attrs.match(/ color\s*=\s*"\s*([^"]+)\s*"/i);
			if(c)styles+='color:'+c[1]+';';
			style=attrs.match(/ style\s*=\s*"\s*([^"]+)\s*"/i);
			if(style)styles+=style[1];
			if(styles)content='<span style="'+styles+'">'+content+'</span>';
			return content;			
		}
		sHtml = sHtml.replace(/<(font)(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S])*?)<\/\1>/ig,font2style);//最里层
		sHtml = sHtml.replace(/<(font)(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?)<\/\1>/ig,font2style);//第2层
		sHtml = sHtml.replace(/<(font)(\s+[^>]*?)?>(((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S]|<\1(\s+[^>]*?)?>((?!<\1(\s+[^>]*?)?>)[\s\S])*?<\/\1>)*?<\/\1>)*?)<\/\1>/ig,font2style);//第3层
		sHtml = sHtml.replace(/^(\s*\r?\n)+|(\s*\r?\n)+$/g,'');//清理首尾换行

		return sHtml;
	}
	this.toggleShowBlocktag=function(state)
	{
		if(bShowBlocktag===state)return;
		bShowBlocktag=!bShowBlocktag;
		var _jBody=$(_doc.body);
		if(bShowBlocktag)
		{
			bodyClass+=' showBlocktag';
			_jBody.addClass('showBlocktag');
		}
		else
		{
			bodyClass=bodyClass.replace(' showBlocktag','');
			_jBody.removeClass('showBlocktag');
		}
	}
	this.toggleReadonly=function(state)
	{
		if(bReadonly===state)return;
		if(bSource)_this.toggleSource(true);
		bReadonly=!bReadonly;
		if(bReadonly)
		{
			if(!bPreview)_this.togglePreview(true);
			_jTools.find('[name=Preview]').toggleClass('xheEnabled').toggleClass('xheActive');
		}
		else
		{
			_jTools.find('[name=Preview]').toggleClass('xheEnabled').toggleClass('xheActive');
			if(bPreview)_this.togglePreview();
		}
		
	}
	this.toggleSource=function(state)
	{
		if(bPreview||bSource===state)return;
		_jTools.find('[name=Source]').toggleClass('xheEnabled').toggleClass('xheActive');
		if(bShowPanel)_this.hidePanel();
		var jBody=$(_doc.body),sHtml=_this.getSource(!bSource);
		bSource=!bSource;
		if(bSource)
		{//转为源代码模式
			if(isIE)_doc.body.contentEditable='false';
			else _doc.designMode = 'Off';
			jBody.attr('scroll','no').attr('class','sourceMode').html('<textarea id="sourceCode" wrap="soft" spellcheck="false" height="100%" />');
			jBody.find('#sourceCode').blur(_this.getSource);
			_this._setSource(sHtml);
		}
		else
		{//转为编辑模式
			jBody.find('#sourceCode').remove();
			jBody.removeAttr('scroll').attr('class','editMode'+bodyClass);
			if(isIE)_doc.body.contentEditable='true';
			else _doc.designMode = 'On';
			if(isMozilla)_this._exec("inserthtml","-");//修正源代码切换回来无法删除文字的问题
			_this._setSource(sHtml);
			setTimeout(function(){_win.scrollTo(0,0);},10);
		}
		_jTools.find('[name=Source]').toggleClass('xheEnabled');
		_jTools.find('.xheButton').not('[name=Source],[name=Fullscreen],[name=About]').toggleClass('xheEnabled');
		setTimeout(_this.setOpts,300);
	}
	this.togglePreview=function(state)
	{
		if(bSource||bPreview===state)return;
		_jTools.find('[name=Preview]').toggleClass('xheActive').toggleClass('xheEnabled');
		var jBody=$(_doc.body);
		if(!bPreview)
		{//转预览模式
			if(isIE)_doc.body.contentEditable='false';
			else _doc.designMode = 'Off';
			jBody.attr('class','previewMode');		
			jBody[0].innerHTML=jBody.html();
			$('head base',_doc).attr('target','_blank');
		}
		else
		{//转编辑模式
			if(isIE)_doc.body.contentEditable='true';
			else _doc.designMode = 'On';
			jBody.attr('class','editMode'+bodyClass);
			jBody[0].innerHTML=jBody.html();
			$('head base',_doc).removeAttr('target');
		}
		bPreview=!bPreview;
		_jTools.find('[name=Preview]').toggleClass('xheEnabled');
		_jTools.find('.xheButton').not('[name=Preview],[name=Fullscreen],[name=About]').toggleClass('xheEnabled');
		setTimeout(_this.setOpts,300);
	}
	this.toggleFullscreen=function(state)
	{
		if(bFullscreen===state)return;
		if(bShowPanel)_this.hidePanel();
		var jLayout=$('#'+idContainer).find('.xheLayout'),jContainer=$('#'+idContainer);
		if(bFullscreen)
		{//取消全屏
			jLayout.attr('style',sLayoutStyle);
			_jArea.height(editHeight-_jTools.outerHeight());
		}
		else
		{//显示全屏
			sLayoutStyle=jLayout.attr('style');
			jLayout.removeAttr('style');
			_jArea.height('100%');
			setTimeout(_this.fixFullHeight,100);
		}
		bFullscreen=!bFullscreen;
		jContainer.toggleClass('xhe_Fullscreen');
		$('html').toggleClass('xhe_Fullfix');
		_jTools.find('[name=Fullscreen]').toggleClass('xheActive');
		setTimeout(_this.setOpts,300);
	}
	this.showMenu=function(menuitems,callback)
	{
		var jMenu=$('<div class="xheMenu"></div>'),jItem;
		$.each(menuitems,function(n,v){
			jItem=$('<a href="javascript:void(0);" title="'+(v.t?v.t:v.s)+'">'+v.s+'</a>').click(function(){_this.focus();callback(v.v);_this.hidePanel();return false;});
			jMenu.append(jItem);
		});
		_this.showPanel(jMenu);
	}
	this.showColor=function(callback)
	{
		var jColor=$('<div class="xheColor"></div>'),jLine,jItem,c=0;
		jLine=$('<div></div>');
		$.each(itemColors,function(n,v)
		{
			c++;
			jItem=$('<a href="javascript:void(0);" title="'+v+'" style="background:'+v+'"><img src="'+skinPath+'img/spacer.gif" /></a>').click(function(){_this.focus();callback(v);_this.hidePanel();return false;});
			jLine.append(jItem);
			if(c%7==0)
			{
				jColor.append(jLine);
				jLine=$('<div></div>');
			}	
		});
		jColor.append(jLine);
		_this.showPanel(jColor);
	}
	this.showPastetext=function()
	{
		var jPastetext=$(htmlPastetext),jValue=$('#xhePastetextValue',jPastetext),jSave=$('#xheSave',jPastetext);
		jSave.click(function(){
			_this.focus();
			var sValue=jValue.val();
			if(sValue)_this.pasteText(sValue);
			_this.hidePanel();
			return false;
		});
		_this.showDialog(jPastetext);
	}
	this.showLink=function()
	{
		var jLink=$(htmlLink),jParent=_this.getParent('a'),jText=$('#xheLinkText',jLink),jHref=$('#xheLinkHref',jLink),jTarget=$('#xheLinkTarget',jLink),jSave=$('#xheSave',jLink),selHtml=_this.getSelect();
		if(jParent.length==1)
		{
			jHref.val(_this.attr(jParent,'href'));
			jTarget.attr('value',jParent.attr('target'));
		}
		else if(selHtml=='')jText.val(_this.settings.defLinkText).closest('div').show();
		if(_this.settings.upLinkUrl)_this.uploadInit(jHref,_this.settings.upLinkUrl,_this.settings.upLinkExt);
		jSave.click(function(){
			_this.focus();
			var url=jHref.val();
			if(url==''||jParent.length==0)_this._exec('unlink');
			if(url!=''&&url!='http://')
			{
				var aUrl=url.split('|'),sTarget=jTarget.val(),sText=jText.val();
				if(aUrl.length>1)
				{//批量插入
					_this._exec('unlink');//批量前删除当前链接并重新获取选择内容
					selHtml=_this.getSelect();
					var sTemplate='<a href="xhe_tmpurl"',sLink;
					if(sTarget!='')sTemplate+=' target="'+sTarget+'"';
					sTemplate+='>xhe_tmptext</a>&nbsp;';
					sText=(selHtml!=''?selHtml:(sText?sText:url));
					for(var i in aUrl)
					{
						url=aUrl[i];
						if(url!='')
						{
							url=url.split(',');
							sLink=sTemplate;
							sLink=sLink.replace('xhe_tmpurl',url[0]);
							sLink=sLink.replace('xhe_tmptext',url[1]?url[1]:sText);
							_this.pasteHTML(sLink);
						}
					}
				}
				else
				{//单url模式
					url=aUrl[0].split(',');
					if(jParent.length==0)
					{
						if(selHtml=='')
						{
							if(!sText)sText=url[0];
							_this.pasteHTML('<a href="#xhe_tmpurl">'+sText+'</a>');
						}
						else _this._exec('createlink','#xhe_tmpurl');
						jParent=$('a[href$="#xhe_tmpurl"]',_doc);
					}
					_this.attr(jParent,'href',url[0]);
					if(sTarget!='')jParent.attr('target',sTarget);
					else jParent.removeAttr('target');
					if(url[1])jParent.text(url[1]);
				}
			}
			_this.hidePanel();
			return false;
		});
		_this.showDialog(jLink);
	}
	this.showImg=function()
	{
		var jImg=$(htmlImg),jParent=_this.getParent('img'),jSrc=$('#xheImgSrc',jImg),jAlt=$('#xheImgAlt',jImg),jAlign=$('#xheImgAlign',jImg),jWidth=$('#xheImgWidth',jImg),jHeight=$('#xheImgHeight',jImg),jBorder=$('#xheImgBorder',jImg),jVspace=$('#xheImgVspace',jImg),jHspace=$('#xheImgHspace',jImg),jSave=$('#xheSave',jImg);
		if(jParent.length==1)
		{
			jSrc.val(_this.attr(jParent,'src'));
			jAlt.val(jParent.attr('alt'));
			jAlign.val(jParent.attr('align'));
			jWidth.val(jParent.attr('width'));
			jHeight.val(jParent.attr('height'));
			jBorder.val(jParent.attr('border'));
			var vspace=jParent.attr('vspace'),hspace=jParent.attr('hspace');
			jVspace.val(vspace<=0?'':vspace);
			jHspace.val(hspace<=0?'':hspace);
		}
		if(_this.settings.upImgUrl)_this.uploadInit(jSrc,_this.settings.upImgUrl,_this.settings.upImgExt);
		jSave.click(function(){
			_this.focus();
			var url=jSrc.val();
			if(url!=''&&url!='http://')
			{
				var aUrl=url.split('|'),sAlt=jAlt.val(),sAlign=jAlign.val(),sWidth=jWidth.val(),sHeight=jHeight.val(),sBorder=jBorder.val(),sVspace=jVspace.val(),sHspace=jHspace.val();;
				if(aUrl.length>1)
				{//批量插入
					var sTemplate='<img src="xhe_tmpurl"',sImg;
					if(sAlt!='')sTemplate+=' alt="'+sAlt+'"';
					if(sAlign!='')sTemplate+=' align="'+sAlign+'"';
					if(sWidth!='')sTemplate+=' width="'+sWidth+'"';
					if(sHeight!='')sTemplate+=' height="'+sHeight+'"';
					if(sBorder!='')sTemplate+=' border="'+sBorder+'"';
					if(sVspace!='')sTemplate+=' vspace="'+sVspace+'"';
					if(sHspace!='')sTemplate+=' hspace="'+sHspace+'"';
					sTemplate+=' />&nbsp;';
					for(var i in aUrl)
					{
						url=aUrl[i];
						if(url!='')
						{
							url=url.split(',');
							sImg=sTemplate;
							sImg=sImg.replace('xhe_tmpurl',url[0]);
							if(url[1])sImg='<a href="'+url[1]+'" target="_blank">'+sImg+'</a>'
							_this.pasteHTML(sImg);
						}
					}
				}
				else if(aUrl.length==1)
				{//单URL模式
					url=aUrl[0];
					if(url!='')
					{
						url=url.split(',');
						if(jParent.length==0)
						{
							_this.pasteHTML('<img src="'+url[0]+'#xhe_tmpurl" />');
							jParent=$('img[src$="#xhe_tmpurl"]',_doc);
						}
						_this.attr(jParent,'src',url[0])
						if(sAlt!='')jParent.attr('alt',sAlt);
						else jParent.removeAttr('alt');
						if(sAlign!='')jParent.attr('align',sAlign);
						else jParent.removeAttr('align');
						if(sWidth!='')jParent.attr('width',sWidth);
						else jParent.removeAttr('width');
						if(sHeight!='')jParent.attr('height',sHeight);
						else jParent.removeAttr('height');
						if(sBorder!='')jParent.attr('border',sBorder);
						else jParent.removeAttr('border');
						if(sVspace!='')jParent.attr('vspace',sVspace);
						else jParent.removeAttr('vspace');
						if(sHspace!='')jParent.attr('hspace',sHspace);
						else jParent.removeAttr('hspace');
						if(url[1])
						{
							var jLink=jParent.parent('a');
							if(jLink.length==0)
							{
								jParent.wrap('<a></a>');
								jLink=jParent.parent('a');
							}
							_this.attr(jLink,'href',url[1]);
							jLink.attr('target','_blank');
						}
					}
				}
			}
			else if(jParent.length==1)jParent.remove();
			_this.hidePanel();
			return false;			
		});
		_this.showDialog(jImg);
	}
	this.showEmbed=function(sType,sHtml,sMime,sClsID,sBaseAttrs,sUploadUrl,sUploadExt)
	{
		var jEmbed=$(sHtml),jParent=_this.getParent('embed[type="'+sMime+'"],embed[classid="'+sClsID+'"]'),jSrc=$('#xhe'+sType+'Src',jEmbed),jWidth=$('#xhe'+sType+'Width',jEmbed),jHeight=$('#xhe'+sType+'Height',jEmbed),jSave=$('#xheSave',jEmbed);
		if(sUploadUrl)_this.uploadInit(jSrc,sUploadUrl,sUploadExt);
		_this.showDialog(jEmbed);
		if(jParent.length==1)
		{
			jSrc.val(_this.attr(jParent,'src'));
			jWidth.val(jParent.attr('width'));
			jHeight.val(jParent.attr('height'));
		}
		jSave.click(function(){
			_this.focus();
			var url=jSrc.val();
			if(url!=''&&url!='http://')
			{
				var w=jWidth.val(),h=jHeight.val(),reg=/^[0-9]+$/;
				if(!reg.test(w))w=412;if(!reg.test(h))h=300;
				var sBaseCode='<embed type="'+sMime+'" classid="'+sClsID+'" src="xhe_tmpurl"'+sBaseAttrs;
				var aUrl=url.split('|');
				if(aUrl.length>1)
				{//批量插入
					var sTemplate=sBaseCode+'',sEmbed;
					sTemplate+=' width="xhe_width" height="xhe_height" />&nbsp;';
					for(var i in aUrl)
					{
						url=aUrl[i].split(',');
						sEmbed=sTemplate;
						sEmbed=sEmbed.replace('xhe_tmpurl',url[0])
						sEmbed=sEmbed.replace('xhe_width',url[1]?url[1]:w)
						sEmbed=sEmbed.replace('xhe_height',url[2]?url[2]:h)
						if(url!='')_this.pasteHTML(sEmbed);
					}
				}
				else if(aUrl.length==1)
				{//单URL模式
					url=aUrl[0].split(',');
					if(jParent.length==0)
					{
						_this.pasteHTML(sBaseCode.replace('xhe_tmpurl',url[0]+'#xhe_tmpurl')+' />');
						jParent=$('embed[src$="#xhe_tmpurl"]',_doc);
					}
					_this.attr(jParent,'src',url[0]);
					jParent.attr('width',url[1]?url[1]:w);
					jParent.attr('height',url[2]?url[2]:h);
				}
			}
			else if(jParent.length==1)jParent.remove();
			_this.hidePanel();
			return false;	
		});
	}
	this.showEmot=function(group)
	{
		var jEmot=$('<div class="xheEmot"></div>');
		
		group=group?group:(selEmotGroup?selEmotGroup:'default');
		var arrEmot=arrEmots[group];
		
		var sEmotPath=emotPath+group+'/',n=0,arrList=[],jList='';
		var ew=arrEmot.width,eh=arrEmot.height,line=arrEmot.line,count=arrEmot.count,list=arrEmot.list;
		if(count)
		{
			for(var i=1;i<=count;i++)
			{
				n++;
				arrList.push('<a href="javascript:void(0);" style="background-image:url('+sEmotPath+i+'.gif);" emot="'+group+','+i+'">&nbsp;</a>');
				if(n%line==0)arrList.push('<br />');
			}
		}
		else
		{
			$.each(list,function(id,title)
			{
				n++;
				arrList.push('<a href="javascript:void(0);" style="background-image:url('+sEmotPath+id+'.gif);" emot="'+group+','+id+'" title="'+title+'">&nbsp;</a>');
				if(n%line==0)arrList.push('<br />');
			});
		}
		var w=line*(ew+12),h=Math.ceil(n/line)*(eh+12),mh=w*0.75;
		if(h<=mh)mh='';
		jList=$('<style>'+(mh?'.xheEmot div{width:'+(w+20)+'px;height:'+mh+'px;}':'')+'.xheEmot div a{width:'+ew+'px;height:'+eh+'px;}</style><div>'+arrList.join('')+'</div>');
		$('a',jList).click(function(){_this.focus();_this.pasteHTML('<img emot="'+$(this).attr('emot')+'">');_this.hidePanel();return false;});
		jEmot.append(jList);
		
		var gcount=0,arrGroup=['<ul>'],jGroup;//表情分类
		$.each(arrEmots,function(g,v){
			gcount++;
			arrGroup.push('<li'+(group==g?' class="cur"':'')+'><a href="javascript:void(0);" group="'+g+'">'+v.name+'</a></li>');
		});
		if(gcount>1)
		{
			arrGroup.push('</ul><br style="clear:both;" />');
			jGroup=$(arrGroup.join(''));
			$('a',jGroup).click(function(){selEmotGroup=$(this).attr('group');_this.showEmot(selEmotGroup);return false;});
			jEmot.append(jGroup);
		}
		
		_this.showPanel(jEmot);
	}
	this.showTable=function()
	{
		var jTable=$(htmlTable),jRows=$('#xheTableRows',jTable),jColumns=$('#xheTableColumns',jTable),jHeaders=$('#xheTableHeaders',jTable),jWidth=$('#xheTableWidth',jTable),jHeight=$('#xheTableHeight',jTable),jBorder=$('#xheTableBorder',jTable),jCellSpacing=$('#xheTableCellSpacing',jTable),jCellPadding=$('#xheTableCellPadding',jTable),jAlign=$('#xheTableAlign',jTable),jCaption=$('#xheTableCaption',jTable),jSave=$('#xheSave',jTable);
		jSave.click(function(){
			_this.focus();
			var sCaption=jCaption.val(),sBorder=jBorder.val(),sRows=jRows.val(),sCols=jColumns.val(),sHeaders=jHeaders.val(),sWidth=jWidth.val(),sHeight=jHeight.val(),sCellSpacing=jCellSpacing.val(),sCellPadding=jCellPadding.val(),sAlign=jAlign.val();
			var i,j,htmlTable='<table'+(sBorder!=''?' border="'+sBorder+'"':'')+(sWidth!=''?' width="'+sWidth+'"':'')+(sHeight!=''?' width="'+sHeight+'"':'')+(sCellSpacing!=''?' cellspacing="'+sCellSpacing+'"':'')+(sCellPadding!=''?' cellpadding="'+sCellPadding+'"':'')+(sAlign!=''?' align="'+sAlign+'"':'')+'>';
			if(sCaption!='')htmlTable+='<caption>'+sCaption+'</caption>';
			if(sHeaders=='row'||sHeaders=='both')
			{
				htmlTable+='<tr>';
				for(i=0;i<sCols;i++)htmlTable+='<th scope="col">&nbsp;</th>';
				htmlTable+='</tr>';
				sRows--;
			}
			htmlTable+='<tbody>';
			for(i=0;i<sRows;i++)
			{
				htmlTable+='<tr>';
				for(j=0;j<sCols;j++)
				{
					if(j==0&&(sHeaders=='col'||sHeaders=='both'))htmlTable+='<th scope="row">&nbsp;</th>';
					else htmlTable+='<td>&nbsp;</td>';
				}
				htmlTable+='</tr>';
			}
			htmlTable+='</tbody></table>';
			_this.pasteHTML(htmlTable);
			_this.hidePanel();
			return false;	
		});
		_this.showDialog(jTable);
	}
	this.showAbout=function()
	{
		var jAbout=$(htmlAbout),jSave=$('#xheSave',jAbout);
		jSave.click(function(){
			_this.focus();
			_this.hidePanel();
			return false;	
		});
		_this.showDialog(jAbout);
	}
	this.attr=function(jObj,n,v)
	{
		if(!n)return false;
		var kn='_xhe_'+n;
		if(v)jObj.attr(n,v).removeAttr(kn).attr(kn,v);//设置属性
		return jObj.attr(kn)||jObj.attr(n);
	}
	this.addShortcuts=function(key,cmd)
	{
		key=key.toLowerCase();
		if(arrShortCuts[key]==undefined)arrShortCuts[key]=Array();
		arrShortCuts[key].push(cmd);
	}
	this.checkShortcuts=function(event)
	{
		if(bSource||bPreview)return true;
		ev = event;
		var code=ev.which,special=specialKeys[code],sChar=special?special:String.fromCharCode(code).toLowerCase();
		sKey='';
		sKey+=ev.ctrlKey?'ctrl+':'';sKey+=ev.altKey?'alt+':'';sKey+=ev.shiftKey?'shift+':'';sKey+=sChar;

		var cmd=arrShortCuts[sKey];
		if(cmd)
		{
			$.each(cmd,function(i,c){
				if($.isFunction(c))c.call(_this);
				else _this.exec(c);
			});
			return false;
		}
	}
	this.uploadInit=function(jText,tourl,upext,setFunc)
	{
		var jUpload=$('<span class="xheUpload"><input type="text" style="visibility:hidden;" tabindex="-1" /><input type="button" value="'+_this.settings.upBtnText+'" class="xheBtn" tabindex="-1" /></span>'),jUpBtn=$('.xheBtn',jUpload);
		jText.after(jUpload);jUpBtn.before(jText);
		if(tourl.substr(0,1)=='!')//自定义上传管理页
		{
			jUpBtn.click(function(){
				bShowPanel=false;//防止按钮面板被关闭
				_this.showIframeModal('上传文件',tourl.substr(1),setUploadUrl,null,null,function(){bShowPanel=true;});
			});
		}
		else
		{//系统默认ajax上传
			jUpload.append('<input type="file" class="xheFile" size="13" name="filedata" tabindex="-1" />');
			var jFile=$('.xheFile',jUpload);
			jFile.change(function(){
				var sFile=jFile.val();
				if(sFile!='')
				{
					if(sFile.match(new RegExp('\.('+upext.replace(/,/g,'|')+')$','i')))
					{
						bShowPanel=false;//防止按钮面板被关闭
						var modal=_this.showModal('文件上传','<div style="margin:22px 0;text-align:center;line-height:30px;">文件上传中，请稍候……<br /><img src="'+skinPath+'img/loading.gif"></div>',320,150,function(){bShowPanel=true;});
						_this.ajaxUpload(jFile,tourl,function(data){
							modal.remove();
							if(data.err)alert(data.err);
							else setUploadUrl(data.msg);
						},function(){modal.remove();});
					}
					else alert('上传文件扩展名必需为: '+upext);
				}
			});
		}
		function setUploadUrl(msg)
		{
			var bImmediate=false,onUpload=_this.settings.onUpload,url=is(msg,'string')?msg:msg.url;
			if(url.substr(0,1)=='!'){bImmediate=true;url=url.substr(1);}
			if(setFunc)setFunc(url);//自定义设置
			else jText.val(url);//默认设置地址方法
			if(onUpload)onUpload(msg);//用户上传回调
			if(bImmediate)jText.closest('.xheDialog').find('#xheSave').click();
		}
	}
	this.ajaxUpload=function(fromfile,tourl,callback,onError)
	{
		var uid = new Date().getTime(),idIO='jUploadFrame'+uid;
		var jIO=$('<iframe name="'+idIO+'" class="xheHideArea" />').appendTo('body');
		var jForm=$('<form action="'+tourl+'" target="'+idIO+'" method="post" enctype="multipart/form-data" class="xheHideArea"></form>').appendTo('body');
		var jOldFile = $(fromfile),jNewFile = jOldFile.clone().attr('disabled','true');
		jOldFile.before(jNewFile).appendTo(jForm);
		jForm.submit();
		jIO.load(function(){
			setTimeout(function(){
				jNewFile.before(jOldFile).remove();
				jIO.remove();jForm.remove();
			},100);
			var strText=$(jIO[0].contentWindow.document.body).text(),data=Object;
			try{data=eval('('+strText+')');}catch(ex){};
			if(data.err!=undefined&&data.msg!=undefined)callback(data);
			else{alert(tourl+' 上传接口发生错误！\r\n\r\n返回的错误内容为: \r\n\r\n'+strText);onError();}
		});
	}
	this.showIframeModal=function(title,ifmurl,callback,w,h,onRemove)
	{
		var jContent=$('<iframe frameborder="0" src="'+ifmurl+'" style="width:100%;height:100%;display:none;" /><div class="xheModalIfmWait"></div>'),jIframe=$(jContent[0]),jWait=$(jContent[1]);
		var modal=_this.showModal(title,jContent,w,h,onRemove);
		jIframe.load(function(){
			var modalWin=jIframe[0].contentWindow,jModalDoc=$(modalWin.document);
			modalWin.callback=function(v){jModalDoc.unbind('keydown',modal.escCheck);modal.remove();callback(v);};
			jModalDoc.keydown(modal.escCheck);
			jIframe.show();jWait.remove();
		});
	}
	this.showModal=function(title,content,w,h,onRemove)
	{
		if($('.xheModal').length==1)return false;//只能弹出一个模式窗口
		var jModal,jModalShadow,jOverlay,layerShadow,jHideSelect;
		w=w?w:_this.settings.modalWidth;h=h?h:_this.settings.modalHeight;
		layerShadow=_this.settings.layerShadow;
		jModal=$('<div class="xheModal" style="width:'+(w-1)+'px;height:'+h+'px;margin-left:-'+Math.ceil(w/2)+'px;'+(isIE&&browerVer<=7.0?'':'margin-top:-'+Math.ceil(h/2)+'px')+'">'+(_this.settings.modalTitle?'<div class="xheModalTitle"><span class="xheModalClose" title="关闭 (Esc)"></span>'+title+'</div>':'')+'<div class="xheModalContent"></div></div>').appendTo('body');
		jOverlay=$('<div class="xheModalOverlay"></div>').appendTo('body');
		if(layerShadow>0)jModalShadow=$('<div class="xheModalShadow" style="width:'+jModal.outerWidth()+'px;height:'+jModal.outerHeight()+'px;margin-left:-'+(Math.ceil(w/2)-layerShadow-2)+'px;'+(isIE&&browerVer<=7.0?'':'margin-top:-'+(Math.ceil(h/2)-layerShadow-2)+'px')+'"></div>').appendTo('body');
		
		$('.xheModalContent',jModal).css('height',h-(_this.settings.modalTitle?$('.xheModalTitle').outerHeight():0)).html(content);
		
		if(isIE&&browerVer==6.0)jHideSelect=$('select:visible').css('visibility','hidden');//隐藏覆盖的select
		
		function remove(){if(jHideSelect)jHideSelect.css('visibility','visible');$(document).unbind('keydown',escCheck);jModal.remove();if(layerShadow>0)jModalShadow.remove();jOverlay.remove();if(onRemove)onRemove();};this.remove=remove;
		function escCheck(ev){if(ev.which==27){remove();return false;}};this.escCheck=escCheck;
		$(document).keydown(escCheck);
		
		$('.xheModalClose',jModal).click(this.remove);
		
		jOverlay.show();if(layerShadow>0)jModalShadow.show();jModal.show();
		return this;
	}
	this.showDialog=function(content)
	{
		var jDialog=$('<div class="xheDialog"></div>'),jContent=$(content),jSave=$('#xheSave',jContent);
		if(jSave.length==1)
		{
			jContent.find('input[type=text]').keypress(function(ev){if(ev.which==13){jSave.click();return false;}});
			jSave.after(' <input type="button" id="xheCancel" value="取消" />');
			$('#xheCancel',jContent).click(_this.hidePanel);
			if(!_this.settings.clickCancelDialog)
			{
				bClickCancel=false;//关闭点击隐藏
				var jFixCancel=$('<div class="xheFixCancel"></div>').appendTo('body').mousedown(function(){return false;});
				var xy=_jArea.offset();
				jFixCancel.css({'left':xy.left,'top':xy.top,width:_jArea.outerWidth(),height:_jArea.outerHeight()})
			}
		}
		jDialog.append(jContent);
		_this.showPanel(jDialog);
	}
	this.clickCancelPanel=function(){if(bClickCancel)_this.hidePanel();}
	this.showPanel=function(content)
	{
		if(bShowPanel)_this.hidePanel();
		_jPanel.empty().append(content).css('left',0).css('top',0);
		_jPanelButton=$(ev.target).closest('a');
		var xy=_jPanelButton.offset();
		var x=xy.left,y=xy.top;y+=_jPanelButton.outerHeight()-1;
		_jPanelButton.addClass('xheActive');
		_jCntLine.css({'left':x+1,'top':y}).show();
		if((x+_jPanel.outerWidth())>document.body.clientWidth)x-=(_jPanel.outerWidth()-_jPanelButton.outerWidth());//向左显示面板
		var layerShadow=_this.settings.layerShadow;
		if(layerShadow>0)_jShadow.css({'left':x+layerShadow,'top':y+layerShadow,'width':_jPanel.outerWidth(),'height':_jPanel.outerHeight()}).show();
		_jPanel.css('left',x).css('top',y).show();
		bShowPanel=true;
	}
	this.hidePanel=function(){if(bShowPanel){_jPanelButton.removeClass('xheActive');_jShadow.hide();_jCntLine.hide();_jPanel.hide();bShowPanel=false;if(!bClickCancel){$('.xheFixCancel').remove();bClickCancel=true;}}}
	this.exec=function(cmd)
	{
		var e=arrTools[cmd].e;
		if(e)return e.call(_this);
		cmd=cmd.toLowerCase();
		switch(cmd)
		{
			case 'cut':
				try{_doc.execCommand(cmd);if(!_doc.queryCommandSupported(cmd))throw 'Error';}
				catch(ex){alert('您的浏览器安全设置不允许使用剪切操作，请使用键盘快捷键(Ctrl + X)来完成');};
				break;
			case 'copy':
				try{_doc.execCommand(cmd);if(!_doc.queryCommandSupported(cmd))throw 'Error';}
				catch(ex){alert('您的浏览器安全设置不允许使用复制操作，请使用键盘快捷键(Ctrl + C)来完成');}
				break;
			case 'paste':
				try{_doc.execCommand(cmd);if(!_doc.queryCommandSupported(cmd))throw 'Error';}
				catch(ex){alert('您的浏览器安全设置不允许使用粘贴操作，请使用键盘快捷键(Ctrl + V)来完成');}
				break;
			case 'pastetext':
				if(window.clipboardData)_this.pasteText(window.clipboardData.getData('Text', true));
				else _this.showPastetext();
				break;
			case 'blocktag':
				var menuBlocktag=[];
				$.each(arrBlocktag,function(n,v){menuBlocktag.push({s:'<'+v.n+'>'+v.t+'</'+v.n+'>',v:'<'+v.n+'>',t:v.t});});
				_this.showMenu(menuBlocktag,function(v){_this._exec('formatblock',v);});
				break;
			case 'fontface':
				var menuFontname=[];
				$.each(arrFontname,function(n,v){v.c=v.c?v.c:v.n;menuFontname.push({s:'<span style="font-family:'+v.c+'">'+v.n+'</span>',v:v.c,t:v.n});});
				_this.showMenu(menuFontname,function(v){_this._exec('fontname',v);});
				break;
			case 'fontsize':
				var menuFontsize=[];
				$.each(arrFontsize,function(n,v){menuFontsize.push({s:'<span style="font-size:'+v.s+'">'+v.t+'('+v.s+')</span>',v:n+1,t:v.t});});
				_this.showMenu(menuFontsize,function(v){_this._exec('fontsize',v);});
				break;
			case 'fontcolor':
				_this.showColor(function(v){_this._exec('forecolor',v);});
				break;
			case 'backcolor':
				_this.showColor(function(v){if(isIE)_this._exec('backcolor',v);else{_this.setCSS(true);_this._exec('hilitecolor',v);_this.setCSS(false);}});
				break;
			case 'align':
				_this.showMenu(menuAlign,function(v){_this._exec(v);});
				break;
			case 'list':
				_this.showMenu(menuList,function(v){_this._exec(v);});
				break;
			case 'link':
				_this.showLink();
				break;
			case 'img':
				_this.showImg();		
				break;
			case 'flash':
				_this.showEmbed('Flash',htmlFlash,'application/x-shockwave-flash','clsid:d27cdb6e-ae6d-11cf-96b8-4445535400000',' wmode="opaque" quality="high" menu="false" play="true" loop="true"',_this.settings.upFlashUrl,_this.settings.upFlashExt);
				break;
			case 'media':
				_this.showEmbed('Media',htmlMedia,'application/x-mplayer2','clsid:6bf52a52-394a-11d3-b153-00c04f79faa6',' enablecontextmenu="false" autostart="false"',_this.settings.upMediaUrl,_this.settings.upMediaExt);
				break;
			case 'emot':
				_this.showEmot();
				break;
			case 'table':
				_this.showTable();
				break;
			case 'source':
				_this.toggleSource();
				break;
			case 'preview':
				_this.togglePreview();
				break;
			case 'fullscreen':
				_this.toggleFullscreen();
				break;
			case 'about':
				_this.showAbout();
				break;
			default:
				_this._exec(cmd);
				break;
		}
	}
	this._exec=function(cmd,param)
	{
		if(param!=undefined)return _doc.execCommand(cmd,false,param);
		else return _doc.execCommand(cmd,false,null);
	}
	function getLocalUrl(url,urlType)//绝对地址：abs,根地址：root,相对地址：rel
	{
		var protocol=location.protocol,host=location.hostname,port=location.port,path=location.pathname.replace(/\\/g,'/').replace(/[^\/]+$/i,'');
		port=(port=='')?'80':port;
		url=$.trim(url);
		if(protocol=='file:')urlType='abs';
		if(urlType!='abs')url=url.replace(new RegExp(protocol+'\\/\\/'+host.replace(/\./g,'\\.')+'(?::'+port+')'+(port=='80'?'?':'')+'(\/|$)','i'),'/');
		if(urlType=='rel')url=url.replace(new RegExp('^'+path.replace(/([\/\.\+\[\]\(\)])/g,'\\$1'),'i'),'');
		if(urlType!='rel')if(!url.match(/^((https?|file):\/\/|\/)/i))url=path+url;
		if(urlType=='abs')if(!url.match(/(https?|file):\/\//i))url=protocol+'//'+location.host+url;
		return url;
	}	
}
$(function(){
$('textarea.xheditor,textarea[xheditor]').xheditor(true);
$('textarea.xheditor-mini').xheditor(true,{tools:'mini'});
$('textarea.xheditor-simple').xheditor(true,{tools:'simple'});
});

})(jQuery);