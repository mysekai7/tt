<?php
/*
上面是结合smarty生成静态页增加代码：
$fp = fopen('index.html','w');
fputs($fp,$smarty->fetch('index.tpl'));
fclose($fp);

fetch是取smarty页面内容的方法


如果不用smarty可以这样
ob_start();

--页面内容代码--

$html = ob_get_contents();
$fp = fopen('index.html','w');
fputs($fp,$html);
fclose($fp);
ob_end_clean();

使用html页最最高效的当该例生成index.html后访问http://coderhome.net/book/php/code /guestbook/时即访问http://coderhome.net/book/php/code/guestbook/index.html而不是http://coderhome.net/book/php/code/guestbook/index.php(当然这必须apache设置正确) 所以提高访问速度
这是个简单的例子，所以并不代表最正确优化的方案

作了个简单测试：
访问index.html apache每秒响应290次
访问index.php apache每秒响应19次
可以发现静态页是动态页的15倍
*/
session_start();
require('common.php');
require('lib/BluePage/BluePage.class.php');// 包含分页类

$count = $query->result($query->query("SELECT count(*) as count FROM gb_content"),'count');//总行数
$lines = 10; // 每页行数

$pBP  = new BluePage($count, $lines) ;// 实例化分页类
$aPDatas = $pBP->get();//分页返回结果
$limit = $aPDatas['offset'] ;//起始行

$result = $query->query('SELECT * FROM gb_content order by id desc limit ' .$limit. ','.$lines);//查询数据
$gblist = array();
while ($row = $query->fetch_array($result)) {// 取一条数据
	$row['username'] = htmlentities($row['username'],ENT_COMPAT,'utf-8');
	$row['content'] = preg_replace('/(http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"\s])*)/i','<a target="_blank" href="$1">$1</a>',htmlentities($row['content'],ENT_COMPAT,'utf-8'));
	$gblist[] = $row;
}
$query->free_result($result);
$query->close();
$strHtml =  $pBP->getFull( $aPDatas);//html分页条
$smarty->assign('pagePanel',$strHtml);
$smarty->assign('gblist',$gblist);//给模板赋值
$fp = fopen('index.html','w');
fputs($fp,$smarty->fetch('index.tpl'));
fclose($fp);
$smarty->display('index.tpl');//显示模板内容
?>