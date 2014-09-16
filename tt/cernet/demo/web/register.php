<?PHP
/**
* 注册页面
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

/*--------------------------------------------------------*/
//简单流程
if($_GET['action'] == "do")
{
	$oLogicUser = new Logic_Demo_User;
	//php5官方用法，以及现在的c#用法，应该是趋势
	try{
		$oUser = new DTO_Demo_User;
		$oUser->username = $_POST['username'];
		$oUser->passwd = $_POST['passwd'];
		$oUser->email = $_POST['email'];
		$oLogicUser->createUser($oUser);
	}
	catch(MyException $e){
		$oApp->error($e,"register.php");
	}
	//最新潮的写法，完全发挥自己的创造性，表示层程序可以用合理的任意写法，因为逻辑层已经做好了安全限制。
	/*
	try{
		$oLogicUser->createUser(new DTO_Demo_User($_POST));
	}
	catch(MyException $e){
		$oApp->error($e);
	}
	*/
	//java写法，如果你喜欢c++或java的编程风格，可以用以下写法
	/*
	try{
		$oUser = new DTO_Demo_User;
		$oUser->setUsername($_POST['username']);
		$oUser->setPasswd($_POST['passwd']);
		$oUser->setEmail($_POST['email']);
		//_dump($oUser);
		$oLogicUser->createUser($oUser);
	}
	catch(MyException $e){
		$oApp->error($e);
	}
	*/
	//
	$oApp->success("注册成功","index.php");
}
else
{
	$oSmarty->display("register.htm");

}

//调试信息
//_dump($_SESSION);

/*--------------------------------------------------------*/
?>