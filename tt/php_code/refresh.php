<?php
// 防恶意刷新代码
$allowTime = 1200;//防刷新时间
$ip = get_client_ip();

// 注意程序里使用变量代替这里的$_GET
$allowT = md5($ip.$_GET['v']);

if(!isset($_SESSION[$allowT]))
{
   $refresh = true;
   $_SESSION[$allowT] = time();
}
elseif(time() - $_SESSION[$allowT]>$allowTime)
{
   $refresh = true;
   $_SESSION[$allowT] = time();
}
else
{
   $refresh = false;
}

if ($refresh==false)
{
   exit("请不要反复刷新!");
}
