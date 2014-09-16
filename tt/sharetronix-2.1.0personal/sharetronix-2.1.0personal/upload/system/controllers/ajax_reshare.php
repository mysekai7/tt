<?php
	
	if( !$this->network->id ) {
		echo 'ERROR';
		return;
	}
	if( !$this->user->is_logged ) {
		echo 'ERROR';
		return;
	}
	if( !isset($_POST['postid']) || !isset($_POST['type']) ) {
		echo 'ERROR';
		return;
	}
	
	$type	= TRUE;
	if( isset($_POST['type']) && ($_POST['type']=='on' || $_POST['type']=='off') ) {
		$type	= $_POST['type']=='off' ? FALSE : TRUE;
	}
	else {
		echo 'ERROR';
		return;
	}
	
	$p	= new post('public', $_POST['postid']);
	if( $p->error ) {
		echo 'ERROR';
		return;
	}
	
	if($type){
		if( $p->reshare_post() ) {
			echo 'OK';
			return;
		}
	}else{
		if( $p->unshare_post() ) {
			echo 'OK';
			return;
		}
	}
	
	echo 'ERROR';
	return;
	
?>