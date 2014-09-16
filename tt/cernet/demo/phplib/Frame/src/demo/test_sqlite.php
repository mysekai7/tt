<?php

//sqlite数据库测试
//by indraw
//2004/11/3

//---------------------------------------------------------
	//包含并初始化
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include_once("DBSQLite.class.php");
	$db = new DBSQLite( "./", "DB" );	//初始化数据库操作类

//---------------------------------------------------------

	//建立一个数据库]
	//$db->query("CREATE TABLE test_table ( ColumnA INTEGER PRIMARY KEY, ColumnB text, test1 varchar(10), test2 int(12))");
	//$db->debug();

	//向数据库中插入数据
	for($i=0;$i<3;++$i)
	{
		//$db->query("INSERT INTO test_table (ColumnB,test1,test2) VALUES ('".md5(microtime())."','168','".time()."')");
		//$db->debug();

	}
	//计算一个表内的行数
	$my_count = $db->get_var("SELECT count(*) FROM test_table");
	$db->debug();

	//查出一个表内所有的数据
	$my_tables = $db->get_results("SELECT * FROM test_table");
	$db->debug();

	//更新一行记录
	$db->query("UPDATE test_table SET test1='解放台湾' WHERE ColumnA ='2'");
	$db->debug();

	//查出一个表内所有的数据
	$my_tables = $db->get_results("SELECT * FROM test_table");
	$db->debug();

	//显示字段信息
	$my_col = $db->get_col_info("name");
	$db->vardump($my_col);

	//删除所有记录
	//$db->query("DELETE FROM test_table");
	//$db->debug();


//---------------------------------------------------------
?>