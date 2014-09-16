<?php
/*
    按条件查询Googlebot 访问次数
    参数1: 要查询的页面
    /usr/local/php5/bin/php tongji3.php buy-

    #统计前最好把统计文件做下切割
    split -l 1000 log39 ./files/log39_

    #scp目录
    scp liuyan@172.18.0.39:/usr/local/apache2/logs/ebs-access_logs/access_log.20100209.gz

    gzip -d name.gz
*/

/*
//读文本
$handle = fopen ($file, "r");
$content = "";
while (!feof($handle)) {
  $content .= fread($handle, 1024);
}
fclose($handle);

$logs = array();
$logs =explode("\n", $content);
*/

$param = $_SERVER["argv"][1];

$path = './files/'; //切割目录

$files = array();
if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            //echo "$file<br />";
            //$k = (int)$file;
            $files[] = $file;
            //ksort($files);
        }
    }
    closedir($handle);
}
//print_r($files);

if(is_array($files) && count($files)>0)
{
    $i=0;
    foreach($files as $file)
    {
        $logs = array();
        $file = $path.$file;
        $logs = file($file);
        if(is_array($logs) && count($logs) > 0)
        {
            foreach($logs as $log)
            {
                if(stripos($log, $param) && stripos($log, 'Googlebot'))
                    $i++;
            }
        }
    }
    flush();
    echo $i."\n";
}
