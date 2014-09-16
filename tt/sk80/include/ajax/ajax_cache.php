<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('ROOT', substr(dirname(__FILE__), 0, -12));  //以/结尾

require_once(ROOT.'include/lib/FileCache.class.php');
$key = isset($_GET['key']) ? trim($_GET['key']) : '';


$cache = new FileCache();
$cache->cachePath = ROOT.'data/cache/';
//$data = $cache->get('yourkey');

if($key == 'categories') {
    $categories = '';
    $data = $cache->get('categories');
    
    if(count($data[0]) > 0) {
        foreach($data[0] as $val) {
            $categories .= "<li><a href=\"/category/{$val->slug}/\">{$val->name}</a> <small>({$val->count})</small></li>\n";
        }
    }
    echo $categories;
}

if($key == 'hot_tags') {
    $hot_tags = '';
    $data = $cache->get('hot_tags');
    if(count($data) > 0) {
        foreach($data as $val) {
            $hot_tags .= "<li><a href=\"/tag/{$val->name}/\">{$val->name}</a></li>\n";
        }
    }
    echo $hot_tags;
}



?>
