<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:MailSmtp.class.php
- 原作者:网上搜集
- 整理者:indraw
- 编写日期:2004/11/17
- 简要描述:imap邮件接收函数，调用imap函数。
- 运行环境:imap模块支持
- 修改记录:2004/11/17，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	$imap=new myimap_ext;
	$imap->hostname="mail.dns.com.cn";
	$imap->port=143; //如果为pop3主机为110
	$imap->username="wangyzh";
	$imap->userpwd="105080";
*/
/*
	open()                                        //打开远程邮箱
	close()                                       //关闭远程邮箱
	delete_mail($msg_no)                          //定义删除标记
	expunge_mail()                                //执行最后的删除操作
	get_mailbox()                                 //获取邮箱列表：：：实验性质，还没有完善！
	check_mailinfo($page_size,$page)              //获取邮箱信息
	decode_mime_string ($string)                  //将mime邮件进行解码
	display_toaddress ($user, $server, $from)     //显示邮件地址
	get_barefrom($user, $server)                  //显示邮件来源
	get_structure($msg_num)                       //获取邮件信息
	proc_structure($msg_part, $part_no, $msg_num) //获取邮箱的详细信息
	get_mail_subject($msg_no)                     //获取邮件标题
	list_attaches()                               //显示附件列表
*/

//=============================================================================
class MailImap
{
	var $show_errors = true;        //显示错误
	var $show_debug = true;         //显示跟踪

	var $username="";               //用户名
	var $userpwd="";                //用户密码
	var $hostname="";               //smtp主机
	var $port=0;                    //smtp端口
	var $connection=0;              //是否连接
	var $state="DISCONNECTED";      //连接状态
	var $greeting="";
	var $must_update=0;
	var $inStream=0;
	var $num_msg_parts = 0;
	var $attach;                    //附件
	var $num_of_attach = 0;         //附件数量

	var $emailContent = "";         //邮件内容

