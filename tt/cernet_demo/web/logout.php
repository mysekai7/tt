<?PHP
/**
* 退出登陆
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
if(!$_SESSION['username'])
{
	$oApp->error("您还没有登陆","index.php");
}
else
{
	unset($_SESSION['username']);
	$oApp->success("退出成功","index.php");
}

//调试信息
//_dump($_SESSION);

/*--------------------------------------------------------*/
?>