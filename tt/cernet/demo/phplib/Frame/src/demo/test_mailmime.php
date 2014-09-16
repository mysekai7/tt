<?
//测试mail发送邮件
//by indraw
//2004/11/15

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$from = "admin@dns.com.cn";
$to = "wangyzh@dns.com.cn";
$attachment = "aaa.rar";
$image = "bbb.jpg";

//-----------------------------------------------------------------------------
/*
* 纯文本
*/
if ($_GET[mimemail] == 1){

	$subject = " MIME 邮件: 纯文本";
	$text = "这是一个 MIME 邮件:\n\n- 纯文本";

	include ('MailMime.class.php');
	$mimemail = new MailMime();

	$mimemail->new_mail($from, $to, $subject, $text);
	//$mimemail->set_cc("fengwei@dns.com.cn", "小冯");

	if ($mimemail->send())
   	echo "MIME邮件已经被成功发送\n\n";
	else
   	echo "发生一个错误，邮件没有被发送。\n\n";
	echo "<br><br><a href='{$_SERVER[PHP_SELF]}'>返回</a>";
}

/**
 * 纯文本
 * html
 */
elseif ($_GET[mimemail] == 2){

	$subject = "MIME 邮件: 纯文本 + HTML";
	$text = "这是一个 MIME 邮件:\n\n- 纯文本\n- HTML";
	$html = "<HTML><HEAD></HEAD><BODY>这里是一个 <b>MIME</b> 邮件:<BR><BR>- 纯文本</BR>- HTML</BODY></HTML>";

	include ('MailMime.class.php');
	$mimemail = new MailMime();

	$mimemail->new_mail($from, $to, $subject, $text, $html);

	if ($mimemail->send())
   	echo "MIME邮件已经被成功发送\n\n";
	else
   	echo "发生一个错误，邮件没有被发送。\n\n";
	echo "<br><br><a href='{$_SERVER[PHP_SELF]}'>返回</a>";
}

/**
 * 纯文本
 * 附件
 */
elseif ($_GET[mimemail] == 3){

	$subject = "MIME Mail 测试: 纯文本 + 附件";
	$text = "这是一个 MIME 邮件:\n\n- 纯文本\n- 附件";

	include ('MailMime.class.php');
	$mimemail = new MailMime();

	$mimemail->new_mail($from, $to, $subject, $text);
	$mimemail->add_attachment($attachment, "file.tar.gz");

	if ($mimemail->send())
   	echo "MIME邮件已经被成功发送\n\n";
	else
   	echo "发生一个错误，邮件没有被发送。\n\n";
	echo "<br><br><a href='{$_SERVER[PHP_SELF]}'>返回</a>";
}


/**
 * 纯文本
 * HTML
 * 附件
 */
elseif ($_GET[mimemail] == 4){

	$subject = "MIME Mail 测试: 纯文本 + HTML + 附件";
	$text = "这是一个 MIME 邮件:\n\n- 纯文本\n- HTML\n- 附件";
	$html = "<HTML><HEAD></HEAD><BODY>这是一个<b>MIME</b>邮件:<BR><BR>- 纯文本</BR>- HTML</BR>- 附件</BODY></HTML>";

	include ('MailMime.class.php');
	$mimemail = new MailMime();

	$mimemail->new_mail($from, $to, $subject, $text, $html);
	$mimemail->add_attachment($attachment, "file.tar.gz");
	
		if ($mimemail->send())
		echo "MIME邮件已经被成功发送\n\n";
		else
		echo "发生一个错误，邮件没有被发送。\n\n";
		echo "<br><br><a href='{$_SERVER[PHP_SELF]}'>返回</a>";

}


/**
 * 纯文本
 * HTML
 * 嵌入式图片
 */

elseif ($_GET[mimemail] == 5){

	$subject = "MIME Mail 测试: 纯文本 + HTML + 嵌入式图片";
	$text = "这是一个 MIME 邮件:\n\n- 纯文本\n- HTML\n- 嵌入式图片";
	$html = "<HTML><HEAD></HEAD><BODY>这是一个<b>MIME</b>邮件:<BR><BR>- 纯文本</BR>- HTML</BR>- 嵌入式图片<br><br><img src='image.jpg' border='0'></BODY></HTML>";

	include ('MailMime.class.php');
	$mimemail = new MailMime();

	$mimemail->new_mail($from, $to, $subject, $text, $html);
	$mimemail->add_attachment($image, "image.jpg");

	if ($mimemail->send())
   	echo "MIME邮件已经被成功发送\n\n";
	else
   	echo "发生一个错误，邮件没有被发送。\n\n";
	echo "<br><br><a href='{$_SERVER[PHP_SELF]}'>返回</a>";
}


/**
 * 纯文本
 * HTML
 * 嵌入式图片
 * 附件
 */
elseif ($_GET[mimemail] == 6){

	$subject = "MIME Mail 测试: 纯文本 + HTML + 嵌入式图片 + 附件";
	$text = "这是一个 MIME 邮件:\n\n- 纯文本\n- HTML\n- 嵌入式图片\n- 附件";
	$html = "<HTML><HEAD></HEAD><BODY>这是一个<b>MIME</b>邮件:<BR><BR>- 纯文本</BR>- HTML</BR>- 嵌入式图片</BR>- 附件<br><br><img src='image.gif' border='0'></BODY></HTML>";

	include ('MailMime.class.php');
	$mimemail = new MailMime();

	$mimemail->new_mail($from, $to, $subject, $text, $html);
	$mimemail->add_attachment($image, "image.gif");
	$mimemail->add_attachment($attachment, "file.tar.gz");

	if ($mimemail->send())
   	echo "MIME邮件已经被成功发送\n\n";
	else
   	echo "发生一个错误，邮件没有被发送。\n\n";
	echo "<br><br><a href='{$_SERVER[PHP_SELF]}'>返回</a>";
}


/**
 * 菜单
 */
else {
	echo "
	<HTML><HEAD>
	<title>MIME Mail 测试</title>
	</HEAD><BODY>
	<h1>MIME Mail 测试</h1>
	<h3><a href='{$_SERVER[PHP_SELF]}?mimemail=1'>纯文本</a></h3>
	<h3><a href='{$_SERVER[PHP_SELF]}?mimemail=2'>纯文本 + HTML</a></h3>
	<h3><a href='{$_SERVER[PHP_SELF]}?mimemail=3'>纯文本 + 附件</a></h3>
	<h3><a href='{$_SERVER[PHP_SELF]}?mimemail=4'>纯文本 + HTML + 附件</a></h3>
	<h3><a href='{$_SERVER[PHP_SELF]}?mimemail=5'>纯文本 + HTML + 嵌入式图片</a></h3>
	<h3><a href='{$_SERVER[PHP_SELF]}?mimemail=6'>纯文本 + HTML + 嵌入式图片 + 附件</a></h3>
	</BODY></HTML>
	";
}

?>
