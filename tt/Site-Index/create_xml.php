<?php

$fp = fopen('./sitemap_url.txt', 'r');

if(!$fp)
{
    die('error! open sitemap_url.txt');
}
$body = '';
while (!feof($fp))
{
    $url = fgets($fp);
    $url = trim($url);
    if(empty($url))
        continue;
    $body .= "<url>\n";
    $body .= "\t<loc>http://www.tootoo.com{$url}</loc>\n";
    $body .= "\t<changefreq>daily</changefreq>\n";
    $body .= "\t<priority>0.9</priority>\n";
    $body .= "</url>\n";
}
fclose($fp);


//xml头部
$xml_top = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
EOT;
$xml_top .= "\n";

//xml底部
$xml_btm = '</urlset>';

$sitemap_xml = $xml_top.$body.$xml_btm;

//生成静态文件
file_put_contents('sitemap-logs.xml', $sitemap_xml);
chmod('sitemap-logs.xml', 0777);

//echo $sitemap_xml;

system("scp ./sitemap-logs.xml    liuyan@172.18.0.39:/home/www/nine/ebs/");
system("scp ./sitemap-logs.xml    liuyan@172.18.0.36:/home/www/nine/ebs/");
system("scp ./sitemap-logs.xml    liuyan@172.18.0.37:/home/www/nine/ebs/");