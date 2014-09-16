<?php

header("Content-type: text/html; charset=utf8");
$tmp = file_get_contents('html.html');

$tmp = preg_replace("|<div id=\"guanggao\">.*?</div>|is", "", $tmp);
$tmp = preg_replace("|<div id=\"abc\"\s.*?>.*?</div>|is", "", $tmp);
$tmp = preg_replace("|<script\s.*?>.*?</script>|is", "", $tmp);

echo $tmp;

preg_match("/<div id=\"c\">\s*(.*?)\s*<\/div>/is", $tmp, $row);

var_dump($row);

