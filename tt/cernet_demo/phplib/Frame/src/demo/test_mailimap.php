<?
//测试imap接收邮件
//by indraw
//2004/11/24

	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	require("MailImap.class.php");

//-----------------------------------------------------------------------------

	$imap=new MailImap;
	$imap->hostname="mail.dns.com.cn";
	$imap->port=143;
	$imap->username="wangyzh";
	$imap->userpwd="105080";


	/*
	$imap=new MailImap;
	$imap->hostname="pop.163.com";
	$imap->port=110;
	$imap->username="indraw";
	$imap->userpwd="iloveyou";
	*/
	

//-----------------------------------------------------------------------------
	$imap->open();

	$imap->get_mailbox();
	if ($page=="") $page=0;
	$getMailInfo = $imap->check_mailinfo(10,$page);

	if ($getMailInfo[info]->Nmsgs>0){
		//echo "您邮箱：".$mboxinfo->Mailbox."<br>";
		echo $imap->username."@".$imap->hostname."的收件箱里共有邮件数：".$getMailInfo[info]->Nmsgs."<br>\n";
		echo "未读邮件数：".$getMailInfo[info]->Unread."　";
		echo "新邮件数：".$getMailInfo[info]->Recent." ";
		echo "总共占用空间：";
		echo $getMailInfo[info]->Size > 1024 ? sprintf("%.0f kb", $getMailInfo[info]->Size / 1024):$getMailInfo[info]->Size;
		echo "字节<br>\n";
		$last_page = ceil($getMailInfo[info]->Nmsgs/10);
		$cur_page = $page +1;
		echo "第".$cur_page."页，共".$last_page."页。\n";
	}
	else{
		echo "您的信箱里没有邮件。<br><hr>\n";
	}

	echo "<table border=1 width=100% cellpadding=2 cellspacing=0 bordercolorlight=#000080 bordercolordark=#ffffff style=\"font:9pt Tahoma,宋体\">\n";
	echo "<tr bgcolor=#ffffd8><td width=24>状态</td><td>发件人</td><td>主题</td><td>时间</td><td>大小</td></tr>\n";

	$getMailList = $getMailInfo['list'];
	for( $i=0; $i<count($getMailInfo['list']); $i++ )
	{
		echo "<tr>\n";
		echo "<td align=center>".$getMailList['newMail'][$i]."</td>\n";
		echo '<td>'.$getMailList['from'][$i].'</td><td><a href="test_mailimap1.php?msg='.$getMailList['msgNum'][$i].'">'.$getMailList['topic'][$i].'</a></td><td width=125>'.$getMailList['date'][$i].'</td><td width=50>'.$getMailList['msgSize'][$i].'</td>';
		echo "</tr>\n";
	}


	echo "</table>\n";
	echo "<table border=0 width=100% cellspacing=4 cellpadding=4><tr>\n";
	if ($page == 0)
		echo "<td>第一页</td>\n";
	else
		echo "<td><a href=\"test_mailimap.php?page=0\">第一页</a></td>\n";
	if (($prev_page = $page-1) < 0)
		echo "<td>前一页</td>\n";
	else
		echo "<td><a href=\"test_mailimap.php?page=$prev_page\">前一页</a></td>\n";

	if (($next_page = $page + 1) >= $last_page)
		echo "<td>后一页</td>\n";
	else
		echo "<td><a href=\"test_mailimap.php?page=$next_page\">后一页</a></td>\n";
	$last_page --;
	if ( $last_page < $next_page)
		echo "<td>最末页</td>\n";
	else
		echo "<td><a href=\"test_mailimap.php?page=$last_page\">最末页</a></td>\n";
		echo "</tr></table>\n";
	@$imap->close();

//-----------------------------------------------------------------------------
?>