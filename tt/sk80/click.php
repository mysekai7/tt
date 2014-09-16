<?php
error_reporting(E_ALL);
$ROOT_PATH = '../';
include_once($ROOT_PATH . "include/config.php");

$update_time = 1800;//多长时间更新一次,单位是秒

$article_id = (isset($_GET['article_id']) && is_numeric($_GET['article_id']) && $_GET['article_id'] > 0) ? intval($_GET['article_id']) : 0;//文章的ID
if ($article_id > 0) {

    $filename = $ROOT_PATH . 'log/click_log.txt';
    $s = '';
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        $d_ary = array();
        if ($content) {
            $ary = explode("\n", $content);
            foreach ($ary as $line) {
                $data_ary = explode('|', $line);
                if (is_numeric($data_ary[0]) && is_numeric($data_ary[1])) {
                    $d_ary[$data_ary[0]] = $data_ary[1];
                }
            }
        }
        if (array_key_exists($article_id, $d_ary)) {
            $d_ary[$article_id] = $d_ary[$article_id]+1;//将当前的文章的点击数加1
        } else {
            $d_ary[$article_id] = 1;
        }
        foreach ($d_ary as $key => $val) {
            $s .= $key . '|' . $val . "\n";
        }
    } else {
        $s .= $article_id . '|1' . "\n";//这个是初始化记录文件
    }

    //写记录文件
    $fp = fopen($filename, "w");
//加入锁定
if (flock($fp, LOCK_EX)) { // 进行排它型锁定
    fwrite($fp, $s);
    flock($fp, LOCK_UN); // 释放锁定
} 
    //fwrite($fp, $s);
    fclose($fp);
    @chmod($filename, 0777);

    $last_update = file_get_contents('../log/last_update.txt');//取上一次更新的时间
    $last_update = intval($last_update);
    if (($last_update + $update_time) < time()) {

        //以下是数据库连接操作,我用的是ADODB,你可以改成你自己的
        require_once("adodb.inc.php");
        $db = NewADOConnection("$DB_TYPE");
        $db->debug = true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        if (!$db->Connect("$DB_HOST", "$DB_USER", "$DB_PASS", "$DB_DATABASE")) {
            exit('<a href="/">服务器忙,请稍候再访问</a>');
        }

        $content = file_get_contents($filename);
        $d_ary = array();
        if ($content) {
            $ary = explode("\n", $content);
            foreach ($ary as $line) {
                $data_ary = explode('|', $line);
                if (is_numeric($data_ary[0]) && is_numeric($data_ary[1])) {
                    $sql = "UPDATE article SET hits=hits+" . $data_ary[1] . " WHERE id=" . $data_ary[0];
                    $db->Execute($sql);
                }
            }
        }
        //点击数更新完了,将这个记录文件清空
        $fp = fopen($filename, "w");
        fwrite($fp, '');
        fclose($fp);
        @chmod($filename, 0777);

        //将最后一次更新时间改为当前时间
        $fp = fopen('../log/last_update.txt', "w");
        fwrite($fp, time());
        fclose($fp);
        @chmod('../log/last_update.txt', 0777);
    
        $db->Close();//关闭数据库连接
    }
    exit();
}
?>