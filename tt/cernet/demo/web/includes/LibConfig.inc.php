<?PHP
/**
* 子系统初始化文件
*
* 包含通用引入文件，并初始化应用类
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     private
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

//session_cache_limiter('none');

//include
include_once("LibSet.inc.php");                  //系统参数
include_once("Function.inc.php");                //系统参数
include_once(CLASS_DIR."/BaseSet.inc.php");       //核心配置

//initialize
$oApp = new Application;    //生成对象App
@session_start();

//debug
//include_once(debug.php");

//---------------------------------------------------------
?>