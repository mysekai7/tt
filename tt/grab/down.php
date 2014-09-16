<?php
header("Content-type:text/html; Charset:GBK");

require_once('PostHttp.class.php');
set_time_limit(0);
//error_reporting(0);


$http = new PostHttp();
$http->referer = 'http://favefavefave.com';
$http->clearFields();
/*
foreach ($postData as $key => $val)
{
    $http->addField($key, $val);
}
$http->postPage($postURL);
$strPostResult = $http->getContent();
 *
 */

//默认从标签页开始采集

$pic_dir = 'pic/';
$index_dir = 'tag_index/';

if(!file_exists($pic_dir))
    mkdir($pic_dir, '0777');
if(!file_exists($index_dir))
    mkdir($index_dir, '0777');

//标签列表
$tags_list = 'tags_list.txt';
if(!file_exists($tags_list))
    get_tags();

$tags = unserialize(file_get_contents($tags_list));
$tags_total = count($tags);
foreach($tags as $key => $val)
{
    //不抓取以下问题标签
    $filter = array(' ', '(', ')');
    $error = false;
    foreach($filter as $kw){
        if(strpos($val['name'], $kw)){
            echo 'Error tag: '.$val['name'].'<br />';
            $error = true;
            break;
        }
    }
    if($error)
        continue;

    //图片目录
    $tag_pic_dir = $pic_dir.md5($val['name']);
    //if(!file_exists($tag_pic_dir))
        //mkdir($tag_pic_dir, '0777');

    //图片索引目录
    $tag_index_dir = $index_dir.md5($val['name']);
    if(!file_exists($tag_index_dir))
        mkdir($tag_index_dir, '0777');

    $file_pages = $tag_index_dir.'/pages.txt';
    if(file_exists($file_pages))
        continue;

    //统计页数
    $count = 0;
    $count = get_max_pages($val['url']);

    if(!$count)
    {
        sleep(5);
        $count = get_max_pages($val['url']);
    }
    if(!$count)
    {
        sleep(5);
        $count = get_max_pages($val['url']);
    }
    if(!$count)
    {
        sleep(5);
        $count = get_max_pages($val['url']);
        echo "{$count}-----{$val['url']}\n<br />";
        ob_flush();
        flush();
        break;

    }

    echo "{$key}/$tags_total----Pages:{$count}-----{$val['url']}\n<br />";
    ob_flush();
    flush();


    if($count) {
        file_put_contents($file_pages, $count);
        for($i=1; $i <= $count; $i++)
        {
            //避免重复采集
            if(file_exists($tag_index_dir.'/'.$i.'.txt'))
                continue;

            //读取每页小图信息
            $page_url = $val['url'].'/page/'.$i;
            $arr = array();
            $arr = get_small_pic($page_url);

            if(!$arr){
                sleep(5);
                $arr = get_small_pic($page_url);
            }
            if(!$arr){
                sleep(5);
                $arr = get_small_pic($page_url);
            }

            //读取大图
            $pic = array();
            foreach($arr as $k => $v)
            {
                if($v['url'] == '')
                    continue;
                $pic_info = array();
                $pic_info = get_big_pic($v['url']);
                if(!$pic_info)
                {
                    //如果没获得大图地址 等待5秒再重新请求一次
                    sleep(5);
                    $pic_info = get_big_pic($v['url']);

                }
                if(!$pic_info)
                {
                    //如果没获得大图地址 等待5秒再重新请求一次
                    sleep(5);
                    $pic_info = get_big_pic($v['url']);

                }
                if(!$pic_info)
                    continue;

                $part_url = array();
                $part_url = pathinfo($pic_info[0]);
                if(!$part_url['extension'])
                    continue;
                $pic[$k]['title'] = empty($v['title']) ? 'Unknown' : $v['title'];
                $pic[$k]['name'] = $part_url['basename'];
                $pic[$k]['src'] = $pic_info[0];
                $pic[$k]['via_name'] = empty($pic_info[2]) ? 'unknown' : $pic_info[2];
                $pic[$k]['via_url'] = empty($pic_info[1]) ? '' : $pic_info[1];
                //暂时放弃抓图
                //my_copy($big_pic_url, $tag_pic_dir.'/'.$part_url['basename']);
            }


            //创建索引, 记录抓取信息
            $str = '';
            foreach($pic as $k => $v)
            {
                $str .= "{$v['title']}#####{$v['name']}#####{$v['src']}#####{$v['via_name']}#####{$v['via_url']}\n";
            }
            //分页
            $file_page = $tag_index_dir.'/'.$i.'.txt';
            file_put_contents($file_page, rtrim($str));
        }
    }
}



//----------------------------------------------------------//


function get_tags()
{
    $tmp = file_get_contents('http://favefavefave.com/tags');

    preg_match("|<ul class=\"tags\">(.*?)</ul>|is", $tmp, $ul);

    $tag_pattern = '|<li>\s*<a href="(.*?)"><span>(.*?)</span></a>\s*</li>|is';

    preg_match_all($tag_pattern, $ul[1], $row);

    $tags = array();
    foreach($row[1] as $key => $val) {
        $tags[$key]['name'] = $row[2][$key];
        $tags[$key]['url'] = $row[1][$key];
    }

    file_put_contents('tags_list.txt', serialize($tags));
}

function get_max_pages($url)
{
    global $http;
    //$tmp = file_get_contents($url);
    $http->postPage($url);
    $tmp = $http->getContent();

    if(!$tmp)
        return false;
    $pattern1 = "|<a href=\"$url/page/(\d+)\" title=\"Last\">...</a>|i";
    $pattern2 = "|<div class=\"pages\">.*?<a href=\"$url/page/(\d)\">\d</a>\s*?</div>|is";
    $pattern3 = "|<div class=\"pages\"></div>|is";

    $row = array();
    $count = '0';
    if(preg_match($pattern1, $tmp, $row))
        $count = $row[1];
    else if(preg_match($pattern2, $tmp, $row))
        $count = $row[1];
    else if(preg_match($pattern3, $tmp))
        $count = 1;

    return $count;
}

function get_small_pic($url)
{
    global $http;
    //$tmp = file_get_contents($url);
    $http->postPage($url);
    $tmp = $http->getContent();

    if(!$tmp)
        return false;
    $pattern = '|<div class="favimg">\s*<a href="(.*?)" class="afavimg" title="(.*?)" >.*?</a>|is';
    preg_match_all($pattern, $tmp, $row);

    $pic = array();
    foreach($row[1] as $key => $val)
    {
        $pic[$key]['url'] = $row[1][$key];
        $pic[$key]['title'] = $row[2][$key];
    }
    return $pic;
}

function get_big_pic($url)
{
    global $http;
    //$tmp = file_get_contents($url);
    $http->postPage($url);
    $tmp = $http->getContent();
    if(!$tmp)
        return false;
    //$pattern = '|<div class="forme" id="view">.*?<p style="text-align:center;"><img src="(.*?)" alt=".*?" /></p>.*?</div>|is';
    $pattern = '|<div class="forme" id="view">.*?<p style="text-align:center;"><img src="(.*?)" alt=".*?" /></p>.*?via:\s*?<a href="(.*?)">(.*?)</a>.*?</div>|is';
    preg_match($pattern, $tmp, $row);
    array_shift($row);
    return $row;
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
