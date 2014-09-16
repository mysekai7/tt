<?php
include_once('MailSmtp.class.php');

$body = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
<head>
    <meta http-equiv="x-ua-compatible" content="ie=7" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="generator" content="Editplus4PHP" />
    <meta name="keywords" content="Editplus4PHP" />
    <meta name="description" content="Editplus4PHP" />
    <meta name="author" content="Leo" />
    <title>Example | xHTML1.0</title>
</head>
<body>
    <p>你好 wangchao</p>
    <p><b>认证地址:</b><a href="http://sadjs.com">http://sadjs.com</a></p>
</body>
</html>
EOT;

//邮件发送
$subject = "测试邮件";
$smtp = new MailSmtp('smtp.163.com',25,true,'3345191837','10562782800');
$smtp->send_mail('mysekai7@gmail.com', '3345191837@163.com', $subject, $body, "HTML");
?>