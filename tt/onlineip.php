<?php
function strtoint($str){
$ip=0;
$tetr=explode(".",$str);
for($i=0;$i<4;$i++){
$ip=$ip<<8;
$ip+=$tetr[$i];
}
return $ip;
}

/**
 * 获取用户真实 IP
 *
 * @Author: 小熊
 * @Return: string
 */
function getIP()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }

    return $realip;
}

/*
if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
    $onlineip = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
    $onlineip = getenv('HTTP_X_FORWARDED_FOR');
} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
    $onlineip = getenv('REMOTE_ADDR');
} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
    $onlineip = $_SERVER['REMOTE_ADDR'];
}
*/
$onlineip = getIP();
$onlineip = preg_replace("/^([\d\.]+).*/", "\\1", $onlineip);
//echo $onlineip;
// 以上为获取IP
//$onlineip="58.244.35.16";
$onlineip0=strtoint($onlineip);

$x=" 您的IP段不在规定范围内!";
$ips=@file("ip.txt");//打开ip文件
$j=@count($ips);
for($i=0;$i<$j;$i++){
$starstr=@explode("-",$ips[$i]);


//if($starstr[0]<=$onlineip and $onlineip>=$starstr[1]){
$a=strtoint($starstr[0]);
$b=strtoint($starstr[1]);

//if (strcmp($onlineip,$a) >= 0 && strcmp($onlineip,$b) <= 0){
if ($onlineip0>=$a && $onlineip0<=$b){
$x="您的IP段在规定范围内!& lt;br>".$starstr[0]."<=".$onlineip."<=".$starstr[1]."<br>";
break;
}

}
echo $x."<br>".$onlineip;