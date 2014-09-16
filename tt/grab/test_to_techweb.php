<?php

set_time_limit(0);
$dbh = new db();
$dbh->connect('localhost', 'root', '123456', "teachweb", "utf8");
//$dbh->connect('localhost', 'root', 'root',"temp","gbk");

$config = array(
    array(
        'name' => 'ctocio_roll',
        'url' => 'http://rss.sina.com.cn/rollnews/finance/finance1_total.js',
    ),
);

foreach ($config as $val) {
    //   tom_roll("http://news.ctocio.com.cn/xwpl/");
    $res = $val['name']($val['url']);
    if (!$res) {
        echo '跳出';
        ob_flush();
        flush();
        continue;
    }
}

echo '全部抓取完毕';
ob_flush();
flush();

//--------------------------------------------------ctocio 滚动新闻 开始--------------------------------------------------------------------
function ctocio_roll($url) {
    $tmp = file_get_contents($url);
    preg_match_all('|category:"国内财经",\s.*?title:"(.*?)",\s.*?"(.*?)",|ims', $tmp, $row  );
    $arr = array();
    if ($row[1]) {
        foreach ($row[1] as $key => $val) {
            $arr[$key]['title'] = $row[1][$key];
            $arr[$key]['link'] = $row[2][$key];
        }
    }
    if ($arr) {
        foreach ($arr as $key => $val) {
            if (empty($val['link']) || empty($val['title'])) {
                continue;
            }
            echo $key . "\n";
            ob_flush();
            flush();

            //查询是否已经抓取过了
            $md5 = md5($val['link']);
            $dir = substr($md5, 0, 2);
            //$path = dirname(__FILE__).'/lock/'.$dir;
            $path = '/usr/home/techweb_www/api/roll/lock/' . $dir;
            if (file_exists($path . '/' . $md5)) {
                return false;
            }
            $tmp = '';
            $tmp = @file_get_contents($val['link']);
            if (empty($tmp)) {
                continue;
            }
//testing is ok!
            preg_match('|<span id="media_name">.*?>(.*?)</a>|ims', $tmp, $media_name);

            //if($media_name[1] == 'IT专家网'){
            //查询是否有分页
            /*
              preg_match('|<div id="numpage">共(\d+)页|ims',$tmp,$page);
              $pageurllist = array();
              $pageurllist[] = $val['link'];
              if($page[1] > 1){
              for($i=1;$i<$page[1];$i++){
              $pageurllist[] = preg_replace('|http://news.ctocio.com.cn/(\d+)/(\d+).shtml|ims',"http://news.ctocio.com.cn/\$1/\$2_".$i.".shtml",$val['link']);
              }
              }
             */
            $pageurllist = array();
            $pageurllist[] = $val['link'];
            $content = array();
            foreach ($pageurllist as $v) {
                //testing is ok!
                $content[] = ctocio_One_Content($v);
            }
            if (!$content) {
                continue;
            }
            $article['title'] = $val['title'];
            $article['media_name'] = '新浪国内财经';
            $article['content'] = implode('[page]', $content);
            $article['link'] = $val['link'];

            echo $article['link'] . "<br>:";
            ob_flush();
            flush();
            //统一入库函数
            insert_DB($article);
            sleep(3);
            //}
        }
    }
    echo 'ctocio 抓取完毕' . "\n";
    ob_flush();
    flush();
}

function ctocio_One_Content($url) {
    $tmp = file_get_contents($url);
    $str = preg_replace('|<div class="guanggao">(.*?)</div>|ims', '', $tmp);
    $str = preg_replace('|<div class="clear">(.*?)</div>|ims', '', $str);
    $str = preg_replace('|<div id="numpage">(.*?)</div>|ims', '', $str);
    preg_match('|<div id="content">(.*?)</div>|ims', $str, $row);
    $str = $row[1];
    $str = preg_replace('|<a\s.*?>(.*?)</a>|ims', "\$1", $str);
    $str = preg_replace('|<IMG.*?src="(.*?)".*?>|ims', "<img src=\"\$1\" />", $str);
    preg_match_all('|<IMG.*?src="(.*?)".*?>|ims', $str, $row);
    if ($row[1]) {
        foreach ($row[1] as $key => $val) {
            if (substr($val, 0, 4) != 'http') {
                $str = preg_replace('|<IMG src="' . $val . '".*?>|ims', "<img src=\"http://news.ctocio.com.cn" . $val . "\" width=\"300\" />", $str);
            }
        }
    }
    $str = str_replace('　', '', $str);
    $str = str_replace('<p>', '<p>　　', $str);
    $str = str_replace('<P>', '<P>　　', $str);


    $str = trim($str);
    return $str;
}

