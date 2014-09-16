<?php

require_once('crawl.php');

//$str = '203.208.60.184 www.tootoo.com - [19/Jul/2010:10:01:02 +8000] "GET /buy-holz-Q_ISO9000/ HTTP/1.1" 200 3435 "-" "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" "-" "255013"';

$str = '203.208.60.184 www.tootoo.com - [19/Jul/2010:10:01:02 +8000] "GET /s-ps/calcium-chloride--p-1323192.html HTTP/1.1" 200 3435 "-" "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" "-" "255013"';

//preg_match('/"(\d+)"$/', $str, $row);

//$pattern = '|GET\s(/buy-(.*?))\sHTTP/|is';
//preg_match($pattern, $str, $row);
//var_dump($row);


$c = new crawl;
$c->log = $str;
//$c->crawler = 'Googlebot';
//$url = $c->get_url();
$c->get_time();
if($c->is_crawler())
{
    echo 'Googlebot';
}
else
{
    echo 'no';
}

if($c->exists_url('s-'))
{
    //echo $c->url;
    //echo 'yes';
}
else
{
    //echo 'no';
}
