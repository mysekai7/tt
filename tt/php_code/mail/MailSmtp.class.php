<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:MailSmtp.class.php
- 原作者:(网上收集)
- 整理者:indraw
- 编写日期:2004/11/17
- 简要描述:smtp邮件发送函数，调用socket函数。
- 运行环境:无要求
- 修改记录:2004/11/17，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	$smtp = new MailSmtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
	$smtp->send_mail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
*/
/*
	MailSmtp($relay_host = "", $smtp_port = 25,$auth = false,$user,$pass)
	send_mail($to, $from, $subject = "", $body = "", $mailtype, $cc = "", $bcc = "", $additional_headers = "")
	                                                   //执行邮件发送操作
	smtp_send($helo, $from, $to, $header, $body = "")  //
	---------------------
	smtp_sockopen($address)
	smtp_sockopen_relay()
	smtp_sockopen_mx($address)
	smtp_message($header, $body)
	smtp_eom()
	smtp_ok()
	smtp_putcmd($cmd, $arg = "")
	strip_comment($address)
*/


//=============================================================================
class MailSmtp
{
	//公用属性
	var $show_errors = true;    //是否显示错误
	var $show_debug = true;     //是否显示跟踪

	var $host_name;             //本地主机
	var $smtp_port;             //smtp端口
	var $user;                  //用户名
	var $pass;                  //密码
	var $relay_host;            //远程主机

	var $time_out;              //超时设置
	var $auth;                  //是否身份验证



	//私有属性
	var $sock;

