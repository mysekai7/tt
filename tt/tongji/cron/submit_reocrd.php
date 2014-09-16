<?php

require_once('PostHttp.class.php');

error_reporting(E_ALL ^ E_NOTICE);

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Last-Modified: '.gmdate('D, d M Y H:i:s'). ' GMT');
header('Content-type:text/html; charset=utf-8');
ini_set('magic_quotes_runtime', 0);
set_time_limit(0);

//基本设置
$C = new stdClass;

//收录配置
$C->RECORD_ENGINE = array(
    'google' => 'http://www.google.com/search?hl=en&source=hp&q=site%3A*****&aq=f&aqi=g4g-s1g5&aql=&oq=&gs_rfai=',
);

//需要查询的urls
$C->RECORD_URLS = array(
    'tootoo' =>  array(
        'www.tootoo.com',
        'www.tootoo.com/buy-',
        'www.tootoo.com/s-',
        'www.tootoo.com/d-',
        'www.tootoo.com/idetail/',
        'www.tootoo.com/inquire/',
    ),
    'yaphon' =>  array(
        'www.yaphon.com',
        'www.yaphon.com/buy-',
        'www.yaphon.com/kb-',
        'www.yaphon.com/kp-',
        'www.yaphon.com/kpr-',
    ),
    'tootoomart' => array(
        'www.tootoomart.com',
        'www.tootoomart.com/buy-',
        'www.tootoomart.com/orderlist/',
        'www.tootoomart.com/product-/',
        'www.tootoomart.com/wholesale-/'
    ),
);


//抓取

$r = array();
foreach($C->RECORD_ENGINE as $key => $val)//循环引擎
{
    if(empty($val))
    {
        continue;
    }

    foreach($C->RECORD_URLS as $site => $urls)//循环各主站
    {
        if(is_array($urls) && count($urls) == 0)
        {
            continue;
        }

        foreach($urls as $k => $v)//循环要抓取的url
        {
            $url = str_replace('*****', $v, $val);
            $cnt = 0;
            $tmp = FALSE;
            while($cnt < 3 && ($tmp=@get_sources($url))===FALSE)
            {
                $cnt++;
                sleep(4);
            }
            if(!$tmp)
            {
                $r[$key][$site][$v] = 0;
            } else {
                $r[$key][$site][$v] = parse_html($tmp); //$r[引擎类型][主站][搜索url]
            }
            ob_flush();
            flush();
            echo $v." ======== ".$r[$key][$site][$v]."<br>";

            sleep(3);//抓取间隔5s
        }
    }
}

$postData['time'] = strtotime('last day');
$postData['data'] = base64_encode(serialize($r));
$postURL = "http://tongji.tootoo.com/cron/api_receive_records.php";

$http = new PostHttp();
$http->clearFields();
foreach ($postData as $key => $val)
{
    $http->addField($key, $val);
}

$http->postPage($postURL);
$strPostResult = $http->getContent();

var_dump($strPostResult);



//-------------------------------------------------------------------
function get_sources($url)
{
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1');
    curl_setopt ($ch, CURLOPT_REFERER, 'http://www.google.com/');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $sources = curl_exec ($ch);
    curl_close($ch);
    return $sources;
}

function parse_html($str)
{
    if(!$str)
    {
        return FALSE;
    }
    $pattern = '|<div id=resultStats>About\s*(.*?)\s*results<nobr>|is';
    preg_match($pattern, $str, $row);
    //var_dump($row);
    return $row[1] ? $row[1] : 0;
}