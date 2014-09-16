<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh">
    <head>
        <title>MSN 测试</title>
        <meta name="generator" content="Bluefish 1.0.6"/>
        <meta name="keywords" content="MSN"/>
        <meta name="description" content="MSN test"/>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <style type="text/css">
            <!--
            div {
                font-family: arial, helvetica, sans-serif;
                font-size : 13px ;
                margin: 10px;
                padding: 2px 10px 1px;
                background-color: #C60;
                color: #FFFFFF;
                border-top: 1px solid #C90;
                border-left: 1px solid #C90;
                border-bottom: 1px solid #333;
                border-right: 1px solid #333;
            }
            a:link {
                color: #00FFBE;
            }
            --></style>
    </head>
    <body>
        <div>

<?php
# filename: fm.php
# purpose: get MSN contact list
# author: http://qartis.com/?qmsn modified by Eric Hu
//phpinfo();exit();
set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $debug = 0;
    $trid = 0;
    $proto = "MSNP10";

    # start here
    echo "通讯协议 $proto<br/>";
    echo "开始登录<br/>";
    # login now
    $sbconn = fsockopen("messenger.hotmail.com",1863) or die("Can't connect to MSN server");
    flush();
    data_out("VER $trid $proto CVR0");
    data_in();
    data_out("CVR $trid 0x0409 winnt 5.1 i386 MSNMSGR 8.0.0812 MSMSGS $username");
    data_in();
    data_out("USR $trid TWN I $username");

    $temp = data_in();

    if (!stristr($temp,":")) {
        if (substr($temp,0,3)==601) {
        #echo "Error: The MSN servers are currently unavailable.";
            echo "很不幸，MSN的服务器又挂了 >.<<br/>";
            die();
        } else {
            echo "连接失败!<br/>";
            fclose($sbconn);
            die();
        }
    }

    @fclose($sbconn);
    $temp_array = explode(" ",$temp);
    $temp_array = explode(":",$temp_array[3]);
    flush();
    $sbconn = fsockopen($temp_array[0],$temp_array[1]) or die("error -_-#");
    data_out("VER $trid $proto CVR0");
    data_in();
    flush();
    data_out("CVR $trid 0x0409 winnt 5.1 i386 MSNMSGR 8.0.0812 MSMSGS $username");
    data_in();
    data_out("USR $trid TWN I $username");
    $temp = data_in();
    $temp_array = explode(" ",$temp);
    flush();
    $TOKENSTRING = trim(end($temp_array));
    #echo "authenticating";
    echo "身份验证中……<br/>";
    flush();

    $nexus_socket = fsockopen("ssl://nexus.passport.com",443);
    fputs($nexus_socket,"GET /rdr/pprdr.asp HTTP/1.0\r\n\r\n");