	/*
	-----------------------------------------------------------
	函数名称:open()
	简要描述:打开远程邮箱
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function open()
	{
		if ($this->port==110)
			$this->inStream=@imap_open("{".$this->hostname."/pop3:110}inbox",$this->username,$this->userpwd);
		else
			$this->inStream=@imap_open("{".$this->hostname.":143}INBOX",$this->username,$this->userpwd);

		if ($this->inStream){
			$this->print_debug("用户：$this->username 的信箱连接成功。");
			return true;
		}
		else{
			$this->print_error("用户：$this->username 的信箱连接失败。");
			return false;
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:close()
	简要描述:关闭远程邮箱
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	Function close()
	{
		if(imap_close($this->inStream)){
			$this->print_debug("已经与服务器 $this->hostname 断开连接。");
			return true;
		}
		else{
			$this->print_error("与服务器 $this->hostname 断开连接失败。");
			return false;
		}
	}
	/*
	-----------------------------------------------------------
	函数名称:DeleteMail($msg_no)
	简要描述:定义删除标记
	输入:int (邮件的唯一编码)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function delete_mail($msg_no)
	{
		if (@imap_delete($this->inStream,$msg_no)){
			$this->print_debug("成功将邮件 $msg_no 定义删除标记");
			return true;
		}
		else{
			$this->print_error("定义邮件 $msg_no 的删除标记失败");
			return false; 
		}
	}
	/*
	-----------------------------------------------------------
	函数名称:ExpungeMail()
	简要描述:执行最后的删除操作
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function expunge_mail()
	{
		if (@imap_expunge($this->inStream)){
			$this->print_debug("成功删除邮件");
			return true;
		}
		else{
			$this->print_error("删除邮件失败");
			return false;
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:get_mailbox()
	简要描述:获取邮箱列表：：：实验性质，还没有完善！
	输入:void
	输出:array
	修改日志:------
	-----------------------------------------------------------
	*/
	function get_mailbox()
	{
		$list = imap_getmailboxes($this->inStream, "{".$this->hostname.":143}", "*");
		//var_dump($list);
		if (is_array($list)) {
			reset($list);
			while (list($key, $val) = each($list)) {
				echo "($key) ";
				echo imap_utf7_decode($val->name) . ",";
				echo "'" . $val->delimiter . "',";
				echo $val->attributes . ",<br>";
				$status = imap_status($this->inStream,$val->name,SA_ALL);
				echo "<pre>";
				var_dump($status);
				echo "</pre><br>";

			}
		}
		else {
			echo "imap_getmailboxes failed: " . imap_last_error() . "<hr>";
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:check_mailinfo($page_size,$page)
	简要描述:获取邮箱信息
	输入:mixed (每页显示，当前页)
	输出:array
	修改日志:------
	-----------------------------------------------------------
	*/
	function check_mailinfo($page_size,$page)
	{
		$mboxinfo=@imap_mailboxmsginfo($this->inStream);
		//$mboxinfo=imap_check($this->inStream);
		//返回邮箱信息
		if ($mboxinfo){
			$mail_info['info'] = $mboxinfo;
		}
		else{
			$this->print_error ("错误：无法获取收件箱的信息。");
			return false;
		}
		$sortby="SORTDATE"; //按照日期排序
		$sort_reverse=1;
		$sorted = imap_sort($this->inStream, $sortby, $sort_reverse, SE_UID);
		
		//显示当前页取得的邮件信息
		for ($i=0;$i<$mboxinfo->Nmsgs;$i++){
			if (($i>=$page*$page_size) and ($i<$page*$page_size+$page_size)){
				$msg_no = @imap_msgno($this->inStream, $sorted[$i]);
				$msgHeader = @imap_header($this->inStream, $msg_no);
				
				//格式化日期：24 Nov 2004 12:58:58转换成11/24 12:58
				if (isset($msgHeader->date)){
					//$date = $msgHeader->date;
					//if (ord($date) > 64)$date = substr($date, 5);
					$date = date ("m/d H:s",strtotime($msgHeader->date));
				}
				//返回邮件的来源
				if (isset($msgHeader->from[0])){
					$from = $msgHeader->from[0];
					if (isset($from->personal)){
						$frm = trim($this->decode_mime_string($from->personal));
						if (isset($from->mailbox) && isset($from->host)){
							$frm_add = $from->mailbox . '@' . $from->host;
						}
					}
					elseif (isset($from->mailbox) && isset($from->host))
						$frm = $from->mailbox . '@' . $from->host;
					elseif (isset($msgHeader->fromaddress))
						$frm = trim($h->fromaddress);
				}
				elseif (isset($msgHeader->fromaddress))
						$frm = trim($msgHeader->fromaddress);
					//if (strlen($frm) > 50)
					//$frm = substr($frm, 0, 50) . '...';
				//返回邮件的接收者
				if (isset($msgHeader->toaddress))
					$to = trim($msgHeader->toaddress);
				else
					$to = "未知";
				//邮件标题
				if (isset($msgHeader->subject))
					$sub = trim($this->decode_mime_string($msgHeader->subject));
				if ($sub == "")
					$sub = "无主题"; 
				if (strlen($sub) > 50)
					$sub = substr($sub, 0, 50) . '...';
				//邮件大小
				if (isset($msgHeader->Size))
					$msg_size = ($msgHeader->Size > 1024) ? sprintf("%.0f kb", $msgHeader->Size / 1024):$msgHeader->Size;

				//获取邮件是否已经读过;
				if ($msgHeader->Unseen == "U")
					$newmail = "未读";
				else
					$newmail = "已读";

				//格式化返回信息
				$mail_info['list']['newMail'][] = $newmail;
				$mail_info['list']['from'][] = $frm;
				$mail_info['list']['msgNum'][] = $msg_no;
				$mail_info['list']['topic'][] = $sub;
				$mail_info['list']['date'][] = $date;
				$mail_info['list']['msgSize'][] = $msg_size;
			}//end if
		}//end for
		return $mail_info;

	}
	/*
	-----------------------------------------------------------
	函数名称:decode_mime_string ($string)
	简要描述:将mime邮件进行解码
	输入:string 
	输出:string 
	修改日志:------
	-----------------------------------------------------------
	*/
	function decode_mime_string ($string)
	{
		$pos = strpos($string, '=?');
		if (!is_int($pos)){
			return $string;
		}

		$preceding = substr($string, 0, $pos); // save any preceding text
		$search = substr($string, $pos+2, 75); // the mime header spec says this is the longest a single encoded word can be
		$d1 = strpos($search, '?');
		if (!is_int($d1)){
			return $string;
		}

		$charset = substr($string, $pos+2, $d1);
		$search = substr($search, $d1+1);

		$d2 = strpos($search, '?');
		if (!is_int($d2)){
			return $string;
		}

		$encoding = substr($search, 0, $d2);
		$search = substr($search, $d2+1);

		$end = strpos($search, '?=');
		if (!is_int($end)){
			return $string;
		}

		$encoded_text = substr($search, 0, $end);
		$rest = substr($string, (strlen($preceding . $charset . $encoding . $encoded_text)+6));

		switch ($encoding)
		{
			case 'Q':
			case 'q':
				$encoded_text = str_replace('_', '%20', $encoded_text);
				$encoded_text = str_replace('=', '%', $encoded_text);
				$decoded = urldecode($encoded_text);
			break;

			case 'B':
			case 'b':
				$decoded = urldecode(base64_decode($encoded_text));
			break;

			default:
				$decoded = '=?' . $charset . '?' . $encoding . '?' . $encoded_text . '?=';
			break;
		}

		return $preceding . $decoded . $this->decode_mime_string($rest);
	}

	/*
	-----------------------------------------------------------
	函数名称:display_toaddress ($user, $server, $from)
	简要描述:显示邮件地址
	输入:mixed (用户，主机，来自)
	输出:string 
	修改日志:------
	-----------------------------------------------------------
	*/
	Function display_toaddress ($user, $server, $from)
	{
		return is_int(strpos($from, $this->get_barefrom($user, $server)));
	}
	/*
	-----------------------------------------------------------
	函数名称:get_barefrom($user, $server)
	简要描述:显示邮件来源
	输入:mixed (用户，主机)
	输出:string (用户信箱)
	修改日志:------
	-----------------------------------------------------------
	*/
	Function get_barefrom($user, $server)
	{
		$barefrom = "$user@$real_server";
		return $barefrom;
	}
	/*
	-----------------------------------------------------------
	函数名称:get_structure($msg_num)
	简要描述:获取邮件信息
	输入:int （邮件唯一表示符）
	输出:object
	修改日志:------
	-----------------------------------------------------------
	*/
	Function get_structure($msg_num)
	{
		$structure=imap_fetchstructure($this->inStream,$msg_num);
		//echo gettype($structure);
		return $structure;
	}

	/*
	-----------------------------------------------------------
	函数名称:proc_structure($msg_part, $part_no, $msg_num)
	简要描述:获取邮箱的详细信息
	输入:mixed (imap_fetchstructure返回值，邮件部分，邮件唯一标识符)
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	Function proc_structure($msg_part, $part_no, $msg_num)
	{
		$emailContent = "无法显示";
		//
		if ($msg_part->ifdisposition){
			// 检查是否有附件
			if ($msg_part->disposition == "attachment"){
				$att_name = "unknown";
				for ($lcv = 0; $lcv < count($msg_part->parameters); $lcv++){
					$param = $msg_part->parameters[$lcv];

					if ($param->attribute == "name"){
						$att_name = $param->value;
						break;
					}
				}

				$att_name = $this->decode_mime_string($att_name);

				$att_path = $this->username."\\".$att_name;
				
				//登记附件列表
				$this->attach[$this->num_of_attach]=$att_name;
				//登记附件数量
				$this->num_of_attach ++; 
				/*
				$att_path = $this->username."\\".$this->decode_mime_string($att_name);
				if ($this->attach=="")
				$this->attach = $att_name;
				else
				$this->attach .= ";".$att_name;
				*/
				if (!is_dir($this->username))
					mkdir($this->username,0700); 

				if (!file_exists($att_path)){
					$fp=fopen($att_path,"w");
					switch ($msg_part->encoding){
						case 3: //base64
							fputs($fp,imap_base64(imap_fetchbody($this->inStream,$msg_num,$part_no)));
						break;
						case 4: //QP
							fputs($fp,imap_qprint(imap_fetchbody($this->inStream,$msg_num,$part_no)));
						break;
						default:
							fputs($fp,imap_fetchbody($this->inStream,$msg_num,$part_no));
						break;
					}
					fclose($fp); 
				}
			//如果有图片，那么显示
			//if ($msg_part->type=="5"){
			//echo "<p align=center><hr align=center>\n";
			//echo "<img src=\"$att_path\" align=center></p>\n";
			//}
			}//如果没有附件
			else{
				//规划中，没有附件的解决办法
			}
		}
		
