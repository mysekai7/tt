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
if($_SESSION['username'])
{
	$sUserName = $_SESSION['username'];
}
else
{
	$sUserName = "访客";
}

//模板调用
$oSmarty->assign("username",$sUserName);
$oSmarty->display("index.htm");

//调试信息
_dump($_SESSION);

/*--------------------------------------------------------*/
?>