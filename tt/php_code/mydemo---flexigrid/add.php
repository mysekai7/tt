<?php
	/*
	本程序存在bug， 且有未完善的功能， 仅做网友交流使用。

    制作：CSSRAIN.CN - 一揪就出来。

    http://www.cssrain.cn
	
	*/

	header('Content-Type:text/html;charset=GB2312');
	/*
	echo $qq_group;
	echo $name;
	echo $qq;
	echo $sex;
	echo $tel;
	*/
	$mysql_server_name = "localhost";
	$mysql_username    = "root";
	$mysql_password    = "123456";
	$mysql_database    = "grid";
	
	$sql = "INSERT INTO `cssrain` ( `id` , `qq_group` , `name` , `qq` , `sex` , `tel` ) VALUES (NULL , '$qq_group' , '$name' , '$qq' , '$sex' , '$tel');";
	$conn=mysql_connect( $mysql_server_name, $mysql_username, $mysql_password);	
	mysql_select_db($mysql_database,$conn);
	mysql_query("SET NAMES 'UTF8'");
	$result = mysql_query($sql);
	mysql_close($conn);
	echo ('<img src="ok.png" /><br><a href="index.html" class="return">点击返回</a>');

?>
