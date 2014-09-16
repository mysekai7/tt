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
//$oMySQL->debug_all = true;
//$oMySQL->show_errors = true;
//echo "abc";
/*--------------------------------------------------------*/
//简单流程
if($_GET['action'] == "do")
{
	try{
		$oLogicUser = new Logic_Demo_User;
		$oUser = $oLogicUser->loginUser($_POST['username'],$_POST['passwd']);
		$_SESSION['username'] = $oUser->username;
	}
	catch(MyException $e){

		//_dump(get_included_files() );

		$oApp->error($e);
	}
	$oApp->success("登陆成功","index.php");
}


//调试信息
$oSmarty->display("login.htm");

//调试信息
//_dump($_SESSION);
/*--------------------------------------------------------*/
?>