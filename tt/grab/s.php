<?php
//$sr = 'http://pic.hellocache.com/2010/03/31/m/1148621910/f092a9a5e03f089149e3d70ea9559afd-1270013381.jpg';
//$s = pathinfo($sr);
//var_dump($s);

require_once('PostHttp.class.php');
set_time_limit(0);
//error_reporting(0);

$c_str = 'lang=zh; time_zone=Asia%2FShanghai; fav_secure=FbUDgQmtnRZinNe4294K1wU%2B0eidTxf%2FKKCPIlsiqVnLheFjVl6Phe365xjOflvLU%2FqptT1%2FZ1KcpkK71gT0Lkbp55DA0zfzS9ZeU5C9%2Bx2rb42k%2FAoW7Dvubigc%2FfdnDXOJV2vQQAX8ysdsYhEqYaHRXQ97RMJRPmCFZ%2F0iFePZwyG6FLOytOy%2FfXGD4%2Bj%2Bsuu7EK%2FpkCs%3D; __utma=169688886.571695815.1276247547.1276394233.1276400706.5; __utmz=169688886.1276247547.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); lzstat_uv=2735514327635723467|1283235; lzstat_ss=2385774387_9_1276429673_1283235; __utmc=169688886; __utmb=169688886.10.10.1276400706; pinnum=56707c73973bec80087e4aa2f72ec53b97d51b38; user_id=10887; user_email=leek1984%40qq.com; user_nickname=kilerall; user_profile=kiler; user_role=user; user_secure=P6zWj1vmA9m5gy%2B45NrB25DbcSNARs8z%2FADNdgqAtjBFUB%2F2ZqZU1CTUK0CS0RJN3fPgI5Z468QUS%2BF0Jkd1rA%3D%3D; action_time=1276400898';


$http = new PostHttp();
$http->show_debug =1;
$http->clearFields();

$cc = $http->getCookies($c_str);
var_dump($cc);

exit;

$count = get_max_pages('http://favefavefave.com/tag/xixi like');
echo $count;

function get_max_pages($url)
{
    global $http;
    //$tmp = file_get_contents($url);
    $http->postPage($url);
    $tmp = $http->getContent();

    if(!$tmp)
        return false;
    $pattern = "|<a href=\"$url/page/(\d+)\" title=\"Last\">...</a>|i";

    $row = array();
    if(!preg_match($pattern, $tmp, $row))
    {
        $pattern = "|<div class=\"pages\">.*?<a href=\"$url/page/(\d)\">\d</a>\s</div>|is";
        preg_match($pattern, $tmp, $row);
    }

var_dump($row);
    return isset($row[1]) ? $row[1] : FALSE;
}
