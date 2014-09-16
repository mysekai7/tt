<?php
header("Content-type: text/html; charset=utf8");

$tmp = file_get_contents('http://51uway.com/bussiness-0755904180.html');

preg_match("|<div id=\"pro_lbot_b\">(.*?)</div>|is", $tmp, $row);

$c = $row[1];

preg_match_all("|<img src=\"(.*?)\".*?/>|is",$row[1], $img);

$new_img = array();
foreach($img[1] as $key => $val){
    $file_img = "http://51uway.com/".substr($val, 6);
    $path_parts = array();
    $path_parts = pathinfo($file_img);

    my_copy($file_img, $path_parts['basename']);
    $new_img[$key] = $path_parts['basename'];
}

var_dump($new_img);



//----------------------
function my_copy($source, $dest)
{
    /*
    $res	= @copy($source, $dest);
    if( $res ) {
        chmod($dest, 0777);
        return TRUE;
    }
    */
    if( function_exists('curl_init') && preg_match('/^(http|https|ftp)\:\/\//u', $source) ) {
        echo '11111111111111111111';
        $dst	= fopen($dest, 'w');
        if( ! $dst ) {
            return FALSE;
        }
        $ch	= curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_FILE	=> $dst,
            CURLOPT_HEADER	=> FALSE,
            CURLOPT_URL		=> $source,
            CURLOPT_CONNECTTIMEOUT	=> 3,
            CURLOPT_TIMEOUT	=> 5,
            CURLOPT_MAXREDIRS	=> 5,
            CURLOPT_REFERER	=> 'http://favefavefave.com',
            CURLOPT_USERAGENT	=> isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1',
        ));
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $res	= curl_exec($ch);
        fclose($dst);
        if( ! $res ) {
            curl_close($ch);
            return FALSE;
        }
        if( curl_errno($ch) ) {
            curl_close($ch);
            return FALSE;
        }
        curl_close($ch);
        chmod($dest, 0777);
        return TRUE;
    }
    return FALSE;
}