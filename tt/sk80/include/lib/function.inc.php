<?php
/**
 * Summary : 自定义函数库
 * Description :
 * Requirement : PHP5 (http://www.php.net)
 * Copyright(C), , 2007, All Rights Reserved.
 *
 * $Rev: 7884 $
 * $Author: yj $
 * $Date: 2007-12-17 08:38:08 +0800 (星期一, 17 十二月 2007) $
 * $URL: http://sq.nine.com/svn/svn_nine/inc/function.inc.php $
 * $Id: function.inc.php 7884 2007-12-17 00:38:08Z yj $
 */
/**
 * 将特殊字符转成 HTML 编码
 *
 * @param string $str
 * @return string
 */
function CheckHtml( $str )
{
	return htmlspecialchars( strip_tags( stripcslashes( $str ) ) );
}
/**
 * 判断是否为空
 *
 * @param mixed $var
 * @return bool
 */
function mempty( $var )
{
	return !empty( $var );
}
/**
 * 使用trim()处理引用
 *
 * @param string $var
 */
function mtrim( &$var )
{
	$var = trim( $var );
}
/**
 * 使用strip_tags()处理引用
 *
 * @param string $var
 */
function mstrip_tags( &$var )
{
	$var = strip_tags( $var );
}
/**
 * 生成指定字符个数随机字符串，字符长度任意
 *
 * @param integer $num
 * @return string
 */
function randomStr( $num )
{
	$word = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$len = strlen( $word );
	$len = $len-2;
	$str = '';
	for ( $x = 0;$x < $num;$x++ )
	{
		$i = rand( 0, $len );
		$theword = substr( $word, $i, 1 );
		$str .= $theword;
	}
	return $str;
}
/**
 * 生成指定字符个数随机字符串,基于md5()，字符长度不超过32位
 *
 * @param integer $num
 * @return string
 */
function randstr( $lenth )
{
	mt_srand( ( double )microtime() * 1000000 );
	for( $i = 0;$i < $lenth;$i++ )
	{
		$randval .= mt_rand( 0, 9 );
	}
	$randval = substr( md5( $randval ), mt_rand( 0, 32 - $lenth ), $lenth );
	return $randval;
}
/**
 * 生成指定字符个数随机数字
 *
 * @param integer $lenth
 * @return integer
 */
function num_rand( $lenth )
{
	mt_srand( ( double )microtime() * 1000000 );
	for( $i = 0;$i < $lenth;$i++ )
	{
		$randval .= mt_rand( 0, 9 );
	}
	return $randval;
}
/**
 * 跳转到新地址
 *
 * @param string $URL
 */
function ObHeader( $URL )
{
	$URL = str_replace( array( "\r", "\n" ), array( '', '' ), $URL );
	if ( strtolower( substr( $URL, 0, 4 ) ) != 'http' )
	{
		$URL = "$URL";
	}
	// ob_end_clean();
	if ( ob_get_length() !== false )
	{
		header( "Location: $URL" );
		exit;
	}
	else
	{
		// ob_start();
		echo "<meta http-equiv='refresh' content='0;url=$URL'>";
		exit;
	}
}
/**
 * 按照指定字符长度截取中文加..
 *
 * @param string $content
 * @param integer $length
 * @param integer $t
 * @return string
 */
function substrs( $content, $length, $t = 0 )
{
	// t=0 截取字节 t=1截取个数
	if ( $length && strlen( $content ) > $length )
	{
		$retstr = '';
		for( $i = 0; $i < $length - 2; $i++ )
		{
			if ( ord( $content[$i] ) > 127 )
			{
				if ( $t )
				{
					$retstr .= $content[$i] . $content[$i + 1];
					$i++;
					$length++;
				}
				else
				{
					if ( ( $i + 1 < $length - 2 ) )
					{
						$retstr .= $content[$i] . $content[$i + 1];
						$i++;
					}
				}
			}
			else
			{
				$retstr .= $content[$i];
			}
		}
		return $retstr . '..';
	}
	return $content;
}

/**
 * 转义字符或遍历转义数组 基于addslashes()
 *
 * @param mixed $array
 */
function Add_S( &$array )
{
	if ( is_array( $array ) )
	{
		foreach( $array as $key => $value )
		{
			if ( !is_array( $value ) )
			{
				$array[$key] = addslashes( $value );
			}
			else
			{
				Add_S( $array[$key] );
			}
		}
	}
	else
	{
		$array = addslashes( $array );
	}
}
/**
 * 替换特殊字符为html安全字符
 *
 * @param string $msg
 * @return string
 */
