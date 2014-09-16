<?
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require("MailImap.class.php");



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
$imap->open();

$mail_structure=$imap->get_structure($msg);
$emailVolue = $imap->proc_structure($mail_structure,"",$msg);
echo "<pre>";
echo "<hr>".$emailVolue;
echo "</pre>";
echo "<hr>";
if ($imap->num_of_attach > 0){
$imap->print_attaches();
}
@$imap->close();
?>