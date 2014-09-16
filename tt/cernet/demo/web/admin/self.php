<?PHP
/**
* 管理页面
*
* @author    indraw <indraw@163.com>
* @version   1.0
* @link      http://***
* @copyright 商业软件,受著作权保护
*/

//包含配置
include_once('../includes/LibAuthentic.inc.php');
$oApp->setMySQL();
$oMySQL->debug_all = true;
$oMySQL->show_errors = true;
/*--------------------------------------------------------*/
//
if($_GET['action'] == "do")
{
	try{
		$oUser = new DTO_Demo_User;
		$oUser->username = $_POST['username'];
		$oUser->passwd = $_POST['passwd'];
		$oUser->email = $_POST['email'];
		//$oUser->id = $_POST['id'];

		$oLogicUser = new Logic_Demo_User;
		$oLogicUser->modifyUser($oUser);
	}
	catch(MyException $e){
		$oApp->error($e,"admin.php");
	}
	$oApp->success("修改信息成功","admin.php");
}
else
{
	try{
		$oUser = new DTO_Demo_User(array("username"=>$_SESSION['username']));
		//_dump($oUser);

		$oLogicUser = new Logic_Demo_User;
		$oLogicUser->getBy($oUser,"username");
	}
	catch(MyException $e){
		$oApp->error($e,"admin.php");
	}
}
//_dump($oUser);

$oSmarty->assign("user",$oUser);
$oSmarty->display("self.htm");
/*--------------------------------------------------------*/
?>