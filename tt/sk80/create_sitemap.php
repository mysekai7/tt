<?php

error_reporting(E_ALL ^ E_NOTICE);

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Last-Modified: '.gmdate('D, d M Y H:i:s'). ' GMT');
header('Content-type:text/html; charset=utf-8');
ini_set('magic_quotes_runtime', 0);
set_time_limit(0);

//数据库设置
$conn = mysql_connect('localhost', 'mysekai7', 'JVWSKEZXTPVP');
mysql_select_db('mysekai7_mydb', $conn);
mysql_query('SET NAMES utf8', $conn);

$qurey = "SELECT id FROM sk_content";
$res = mysql_query($qurey, $conn);
//$s = mysql_error($conn);
//var_dump($s);

$data = array();
if($res != FALSE)
{
    while($obj = mysql_fetch_object($res))
    {
        $data[] = $obj;
    }
    mysql_free_result($res);
}

//var_dump($data);

$date = date(DATE_ATOM, time());// 输出类似：2000-07-01T00:00:00+00:00
if(count($data)>0)
{
    $body = '';
    foreach($data as $val)
    {
        $body .= "<url>\n";
        $body .= "\t<loc>http://blog.sk80.com/post/{$val->id}.html</loc>\n";
        $body .= "\t<lastmod>{$date}</lastmod>\n";
        $body .= "</url>\n";
    }





}

//xml头部
$xml_top = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
EOT;
$xml_top .= "\n";

//xml底部
$xml_btm = '</urlset>';

$sitemap_xml = $xml_top.$body.$xml_btm;

file_put_contents('sitemap.xml', $sitemap_xml);
chmod('sitemap.xml', 0777);

echo $sitemap_xml;