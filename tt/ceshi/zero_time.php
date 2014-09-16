<?php
$time = getdate(time());
$zero_timestamp = mktime(0, 0, 30, $time['mon'], $time['mday'], $time['year']);
echo $zero_timestamp;