		else{
		//
			switch ($msg_part->type){
				case 0:
					$mime_type = "text";
				break;
				case 1:
					$mime_type = "multipart";
					//如果是multipart类型，那么递归获取附件信息
					$this->num_msg_parts = count($msg_part->parts);
					for ($i = 0; $i < $this->num_msg_parts; $i++){
						if ($part_no != ""){
							$part_no = $part_no.".";
						}
						for ($i = 0; $i < count($msg_part->parts); $i++){
							$this->proc_structure($msg_part->parts[$i], $part_no.($i + 1), $msg_num);
						}
					}
				break;
				case 2:
					$mime_type = "message";
				break;
				case 3:
					$mime_type = "application";
				break;
				case 4:
					$mime_type = "audio";
				break;
				case 5:
					$mime_type = "image";
				break;
				case 6:
					$mime_type = "video";
				break;
				case 7:
					$mime_type = "model";
				break;
				default:
					$mime_type = "unknown";
			}//end switch
			

			$full_mime_type = $mime_type."/".$msg_part->subtype;
			$full_mime_type = strtolower($full_mime_type);

			//对邮件内容编码进行识别，并正确选择解码方式
			switch ($msg_part->encoding){
				case 0:
				case 1:
					if ($this->num_msg_parts == 0){
						$this->emailContent .= ereg_replace("\r\n","<br>\r\n",imap_body($this->inStream,$msg_num));
					}
					else{
						if ($part_no!=""){
							$this->emailContent .= ereg_replace("\r\n","<br>\r\n",imap_fetchbody($this->inStream,$msg_num,$part_no));
						}
					}
				break;
				case 3://BASE64
					//使用imap_base64进行解码
					if ($full_mime_type=="text/plain"){
						if ($this->num_msg_parts == 0){
							$content=imap_base64(imap_body($this->inStream,$msg_num));
						}
						else{
							$content = imap_base64(imap_fetchbody($this->inStream,$msg_num,$part_no));
							$att_path = $this->username . "\\text.txt";
							$fp = fopen($att_path,"w");
							fputs($fp,$content);
							fclose($fp);
							$this->attach[$this->num_of_attach]="text.txt";
							$this->num_of_attach++; 
						}
					$this->emailContent .= $content;
					}
					if ($full_mime_type=="text/html"){
						$att_path = $this->username . "\\html.htm";
						$fp = fopen($att_path,"w");
						fputs($fp,imap_base64(imap_fetchbody($this->inStream,$msg_num,$part_no)));
						fclose($fp);
						$this->attach[$this->num_of_attach]="html.htm";
						$this->num_of_attach++;
					}
				break;
				case 4: //Qp
					//使用imap_qprint进行解码
					if ($this->num_msg_parts == 0){
						$this->emailContent .= ereg_replace("\n","<br>",imap_qprint(imap_body($this->inStream,$msg_num)));
					}
					else{
						$this->emailContent .= ereg_replace("\n","<br>",imap_qprint(imap_fetchbody($this->inStream,$msg_num,$part_no)));
					}
					if ($full_mime_type=="text/html"){
						$att_path = $this->username . "\\qphtml.htm";
						$fp = fopen($att_path,"w");
						fputs($fp,imap_qprint(imap_fetchbody($this->inStream,$msg_num,$part_no)));
						fclose($fp);
						$this->attach[$this->num_of_attach]="qphtml.htm";
						$this->num_of_attach++;
					} 
				break;
				case 5:
					//默认解码方式
					$this->emailContent .= ereg_replace("\n","<br>",imap_fetchbody($this->inStream,$msg_num));
				break;
			}//end switch
		}//end if
		return $this->emailContent;
	}
	/*
	-----------------------------------------------------------
	函数名称:get_mail_subject($msg_no)
	简要描述:获取邮件标题
	输入:int
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function get_mail_subject($msg_no)
	{
		$msgHeader = @imap_header($this->inStream, $msg_no);
		if (isset($msgHeader->subject))
			$sub = trim($this->decode_mime_string($msgHeader->subject));
		if ($sub == "")
			$sub = "无主题";
		return "Fw:".$sub; 
	}
	/*
	-----------------------------------------------------------
	函数名称:list_attaches()
	简要描述:返回附件列表
	输入:void
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function list_attaches()
	{
		for ($i=0;$i<count($this->attach);$i++){
			if ($i==0) 
				$attaches = $this->attach[$i];
			else
			$attaches .= ";".$this->attach[$i];
		}
		return $attaches;
	}
	/*
	-----------------------------------------------------------
	函数名称:print_attaches()
	简要描述:显示附件列表
	输入:void 
	输出:oid
	修改日志:------
	-----------------------------------------------------------
	*/
	function print_attaches()
	{
		for ($i=0;$i<count($this->attach);$i++){
			echo "<a target=_blank href=\"".$this->username."\\".$this->attach[$i]."\">".$this->attach[$i]."</a><br/>";
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:print_error($str = "")
	简要描述:显示操作错误信息
	输入:string 
	输出:echo or false
	修改日志:------
	-----------------------------------------------------------
	*/
	function print_error($str = "")
	{
		//设置全局变量$PHPSEA_ERROR..
		global $PHPSEA_ERROR;
		$PHPSEA_ERROR['MailImap_Error'] = $str;
	
		//判断是否显示错误输出..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>";
			print "<b>MailImap Error --</b> ";
			print "[<font color=000077>$str</font>]";
			print "</font></blockquote>";
		}
		else
		{
			return false;	
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
			print "<b>MailImap Debug --</b> ";
			print "[<font color=000077>$str</font>]";
			print "</font></blockquote>";
		}
	}//end func

}//end class
//=============================================================================
?>
