<?php
/**
 *  文件名: config.php
 *  描述:   程序配置文件
 *  博客:   www.sk80.com
 *  作者:   mysekai7
 *  时间:   2010-1-29 下午
 */

//设置调试开关
define('DEBUG', true);

if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
{
    //设置数据库
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '123456');
    define('DB_NAME', 'sk80');

    //设置网站url
    define('SITE_URL', 'http://sk2.com/');
}
else
{
    /*
    | Domain: sk80.com
    | Ip: 69.73.183.118
    | UserName: mysekai7
    | PassWord: JVWSKEZXTPVP

    空间：69.73.183.63/~mysekai7/
    面板：69.73.183.63/cpanel

    */
    //设置数据库
    define('DB_HOST', 'localhost');
    define('DB_USER', 'mysekai7');
    define('DB_PASS', 'JVWSKEZXTPVP');
    define('DB_NAME', 'mysekai7_mydb');

    //设置网站url, 以'/'结尾
    define('SITE_URL', 'http://blog.sk80.com/');
}

//设置数据库表前缀
define('TABLE_PREFIX', 'sk_');

//设置默认时区
define('DEFAULT_TIMEZONE', 'PRC');

//设置默认时区
define('DEFAULT_TEMPLATE', 'default');

//设置rewrite
define('USE_MOD_REWRITE', false);


?>