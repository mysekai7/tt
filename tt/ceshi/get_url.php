<?php
$log = '67.195.114.62 www.tootoo.com - [11/Aug/2010:00:01:24 +0800] "GET /buy-electronics_appliance/ HTTP/1.0" 200 13654 "-" "Mozilla/5.0 (compatible; Yahoo! Slurp/3.0; http://help.yahoo.com/help/us/ysearch/slurp)" "-" "527707"'."\n";

//$pattern = '|GET\s(.*?)\sHTTP/|is';

//preg_match($pattern, $log, $row);

//preg_match('|GET\s/buy-(.*?)/\sHTTP/|is', $log, $row);
$str = '/buy-cutting_unit-Greece/scr_product,country_300/';

//preg_match('|^/buy-(.*?)$|is', $str, $row);

//var_dump($row);

//$newstring = 'abcdef2abcdef';
//$pos = strpos($newstring, 'a', 1); // $pos = 7, not 0
//$s = substr($newstring, 0, $pos);

// $s;



$s = 'co.,ltd 1.5 inch';

echo preg_replace("/(co)(\W+)(ltd)/i", "$1 $3", $s);


