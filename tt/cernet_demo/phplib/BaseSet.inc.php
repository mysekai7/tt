<?PHP
/**
* 系统配置文件
*
* 定义系统层面的通用属性，或可以在各个子系统中使用的变量，或需要防止web访问的变量
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     private
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

//数据库设置
/*
$DBSet['host'] = "localhost";    //数据库地址
$DBSet['user'] = "root";         //数据库用户名
$DBSet['pass'] = "";             //数据库密码
$DBSet['name'] = "demo";       //数据库名
*/

//路径设置

//包含文件
include_once(CLASS_DIR."/Frame/Application.class.php");   //表示层入口
include_once(CLASS_DIR."/Frame/MyException.class.php");   //核心错误处理
include_once(CLASS_DIR."/Frame/InitCommon.class.php");    //系统核心父类
include_once(CLASS_DIR."/Frame/InitDTO.class.php");       //系统核心父类
include_once(CLASS_DIR."/Frame/InitDMO.class.php");       //系统核心父类
include_once(CLASS_DIR."/Frame/InitLogic.class.php");     //系统核心父类

//---------------------------------------------------------
?>