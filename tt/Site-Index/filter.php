#!/usr/local/php/bin/php
<?php

$datalist = file('./alldata.txt');

foreach($datalist as $k => $v)
{
	if(!$v)
		continue;
	$line = explode("\t", $v);
	$file = $line[0];	
	system("./filter.pl $file");
}


