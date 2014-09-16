<?php

/*
mysql是与PHP配置最常用的开源数据库，PHP可以使用的数据库有非常多

所有函数http://www.coderhome.net/m/php/ref.mysql.html
*/


   /// 连接数据库服务器
    $link = mysql_connect("localhost", "mysql_user", "mysql_password") or
        die("Could not connect: " . mysql_error());
    // 选择数据库
    mysql_select_db("mydb");
    // 查询数据
    $result = mysql_query("SELECT id, name FROM mytable");
    // 取数据
    while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
        printf ("ID: %s  Name: %s", $row["id"], $row["name"]);
    }
    // 释放结果集资源
    mysql_free_result($result);
    mysql_close($link);//关闭连接
?>