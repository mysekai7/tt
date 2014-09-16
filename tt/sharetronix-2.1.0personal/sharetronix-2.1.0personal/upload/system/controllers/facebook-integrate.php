<?php

	$error_parameters 	= array('error_reason' => 1, 'error' => 1, 'error_description' => 1);
	//$success_parameters 	= array('code');

	if( !isset($_GET['code']) &&  count(array_intersect_key($_GET, $error_parameters)) == 0 )
	{
		header('Location: https://www.facebook.com/dialog/oauth?client_id='.urlencode($C->FACEBOOK_API_ID).'&redirect_uri='.urlencode($C->SITE_URL.'facebook-integrate').'&scope=email,read_stream,publish_stream,offline_access');
		exit;
	}elseif( isset($_GET['code']) )
	{
		$html = 'https://graph.facebook.com/oauth/access_token?client_id='.urlencode($C->FACEBOOK_API_ID).'&redirect_uri='.urlencode($C->SITE_URL.'facebook-integrate').'&client_secret='.urlencode($C->FACEBOOK_API_SECRET).'&code='.urlencode($_GET['code']);
		
		$fb_call = curl_init();
		curl_setopt($fb_call, CURLOPT_URL, $html);
		curl_setopt($fb_call, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($fb_call, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($fb_call, CURLOPT_SSL_VERIFYHOST, FALSE);
		$fb_answer 	= curl_exec($fb_call);
		$fb_info 	= curl_getinfo($fb_call);
		curl_close($fb_call);
		
		if( $fb_info['http_code']==400 ){
			$fb_answer = json_decode($fb_answer);
			$D->noposts_box_title	= $fb_answer->error->type;	
			$D->noposts_box_text	= $fb_answer->error->message;
			
			$this->load_template('noposts_box.php');		
		}else{
			parse_str($fb_answer, $fb_answer);
			if( !isset($fb_answer['access_token']) ){
				$D->noposts_box_title	= $this->lang('global_fb_not_finished1_ttl');	
				$D->noposts_box_text	= $this->lang('global_fb_not_finished1_msg');
				
				$this->load_template('noposts_box.php');	
			}else{
				$html = 'https://graph.facebook.com/me?access_token='.urlencode($fb_answer['access_token']);
				
				$fb_access_token = $fb_answer['access_token'];
				
				$fb_call = curl_init();
				curl_setopt($fb_call, CURLOPT_URL, $html);
				curl_setopt($fb_call, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($fb_call, CURLOPT_HEADER, FALSE);
				curl_setopt($fb_call, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($fb_call, CURLOPT_SSL_VERIFYHOST, FALSE);
				$fb_answer 	= curl_exec($fb_call);
				$fb_info 	= curl_getinfo($fb_call);
				curl_close($fb_call);
				
				$fb_answer = json_decode($fb_answer);	
				if( $fb_info['http_code']==400 ){	
					$D->noposts_box_title	= $fb_answer->error->type;	
					$D->noposts_box_text	= $fb_answer->error->message;
					$this->load_template('noposts_box.php');
				}else{
					$check = $db2->fetch_field('SELECT 1 FROM users_details WHERE extrnlusr_facebook="'.$fb_answer->id.'" LIMIT 1');
					
					if(!$check){
						$res = $db2->fetch_field('SELECT 1 FROM users_details WHERE user_id="'.$this->user->id.'" LIMIT 1');
				
						if($res){
							$db2->query('UPDATE users_details SET integr_facebook="'.$db2->e($fb_access_token).'", extrnlusr_facebook="'.$db2->e($fb_answer->id).'" WHERE user_id="'.$this->user->id.'" LIMIT 1');
						}else{
							$db2->query('INSERT INTO users_details(`user_id`, `integr_facebook`, `extrnlusr_facebook`) VALUES("'.$this->user->id.'", "'.$db2->e($fb_access_token).'", "'.$db2->e($fb_answer->id).'")');
						}
				
						$this->network->get_user_by_id($this->user->id, TRUE);
						$this->network->get_user_details_by_id($this->user->id, TRUE);
					}
					$this->redirect('settings/integrations');
				}
			}
		}

	}elseif( count(array_intersect_key($_GET, $error_parameters)) > 0 ){
		foreach($error_parameters as $k=>$v){
			echo (isset($_GET[$k]))? $k.':'.$_GET[$k].'<br />': '';
		}
	}
?>