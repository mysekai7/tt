<?php
//测试csv文件操作类
//by indraw
//2004/11/4

//-----------------------------------------------------------------------------
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require('FileDir.class.php') ;

$test_dir = "../metoo";
$rename_dir = "../haha";
$newDir = new FileDir();

echo "<A HREF=\"test_FileDir.php?action=creat\">建立新目录</A>&nbsp;&nbsp;<A HREF=\"test_FileDir.php?action=setdir\">定位到新目录</A>&nbsp;&nbsp;<A HREF=\"test_FileDir.php?action=rename\">文件夹改名</A>&nbsp;&nbsp;<A HREF=\"test_FileDir.php?action=deletedir\">文件夹删除</A>&nbsp;&nbsp;<A HREF=\"test_FileDir.php?action=emptydir\">文件夹清空</A>&nbsp;&nbsp;<A HREF=\"test_FileDir.php?action=get_dir_info\">文件夹信息</A>&nbsp;&nbsp;<A HREF=\"test_FileDir.php?action=copy_dir\">copy文件夹</A>&nbsp;&nbsp;<A HREF=\"test_FileDir.php?action=three_dir\">三级转换</A>&nbsp;&nbsp;<A HREF=\"test_FileDir.php?action=dir_size\">文件夹大小</A><hr>";

//-----------------------------------------------------------------------------

if($action == "creat"){
	echo "生成目录：$test_dir<br>";
	$newDir->create_dir($test_dir);
}
elseif($action == "setdir"){
	echo "改变目录到：$test_dir<br>";
	$newDir->set_current_dir($test_dir);
}
elseif($action == "rename"){
	echo "文件夹“ $test_dir ” 重命名为：$rename_dir<br>";
	$newDir->set_current_dir($test_dir);
	$newDir->rename_dir($rename_dir);
}

elseif($action == "deletedir"){
	echo "文件夹“ $rename_dir ”删除操作<br>";
	$newDir->set_current_dir($rename_dir);
	$newDir->delete_dir($rename_dir);
}

elseif($action == "emptydir"){
	echo "文件夹“ $rename_dir ”删除操作<br>";
	$newDir->set_current_dir($rename_dir);
	$newDir->empty_dir($rename_dir);
}

elseif($action == "get_dir_info"){
	echo "文件夹“ $rename_dir ”获取所有信息操作<br>";
	$newDir->set_current_dir($rename_dir);
	$newDir->get_dir_info();
	echo "<pre>";
	var_dump($newDir->current_dirs);
	var_dump($newDir->current_files);
	echo "</pre>";
}

elseif($action == "copy_dir"){
	echo "文件夹“ $rename_dir ”copy操作<br>";
	$newDir->set_current_dir($rename_dir);
	$newDir->copy_dir($rename_dir,"../newdir","N");
}
elseif($action == "three_dir"){
	echo "文件夹“ $rename_dir ”三级分类操作<br>";
	$newDir->set_current_dir($rename_dir);
	$getDirNum = $newDir->three_dir("Y");
	echo "成功转化：".$getDirNum;
}

elseif($action == "dir_size"){
	echo "文件夹“ $rename_dir ”获取大小<br>";
	$newDir->set_current_dir($rename_dir);
	$getDirNum = $newDir->get_dir_size($rename_dir);
	echo "文件大小：".$newDir->get_file_size($newDir->current_size);
}


//-----------------------------------------------------------------------------
?>