<?php
/*
cookie,session 是WEB应用程序保持用户状态的方法

cookie是保存的客户端的信息，由客户端连接服务器时发送到服务器的。

session是保存在服务端的信息，从这个角度session相对cookie更安全
当会话创建时服务器返回给客户端一个加密后的session id以标识用户身份，session id 一般保存在cookie当cookie不可用时由URL传递

上面的代码演示如何创建和使用session cookie变量

setcookie详细使用说明：http://www.coderhome.net/m/php/function.setcookie.html
*/




// session start
session_start(); // 开始一个会话，如果要使用session程序最前面一定要加上这句
$_SESSION['user_id'] = '123';//给一个session 变量赋值，如果该变量不存在即创建

echo $_SESSION['user_id'];//访问 session变量

$_SESSION = array();//清空所有session变量

session_destroy();//清除会话ID
// session end

// cookie start
setcookie('user_id',123);//创建一个cookie变量user_id=123

echo $_COOKIE['user_id'];//访问 cookie变量 和变通变量一样

setcookie('user_id',0,time()-1);//删除cookie变量
// codie end

// 该代码不可运行，只是将所有使用方法在这里列出，实际应该不同功能在不同页面使用，将在下面的例子中演示
?>