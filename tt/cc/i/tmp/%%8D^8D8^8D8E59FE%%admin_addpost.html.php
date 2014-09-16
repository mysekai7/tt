<?php /* Smarty version 2.6.10, created on 2010-07-30 01:03:26
         compiled from html/admin_addpost.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>写日志 ### 草稿版</title>
    <style>
        *{margin:0; padding:);}
        body{padding:20px 0 0 10px; font-size:12px; line-height:1.2;}
        table{border-collapse:collapse; border-spacing:0;}
        td{padding:3px 2px;}
    </style>
</head>
<body>

<table>
    <tr>
        <td><a href="">主页</a></td>
        <td>日志</td>
        <td><a href="">相册</a></td>
        <td><a href="">说说</a></td>
        <td><a href="">留言板</a></td>
    </tr>
</table>
<br />

<table>
    <tr>
        <td>我的日志</td>
        <td><a href="">私密日志</a></td>
        <td><a href="">记事本</a></td>
        <td><a href="">草稿箱</a></td>
    </tr>
</table>
<form name="form1" onsubmit="return check_form()">
<table style="width:750px;">
    <tr>
        <td>标题：<input name="post[title]" type="text" /></td>
    </tr>
    <tr>
        <td><textarea id="elem1" name="post[text]" style="width:99%" rows="10"></textarea></td>
    </tr>
    <tr>
        <td>
            分类：<select name="post[cat_id]">
                <option value="0">未分类</option>
                <option value="1">PHP</option>
            </select>
            <a href="javascript:void(0);">添加分类</a>
            上传文件列表：<!--select id="uploadList" style="width:350px;"></select-->
        </td>
    </tr>
    <tr>
        <td>
            <button>发表日志</button>
            <button onclick="save_drafting(); return false">保存草稿</button>
            <button>预览</button>
            <button>取消</button>
        </td>
    </tr>
</table>
</form>
<div id="debug"></div>

<script type="text/javascript" src="themes/default/js/xheditor-1.0.0-final/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="themes/default/js/xheditor-1.0.0-final/xheditor-zh-cn.min.js"></script>
<script type="text/javascript" src="themes/default/js/ajax.js"></script>
<script type="text/javascript" src="themes/default/js/js.js"></script>

<script type="text/javascript">
$(pageInit);
var editor;
function pageInit()
{
    editor = $('#elem1').xheditor({plugins:{Code:{c:'btnCode',t:'插入代码',e:function(){
			var _this=this;
			var htmlCode='<div><select id="xheCodeType"><option value="plain">其它</option><option value="php">PHP</option><option value="C">C</option><option value="Bash">Bash</option><option value="Python">Python</option><option value="Perl">Perl</option><option value="SQL">SQL</option><option value="MySql">MySql</option><option value="ActionScript 3">AS3</option><option value="Javascript">Javascript</option><option value="HTML">HTML</option><option value="div">DIV</option><option value="CSS">CSS</option><option value="text">Text</option></select></div><div><textarea id="xheCodeValue" wrap="soft" spellcheck="false" style="font:normal 12px Consolas, courier new; width:350px;height:150px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
			var jCode=$(htmlCode),jType=jSave=$('#xheCodeType',jCode),jValue=jSave=$('#xheCodeValue',jCode),jSave=$('#xheSave',jCode);
			jSave.click(function(){
				_this.focus();
				_this.pasteText('[code='+jType.val()+']\r\n'+jValue.val()+'\r\n[/code]');
				_this.hidePanel();
				return false;
			});
			_this.showDialog(jCode);
		}}},forcePtag:false,upLinkUrl:"upload.php",upLinkExt:"zip,rar,txt",upImgUrl:"upload.php",upImgExt:"jpg,jpeg,gif,png",upFlashUrl:"upload.php",upFlashExt:"swf",upMediaUrl:"upload.php",upMediaExt:"wmv,avi,wma,mp3,mid",onUpload:insertUpload});

}

function insertUpload(msg)
{
    msg=msg[0];
     $("#uploadList").append('<option value="'+msg.id+'">'+msg.localname+'</option>');
}


function check_form()
{
    
    var ele = document.form1.elements;
    var msg='';
    
    for(var i=0; i<ele.length; i++)
    {
        if(ele[i].type == 'submit' || ele[i].type == '' || ele[i].name == '')
        {
            continue;
        }

        if(ele[i].name == 'post[title]')
        {
            ele[i].value = trim( ele[i].value );
            if(ele[i].value.length < 1)
            {
                //ele[i].style.border = '1px solid red';
                msg += "标题不能为空\n";
            }
        }
        else if(ele[i].name == 'post[text]')
        {
            ele[i].value = trim($('#elem1').val());
            if(ele[i].value.length < 11)
            {
                msg += "内容少于5个字\n";
            }
        }
        else if(ele[i].name == 'post[cat_id]')
        {
            if(ele[i].value == 0)
            {
                msg += "分类不能为空";
            }
        }
        //msg = "##ID: "+ ele[i].id +"##name: "+ ele[i].name +"##type: " + ele[i].type +"##value: "+ele[i].value;
        //alert(msg);
    }

    if(msg != '')
    {
        alert(msg);
        return false;
    }
    return true;
}

function save()
{
}

var timer;
 
function save_drafting()
{
    clearTimeout(timer);
    timer = setTimeout("save_draft()", 300);
}


function save_draft()
{
    if(!check_form())
    {
        return false;
    }

    var ele = document.form1.elements;
    var data = null;
    var post = new Array();

    for(var i=0; i<ele.length; i++)
    {
        if(ele[i].type == 'submit' || ele[i].type == '')
        {
            continue;
        }
        if(ele[i].name == 'post[text]')
        {
            ele[i].value = $('#elem1').val();
        }
        post[i] = ele[i].name +'='+ encodeURIComponent( trim(ele[i].value) );
    }

    data = post.join('&');
    //alert(data);
    ajax('index.php?c=ajax_post&m=save', data, r);

}

function publish_post()
{
}

function r(json)
{
    var tmp = eval('('+json+')');
    //alert(tmp.title);
    var html='';
    for(var key in tmp)
    {
        html += tmp[key]+'<br>';
    }
    document.getElementById('debug').innerHTML = html;
}



</script>
    
</body>
</html>