<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$config = array(
    array(
        'name' => 'grab',
        'url' => 'http://rss.sina.com.cn/rollnews/finance/finance1_total.js'
    ),
);

foreach($config as $val) {
    $res = $val['name']($val['url']);
    //var_dump($res);
    //echo $res;
}

function grab($url) {
    $tmp = file_get_contents($url);
    $pattern = '/category:"国内财经",\s.*?title:"(.*?)",\s.*?"(.*?)",/is';
    preg_match_all($pattern, $tmp, $row);
    $arr = array();
    if($row[1]){
        foreach($row[1] as $key => $val){
            $arr[$key]['title'] = $row[1][$key];
            $arr[$key]['url'] = $row[2][$key];
        }
    }

    if($arr){
        foreach($arr as $key => $val){
            if(empty($val['title']) || empty($val['url']))
                continue;
            //echo $key."<br />\n";
            //flush();

            $tmp = '';
            $tmp = file_get_contents($val['url']);
            if(empty($tmp))
                continue;

            $arr[$key]['content'] = get_conetnt($tmp);

            if($key > 0)
                break;

        }
    }
}//end func

function get_conetnt($html){
    $html = preg_replace("|<head>.*?</head>|is", '', $html);
    echo $html;
    $pattern = '';
    //preg_match($pattern, $html, $row);
    //var_dump($row);
}

?>
