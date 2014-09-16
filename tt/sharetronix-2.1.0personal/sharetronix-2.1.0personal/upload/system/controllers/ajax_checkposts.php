<?php
	
	$current_time 		= time();
	$spam_period 		= 5*60; //5 minutes
	$allowed_post_numer	= 5;	//allowed post number in $spam_period 
	
	$post_number = $db2->fetch_field('SELECT COUNT(*) AS latest_posts_num FROM posts WHERE user_id="'.$db2->e($this->user->id).'" AND date>"'.($current_time-$spam_period).'" ORDER BY id DESC');
	if($post_number >= $allowed_post_numer){
		echo 'ERROR';
		return;
	}
	
	echo 'OK';
	return;
?>