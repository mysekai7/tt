<?php
include('PostHttp.class.php');

function generateCode($length=6) {
       $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
       $code = "";
       while (strlen($code) < $length) {
           $code .= $chars[mt_rand(0,strlen($chars))];
       }
       return $code;
   }

for($i=0;$i<5;$i++)
{
	$e = generateCode(10);
	$e .='@';
	$stmp = array('qq','126','hotmail','sina','gmail','163','tom','maidezhi');
	$len  = count($stmp)-1;
	$e .= $stmp[mt_rand(0,$len)];
	$e .='.com';

	$postData['author'] = $e;
	$postData['mail'] = $e;
    $postData['text'] = 'test';
    //$postData['confirm_password'] = '123456';
	$postURL = "http://tension.name/2009/2012/comment";

	$http = new PostHttp();
	$http->clearFields();
	foreach ($postData as $key => $val)
	{
		$http->addField($key, $val);
	}
	$http->postPage($postURL);
	//$strPostResult = $http->getContent();
    //echo $strPostResult.'<br />';
    echo "<br />##email:".$e;
/*

	$cookie_jar = dirname(__FILE__).'/cookie.txt';

	$p = '123456';
	$data = 'act=act_register&email='.$e.'&password='.$p.'&confirm_password='.$p;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://www.maidezhi.com/user.php');
	//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	curl_close($ch);


	//$hd =fopen(dirname(__FILE__).'/'.'log.txt','a+');
	//fwrite($hd,$e."\r\n");
	ob_start();
	ob_flush();
	echo "<br />##email:".$e;
    */
}
?>