<?PHP
/**
* 子系统配置文件
*
* 定义系统路径，以及数据库连接，通用变量等信息
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     private
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

//class
define("SYSTEM_DIR","D:/www/demo/");     //系统根路径
define("CLASS_DIR",SYSTEM_DIR."phplib/");          //类存放路径
define("LOG_DIR",SYSTEM_DIR."/phplib/Log/");        //日志记录路径

define("SITE_DIR",SYSTEM_DIR."/web/");              //子系统根路径
define("SITE_URL","http://localhost/demo/web/");                   //url地址

define("SITE_ID","1000");          //子系统编号
define("SITE_NAME","demo");    //子系统名称

//mysql
$DBSet['host'] = "localhost";    //数据库地址
$DBSet['user'] = "root";         //数据库用户名
$DBSet['pass'] = "123456";             //数据库密码
$DBSet['name'] = "demo";       //数据库名

//smtp
$MailSet['host'] = "202.205.109.69";    //SMTP
$MailSet['user'] = "admin@eol.cn";      //SMTP
$MailSet['pass'] = "admin123";          //SMTP

//subsystem
define("ROOT_DOMAIN",".1cm.mobi");   //公司根域


//---------------------------------------------------------
?>