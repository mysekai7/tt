<?php

//ftpÀàÐÍftp²âÊÔ
//by indraw
//2004/11/9

require('FtpPure.class.php') ;

$ftp = new FtpPure("192.168.0.168","john","111111");

//-----------------------------------------------------------------------------

$local_filename  = "test.rar";
$remote_filename = "up_test.rar";


	$ftp->ftp_put($remote_filename, $local_filename);
	$ftp->ftp_quit();


//-----------------------------------------------------------------------------
?>