//--------------------------------------------------ctocio 滚动新闻 结束--------------------------------------------------------------------
//###############################以下是公用函数###############################
//--------------------------------------------------获取文章关键词 开始------------------------------------------------------------------
function KeyWord($str) {
    $str = cutstr($str, 1000);
    $str = rawurlencode(strip_tags(preg_replace("/\[.+?\]/U", '', $str)));

    $data = @implode('', file("http://keyword.discuz.com/related_kw.html?title=$str&ics=gbk&ocs=gbk"));
    if ($data) {
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $data, $values, $index);
        xml_parser_free($parser);

        $kws = array();

        foreach ($values as $valuearray) {
            if ($valuearray['tag'] == 'kw' || $valuearray['tag'] == 'ekw') {
                $kws[] = $valuearray['value'];
            }
        }
        $return = '';
        $not = array('quot', 'style');
        if ($kws) {
            foreach ($kws as $kw) {
                if (!in_array($kw, $not)) {
                    $kw = htmlspecialchars($kw);
                    $return .= $kw . ',';
                }
            }
            $return = trim($return);
            $return = substr($return, 0, -1);
        }
        return $return;
    } else {
        return '';
    }
}

function KeyWord_to_sina($str) {
    //$str = iconv("GBK","UTF-8",$str);
    $str = mb_convert_encoding($str, 'utf-8', 'gbk');
    $str = rawurlencode($str);

    $ch = curl_init("http://itag.blog.sina.com.cn/indexv5.php");

    $data = 'album=&blog_id=&is_album=0&stag=&sno=&book_worksid=&channel_id=&url=&channel=&newsid=&fromuid=&wid=&articletj=&vtoken=42d6d07d285fa751ee236faa868dcfec&is_media=0&blog_title=&time=16%3A12%3A43&blog_body=' . $str . '&tag=%E4%BE%8B%E5%A6%82%EF%BC%9A%E9%AB%98%E8%80%83+%E4%BA%92%E8%81%94%E7%BD%91+%E5%BD%B1%E8%AF%84+%E6%9D%8E%E5%AE%87%E6%98%A5+%E6%83%85%E6%84%9F+%E5%8F%A3%E8%BF%B0%E5%AE%9E%E5%BD%95+%E8%B6%8A%E7%8B%B1+%E8%80%81%E7%85%A7%E7%89%87&blog_class=0&x_cms_flag=0&x_rank=0&sina_sort_id=117&is2bbs=1&join_circle=1';
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $res = curl_exec($ch);
    curl_close($ch);
    if ($res) {
        preg_match('|URL=(.*?)">|ims', $res, $row);
        $str = str_replace('http://control.blog.sina.com.cn/admin/article/article_itag_html.php?type=00006&tags=', '', $row[1]);
        $str = explode('+', $str);
        $keyword = array();
        foreach ($str as $val) {
            $keyword[] = mb_convert_encoding(urldecode($val), "GBK", "UTF-8");
        }
        return implode(',', $keyword);
    } else {
        return '';
    }
}

//字符串截取函数
function cutstr($string, $length, $dot = '') {
    if (strlen($string) <= $length) {
        return $string;
    }

    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

    $strcut = '';
    if (strtolower($charset) == 'utf-8') {

        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {

            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t < 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }

            if ($noc >= $length) {
                break;
            }
        }
        if ($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);
    } else {
        for ($i = 0; $i < $length; $i++) {
            $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
        }
    }

    $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

    return $strcut . $dot;
}

//--------------------------------------------------获取文章关键词 结束------------------------------------------------------------------
//--------------------------------------------------图片本地化 开始--------------------------------------------------------------------

