<?PHP
/**
* 登陆页面
*
* @author    indraw <indraw@163.com>
* @version   1.0
* @copyright 商业软件,受著作权保护
* @link      http://***
* @create     2007/2/9 下午
*/

//包含配置
include_once('includes/LibConfig.inc.php');
$oApp->setMySQL();
$oApp->loadClass("PageMaker");
//$oMySQL->debug_all = true;
$oMySQL->show_errors = true;
/*--------------------------------------------------------*/
//简单流程

$oLogicUser = new Logic_Demo_User;
$aListUser = $oLogicUser->topUser();

//_dump($aListUser);

//调试信息
$oSmarty->assign("user",$aListUser);
$oSmarty->display("list_all.htm");

//调试信息
//_dump($_SESSION);

/*--------------------------------------------------------*/
?>