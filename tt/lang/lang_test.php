<?php

error_reporting(E_ALL ^ E_NOTICE);
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Last-Modified: '.gmdate('D, d M Y H:i:s'). ' GMT');
header('Content-type:text/html; charset=utf-8');
ini_set('magic_quotes_runtime', 0);

//$q = urlencode($html);

$lang = $_POST['lang'];
$data = $_POST['data'];

//test
//$lang = 'zh-CN';
//$data = "1####mp3^^^^2####apple";

$words = array();
if($data)
{
    $tmp = explode('^^^^', $data);
    if($tmp && is_array($tmp) && count($tmp)>0)
    {
        foreach($tmp as $val)
        {
            list($no, $word) = explode('####', $val);
            $words[$no] = $word;
        }
    }
}

$q = $q_prev ='';

$return =array();
$total = count($words);
if($words && $total>0)
{
    foreach($words as $key => $word)
    {
        $q .= '&q='.urlencode($word);
        $len = strlen(trim($q, '&'));

        if($len>=1400 || ($total - $key - 1)==0)
        {
            //echo "total: $total, key: $key, flag: $flag <br>";
            //$no = $key - $flag;
            dealwith_return($q, $lang);
            $flag = 0;
            $len=0;
            $q='';
            $q_prev='';
        }
    }
}

echo json_encode($return);


//$url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&key=ABQIAAAAOx4FK3QhsiXWcFxWf0hrDhRh0Cj5vMW1H2sgWc5zqzWwev4AKRRtxKto2WM3Ur5ahaFE09aDoT-LEw&q=hello%20world&langpair=en|zh-CN";
//$q = urlencode('hello <b>world</b>');

//url最大长度1800

//echo strlen($url);


function dealwith_return($q, $lang)
{
    global $return;
    $q = trim($q, '&');
    $url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&key=ABQIAAAAOx4FK3QhsiXWcFxWf0hrDhRh0Cj5vMW1H2sgWc5zqzWwev4AKRRtxKto2WM3Ur5ahaFE09aDoT-LEw&{$q}&langpair=en|".$lang;
    $rs = get_data($url);
    if($rs->responseStatus == 200)
    {
        if(count($rs->responseData)>0)
        {
            foreach($rs->responseData as $k => $v)
            {
                //echo $k;
                //$index = $k + $no;
                $return[] = $v->responseData->translatedText;
            }
        }
    }
}


function get_data($url)
{

    // sendRequest
    // note how referer is set manually
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, "http://www.tootoo.com");
    curl_getinfo($ch);
    $body = curl_exec($ch);
    //var_dump(curl_error($ch));
    curl_close($ch);

    //$body = file_get_contents($url);
    // now, process the JSON string
    $json = json_decode($body);
    // now have some fun with the results...

    return $json;
}