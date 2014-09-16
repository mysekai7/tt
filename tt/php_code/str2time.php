<?php

$time = 'Mon, 9 Nov 2009';

//将字符串转化为时间
//$yesterday = date('Ymd',strtotime('last day')); //显示格式20091208


$yesterday = date('Ymd',strtotime($time));

echo $yesterday;