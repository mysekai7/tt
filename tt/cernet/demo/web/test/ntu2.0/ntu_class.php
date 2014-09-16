<?PHP
/**
* NTU Class Creator
*
* 自动生成DTO和DMO,DTO部分需要根据实际需求进行相应的数据检测
*
* @author    indraw <indraw@163.com>
* @version   1.0
* @copyright 商业软件,受著作权保护
* @link      http://***
* @create    2007/04/10 上午
*/

include("MySQLToClass_DTO.php");
include("MySQLToClass_DMO.php");

include("MySQLToClass_Logic.php");

$sVersion = "NTU Class Creator 1.0";

//-----------------------------------------------------------------------------
//显示函数
function echoinfo($input)
{
	global $sVersion;

	print <<<EOT
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	<HTML>
	<HEAD>
	<TITLE> $sVersion </TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
	<!--
	a {color:blue;font-size: 9pt;text-decoration:none; }
	a:hover {color:blue;text-decoration:underline overline;}
	td{FONT-SIZE: 9pt; color:#333333; font-family:宋体}
	body{FONT-SIZE: 9pt; color:#333333; font-family:宋体}
	-->
	</style>
	</HEAD>
	<BODY>
	<table cellspacing=0 cellpadding=5 border=0 >
	  <tr>
		<td bgcolor=#CCCCFF><A HREF="ntu_class.php">自动生成DTO/DMO</A>
		</td>
	  </tr>
	</table>
	<!--显示信息-->
	<br>
	<table cellspacing=0 cellpadding=5 border=0 >
		<tr>
		<td bgcolor=#CCCCFF>自动生成信息
		</td>
		</tr>
	</table>
	<hr noshade color=dddddd size=1>
	$input
	<!------------>
	<br>
	<table cellspacing=0 cellpadding=5 border=0 >
	  <tr>
		<td bgcolor=#CCCCFF>开发说明
		</td>
	  </tr>
	</table>
	<hr noshade color=dddddd size=1>
	研发部 $sVersion
	</BODY>
	</HTML>
EOT;
}
//-----------------------------------------------------------------------------
//处理部分
if($_GET["action"] == "do")
{
	//测试
	$aClassInfo['class_pre'] = $_POST['class_pre'];
	$aClassInfo['class_dir'] = $_POST['class_dir'];
	$aClassInfo['class_author'] = $_POST['class_author'];
	$aClassInfo['class_version'] = $_POST['class_version'];
	$aClassInfo['class_module'] = $_POST['class_module'];
	$aClassInfo['class_copy'] = $_POST['class_copy'];
	$aClassInfo['class_link'] = $_POST['class_link'];
	$aClassInfo['class_date'] = $_POST['class_date'];
	//生成dto
	$test = new MySQLToClass_DTO($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpasswd'],$_POST['dbname'],$aClassInfo);
	//$test->ClassNameCallBack = "removeWapPrefix";
	$test->Run();
	foreach($test->ClassList as $sClassName)
	{
		$sHtmlInfo .= "$sClassName<br>";
	}
	//生成dmo
	$test2 = new MySQLToClass_DMO($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpasswd'],$_POST['dbname'],$aClassInfo);
	$test2->Run();
	foreach($test2->ClassList as $sClassName)
	{
		$sHtmlInfo .= "$sClassName<br>";
	}
	//生成逻辑
	$test3 = new MySQLToClass_Logic($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpasswd'],$_POST['dbname'],$aClassInfo);
	$test3->Run();
	foreach($test3->ClassList as $sClassName)
	{
		$sHtmlInfo .= "$sClassName<br>";
	}

	//显示输出
	$sHtmlInfo .= "<br>成功生成类库(".(count($test->ClassList)+count($test2->ClassList)).")个. <a href=\"ntu_class.php\">返回</a><br><br>";
	echoinfo($sHtmlInfo);
}
//-----------------------------------------------------------------------------
//显示页面
else
{
	$fileDir = eregi_replace("\\\\","/",getcwd() );
	$input = "<form method=post action=\"?action=do\">
		<table cellspacing=\"3\">
		  <tr>
			<td width=\"20%\">数据库地址：</td>
			<td><input type=\"text\" name=\"dbhost\" size=\"20\" value=\"localhost\"> *</td>
		  </tr>
		  <tr>
			<td>数据库用户：</td>
			<td><input type=\"text\" name=\"dbuser\" size=\"20\" value=\"root\"> *</td>
		  </tr>
		  <tr>
			<td>数据库密码：</td>
			<td><input type=\"text\" name=\"dbpasswd\" size=\"20\"> 如果没有密码，请留空！</td>
		  </tr>
		  <tr>
			<td>数据库名称：</td>
			<td><input type=\"text\" name=\"dbname\" size=\"20\" value=\"demo\"> *</td>
		  </tr>
		  <!--------
		  <tr>
			<td>模块前缀：</td>
			<td><input type=\"text\" name=\"class_pre\" size=\"20\" value=\"\"> 如果不想统一前缀，请留空！系统将自动按照数据表进行命名。</td>
		  </tr>
		  ------------>
		  <tr>
			<td>生成类存放路径：</td>
			<td><input type=\"text\" name=\"class_dir\" size=\"60\" value=\"{$fileDir}/class\"> *</td>
		  </tr>
		  <tr>
			<td colspan=2><hr></td>
		  </tr>
		  <tr>
			<td >作者：</td>
			<td><input type=\"text\" name=\"class_author\" size=\"20\" value=\"indraw\"> *</td>
		  </tr>
		  <tr>
			<td >版本：</td>
			<td><input type=\"text\" name=\"class_version\" size=\"20\" value=\"1.0\"> *</td>
		  </tr>
		  <tr>
			<td >所属模块名称：</td>
			<td><input type=\"text\" name=\"class_module\" size=\"20\" value=\"NTU NEW1.0\"> *</td>
		  </tr>
		  <tr>
			<td >版权所有：</td>
			<td><input type=\"text\" name=\"class_copy\" size=\"40\" value=\"商业软件,受著作权保护\"> *</td>
		  </tr>
		  <tr>
			<td >联接地址：</td>
			<td><input type=\"text\" name=\"class_link\" size=\"40\" value=\"http://***\"> *</td>
		  </tr>
		  <tr>
			<td >生成日期：</td>
			<td><input type=\"text\" name=\"class_date\" size=\"20\" value=\"2007/04/09\"> *</td>
		  </tr>
		  <tr>
			<td colspan=\"2\"><input type=\"submit\" value=\" 开始生成 \">
			<input type=\"reset\" value=\" 重置 \">
			</td>
		  </tr>
		</table>
		</form>";
	echoinfo($input);
}
//-----------------------------------------------------------------------------
//end class
?>