function img_to_local($Content) {
    $imagedir = "/data/techweb_publish/2006twcms/resource";
    //$imagedir = dirname(__FILE__);
    preg_match_all('|<img\s.*?src="(.*?)".*?>|ims', $Content, $row);
    if ($row[1]) {
        $pic_info = array();
        $path = lymkdir();
        foreach ($row[1] as $key => $val) {
            $name = '';
            $size = array();
            $data = @file_get_contents($val);
            $name = explode('/', $val);
            $name = $name[count($name) - 1];
            $name = explode('.', $name);
            $name = $name[count($name) - 1];
            $newName = 'img' . date("Y") . date("m") . time() . $key . '.' . $name;
            $fp = fopen($imagedir . '/' . $path . '/' . $newName, 'w');
            fwrite($fp, $data);
            fclose($fp);

            $size = getimagesize($imagedir . '/' . $path . '/' . $newName);

            $pic_info[$key]['n'] = $newName; //图片名称
            $pic_info[$key]['w'] = $size[0]; //图片宽度
            $pic_info[$key]['h'] = $size[1]; //图片高度
            $pic_info[$key]['s'] = filesize($imagedir . '/' . $path . '/' . $newName); //图片大小
            $pic_info[$key]['p'] = $path . '/' . $newName; //图片路径
            //if($size){
            $Content = str_replace($val, '../resource/' . $path . '/' . $newName, $Content);
            //}
        }
        $return['Content'] = $Content;
        $return['pic_info'] = $pic_info;
        return $return;
    }

    $return['Content'] = $Content;
    $return['pic_info'] = '';
    return $return;
}

function lymkdir() {
    $imagedir = "/data/techweb_publish/2006twcms/resource";
    //$imagedir = dirname(__FILE__);
    $y = date("Y");
    $m = date("m");
    $d = date("d");
    if (!file_exists($imagedir . '/img/' . $y)) {
        mkdir($imagedir . '/img/' . $y);
    }

    if (!file_exists($imagedir . '/img/' . $y . '/' . $m)) {
        mkdir($imagedir . '/img/' . $y . '/' . $m);
    }

    if (!file_exists($imagedir . '/img/' . $y . '/' . $m . '/' . $d)) {
        mkdir($imagedir . '/img/' . $y . '/' . $m . '/' . $d);
    }

    return 'img/' . $y . '/' . $m . '/' . $d;
}

//--------------------------------------------------图片本地化 结束--------------------------------------------------------------------
//--------------------------------------------------统一入库函数 开始--------------------------------------------------------------------
function insert_DB($arr) {
    global $dbh;
    if (!is_array($arr)) {
        //记录错误信息
        return false;
    }
    $NodeID = 261;
    $return = array();
    $return = img_to_local($arr['content']);
    $Content = $return['Content'];
    $pic_info = $return['pic_info'];
    unset($return);
    $Keywords = KeyWord($arr['title'] . ' ' . $Content);
    if (!$Keywords) {
        $Keywords = KeyWord_to_sina($Content);
    }

    $Content = str_replace('[page]', '<H3><FONT color=#888888>[Page: ]</FONT></H3>', $Content); //这里要替换分页标签
    $Content = addslashes($Content);
    $CreationUserID = 79;
    $LastModifiedUserID = 79;

    //新闻先入 content_1 表
    $_s = "INSERT INTO `cmsware_content_1` (`Title` ,`TitleColor` ,`Author` ,`Editor` ,`Photo` ,`SubTitle` ,`Content` ,`Keywords` ,`FromSite` ,`Intro` ,`CreationDate` ,`ModifiedDate` ,`CreationUserID` ,`LastModifiedUserID` ,`RelateDoc` ,`ContributionUserID` ,`ContributionID` ,`Lanmu` ,`WantComment` )
	VALUES ('" . $arr['title'] . "', '#000000', '', '', '', '', '" . $Content . "', '" . $Keywords . "', '" . $arr['media_name'] . "', '', '" . time() . "', '" . time() . "', '" . $CreationUserID . "', '" . $LastModifiedUserID . "', '', '0', '0', '显示', 'Yes')";
    $dbh->query($_s);
    $insert_id = $dbh->insert_id();

    $_s = "INSERT INTO `cmsware_content_index` (`IndexID` ,`ContentID` ,`NodeID` ,`ParentIndexID` ,`Type` ,`PublishDate` ,`SelfTemplate` ,`SelfPSN` ,`SelfPublishFileName` ,`SelfPSNURL` ,`SelfURL` ,`State` ,`URL` ,`Top` ,`Pink` ,`Sort` ,`TableID` )
	VALUES (NULL , '" . $insert_id . "', '" . $NodeID . "', '0', '1', '" . time() . "', '', '', '', '', '', '0', '', '0', '0', '100', '1')";

    $dbh->query($_s);
    $IndexID = $dbh->insert_id();

    //更新表
    $_s = "UPDATE `cmsware_content_index` SET `ParentIndexID` = " . intval($IndexID) . " WHERE IndexID = " . intval($IndexID) . " ";
    $dbh->query($_s);

    //存储图片信息  写入 cmsware_resource 表 和 cmsware_resource_ref
    if ($pic_info) {
        //写入 cmsware_resource 表 和 cmsware_resource_ref
        foreach ($pic_info as $val) {
            $_s = "INSERT INTO `cmsware_resource`(`NodeID`,`Type`,`Category`,`Name`,`Path`,`Size`,`Info`,`CreationDate`,`ModifiedDate`) ";
            $_s .= "VALUES('" . $NodeID . "','1','img','" . $val['n'] . "','" . $val['p'] . "','" . $val['s'] . "','" . $val['w'] . '*' . $val['h'] . "','" . time() . "','" . time() . "')";
            $dbh->query($_s);
            $ResourceID = $dbh->insert_id();

            $_s = "INSERT INTO `cmsware_resource_ref`(`NodeID`,`IndexID`,`ResourceID`,`CollectionKey`) ";
            $_s .= "VALUES('" . $NodeID . "','" . $IndexID . "','" . $ResourceID . "','')";
            $dbh->query($_s);
        }
    }


    //分目录
    $md5 = md5($arr['link']);
    $dir = substr($md5, 0, 2);
    //本地路径
    $path = dirname(__FILE__) . '/lock/' . $dir;
    //服务器路径
    //$path = '/usr/home/techweb_www/api/roll/lock/'.$dir;
    if (!file_exists($path)) {
        mkdir($path);
    }
    touch($path . '/' . md5($arr['link']));

    unset($arr);
}

