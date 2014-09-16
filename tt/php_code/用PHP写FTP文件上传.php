<?php
$ftp_server = "69.73.183.118";
$ftp_user = "lu";
$ftp_pass = "love you";

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");

$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);

if ((!$conn_id) || (!$login_result)) {
        echo "FTP connection has failed!";
        echo "Attempted to connect to $ftp_server for user $ftp_user_name";
        exit;
    } else {
        echo "Connected to $ftp_server, for user $ftp_user_name";
    }

// try to login
 $filename=date('Ymd').".xml";
 $source_file="/usr/local/IVR/sendwireless/xml/data/".$filename;  //源地址
 echo $source_file;
 $destination_file="/ITC/admin/logstat/ftplog/".$filename;  //目标地址
 $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY) or die("Couldn't connect to $ftp_server");
 ftp_quit($conn_id);

 if (!$upload) {
        echo "FTP upload has failed!";
    } else {
        echo "Uploaded $source_file to $ftp_server as $destination_file";
    }
ftp_close($conn_id);

?>