<?php
require_once('class_log2sitemap.php');
require_once('class_page.php');
require_once('./smarty/Smarty.class.php');


//------------------------------------------------------

$date = date('Ymd', strtotime('last day'));
$o = new log2sitemap($date);

//分页设置
$o->page = new page;
$o->page->PerPage = 80;
$o->page->PerDiv = 20;
$o->page->PerDiv2 = 20;
$o->page->Condition = ".html";
//模板设置
$o->tpl = new Smarty;
$o->tpl->left_delimiter = '<{';
$o->tpl->right_delimiter = '}>';
$o->tpl->template_dir = "./templates/";
$o->tpl->compile_dir = "./templates_c/";

//beigin
$o->run();


//同步所有html
//log2sitemap::sync_all();



//老数据处理生成
//$o->create_old_datalist();
//$o->make_olddata_to_html();
//$o->update_index_html();

//------------------------------------------------------

/*
2010年5月31号以前的日志处理

$path = '/home/zoujiaqi/tootoo_log/';
if ($handle = opendir($path)) {
    $find = 'access_log-web1.';
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
        	$ext = substr(strrchr($file, '.'), 1);
		if(is_numeric($ext)) $date_arr[] = $ext;
	}
    }
    closedir($handle);
}
//print_r($date_arr);
foreach($date_arr as $key => $val)
{
    $log_file = $path.'access_log-web1.'.$val;
    if(!file_exists($log_file))
        continue;
    $o->log_file = $log_file;
    $o->parse_log2txt();
    echo $val." - end\n";
}
*/