function Char_cv( $msg )
{
	$msg = str_replace( '&amp;', '&', $msg );
	$msg = str_replace( '&nbsp;', ' ', $msg );
	$msg = str_replace( '"', '&quot;', $msg );
	$msg = str_replace( "'", '&#39;', $msg );
	$msg = str_replace( "<", "&lt;", $msg );
	$msg = str_replace( ">", "&gt;", $msg );
	$msg = str_replace( "\t", "&nbsp; &nbsp; ", $msg );
	$msg = str_replace( "\r", "", $msg );
	$msg = str_replace( "  ", "&nbsp; ", $msg );
	return $msg;
}
/**
 * 基于htmlspecialchars改写数组
 *
 * @param mixed $arr
 */
function mhtmlspecialchars( &$arr )
{
	if ( is_array( $arr ) )
	{
		foreach( $arr as $key => $val )
		{
			if ( is_array( $val ) )
			{
				mhtmlspecialchars( $arr[$key] );
			}
			else
			{
				$arr[$key] = htmlspecialchars( $arr[$key] , ENT_QUOTES );
			}
		}
	}
	else
	{
		$arr = htmlspecialchars( $arr, ENT_QUOTES );
	}
}
/**
 * 读取文件内容到字符串
 *
 * @copyright PHPWind
 * @param string $filename
 * @param string $method
 * @return string
 */
function readover( $filename, $method = "rb" )
{
	strpos( $filename, '..' ) !== false && exit( 'Forbidden' );
	if ( $handle = @fopen( $filename, $method ) )
	{
		flock( $handle, LOCK_SH );
		$filedata = fread( $handle, filesize( $filename ) );
		fclose( $handle );
	}
	return $filedata;
}

/**
 * 将指定字符串内容写入文件
 *
 * @copyright PHPWind
 * @param string $filename 文件名
 * @param string $data 要写入的数据
 * @param string $method 操作方法
 * @param boolean $iflock 是否锁定
 */
function writeover( $filename, $data, $method = "rb+", $iflock = 1, $check = 1, $chmod = 1 )
{
	$check && strpos( $filename, '..' ) !== false && exit( 'Forbidden' );
	touch( $filename );
	$handle = fopen( $filename, $method );
	if ( $iflock )
	{
		flock( $handle, LOCK_EX );
	}
	fwrite( $handle, $data );
	if ( $method == "rb+" ) ftruncate( $handle, strlen( $data ) );
	fclose( $handle );
	if ( $chmod )
	{
		return @chmod( $filename, 0777 );
	}
	else
	{
		return true;
	}
}
/**
 * 得到输入字符串的路径，考虑操作系统的分割字符/ \\
 *
 * @param string $path
 * @return string
 */
function getdirname( $path )
{
	if ( strpos( $path, '\\' ) !== false )
	{
		return substr( $path, 0, strrpos( $path, '\\' ) );
	} elseif ( strpos( $path, '/' ) !== false )
	{
		return substr( $path, 0, strrpos( $path, '/' ) );
	}
	else
	{
		return '/';
	}
}
/**
 * 取得文件后缀名
 *
 * @param string $filename
 * @return string
 */
function fileExtName ( $filename )
{
	if ( $filename )
	{
		$pt = strrpos( $filename, "." );
		if ( $pt )
		{
			$f_ext = strtolower( substr( $filename, $pt + 1, strlen( $filename ) - $pt ) );
		}
	}
	else
	{
		$f_ext = false;
	}
	return $f_ext;
}
/**
 * 建立目录，基于mkdir()，禁止在根目录或上级目录建立
 *
 * @param string $path
 * @param string $mode
 * @return bool
 */
function mkdir_recursive ( $path , $mode = 0777 )
{
	$path = trim( $path );
	if ( is_dir( $path ) )
	{
		return false;
	}
	if ( $path )
	{
		$path = str_replace( '\\', '/', $path );
		if ( preg_match( "/^[\/|\.]| .*/", $path ) )
		{
			return false;
		}
		$folders = explode( "/" , $path );
		foreach ( $folders as $folder )
		{
			if ( $folder )
			{
				$mkfolder .= $folder . '/';
				if ( !is_dir( $mkfolder ) )
				{
					mkdir( $mkfolder, $mode );
					chmod( $mkfolder, $mode );
				}
			}
		}
		return true;
	}
	else
	{
		return false;
	}
}
/**
 * 抛出一个致命的程序错误提示
 *
 * @param string $msg
 */
