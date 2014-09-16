<?php

    $ServerName = $_SERVER["SERVER_NAME"] ;
	$ServerPort = $_SERVER["SERVER_PORT"] ;
	$ScriptName = $_SERVER["SCRIPT_NAME"] ;
	$QueryString = $_SERVER["QUERY_STRING"]  ;
	$serverip = $_SERVER["REMOTE_ADDR"] ;
	$Url="http://".$ServerName ;
	If ($ServerPort != "80")
	    {$Url = $Url.":".$ServerPort ; }
	$Url=$Url.$ScriptName ;
	If ($QueryString !="")
	    {$Url=$Url."?".$QueryString  ; }
	$GetLocationURL=$Url ;
    $agent1 = $_SERVER["HTTP_USER_AGENT"];
	$agent=strtolower($agent1);
	$Bot ="";
    if (strpos($agent,"bot")>-1)
	    {$Bot = "ÆäËüÖ©Öë";}
	if (strpos($agent,"googlebot")>-1)
	    {$Bot = "Google";}
    if (strpos($agent,"mediapartners-google")>-1)
	   {$Bot = "Google Adsense";}
	if (strpos($agent,"baiduspider")>-1)
	   {$Bot = "Baidu";}
	if (strpos($agent,"sogou spider")>-1)
	   {$Bot = "Sogou";}
	if (strpos($agent,"yahoo")>-1)
	   {$Bot = "Yahoo!";}
	if (strpos($agent,"msn")>-1)
	   {$Bot = "MSN";}
	if (strpos($agent,"ia_archiver")>-1)
	   {$Bot = "Alexa";}
	if (strpos($agent,"iaarchiver")>-1)
	   {$Bot = "Alexa";}
	if (strpos($agent,"sohu")>-1)
	   {$Bot = "Sohu";}
	if (strpos($agent,"sqworm")>-1)
	   {$Bot = "AOL";}
	if (strpos($agent,"yodaoBot")>-1)
	   {$Bot = "Yodao";}
	if (strpos($agent,"iaskspider")>-1)
	   {$Bot = "Iask";}

	 $conn = new COM('ADODB.Connection') or die('can not start Active X Data Objects');
     $conn->Open("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . realpath("bot.mdb"));
	 $shijian=date("Y-m-d h:i:s", time());
	 $rs = $conn->Execute("insert into bot (bot,shijian,url,serverip) values ('$Bot','$shijian','$GetLocationURL','$serverip')");


?>