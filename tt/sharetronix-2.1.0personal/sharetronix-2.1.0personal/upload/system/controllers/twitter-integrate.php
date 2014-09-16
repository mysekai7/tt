<?php

	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	
	if( !function_exists('curl_init') ) {
		$this->redirect('dashboard');
	}elseif( $C->TWITTER_CONSUMER_KEY == '' || $C->TWITTER_CONSUMER_SECRET=='' ){
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('inside/global.php');
	
	require_once( $C->INCPATH.'helpers/func_additional.php' );

	define("REQUEST_TOKEN_URL", "https://api.twitter.com/oauth/request_token");
	define("ACCESS_TOKEN_URL", "https://api.twitter.com/oauth/access_token");
	define("AUTHORIZE_URL", "https://api.twitter.com/oauth/authorize");
	define("AUTHENTICATE_URL", "https://api.twitter.com/oauth/authenticate");
	define("UPDATE_STATUS_URL", "https://api.twitter.com/1/statuses/update.json");
	
	if(!isset($_GET['oauth_token']))
	{
		$request_parameters = array(
			'oauth_consumer_key' 		=> $C->TWITTER_CONSUMER_KEY,
			'oauth_signature_method'	=> 'HMAC-SHA1',
			'oauth_timestamp'			=> time(),
			'oauth_nonce'			=> (md5(rand().time().rand())),
			'oauth_version'			=> '1.0',
			'oauth_callback'			=> $C->SITE_URL.'twitter-integrate'
		);	
		$params = normalize_oauth_params($request_parameters);
		$signature = base64_encode(hash_hmac('sha1', 'GET&'.urlencode(utf8_encode(REQUEST_TOKEN_URL)).'&'.urlencode(utf8_encode($params)), $C->TWITTER_CONSUMER_SECRET.'&', true)); 
		
		$request_parameters['oauth_signature'] = $signature;
		$params = normalize_oauth_params($request_parameters);
				
		$call_twitter = curl_init();
		curl_setopt($call_twitter, CURLOPT_URL, REQUEST_TOKEN_URL.'?'.$params);
		curl_setopt($call_twitter, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($call_twitter, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($call_twitter, CURLOPT_SSL_VERIFYHOST, FALSE);
		$consumer = curl_exec($call_twitter);
		curl_close($call_twitter);

		parse_str($consumer, $consumer);

		if(isset($consumer['oauth_token_secret'],  $consumer['oauth_token'])){
			$this->user->sess['oauth_token_secret'] = $consumer['oauth_token_secret'];
			header('Location: '.AUTHENTICATE_URL.'?oauth_token='.$consumer['oauth_token']);
			exit;
		}else{
			$D->noposts_box_title	= $this->lang('global_twit_not_finished1_ttl');	
			$D->noposts_box_text	= $this->lang('global_twit_not_finished1_msg');
			
			$this->load_template('noposts_box.php');	
		}
	}elseif(isset($_GET['oauth_verifier']))
	{
		$request_parameters = array(
			'oauth_consumer_key' 		=> $C->TWITTER_CONSUMER_KEY,
			'oauth_signature_method'	=> 'HMAC-SHA1',
			'oauth_timestamp'			=> time(),
			'oauth_token'			=> $_GET['oauth_token'],
			'oauth_nonce'			=> (md5(rand().time().rand())),
			'oauth_version'			=> '1.0',
		);	
		$params 	= normalize_oauth_params($request_parameters);	
		$signature  = base64_encode(hash_hmac('sha1', 'GET&'.urlencode(utf8_encode(ACCESS_TOKEN_URL)).'&'.encode_rfc3986($params), $C->TWITTER_CONSUMER_SECRET.'&'.$this->user->sess['oauth_token_secret'], true)); 

		$call_twitter = curl_init();
		curl_setopt($call_twitter, CURLOPT_URL, ACCESS_TOKEN_URL.'?'.$params.'&oauth_signature='.$signature);
		curl_setopt($call_twitter, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($call_twitter, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($call_twitter, CURLOPT_SSL_VERIFYHOST, FALSE);
		$consumer = curl_exec($call_twitter);
		curl_close($call_twitter);
		
		parse_str($consumer, $consumer);
		
		if(is_array($consumer) && isset($consumer['oauth_token'], $consumer['oauth_token_secret'], $consumer['user_id'], $consumer['screen_name'])){
			$check = $db2->fetch_field('SELECT 1 FROM users_details WHERE extrnlusr_twitter="'.$consumer['screen_name'].'" LIMIT 1');
			
			if(!$check){
				$res = $db2->fetch_field('SELECT 1 FROM users_details WHERE user_id="'.$this->user->id.'" LIMIT 1');
				if($res){
					$db2->query('UPDATE users_details SET integr_twitter=\''.json_encode($consumer).'\', extrnlusr_twitter="'.$db2->e($consumer['screen_name']).'" WHERE user_id="'.$this->user->id.'" LIMIT 1');
				}else{
					$db2->query('INSERT INTO users_details(`user_id`, `integr_twitter`, `extrnlusr_twitter`) VALUES("'.$this->user->id.'", \''.json_encode($consumer).'\', "'.$db2->e($consumer['screen_name']).'")');
				}
				
				$this->network->get_user_by_id($this->user->id, TRUE);
				$this->network->get_user_by_twitter_username($consumer['screen_name'], TRUE);
				$this->network->get_user_details_by_id($this->user->id, TRUE);
			}
			
			$this->redirect('settings/integrations');
		}else{
			$D->noposts_box_title	= $this->lang('global_twit_not_finished2_ttl');	
			$D->noposts_box_text	= $this->lang('global_twit_not_finished2_msg');
			
			$this->load_template('noposts_box.php');	
		}
	}
?>