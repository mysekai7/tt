<?php

/*
数组排序，常用的有这几个函数
sort 对数组排序
asort 对数组进行排序并保持索引关系
ksort 对数组按照键名排序
shuffle 将数组打乱
等

*/


$fruits = array("lemon", "orange", "banana", "apple");
sort($fruits);
foreach ($fruits as $key => $val) {
    echo "fruits[".$key."] = " . $val . "<br>";
}

echo '<br>';

$fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");
asort($fruits);
foreach ($fruits as $key => $val) {
    echo "$key = $val<br>";
}

echo '<br>';

$fruits = array("d"=>"lemon", "a"=>"orange", "b"=>"banana", "c"=>"apple");
ksort($fruits);
foreach ($fruits as $key => $val) {
    echo "$key = $val<br>";
}
?>