<?php

/*

今天写TAG时为防止别人恶意刷新增加TAG的点击数，所以就考虑增加一个参数来防止这类情况的发生，COOKIE和SESSION可供选择，不过 COOKIE是客户端的，如果人家禁用COOKIE的话，照样可以恶意刷新点击数。还是用SESSION的好，IP+URL参数的MD5值做 SESSION名，我想大家也不好伪造了吧。
*/


session_start();
$k=$_GET['k'];
$t=$_GET['t'];
$allowTime = 1800;//防刷新时间
$ip = get_client_ip();
$allowT = md5($ip.$k.$t);
if(!isset($_SESSION[$allowT]))
{
    $refresh = true;
    $_SESSION[$allowT] = time();
}elseif(time() - $_SESSION[$allowT]>$allowTime){
    $refresh = true;
    $_SESSION[$allowT] = time();
}else{
    $refresh = false;
}
?>