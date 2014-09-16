<?php
header("Content-type:text/html; Charset:utf8");

require_once('PostHttp.class.php');
set_time_limit(0);
//error_reporting(0);


$http = new PostHttp();
$http->clearFields();

$url = get_big_pic('http://favefavefave.com/view/13146');

//my_copy($url, 'aaa.jpg');


//my_copy('http://pic.hellocache.com/2010/06/03/m/1184507678/4717ecb6bbf33f8156b54a90b392270f-1275556274.jpg', 'vv.jpg');


function get_big_pic($url)
{
    global $http;
    //$tmp = file_get_contents($url);
    $http->postPage($url);
    $tmp = $http->getContent();
    if(!$tmp)
        return false;
    $pattern = '|<div class="forme" id="view">.*?<p style="text-align:center;"><img src="(.*?)" alt=".*?" /></p>.*?via:\s*?<a href="(.*?)">(.*?)</a>.*?</div>|is';
    preg_match($pattern, $tmp, $row);
    array_shift($row);
    var_dump($row);
    //return $row[1];
}


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