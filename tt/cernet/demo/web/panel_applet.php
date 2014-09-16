<?PHP
/**
* 测试首页
*
* @author    indraw <indraw@163.com>
* @version   1.0
* @copyright 商业软件,受著作权保护
* @link      http://***
* @create     2007/2/9 下午
*/

//包含配置
include_once('includes/LibConfig.inc.php');

/*--------------------------------------------------------*/
//简单流程
$_GET['abc'];
if($_POST)
{
	echo "What you submitted is \"".$_POST['name']."\"";
}
else {
//模板调用
//$oSmarty->assign("username",$sUserName);
$oSmarty->display("panel/panel_applet.html");
}
//调试信息
//_dump($_SESSION);

/*--------------------------------------------------------*/
?>