//--------------------------------------------------统一入库函数 结束--------------------------------------------------------------------
//--------------------------------------------------数据库类--------------------------------------------------------------------
class db {

    var $querynum = 0;
    var $link;
    var $histories;
    var $time;
    var $tablepre;

    function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = '', $pconnect = 0, $tablepre='', $time = 0) {
        $this->time = $time;
        $this->tablepre = $tablepre;
        if ($pconnect) {
            if (!$this->link = mysql_pconnect($dbhost, $dbuser, $dbpw)) {
                $this->halt('Can not connect to MySQL server');
            }
        } else {
            if (!$this->link = mysql_connect($dbhost, $dbuser, $dbpw, 1)) {
                $this->halt('Can not connect to MySQL server');
            }
        }

        if ($this->version() > '4.1') {

            if ($dbcharset) {
                mysql_query("SET character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary", $this->link);
            }

            if ($this->version() > '5.0.1') {
                mysql_query("SET sql_mode=''", $this->link);
            }
        }

        if ($dbname) {
            mysql_select_db($dbname, $this->link);
        }
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC) {
        return mysql_fetch_array($query, $result_type);
    }

    function result_first($sql) {
        $query = $this->query($sql);
        return $this->result($query, 0);
    }

    function fetch_first($sql) {
        $query = $this->query($sql);
        return $this->fetch_array($query);
    }

    function fetch_all($sql) {
        $arr = array();
        $query = $this->query($sql);
        while ($data = $this->fetch_array($query)) {
            $arr[] = $data;
        }
        return $arr;
    }

    function cache_gc() {
        $this->query("DELETE FROM {$this->tablepre}sqlcaches WHERE expiry<$this->time");
    }

    function query($sql, $type = '', $cachetime = FALSE, $isForce = false) {
        $func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';

        if ($isForce) {
            $query = @mysql_query($sql, $this->link);
        } else {

            if (!($query = $func($sql, $this->link)) && $type != 'SILENT') {
                $this->halt('MySQL Query Error', $sql);
            }
        }

        $this->querynum++;
        $this->histories[] = $sql;
        return $query;
    }

    function affected_rows() {
        return mysql_affected_rows($this->link);
    }

    function error() {
        return (($this->link) ? mysql_error($this->link) : mysql_error());
    }

    function errno() {
        return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
    }

    function result($query, $row) {
        $query = @mysql_result($query, $row);
        return $query;
    }

    function num_rows($query) {
        $query = mysql_num_rows($query);
        return $query;
    }

    function num_fields($query) {
        return mysql_num_fields($query);
    }

    function free_result($query) {
        return mysql_free_result($query);
    }

    function insert_id() {
        return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
    }

    function fetch_row($query) {
        $query = mysql_fetch_row($query);
        return $query;
    }

    function fetch_fields($query) {
        return mysql_fetch_field($query);
    }

    function version() {
        return mysql_get_server_info($this->link);
    }

    function close() {
        return mysql_close($this->link);
    }

    function halt($message = '', $sql = '') {
        exit($message . '<br /><br />' . $sql . '<br /> ' . mysql_error());
    }

}

//--------------------------------------------------数据库类--------------------------------------------------------------------
?>