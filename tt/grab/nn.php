<?php
$s = '72863d912d2b801528345e745bdc96b0-1267351612.78e75d66adc34408995e60fbb5d0f9ca';

$dd = strripos($s, '.');
var_dump($dd);

$ss = substr($s, 0, $dd);
echo $ss.'.jpg';