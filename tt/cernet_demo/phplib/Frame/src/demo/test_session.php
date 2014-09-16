<?php
//²âÊÔÊý¾Ý¿âsession
//by indraw
//2004/11/10

require_once("ClassSession.php");
require_once("../db/ClassMySQL.php");
$db = new MySQL("root", "", "test", "localhost");
$Session = new Session($db,"MySessionName",1,true,false,100);

//-------------------------------------------------------------------
if(!isset($_SESSION["test"]))
{
	print "Session var test - not set<br>";
	$_SESSION["test"]["test"] = "test";
	$_SESSION["test"]["test1"] = "test1";
	$_SESSION["test"]["test2"] = "test2";
	$_SESSION["test"]["inttest"] = 100;
	$_SESSION["test"]["resltest"] = 100.0003;
}
else
{
	print "Session is set - Session name: " . session_name() . "<br>";
	print "<pre>";
	print_r($_SESSION);
	print "</pre>";
}

//-------------------------------------------------------------------
?>
