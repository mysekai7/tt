<?PHP
/**
* AJAX操作：公用进入接口
*
* 对ajax调用进行统一调度，同时控制权限
*
* @author      indraw<indraw@163.com>
* @version     1.0
* @package     PHPSea Ajax
* @access      public
* @copyright   商业软件,受著作权保护
* @link        http://*
*/

//取消缓存
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 2000 05:00:00 GMT");
ob_start();
//初始化操作
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
include_once("../includes/LibConfig.inc.php");
include_once("./lib/InitAjax.class.php");
$oApp->setMySQL(); 

//$oAppNew->SetMySQL($LibSet['DBname']);
//$_GET['mod'] //A:GetLine
//AppNew::SetErrorType(2);

sleep(1);
//如果是小于php5.2那么起用php类
//if( phpversion() < 5.2) 
//{
	//require_once ("./lib/JSON.class.php");
//}
/*--------------------------------------------------------*/
//获取参数
if($_GET['Mod'])
{
	$aParaMods = explode(":",$_GET['Mod']);
}
else
{
	echo "parameter error (".$_GET['Mod'].")";
	exit;
}
$sModName = $aParaMods[0];
$sModMethod = $aParaMods[1];
$sModFile = "./mods/".$sModName.".class.php";
/*
if(eregi("^Dns",$sModName))
{
	$sModFile = $LibSet['SystemDir']."AJAX/mods/".$sModName.".class.php";
}
*/

if (!file_exists($sModFile)) {
	echo "module file error (".$aParaMods[0].")";
	exit;
}
//加载模块，并执行方法
require_once ($sModFile);
$oModule = new Module;
if(!method_exists($oModule,$sModMethod))
{
	echo "method error (".$aParaMods[1].")";
	exit;
}
$oModule->$sModMethod();

/*--------------------------------------------------------*/
?>