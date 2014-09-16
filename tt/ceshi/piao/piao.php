<?php
set_time_limit(0);
require_once('PostHttp.class.php');
echo "piao.php?id=8&total=10000<br>";

$id=$_GET['id'];
$total=$_GET['total'];

$postData['handlekey'] = "pollresult";
$postData['id'] = "1";
$postData['formhash'] = "aab37902";
$postData['choose_value'] = $id;
$postURL = "http://www.yyfs.cc/plus/poll.php?action=choose";

$http = new PostHttp();

if($_GET['begin'] == 1)
{
for($i=0; $i<$total; $i++)
{
$http->clearFields();
$http->setReferer('http://www.yyfs.cc/plus/poll.php?id=1');
foreach ($postData as $key => $val)
{
	$http->addField($key, $val);
}
sleep(1);
$http->postPage($postURL);
echo "posted: $i<br>";
ob_flush();
flush();
}
}