<?php
//此程序为UBB模式下的服务端显示测试程序
header('Content-Type: text/html; charset=utf-8');
require_once '../serverscript/php/ubb2html.php';
$sHtml=ubb2html($_POST['elm1']);//htmlspecialchars
function showCode($match)
{
	$match[1]=strtolower($match[1]);
	if(!$match[1])$match[1]='plain';
	$match[2]=preg_replace("/</",'&lt;',$match[2]);
	$match[2]=preg_replace("/>/",'&gt;',$match[2]);
	return '<pre class="brush: '.$match[1].';">'.$match[2].'</pre>';
}
$sHtml=preg_replace_callback('/\[code\s*(?:=\s*((?:(?!")[\s\S])+?)(?:"[\s\S]*?)?)?\]([\s\S]*?)\[\/code\]/i','showCode',$sHtml);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UBB文章显示测试页</title>
<style type="text/css">
pre1 {
margin: 5px 20px !important;
border-color:#2080D0 !important;
border-style:solid !important;
border-width:1px 1px 1px 4px !important;
padding: 5px !important;
background: #f8f8f8 !important;
word-break:break-word;word-wrap:break-word;white-space:-moz-pre-wrap;white-space:-hp-pre-wrap;white-space:-o-pre-wrap;white-space:-pre-wrap;white-space:pre;white-space:pre-wrap;white-space:pre-line;
</style>
<script type="text/javascript" src="syntaxhighlighter/shCore.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushXml.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushJScript.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushCss.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushPhp.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushCSharp.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushCpp.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushJava.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushPython.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushPerl.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushRuby.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushVb.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushDelphi.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushAS3.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushSql.js"></script>
<script type="text/javascript" src="syntaxhighlighter/shBrushPlain.js"></script>	
<link type="text/css" rel="stylesheet" href="syntaxhighlighter/shCore.css"/>
<link type="text/css" rel="stylesheet" href="syntaxhighlighter/shThemeDefault.css"/>
<script type="text/javascript">
	SyntaxHighlighter.config.clipboardSwf = 'syntaxhighlighter/clipboard.swf';
	SyntaxHighlighter.all();
</script>
<body>
	<?php echo $sHtml?>
</body>
</html>