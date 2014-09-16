<?php

/*
这里先介绍两个最简单的函数，就可以使用PHP做最基本的操作啦

file_get_contents 读文件内容
file_put_contents 写文件内容，php5以上支持

*/



echo file_get_contents('inc/info.txt');// 读出inc目录info,txt文件的内容

// 如果服务器配置允许可以使用下面语句直接抓取网址内容

$html = file_get_contents('http://www.baidu.com');

// file_get_contents与下面几个语句组成的效果相同
$filename = "inc/info.txt";
$handle = fopen($filename, "r");// 打开文件
$contents = fread($handle, filesize ($filename));// 读出内容
fclose($handle);// 关闭文件


//file_put_contents如果PHP5以下我们可以这样自己定义
define('FILE_APPEND', 1);
function file_put_contents($n, $d) {

   $f = @fopen($n, 'w');
   if ($f === false) {
       return 0;
   } else {
       if (is_array($d)) $d = implode($d);
       $bytes_written = fwrite($f, $d);
       fclose($f);
       return $bytes_written;
   }
}

file_put_contents('inc/info.txt','abc');//这样可以把abc写入inc/info.txt文件中

?>