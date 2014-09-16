<?php

set_time_limit(0);

$pic_dir = "./pic/";
$tags_index = "tag_index/";
$error_log = 'error_log.txt';
$success_log = 'success_log.txt';

//读取已下载的图片数组
$down = array();
if(file_exists($success_log))
    $down = file($success_log);

//获得标签目录
$tags_dir = get_file($tags_index);
$total = count($tags_dir);

foreach($tags_dir as $key => $val)
{
    //if($key > 0 ) break;

    $path = $tags_index.$val.'/';
    $pages = get_file($path);
    foreach($pages as $k => $v)
    {
        if($v == 'pages.txt')
            continue;
        $page_file = $path.$v;
        $tmp = read_page($page_file);
        //var_dump($tmp);
        foreach($tmp as $pic)
        {
            if(isset($down[$pic['filename']]))
                continue;
            //下载大图
            $source = $pic['src'];
            if($pos = strripos($pic['filename'], '.jpg'))
                $dest = $pic_dir.substr($pic['filename'], 0, ($pos+4));
            else if($pos = strripos($pic['filename'], '.jpeg'))
                $dest = $pic_dir.substr($pic['filename'], 0, ($pos+5));
            else if($pos = strripos($pic['filename'], '.png'))
                $dest = $pic_dir.substr($pic['filename'], 0, ($pos+4));
            else if($pos = strripos($pic['filename'], '.gif'))
                $dest = $pic_dir.substr($pic['filename'], 0, ($pos+4));
            else
                $dest = $pic_dir.substr($pic['filename'], 0, strripos($s, '.')).'.jpg';


            $error = my_copy($source, $dest);
            if(!$error) {
                sleep(5);
                $error = my_copy($source, $dest);
            }
            if(!$error) {
                sleep(5);
                $error = my_copy($source, $dest);
            }
            if(!$error){
                do_log($error_log, $pic['src']."\n");//记录没有采集成功的图片
                continue;
            }
            do_log($success_log, $pic['filename']."\n");

        }
    }
    ob_flush();
    flush();
    $posi = $key + 1;
    echo $posi.'/'.$total.'<br />';
}

function get_file($path)
{
    $return = array();
    if($handle = opendir($path)) {
        while(false !== ($file = readdir($handle))) {
            if($file != '.' && $file != '..') {
                $return[] = $file;
            }
        }
        closedir($handle);
    }
    return $return;
}

function read_page($url)
{
    $row = $r = array();
    if(file_exists($url))
        $row = file($url);
    if(is_array($row) && count($row) > 0)
    {
        foreach($row as $key => $val)
        {
            list($r[$key]['title'], $r[$key]['filename'], $r[$key]['src'], $r[$key]['via_site'], $r[$key]['via_url']) = explode('#####', $val);
        }
    }
    return $r;
}

function my_copy($source, $dest, $cookie='')
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
            CURLOPT_COOKIE,
            CURLOPT_CONNECTTIMEOUT	=> 3,
            CURLOPT_TIMEOUT	=> 5,
            CURLOPT_MAXREDIRS	=> 5,
            CURLOPT_REFERER	=> 'http://pic.hellocache.com',
            CURLOPT_USERAGENT	=> isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1',
        ));
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $res	= curl_exec($ch);   //返回资源
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

function do_log($file, $str)
{
    $f2 = fopen($file, 'a');
    fputs($f2, $str);
    fclose($f2);
}
