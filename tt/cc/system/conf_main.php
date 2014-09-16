<?php

    $C->DOMAIN = 'cc.com';
    $C->SITE_URL = 'http://cc.com';

    $C->RNDKEY = '1205';

    $C->DB_HOST	= 'localhost';
    $C->DB_USER	= 'root';
    $C->DB_PASS	= '123456';
    $C->DB_NAME	= 'cc';
    $C->DB_MYEXT = 'mysql'; // 'mysqli' or 'mysql'
    $C->DB_PREFIX = '';

    $C->CACHE_CASE	= 'filesystem';	// 'apc' or 'memcached' or 'mysqlheap' or 'filesystem'
    $C->CACHE_EXPIRE		= 3600;
    $C->CACHE_KEYS_PREFIX	= '1205';

    // If 'memcached':
    $C->CACHE_MEMCACHE_HOST	= '';
    $C->CACHE_MEMCACHE_PORT	= '';

    // If 'filesystem':
    $C->CACHE_FILESYSTEM_PATH	= $C->INCPATH.'cache/';


    $C->LANGUAGE = 'cn';

    $C->CRONJOB_IS_INSTALLED	= FALSE;

    $C->USE_REWRITE = FALSE;

    $C->INSTALLED = TRUE;
    $C->VERSION	= '0.01';
    $C->DEBUG_USERS = array('127.0.0.1');

?>
