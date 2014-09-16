<?php
session_start();
require('common.php');
// 查询出留言信息
$q = mysql_query('select * from gb_content where id='.intval($_GET['id']));
$rs = mysql_fetch_array($q);
if ($rs['user_id']!=intval($_SESSION['user_id'])) {// 判断user_id是否相同
	echo '该信息你不能删除，只能删除自己发布的';
	exit;
}
mysql_query('delete from gb_content where id='.intval($_GET['id']));//删除语句
echo '已删除！<a href="index.php">查看留言</a>';
?>