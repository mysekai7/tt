<?php
	
	header('Content-type: text/plain; charset=UTF-8');
	ini_set('max_execution_time', 5);
	
	if( !$this->network->id ) {
		echo 'ERROR';
		exit;
	}
	if( !$this->user->is_logged ) {
		echo 'ERROR';
		exit;
	}
	$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
	if( 0 == $db2->num_rows() ) {
		echo 'ERROR';
		exit;
	}
	if( isset($C->AUTOCHECK_FOR_UPDATE) && $C->AUTOCHECK_FOR_UPDATE==0) {
		echo 'ERROR';
		exit;
	}
	$cachekey	= 'last_check4update_from_'.$C->VERSION;
	
	$lastcheck	= (object) array(
		'date'	=> 0,
		'message'	=> '',
	); 
	if( $tmp = $cache->get($cachekey) ) {
		$lastcheck	= $tmp;
	}
	
	if( $lastcheck->date > time()-6*60*60 ) {
		echo 'OK:'."\n";
		echo trim($lastcheck->message);
		exit;
	}
	
	$url	= 'http://www.sharetronix.com/sharetronix/versioncheck/?version='.urlencode($C->VERSION).'&site='.urlencode($C->SITE_URL).'&lang='.urlencode($C->LANGUAGE).'&type='.urlencode('personal');
	$res	= @file_get_contents($url);
	
	if( !$res || empty($res) ) {
		echo 'ERROR';
		exit;
	}
	$res	= trim($res);
	if( substr($res, 0, 3) != 'OK:' ) {
		echo 'ERROR';
		exit;
	}
	$res	= trim(substr($res, 3));
	$lastcheck->date	= time();
	$lastcheck->message	= $res;
	
	$cache->set($cachekey, $lastcheck, $C->CACHE_EXPIRE);
	
	echo 'OK:'."\n";
	echo $lastcheck->message;
	exit;
	
?>