exit();
    while ($temp != "\r\n") {
        $temp = fgets($nexus_socket,1024);
        if (substr($temp,0,12)=="PassportURLs") {
            $urls = substr($temp,14);
        }
    }

    $temp_array = explode(",",$urls);
    $temp = $temp_array[1];
    $temp = substr($temp,8);

    $temp_array = explode("/",$temp);
    @fclose($nexus_socket);

    $ssl_conn = fsockopen("ssl://".$temp_array[0],443);
    fputs($ssl_conn,"GET /{$temp_array[1]} HTTP/1.1\r\n");
    fputs($ssl_conn,"Authorization: Passport1.4 OrgVerb=GET,OrgURL=http%3A%2F%2Fmessenger%2Emsn%2Ecom,sign-in=".urlencode($username).",pwd=$password,$TOKENSTRING\r\n");
    fputs($ssl_conn,"User-Agent: MSMSGS\r\n");
    fputs($ssl_conn,"Host: {$temp_array[0]}\r\n");
    fputs($ssl_conn,"Connection: Keep-Alive\r\n");
    fputs($ssl_conn,"Cache-Control: no-cache\r\n\r\n");
    $temp = fgets($ssl_conn,512);

    if (rtrim($temp) == "HTTP/1.1 302 Found") {
    #echo "redirection";
        echo "开始重定向<br/>";
        flush();
        while ($temp != "\r\n") {
            $temp = fgets($ssl_conn,256);
            if (substr($temp,0,9)=="Location:") {
                $temp_array = explode(":",$temp);
                $temp_array = explode("/",trim(end($temp_array)));
                break;
            }
        }
        @fclose($ssl_conn);
        $ssl_conn = fsockopen("ssl://".$temp_array[2],443);
        fputs($ssl_conn,"GET /{$temp_array[3]} HTTP/1.1\r\n");
        fputs($ssl_conn,"Authorization: Passport1.4 OrgVerb=GET,OrgURL=http%3A%2F%2Fmessenger%2Emsn%2Ecom,sign-in=".urlencode($username).",pwd=$password,$TOKENSTRING\r\n");
        fputs($ssl_conn,"User-Agent: MSMSGS\r\n");
        fputs($ssl_conn,"Host: {$temp_array[2]}\r\n");
        fputs($ssl_conn,"Connection: Keep-Alive\r\n");
        fputs($ssl_conn,"Cache-Control: no-cache\r\n\r\n");
    } elseif (rtrim($temp)=="HTTP/1.1 401 Unauthorized") {
    #echo "invalidcreds";
        echo "验证失败！<br/>";
        @fclose($ssl_conn);
        die();
    } else {
        if (rtrim($temp) != "HTTP/1.1 200 OK") {
        #echo "Unknown HTTP status code: $temp<br>";
            echo "未知状态码 $temp<br/>";
            flush();
            die();
        } else {
        #echo "set_bar_len30?";
        }
    }

    while ($temp != "\r\n") {
        $temp = fgets($ssl_conn,1024);
        if (substr($temp,0,19)=="Authentication-Info") {
            $auth_info = $temp;
            $temp = fgets($ssl_conn,1024);
            if (substr($temp,0,14)!="Content-Length") {
                $auth_info.= fgets($ssl_conn,1024);
            }
            break;
        }
    }
    @fclose($ssl_conn);

    $temp_array = explode("'",$auth_info);
    flush();

    data_out("USR $trid TWN S {$temp_array[1]}");
    flush();

    $temp=data_in();

    flush();
    $time_since_initmsg = time();
    while(!strstr($temp,"ABCHMigrated") && is_string(trim($temp))) {
        if (substr($temp,0,3)=="sid") {
            $sid = trim(substr($temp,5));
        }
        if (substr($temp,0,2)=="kv") {
            $kv = trim(substr($temp,4));
        }
        if (substr($temp,0,7)=="MSPAuth") {
            $mspauth = trim(substr($temp,9));
            flush();
        }
        $temp = data_in();
    }
    $temp = data_in();
    #echo "authenticated<br />";
    echo "验证通过！<br/>";
    flush();

    #data_out("LST 9 RL");
    #data_in();

    data_out("SYN $trid 0 0");
    #echo "retreiving_contact_list<br />";
    echo "正在获取好友列表……<br/><br/>";
    flush();
    stream_set_timeout($sbconn,0,125000);

    /* a lazy man doing this :D */
    for($i=0;$i<160;$i++) # some say max is 150
    {
        $temp = data_in();
        switch (substr($temp, 0, 3)) {
            case "LST":
                $temp_array = explode(" ",$temp);
                $un = substr($temp_array[1], 2);
                $nn = substr($temp_array[2], 2);
                $nn1 = substr($temp_array[2], 0, 1);
                if($nn1 == "F") {
                    echo "<a href=\"mailto:$un\">$nn</a><br/>";
                }
                else {
                    echo "曾经的好友: $un<br/>";
                }
                #echo $temp."<br/>";
                break;
            default:
            # no nothing
                break;
        }
    }
    echo "列表结束";
    @fclose($sbconn);
} else {
    ?>
            <div>
                <form method="post" action="">
                    用户名（邮箱的全名，如xxx@hotmail.com）：<input type="text" value="" name="username" />
                    密码：<input type="password" value="" name="password" />
                    <input type="submit" value="提交" name="submit" />
                </form>
            </div>

    <?php
}


# end here

# functions

function data_out($data) {
    global $sbconn,$debug,$trid;
    fputs($sbconn,$data."\r\n");
    $trid++;
    if ($debug && !empty($data)) { echo "> ".$data."<br>\r\n";}
}

function data_in() {
    global $sbconn,$debug;
    $temp = fgets($sbconn,256);
    if ($debug && !empty($temp)) {echo "< ".$temp."<br>\r\n";}
    return $temp;
}
?>

        </div>
    </body>
</html>
