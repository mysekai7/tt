<?php
require_once('global.php');

$engine = isset($_GET['engine']) && !empty($_GET['engine']) ? $_GET['engine'] : '';
$site = isset($_GET['sitename']) && !empty($_GET['sitename']) ? $_GET['sitename'] : '';
$url = isset($_GET['url']) && !empty($_GET['url']) ? $_GET['url'] : '';
$date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : '';

if(!$engine || !$site || !$url || !$date)
{
    echo 'Error!';
    exit;
}

$timestamp = strtotime($date);

$r = new record;
//当前月数据
$curr_tmp = $r->get_record_by_url($url, $date);

//上月数据
$days = date('t', $timestamp);
$prev_tmp = $r->get_record_by_url( $url, date('Y-n-d', ($timestamp - $days*24*3600)) );

//月份参数
$filters = array('yr'=>date('Y', $timestamp), 'mo'=>date('n',$timestamp));


$curr_data = $prev_data = array();
if($curr_tmp)
{
    foreach($curr_tmp as $key => $val)
    {
        $curr_data['dy'][$key] = $val;
    }
}

if($prev_tmp)
{
    foreach($prev_tmp as $key => $val)
    {
        $prev_data['dy'][$key] = $val;
    }
}

chart_days($filters, $curr_data, $prev_data);