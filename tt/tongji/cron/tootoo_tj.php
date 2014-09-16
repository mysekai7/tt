<?php
/**
 *  统计抓取
 *  统计点击
 */


require_once('/home/www/nine/liuyan/tongji/global.php');
require_once('crawl_count.php');
require_once('hit_count.php');
set_time_limit(0);

//---------------------------------------------------------
//从36,37,39拷贝日志
$timestamp = strtotime('last day');
//$timestamp = strtotime('20100720');
$yesterday = date('Ymd', $timestamp);

system("scp liuyan@172.18.0.36:/usr/local/apache2/logs/ebs-access_logs/access_log.{$yesterday}.gz /home/whz/tongji/access_log.{$yesterday}_36.gz");
system("scp liuyan@172.18.0.37:/usr/local/apache2/logs/ebs-access_logs/access_log.{$yesterday}.gz /home/whz/tongji/access_log.{$yesterday}_37.gz");
system("scp liuyan@172.18.0.39:/usr/local/apache/logs/ebs-access_logs/access_log.{$yesterday}.gz /home/whz/tongji/access_log.{$yesterday}_39.gz");
//解压
system("gzip -d /home/whz/tongji/access_log.{$yesterday}_36.gz");
system("gzip -d /home/whz/tongji/access_log.{$yesterday}_37.gz");
system("gzip -d /home/whz/tongji/access_log.{$yesterday}_39.gz");

$logs = array(
    '/home/whz/tongji/access_log.'.$yesterday.'_36',
    '/home/whz/tongji/access_log.'.$yesterday.'_37',
    '/home/whz/tongji/access_log.'.$yesterday.'_39'
);

#####抓取统计初始化#####
$cc = new crawl_count;
$cc->timestamp = $timestamp;
$cc->site = 'tootoo';
$cc->crawler = 'Googlebot';
$cc->search_urls = array('buy-','d-rp','d-c','d-p','s-','company');
//$cc->find($log);
//$cc->do_result();
//$cc->output();
//$cc->save();

#####点击统计初始化#####
$hc = new hit_count;
$hc->timestamp = $timestamp;
$hc->site = 'tootoo';
$hc->mark = 'tt?id'; //查找标记
$hc->words = array(//搜索关键词列表
    'list' => 'search_list.txt',
    'inquire_list' => 'search_ilist.txt',
    'detail' => 'search_detail.txt',
    'translation' => 'search_tran.txt'
);

$hc->parse_words(); //处理关键词列表
//$hc->find($log);
//$hc->output();
//$hc->save();


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
        $hc->find($log);
    }

    echo $file_log." end\n";
}

//处理抓取结果输出
$cc->do_result();
//$cc->output();
$cc->save();

//输出点击率
//$hc->output();
$hc->save();



//清理日志
system("rm -rf /home/whz/tongji/access_log.{$yesterday}_36");
system("rm -rf /home/whz/tongji/access_log.{$yesterday}_37");
system("rm -rf /home/whz/tongji/access_log.{$yesterday}_39");