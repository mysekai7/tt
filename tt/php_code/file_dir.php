<?php

//目录操作

$base_dir = "D:/www/";
echo $base_dir."<hr/>";

echo '磁盘空间剩余: ' . convert_size(disk_free_space( $base_dir )) . '<br />';
echo '目录路径: ' . dirname($base_dir) . '<br />';
echo '文件名名称: ' . basename($base_dir) . '<br />';
echo '<hr />';


$fso = @opendir($base_dir); //打开目录 失败返回false

if($fso )
{
    while($flist = readdir($fso))
    {
        echo $flist.' - '.filetype($flist).'<br />';
    }

    closedir($fso);
}

//------------------------------
$file = 'dirlist.php';
if (is_readable($file) == false) {
         die('文件不存在或者无法读取');
} else {
         echo '存在';
}


//--------------------------------
//创建文件夹
if(isset($_REQUEST['dir']))
{
    $new_dir = $base_dir . trim($_REQUEST['dir']);
    if(!file_exists( $new_dir ))
    {
        mkdir($new_dir, 0777);  //rmdir() 删除目录
        echo '创建文件夹<b>' . $_REQUEST['dir'] .'成功!';
    }
    else
    {
        echo '文件夹<b>' . $_REQUEST['dir'] .'已存在!';
    }

}



//------------------------------
//计算磁盘大小
function convert_size($num)
{
    if ($num >= 1073741824) $num = round($num / 1073741824 * 100) / 100 .' GB';
    else if ($num >= 1048576) $num = round($num / 1048576 * 100) / 100 .' MB';
    else if ($num >= 1024) $num = round($num / 1024 * 100) / 100 .' KB';
    else $num .= ' B';
    return $num;
}


?>

