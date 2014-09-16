<?php
$log = '203.208.60.169 - - [24/Sep/2010:00:00:36 +0800] "GET /d-p10390079-New_Fashion_Clock/ HTTP/1.1" 200 6112 "-" "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" 318 6495 270022';

preg_match('|GET\s(.*?)\sHTTP.*?(\d+)$|is', trim($log), $row);

var_dump($row);