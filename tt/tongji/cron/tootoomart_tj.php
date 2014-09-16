<?php
/**
 *  统计抓取
 */


require_once('/home/www/nine/liuyan/tongji/global.php');
require_once('crawl_count.php');
set_time_limit(0);

//---------------------------------------------------------
//从36,37,39拷贝日志
$timestamp = strtotime('last day');
//$timestamp = strtotime('20100720');
$yesterday = date('Ymd', $timestamp);

//172.18.0.53 ：/usr/local/apache2/logs/access.log.20100721.gz
//172.18.0.113 ：/usr/local/apache/logs/access.log.20100721.gz
system("scp ninettm@172.18.0.53:/usr/local/apache2/logs/access.log.{$yesterday}.gz /home/whz/tongji/access.log.{$yesterday}_53.gz");
system("scp ninettm@172.18.0.113:/usr/local/apache2/logs/access.log.{$yesterday}.gz /home/whz/tongji/access.log.{$yesterday}_113.gz");
//解压
system("gzip -d /home/whz/tongji/access.log.{$yesterday}_53.gz");
system("gzip -d /home/whz/tongji/access.log.{$yesterday}_113.gz");

$logs = array(
    '/home/whz/tongji/access.log.'.$yesterday.'_53',
    '/home/whz/tongji/access.log.'.$yesterday.'_113',
);

#####抓取统计初始化#####
$cc = new crawl_count;
$cc->timestamp = $timestamp;
$cc->site = 'tootoomart';
$cc->crawler = 'Googlebot';
$cc->search_urls = array('buy-','product-','orderlist','wholesale-');
//$cc->find($log);
//$cc->do_result();
//$cc->output();
//$cc->save();

//start
foreach($logs as $file_log)
{
    if(!file_exists($file_log))
        continue;

    $fp = fopen($file_log, 'r');
    if(!$fp)
        continue;

    while(!feof($fp))
    {
        $log = fgets($fp);//获得一行日志
        $cc->find($log);
    }

    echo $file_log." end\n";
}

//处理抓取结果输出
$cc->do_result();
//$cc->output();
$cc->save();




//清理日志
system("rm -rf /home/whz/tongji/access.log.{$yesterday}_53");
system("rm -rf /home/whz/tongji/access.log.{$yesterday}_113");
