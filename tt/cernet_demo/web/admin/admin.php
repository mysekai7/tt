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
/*--------------------------------------------------------*/
//

$oSmarty->display("admin.htm");
/*--------------------------------------------------------*/
?>