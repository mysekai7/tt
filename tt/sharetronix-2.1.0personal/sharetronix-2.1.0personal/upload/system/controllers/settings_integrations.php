<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/settings.php');

	$D->integration = new stdClass;
	$D->integration->twitter 	= FALSE;
	$D->integration->facebook 	= FALSE;
	
	$D->udtls = $this->network->get_user_details_by_id(intval($this->params->user));
	$D->udtls = ($D->udtls === FALSE)? array() : $D->udtls;
	
	if( isset($_POST['twit_intgr_remove']) ){
		$db->query('UPDATE users_details SET integr_twitter="", extrnlusr_twitter="" WHERE user_id="'.$this->user->id.'" LIMIT 1');
		$this->network->get_user_by_id($this->user->id, TRUE);
		$this->network->get_user_details_by_id($this->user->id, TRUE);
		$this->redirect('settings/integrations');
	}
	if( isset($_POST['fb_intgr_remove']) ){
		$db->query('UPDATE users_details SET integr_facebook="", extrnlusr_facebook="" WHERE user_id="'.$this->user->id.'" LIMIT 1');
		$this->network->get_user_by_id($this->user->id, TRUE);
		$this->network->get_user_details_by_id($this->user->id, TRUE);
		$this->redirect('settings/integrations');
	}
	
	if($D->udtls && $D->udtls->integr_twitter != '' ){
		$D->integration->twitter = TRUE;
	}
	if($D->udtls && $D->udtls->integr_facebook != '' ){
		$D->integration->facebook = TRUE;
	}

	if( function_exists('curl_init') && function_exists('json_encode') && function_exists('json_decode') && $C->TWITTER_CONSUMER_KEY != '' && $C->TWITTER_CONSUMER_SECRET!=''  ) {
		$D->integration->tw_err 	= $this->lang('settings_twit_opt_integrate', array('#SITE_URL#' => $C->SITE_URL));
	}else{
		$D->integration->tw_err = $this->lang('settings_twit_opt_not_supported');;
	}
	
	if( function_exists('curl_init') && function_exists('json_encode') && function_exists('json_decode') && $C->FACEBOOK_API_KEY != '' && $C->FACEBOOK_API_ID!='' && $C->FACEBOOK_API_SECRET!='') {
		$D->integration->fb_err 	= $this->lang('settings_fb_opt_integrate', array('#SITE_URL#' => $C->SITE_URL));
	}else{
		$D->integration->fb_err 	= $this->lang('settings_fb_opt_not_supported');;
	}
	
	$this->load_template('settings_integrations.php');
?>