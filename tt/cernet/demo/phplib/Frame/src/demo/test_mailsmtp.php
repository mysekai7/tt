<?php

//测试是smtp发送邮件
//by indraw
//2004/11/15

error_reporting(E_ERROR | E_WARNING | E_PARSE);

require("MailSmtp.class.php");

//-----------------------------------------------------------------------------
$smtpserver = "smtp.163.com";        //SMTP邮件服务器
$smtpserverport =25;                    //SMTP端口号
$smtpusermail = "indraw@163.com";    //SMTP发送邮件地址
$smtpuser = "indraw";
$smtppass = "iloveyou";                    //SMTP邮件密码
$smtpemailto = "wangyzh@dns.com.cn";    //目标邮件地址
$mailsubject = "Test Subject";            //邮件主题
$mailbody = "<h1>This is a test mail</h1>";//邮件内容
$mailtype = "HTML";                        //邮件发送方式(HTML/TXT)

$smtp = new MailSmtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
$smtp->show_debug = TRUE;                //是否显示发送的调试信息
$smtp->send_mail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
//-------------------------------------------------------------------------------

?>
