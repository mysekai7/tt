<?php
/**
 *  统计抓取
 */
require_once('/home/www/nine/liuyan/tongji/global.php');
require_once('crawl_count.php');
set_time_limit(0);

//---------------------------------------------------------
//从45拷贝日志
$timestamp = strtotime('last day');
$yesterday = date('Ymd', $timestamp);//$timestamp = strtotime('20100720');

//copy
system("scp liuyan@172.18.0.45:/usr/local/apache/logs/chinatopsupplier_com-access_{$yesterday}.log /home/whz/tongji/chinatopsupplier_com-access_{$yesterday}.log");

$logs = array(
    '/home/whz/tongji/chinatopsupplier_com-access_'.$yesterday.'.log',
);

#####抓取统计初始化#####
$cc = new crawl_count;
$cc->timestamp = $timestamp;
$cc->site = 'chinatopsupplier';
$cc->crawler = 'Googlebot';
$cc->search_urls = array('buy-','manufacturer-','ps-','d-p','d-c');
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
system("rm -rf /home/whz/tongji/chinatopsupplier_com-access_{$yesterday}.log");
