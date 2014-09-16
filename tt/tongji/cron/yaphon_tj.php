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

system("scp wwwyaphon@172.18.0.66:/usr/local/apache2/logs/ebs-yaphon-access_logs/access_log.{$yesterday}.gz /home/whz/tongji/access.log.{$yesterday}_66.gz");
//解压
system("gzip -d /home/whz/tongji/access.log.{$yesterday}_66.gz");

$logs = array(
    '/home/whz/tongji/access.log.'.$yesterday.'_66',
);

#####抓取统计初始化#####
$cc = new crawl_count;
$cc->timestamp = $timestamp;
$cc->site = 'yaphon';
$cc->crawler = 'Googlebot';
$cc->search_urls = array('buy-','kb-','kp-','kpr-');
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
system("rm -rf /home/whz/tongji/access.log.{$yesterday}_66");
