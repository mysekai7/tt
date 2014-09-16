<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
	$path=$_GET["path"];
	if(empty($path))
		$path="D:/www";
	$dir=opendir($path);
	chdir($path);
	echo "当前路径为：$path<br />";
	while($d=readdir($dir)){
		$p=$path."/".$d;
		if(is_dir($p) && $d!="."){
			if($d==".."){
				chdir("..");
				$tmp=getcwd();
				echo "<a href='?path={$tmp}'>返回上层目录</a>";
			}else{
				echo "<a href='?path={$p}'>{$d}</a>";
			}
		}else if(is_file($p)){
			echo $d;
		}
		echo "<br />";
	}


	closedir($dir);
?>
</body>
</html>