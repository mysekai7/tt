<?php

    $SCRIPT_BEGIN_TIME = microtime(TRUE);

    chdir(dirname(__FILE__));//system

    require_once('./helpers/func_main.php');
    require_once('conf_system.php');

    session_start();

    $cache = new cache;
    $db = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME);

    if(!$C->INSTALLED){
        exit;
    }

    $network = new network();
    $network->load();

    $user = new user();
    $user->load();

    if (extension_loaded('zlib')) {
            ob_start('ob_gzhandler', 6);
    } else {
            ob_start();
    }

    $page = new page();
    $page->load();
