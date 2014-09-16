<?php
//phpinfo();
ini_set('error_reporting', E_ALL);
/* OO API */

$memcache_obj = new Memcache;

var_dump($memcache_obj);

/* connect to memcached server */
$memcache_obj->connect('127.0.0.1', 11211);

/*
set value of item with key 'var_key', using on-the-fly compression
expire time is 50 seconds
*/
$memcache_obj->set('var_key', 'some really big variable', MEMCACHE_COMPRESSED, 50);

echo $memcache_obj->get('var_key');
?>