<?php
error_reporting(E_ALL ^ E_NOTICE);

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Last-Modified: '.gmdate('D, d M Y H:i:s'). ' GMT');
header('Content-type:text/html; charset=utf-8');
ini_set('magic_quotes_runtime', 0);

//基本设置
$C = new stdClass;
$C->INCPATH = dirname(__FILE__).'/';
//$C->DOMAIN = '';
$C->SITE_URL = 'http://tongji.tootoo.com';
$C->DATA_DIR = $C->INCPATH.'data/';

require_once($C->INCPATH.'inc/func_main.php');
require_once($C->INCPATH.'inc/conf_main.php');

$tpl = new template;
$tpl->template_dir = $C->INCPATH.'theme/';
$tpl->assign('C', $C);
