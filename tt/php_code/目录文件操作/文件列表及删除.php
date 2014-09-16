<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php 
	$dir=new DirectoryIterator("E:/AppServ");
	
	echo "<table border='1' width='500'>";
	echo "<tr><th>目录/文件名</th><th>大小</th><th>操作</th></tr>";
	while($dir->valid()){
		echo "<tr>";
		$d=$dir->current();
		
		if($dir->isDir()){
			echo "<td><a href=''>{$d}</a></td>";
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			echo "<td>&nbsp;</td>";
		}else{
			echo "<td>".$d."</td>";
			echo "<td>".$dir->getSize()."byte</td>";
			echo "<td><a href=''>删除</a></td>";
		}
		echo "</tr>";
		$dir->next();
	}
	echo "</table>";
?>
</body>
</html>