	/*
	-----------------------------------------------------------
	函数名称:MailSmtp($relay_host = "", $smtp_port = 25,$auth = false,$user,$pass)
	简要描述:构造函数
	输入:mixed (主机地址，端口，是否身份验证，用户名，密码)
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function MailSmtp($relay_host = "", $smtp_port = 25,$auth = false,$user,$pass)
	{
		$this->smtp_port = $smtp_port;
		$this->relay_host = $relay_host;
		$this->time_out = 30;
		//
		$this->auth = $auth;
		$this->user = $user;
		$this->pass = $pass;
		//
		$this->host_name = "localhost";
		$this->log_file = "";

		$this->sock = FALSE;
	}

	/*
	-----------------------------------------------------------
	函数名称:sendmail()
	简要描述:执行邮件发送操作
	输入:mixed (接收email，来自email，标题，内容，类型，抄送，暗送，信息头)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function send_mail($to, $from, $subject = "", $body = "", $mailtype, $cc = "", $bcc = "", $additional_headers = "")
	{
		$mail_from = $this->get_address($this->strip_comment($from));
		//$body = ereg_replace("(^|(\r\n))(.)", "1.3", $body);
		$header .= "MIME-Version:1.0\r\n";
		if($mailtype=="HTML")
		{
			$header .= "Content-Type:text/html\r\n";
		}
		$header .= "To: ".$to."\r\n";
		if ($cc != "")
		{
			$header .= "Cc: ".$cc."\r\n";
		}
		$value = stripos($from,"<",0);
		if(!empty($value))
			$header .= "From: $from\r\n";
		else
			$header .= "From: $from<".$from.">\r\n";
		$header .= "Subject: ".$subject."\r\n";
		$header .= $additional_headers;
		$header .= "Date: ".date("r")."\r\n";
		$header .= "X-Mailer:By Redhat (PHP/".phpversion().")\r\n";
		list($msec, $sec) = explode(" ", microtime());
		$header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mail_from.">\r\n";
		$TO = explode(",", $this->strip_comment($to));

		if ($cc != "")
		{
			$TO = array_merge($TO, explode(",", $this->strip_comment($cc)));
		}

		if ($bcc != "")
		{
			$TO = array_merge($TO, explode(",", $this->strip_comment($bcc)));
		}

		$sent = TRUE;
		foreach ($TO as $rcpt_to)
		{
			$rcpt_to = $this->get_address($rcpt_to);
			if (!$this->smtp_sockopen($rcpt_to))
			{
				$this->print_error("不能发送邮件到 ".$rcpt_to."\n");
				$sent = FALSE;
				continue;
			}
			if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $header, $body))
			{
				$this->print_debug("邮件成功发送到 <".$rcpt_to.">\n");
			}
			else
			{
				$this->print_error("不能发送邮件到 <".$rcpt_to.">\n");
				$sent = FALSE;
			}
			fclose($this->sock);
			$this->print_debug("跟远程smtp服务器断开连接\n");
		}
		return $sent;
	}

//-----------------------------------------------------------------------------
	/*
	-----------------------------------------------------------
	函数名称:smtp_send()
	简要描述:具体执行邮件发送方法
	输入:mixed
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function smtp_send($helo, $from, $to, $header, $body = "")
	{
		if (!$this->smtp_putcmd("HELO", $helo))
		{
			return $this->print_error("sending HELO command");
		}
		//auth
		if($this->auth)
		{
			if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode($this->user)))
			{
				return $this->print_error("sending HELO command");
			}

			if (!$this->smtp_putcmd("", base64_encode($this->pass)))
			{
				return $this->print_error("sending HELO command");
			}
		}
		//
		if (!$this->smtp_putcmd("MAIL", "FROM:<".$from.">"))
		{
			return $this->print_error("sending MAIL FROM command");
		}

		if (!$this->smtp_putcmd("RCPT", "TO:<".$to.">"))
		{
			return $this->print_error("sending RCPT TO command");
		}

		if (!$this->smtp_putcmd("DATA"))
		{
			return $this->print_error("sending DATA command");
		}

		if (!$this->smtp_message($header, $body))
		{
			return $this->print_error("sending message");
		}

		if (!$this->smtp_eom())
		{
			return $this->print_error("sending <CR><LF>.<CR><LF> [EOM]");
		}

		if (!$this->smtp_putcmd("QUIT"))
		{
			return $this->print_error("sending QUIT command");
		}

		return TRUE;
	}
	/*
	-----------------------------------------------------------
	函数名称:smtp_sockopen($address)
	简要描述:用socket方式打开远程smtp主机
	输入:string (smtp主机地址)
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function smtp_sockopen($address)
	{
		if ($this->relay_host == "")
		{
			return $this->smtp_sockopen_mx($address);
		}
		else
		{
			return $this->smtp_sockopen_relay();
		}
	}
	/*
	-----------------------------------------------------------
	函数名称:smtp_sockopen_relay()
	简要描述:等待smtp主机回应
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function smtp_sockopen_relay()
	{
		$this->print_debug("尝试连接到 ".$this->relay_host.":".$this->smtp_port."\n");
		$this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);

		if (!($this->sock && $this->smtp_ok()))
		{
			$this->print_error("不能连接到发送主机 ".$this->relay_host."\n");
			$this->print_error("错误: ".$errstr." (".$errno.")\n");
			return FALSE;
		}
		$this->print_debug("连接到发送主机 ".$this->relay_host."\n");
		return TRUE;;
	}
	/*
	-----------------------------------------------------------
	函数名称:smtp_sockopen_mx($address)
	简要描述:---
	输入:---
	输出:---
	修改日志:------
	-----------------------------------------------------------
	*/
	function smtp_sockopen_mx($address)
	{
		$domain = ereg_replace("^.+@([^@]+)$", "1", $address);
		if (!@getmxrr($domain, $MXHOSTS))
		{
			$this->print_error("错误: 不能解决 MX \"".$domain."\"\n");
			return FALSE;
		}
		foreach ($MXHOSTS as $host)
		{
			$this->print_debug("尝试连接到 ".$host.":".$this->smtp_port."\n");
			$this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);
			if (!($this->sock && $this->smtp_ok()))
			{
				$this->print_error("警告: 不能连接到 mx host ".$host."\n");
				$this->print_error("错误: ".$errstr." (".$errno.")\n");
				continue;
			}
			$this->print_debug("连接到 mx host ".$host."\n");
			return TRUE;
		}
		$this->print_error("错误: 不能连接到任何 mx hosts (".implode(", ", $MXHOSTS).")\n");
		return FALSE;
	}
	/*
	-----------------------------------------------------------
	函数名称:smtp_message($header, $body)
	简要描述:执行smtp信息写入操作
	输入:mixed （邮件头，邮件内容）
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function smtp_message($header, $body)
	{
		fputs($this->sock, $header."\r\n".$body);
		$this->print_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));

		return TRUE;
	}

	/*
	-----------------------------------------------------------
	函数名称:smtp_eom()
	简要描述:---
	输入:---
	输出:---
	修改日志:------
	-----------------------------------------------------------
	*/
	function smtp_eom()
	{
		fputs($this->sock, "\r\n.\r\n");
		$this->print_debug(". [EOM]\n");

		return $this->smtp_ok();
	}

	/*
	-----------------------------------------------------------
	函数名称:smtp_ok()
	简要描述:---
	输入:---
	输出:---
	修改日志:------
	-----------------------------------------------------------
	*/
	function smtp_ok()
	{
		$response = str_replace("\r\n", "", fgets($this->sock, 512));
		$this->print_debug($response."\n");

		if (!ereg("^[23]", $response))
		{
			fputs($this->sock, "QUIT\r\n");
			fgets($this->sock, 512);
			$this->print_error("错误:远程主机返回 \"".$response."\"\n");
			return FALSE;
		}
		return TRUE;
	}
	/*
	-----------------------------------------------------------
	函数名称:smtp_putcmd($cmd, $arg = "")
	简要描述:---
	输入:---
	输出:---
	修改日志:------
	-----------------------------------------------------------
	*/
	function smtp_putcmd($cmd, $arg = "")
	{
		if ($arg != "")
		{
			if($cmd=="")
				$cmd = $arg;
			else
				$cmd = $cmd." ".$arg;
		}

		fputs($this->sock, $cmd."\r\n");
		$this->print_debug("> ".$cmd."\n");

		return $this->smtp_ok();
	}

	/*
	-----------------------------------------------------------
	函数名称:strip_comment($address)
	简要描述:---
	输入:---
	输出:---
	修改日志:------
	-----------------------------------------------------------
	*/
	function strip_comment($address)
	{
		$comment = "\([^()]*\)";
		while (ereg($comment, $address))
		{
			$address = ereg_replace($comment, "", $address);
		}

		return $address;
	}
	/*
	-----------------------------------------------------------
	函数名称:get_address($address)
	简要描述:获取邮件地址，主要为格式化
	输入:string
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function get_address($address)
	{
		$address = ereg_replace("([ \t\r\n])+", "", $address);
		$address = ereg_replace("^.*<(.+)>.*$", "1", $address);

		return $address;
	}
	/*
	-----------------------------------------------------------
	函数名称:print_error($str = "")
	简要描述:显示操作错误信息
	输入:string
	输出:echo
	修改日志:------
	-----------------------------------------------------------
	*/
	function print_error($str = "")
	{
		//设置全局变量$PHPSEA_ERROR..
		global $PHPSEA_ERROR;
		$PHPSEA_ERROR['MailSmtp_Error'] = $str;

		//判断是否显示错误输出..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>";
			print "<b>MailSmtp Error --</b> ";
			print "[<font color=000077>$str</font>]";
			print "</font></blockquote>";
		}
	}//end func

	/*
	-----------------------------------------------------------
	函数名称:print_debug($str = "")
	简要描述:显示操作信息show_debug
	输入:string
	输出:echo
	修改日志:------
	-----------------------------------------------------------
	*/
	function print_debug($str = "")
	{

		//判断是否显示错误输出..
		if ( $this->show_debug )
		{
			print "<blockquote><font face=arial size=2 color=green>";
			print "<b>MailSmtp Debug --</b> ";
			print "[<font color=000077>$str</font>]";
			print "</font></blockquote>";
		}
	}//end func

}//CLASS END
//=============================================================================
?>

