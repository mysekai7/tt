<?PHP
/**
* 子系统权限验证
*
* 需要登陆后使用的功能程序包含此文件
* 
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     private
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

//
include_once('LibConfig.inc.php');
$oSmarty->template_dir = SITE_DIR."admin/templates";
$oSmarty->compile_dir = SITE_DIR."admin/templates_c";

//---------------------------------------------------------
//
if(!$_SESSION['username'])
{
	$oApp->error("您还没有登陆","/index.php");
}
//

//---------------------------------------------------------
?>