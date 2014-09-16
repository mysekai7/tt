<?php
$url = 'http://pic.hellocache.com/2010/05/01/m/1145298959/298a66829b1d0263303ed6b33568a143-1272705875.jpg';



my_copy($url, 'pp.jpg');
echo "<img src='pp.jpg' />";





function my_copy($source, $dest)
{
    $res	= @copy($source, $dest);
    if( $res ) {
        chmod($dest, 0777);
        return TRUE;
    }
    if( function_exists('curl_init') && preg_match('/^(http|https|ftp)\:\/\//u', $source) ) {
        global $C;
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
            CURLOPT_REFERER	=> $C->SITE_URL,
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