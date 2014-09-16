<?php
$number = 1234.56;

// english notation (default)
$english_format_number = number_format($number,3);
// 1,235

echo $english_format_number;    //1,234.560

