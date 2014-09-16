<?php
/*******************************
* 作者：李英江
*　日期：2006-12-7
*******************************/
/*
要注意的内容：
　　1.　邮件的字符集设置， $mail->CharSet = "GB2312"; // 这里指定字符集！在这里我只指定为GB2312因为这样Outlook能正常显示邮件主题，我尝试过设为utf-8，但在Outlook下显示乱码。
　　2.　如果是发送html格式的邮件，那么记得也指定为<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
　　3.　如果你想用它来群发邮件的话，记得修改包含文件函数，如：
　　require("phpmailer/class.phpmailer.php");
　　改为
　　require_once("phpmailer/class.phpmailer.php");
*/


require("phpmailer/class.phpmailer.php");
function smtp_mail ( $sendto_email, $subject, $body, $extra_hdrs, $user_name) {
$mail = new PHPMailer();
$mail->IsSMTP(); // send via SMTP
$mail->Host = "200.162.244.66"; // SMTP servers
$mail->SMTPAuth = true; // turn on SMTP authentication
$mail->Username = "yourmail"; // SMTP username 注意：普通邮件认证不需要加 @域名
$mail->Password = "mailPassword"; // SMTP password
$mail->From = "yourmail@cgsir.com"; // 发件人邮箱
$mail->FromName = "cgsir.com管理员"; // 发件人
$mail->CharSet = "GB2312"; // 这里指定字符集！
$mail->Encoding = "base64";
$mail->AddAddress($sendto_email,"username"); // 收件人邮箱和姓名
$mail->AddReplyTo("yourmail@cgsir.com","cgsir.com");
//$mail->WordWrap = 50; // set word wrap
//$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");
$mail->IsHTML(true); // send as HTML
// 邮件主题
$mail->Subject = $subject;
// 邮件内容
$mail->Body = '
<html><head>
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=GB2312"></head>
<body>
欢迎来到<a href="http://www.cgsir.com">http://www.cgsir.com</a> <br /><br />
感谢您注册为本站会员！<br /><br />
</body>
</html>
';
$mail->AltBody ="text/html";
if(!$mail->Send())
{
echo "邮件发送有误 <p>";
echo "邮件错误信息: " . $mail->ErrorInfo;
exit;
}
else {
echo "$user_name 邮件发送成功!<br />";
}
}
// 参数说明(发送到, 邮件主题, 邮件内容, 附加信息, 用户名)
smtp_mail('yourmail@cgsir.com', '欢迎来到cgsir.com！', 'NULL', 'cgsir.com', 'username');
?>