function throwError( $msg )
{
	// ob_end_clean();
	extract( $GLOBALS, EXTR_SKIP );
	// require GetLang( 'error' );
	$lang[$msg] && $msg = $lang[$msg];
	$errmsg = "<div style='font-size:12px;font-family:verdana;line-height:180%;color:#000;border:dashed 1px #ccc;padding:10px;margin:20px;'>";
	$errmsg .= "<span style='color:red;font-weight:bold'>Error: </span>";
	$errmsg .= $msg;
	$errmsg .= "<br/><button onclick='history.back()'>back</button>";
	$errmsg .= "</div>";
	die( $errmsg );
}
/**
 * 输出调试信息.
 *
 * @copyright ninetowns
 * @param mixed $arr 输出对象
 * @param string $title 标题提示信息
 * @param int $T 输出类型
 */
function mprint_r( $arr, $title = '', $T = 0 )
{
	global $count_mprint_r;
	$count_mprint_r++;
	if ( $count_mprint_r == 1 )
	{

		?>
<style type="text/css">
<!--
*{
	margin:0px;
	padding:0px;
}
.m_fileldset {
	margin: 0px;
	padding: 2px;/*background-color: #06c;*/
	border: 1px dashed #09c;
	word-break:break-all;
	overflow:auto;
}
.m_legend {
	background-color: #06c;
	margin: 5px;
	padding: 2px;
	border: 1px solid #fff;
	color: #ffe;
	font-weight: bold;
	font-size:12px;
}
.m_button {
	border:1px solid #f96;
	background-color: #ffc;
}
.m_pre {
	text-align:left;
	font-size:11px;
}
-->
</style>
<script>
var m_sign = true;
function m_toggle() {
	var cs = document.getElementsByTagName("pre");
	var r = new Array();
	for(var i = 0;i<cs.length;i++)
	{
		var e = cs[i];
		if("m_pre" == e.className)
		{
			e.style.display = (m_sign == false ? "block" : "none");
			r.push( e);
		}
	}

	var cs = document.getElementsByTagName("button");

	for(var i = 0;i<cs.length;i++)
	{
		var e = cs[i];
		if("m_button" == e.className)
		{
			e.innerHTML = (m_sign == false ? "-" : "+");
		}
	}
	m_sign = !m_sign;
}
</script>
<button onclick="m_toggle()">Expand/Collapse All</button>
<?php
	}
	$temp_name = substr( md5( microtime() . $arr . $title . $T ), 0, 3 );

	?>
<fieldset class="m_fileldset" >
<legend class="m_legend">
<label style="cursor:pointer">
<?=$title?>
<?php
	if ( $arr )
	{

		?>
<button class="m_button" onclick="
	var target = document.getElementById('<?=$temp_name?>');
if (target.style.display != 'none' )
{
  target.style.display = 'none';
  this.innerHTML='+';
}
else
{
  target.style.display = 'block';
  this.innerHTML='-';
}">-</button>
</label>
<?php
	}

	?>
</legend>
<?php

	if ( $arr )
	{

		?>
<pre id="<?=$temp_name?>" class="m_pre"><?php
		if ( 0 == $T )
		{
			print_r ( $arr );
		}
		else
		{
			var_export ( $arr );
		}

		?>
</pre>
<?php
	}

	?>
</fieldset>
<?php
}
/**
 * 允许部分html代码
 */
function allow_html($str){
	$arr_allow_tag = array("br","p","strong","u","em","ol","ul","li","div","sup","sub","blockquote");
	$ret = str_replace("&amp;","&",$str);
	$ret = str_replace("&quot;","\"",$ret);
	foreach ($arr_allow_tag as $tag){
		$exp = "'&lt;(\/?".$tag.".*?)&gt;'";
		//echo $exp;
		$ret = preg_replace($exp,"<\$1>",$ret);
	}
	return $ret;
}
/**
 * 删除html代码
 */
function del_html($str){
	$arr_allow_tag = array("br","p","strong","u","em","ol","ul","li","div","sup","sub","blockquote");
	$ret = str_replace("&amp;","&",$str);
	foreach ($arr_allow_tag as $tag){
		$exp = "'&lt;(\/?".$tag.".*?)&gt;'";
		//echo $exp;
		$ret = preg_replace($exp,"",$ret);
	}
	return $ret;
}
/**
 *
 * @DESC 功能 英文按词截取字符串
 * @author :yangj 修改
 * @param String $str 字符串
 */
function w_substr( $str , $slen)
{
	$str = del_html( $str );
	$str_len = strlen( $str );
	if ( $str_len < $startdd + 1 ) return "";
	$strw_arr = str_word_count( $str , 2 );
	$abreak = false;
	$startdd = 0;
	if ( $strw_arr )
	{
		foreach( $strw_arr as $key => $var )
		{
			if ( $key > ( $startdd + $slen ) )
			{
				break;
			}
			$newend = $key;
		}
		if ( !$newend ) $newend = $str_len;
	}
	if ( ( $startdd + $slen ) >= $str_len ) $newend = $str_len;
	return substr( $str , $startdd , ( $newend - $startdd ) );
}
?>