<?php

    $C = new stdClass;
    $C->INCPATH = dirname(__FILE__).'/';    //system目录

    if(!file_exists($C->INCPATH.'conf_main.php')){
        exit;
    }

    require_once('conf_main.php');

    $C->DEBUG_MODE = in_array($_SERVER['REMOTE_ADDR'], $C->DEBUG_USERS);

    if($C->DEBUG_MODE){
        ini_set('error_reporting', E_ALL | E_STRICT);
        ini_set('display_errors', 1);
    }

    $C->IMG_URL = $C->SITE_URL.'i/';
    $C->IMG_DIR = $C->INCPATH.'../i/';
    $C->TMP_URL = $C->IMG_URL.'tmp/';
    $C->TMP_DIR = $C->IMG_DIR.'tmp/';

    $C->PAGING_NUM_USERS = 10;
    $C->PAGING_NUM_POSTS = 5;
    $C->PAGING_NUM_GROUPS = 10;
    $C->PAGING_NUM_COMMENTS = 5;

    $C->ATTACH_IMG_THUMBSIZE = 60;
    $C->ATTACH_IMG_MAXWIDTH = 600;
    $C->ATTACH_IMG_MAXHEIGHT = 500;

    $C->POST_ICONS = array(
        ':)' => 'icon_smile.gif',
        ':(' => 'icon_sad.gif',
        ';)' => 'icon_wink.gif',
        ':P' => 'icon_razz.gif',
        ':Р' => 'icon_razz.gif',
        ':D' => 'icon_biggrin.gif',
        ';(' => 'icon_cry.gif'
    );

    $C->THEME = 'default';

    $C->SITE_TITLE = 'CC';
    $C->OUTSIDE_SITE_TITLE = '';
    $C->DEF_LAGUAGE = $C->LANGUAGE;

    ini_set('magic_quotes_runtime', 0);
    ini_set('session.name', my_session_name($C->DOMAIN));
    ini_set('session.cache_expire', 300);
    ini_set('session.cookie_lifetime', 0);
    ini_set('session.cookie_path', '/');
    ini_set('session.cookie_domain', cookie_domain());
    ini_set('max_execution_time', 20);
    date_default_timezone_set('PRC');
    if(get_magic_quotes_gpc()){
        fix_input_quotes();
    }


    if(!function_exists('mb_internal_encoding')){
        require_once($C->INCPATH.'helpers/func_mbstring.php');
    }
    mb_internal_encoding('UTF-8');

    set_exception_handler("my_exception_handler");


?>
