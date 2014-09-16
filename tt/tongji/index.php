<?php

    require_once('global.php');

    //init
    $site = isset($_GET['site']) && array_key_exists($_GET['site'], $C->RECORD_URLS) ? $_GET['site'] : 'tootoo';
    $engine = isset($_GET['engine']) ? $_GET['engine'] : 'google';
    $tj_starttime = strtotime($C->TJ_STARTTIME[$site]);//统计开始时间
    //$date = isset($_GET['date']) && !empty($_GET['date']) && strtotime($_GET['date']) < time() && strtotime($_GET['date']) >= $tj_starttime ? $_GET['date'] : date('Y-n-d', strtotime('last day'));
    $date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date('Y-n-d', strtotime('last day'));
    $prevdate = date('Y-n-d', (strtotime($date)-(7*24*3600)) );

    //日历
    $calender = new calender;
    $calender->set_url($site,$engine);
    $calender_str = $calender->build($date);

	//抓取当月平均值(2010.9.6新加的)
	$file_crawl_mon = './data/crawl/'.$engine.'/'.$site.'/'.md5('all').'/'.date('Ym', strtotime($date)).'.txt';
	//echo $file_crawl_mon;
	$crawl_average = file_exists($file_crawl_mon) ? file($file_crawl_mon) : 0;
	if($crawl_average)
	{
		$total = 0;
		foreach($crawl_average as $v)
		{
			$tmp = explode('#####', $v);
			$arr = unserialize( $tmp[1] );
			$total += $arr['count'];
		}
		$crawl_average_value = number_format($total / count($crawl_average));
		//echo $crawl_average_value;
	}



    //收录统计
    $r = new record;
    $curr_record = $r->get_records_by_day($date, $engine);
    $last_record = $r->get_records_by_day($prevdate, $engine);
    //收录概要
    $record_summary = array();
    if(isset($curr_record['data']) && count($curr_record['data'])>0)
    {
        $curr_rc = current($curr_record['data']);
        if($last_record['data'])
            $prev_rc = current($last_record['data']);
        $record_summary = summary_diff($curr_rc, $prev_rc);
    }



    //抓取统计
    $crawl = new crawl;
    $curr_crawl = $crawl->get_crawls_by_date($date, $engine);
    $last_crawl = $crawl->get_crawls_by_date($prevdate, $engine);
    //抓取概要
    $crawl_summary = summary_diff($curr_crawl['all']['count'], $last_crawl['all']['count']);



    //点击率统计
    $hit = new hit;
    $curr_hits = $hit->get_hits_by_day($date);
    $prev_hits = $hit->get_hits_by_day($prevdate);
    //var_dump($prev_hits);




    $tpl->assign('sitename', $site);
    $tpl->assign('engine',$engine);
    $tpl->assign('today', $date);
    $tpl->assign('lastday', $prevdate);
    $tpl->assign('calender_str', $calender_str);
    $tpl->assign('record_summary', $record_summary);
    $tpl->assign('crawl_summary', $crawl_summary);
    $tpl->assign('curr_record', $curr_record);
    $tpl->assign('last_record', $last_record);
    $tpl->assign('curr_crawl', $curr_crawl);
    $tpl->assign('last_crawl', $last_crawl);
    $tpl->assign('curr_hits', $curr_hits);
    $tpl->assign('prev_hits', $prev_hits);
	$tpl->assign('current_average_crawl', $crawl_average_value);

    $tpl->display('record_html.php');