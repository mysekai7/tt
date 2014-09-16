<?php
	$twitter_tag  = $db2->fetch_field('SELECT value FROM settings WHERE word="POST_FROM_TWITTER_TAG" LIMIT 1');
	$last_post_id = $db2->fetch_field('SELECT value FROM settings WHERE word="LAST_TWITTER_POST_ID" LIMIT 1');
	
	global $C;
	
	require_once( $C->INCPATH.'helpers/func_api.php' );
	require_once( $C->INCPATH.'helpers/func_additional.php' );

	if( $twitter_tag != '0' && !empty($twitter_tag) ){
		$since_id = ( $last_post_id != '0' && !empty($last_post_id) ) ? '&since_id='.$last_post_id:'';

		$http = curl_init();
		curl_setopt($http, CURLOPT_URL, 'http://search.twitter.com/search.json?q=%23'.$twitter_tag.'&result_type=recent&rpp=100'.$since_id);
		curl_setopt( $http, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $http, CURLOPT_HEADER, FALSE);
		$twitter = curl_exec($http);
		curl_close($http);
		
		$twitter = json_decode($twitter, true);
		$posts_id = array();
		
		foreach($twitter['results'] as $twit){
			$posts_id[] = $twit['id_str'];	

			if($tmpu = $network->get_user_by_twitter_username($twit['from_user'])){
				$p	= null;
				$p	= new newpost();
				$p->set_api_id( get_app_id('twitter') );
				$tmpu->info	= $tmpu;
				$tmpu->is_logged	= TRUE;
				$p->set_user_advanced( $network,  $tmpu );
				$p->set_message( preg_replace( '/#'.$twitter_tag.'/iu', '', $twit['text'] ) );
				$p->save();
			}
		}

		
		if( count($posts_id)>0 ){
			if( is_64bit() ){
				$max_post_id = max($posts_id);	
			}else{
				$numb = new bigcompare();
	
				//$numb->try_new_candidate(1234);
				//$numb->try_new_candidate(1234);
				$numb->try_new_candidate_by_array($posts_id);
				$max_post_id = $numb->get_biggest();
			}
		}else{
			$max_post_id = 0;
		}
		
		$update_value = FALSE;
		
		if( is_64bit() ){
			if( $last_post_id < $max_post_id ){
				$update_value = TRUE;
			}
		}else{
			$last = new bigcompare();
	
			$last->try_new_candidate($last_post_id);
			$last->try_new_candidate($max_post_id);
			$max_post_id = $last->get_biggest();
			
			if( $last_post_id != $max_post_id ){
				$update_value = TRUE;
			}

		}
		if( $update_value ){
			$db2->query('UPDATE settings SET value="'.($max_post_id).'" WHERE word="LAST_TWITTER_POST_ID" LIMIT 1');
		} 
	}
?>