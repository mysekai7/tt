<?php
/**
 *  文件:   index.php
 *  说明：  前台入口文件
 *  时间:   2010-1-29 下午
 */

//缓冲
if (extension_loaded('zlib')) {
	ob_start('ob_gzhandler');
} else {
	ob_start();
}

require_once('include/common.php');

$app = new Application;

Dispatcher::dispatch();

ob_end_flush();