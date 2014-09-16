<?php
/*

时间所有函数：http://www.coderhome.net/m/php/ref.datetime.html

关于date的参数说明：http://www.coderhome.net/m/php/function.date.html

*/



// 设定要用的默认时区。自 PHP 5.1 可用
date_default_timezone_set('Asia/Chongqing');//中国地区使用

echo date("Y-m-d H:i:s");//显示当前时间



// 简单实现时间加减
$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));//明天
//现在的结果是时间戳，格式化显示，下面相同
echo date("Y-m-d H:i:s",$tomorrow);
$lastmonth = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));// 下个月
$nextyear  = mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1);// 明天

// 返回当前的 Unix 时间戳
echo time();
// 返回当前 Unix 时间戳和微秒数 microtime()
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

// Sleep for a while
usleep(100);

$time_end = microtime_float();
$time = $time_end - $time_start;//程序执行时间

?>