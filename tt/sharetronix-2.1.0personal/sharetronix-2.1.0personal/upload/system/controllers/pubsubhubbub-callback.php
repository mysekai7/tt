<?php
	
	$info	= array();
	foreach(array($_POST,$_GET) as $arr) {
		foreach(array('hub.','hub_') as $pref) {
			foreach(array('mode','topic','challenge') as $attr) {
				if( !isset($info[$attr]) && isset($arr[$pref.$attr]) && !empty($arr[$pref.$attr]) ) {
					$info[$attr]	= $arr[$pref.$attr];
				}
			}
		}
	}
	$p	= new pubsubhubbub();
	$result	= $p->receive_notif($info);
	echo $result ? $result : 'ERROR';
	exit;
	
?>