<?php
	
	if( $this->network->id && $this->user->is_logged ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('outside/global.php');
	$this->load_langfile('outside/signin.php');
	
	$D->page_title	= $this->lang('signin_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	
	$D->allow_fb_connect	= FALSE;
	if( isset($C->FACEBOOK_API_ID, $C->FACEBOOK_API_SECRET) && !empty($C->FACEBOOK_API_ID) && !empty($C->FACEBOOK_API_SECRET) && function_exists('curl_init') && function_exists('json_decode') ) {
		$D->allow_fb_connect	= TRUE;
	}
	if( isset($C->TWITTER_CONSUMER_KEY,$C->TWITTER_CONSUMER_SECRET) && !empty($C->TWITTER_CONSUMER_KEY) && !empty($C->TWITTER_CONSUMER_SECRET) ) {
		$D->allow_fb_connect	= TRUE;
	}
	if( $D->allow_fb_connect ) {
		require_once( $C->INCPATH.'classes/class_facebook.php');
		$facebook = new Facebook(array(
			'appId'  => $C->FACEBOOK_API_ID,
			'secret' => $C->FACEBOOK_API_SECRET,
		));

		$D->fb_login_url = $facebook->getLoginUrl();
	}else{
		$D->fb_login_url = FALSE;
	}
	
	if( $D->allow_fb_connect ){
		if( $this->param('get')=='twitter' ) {
			if( isset($_SESSION['TWITTER_CONNECTED']) && $_SESSION['TWITTER_CONNECTED'] && $_SESSION['TWITTER_CONNECTED']->id ) {
				$uid	= intval($_SESSION['TWITTER_CONNECTED']->id);
				$db2->query('SELECT email, password FROM users WHERE twitter_uid<>"" AND twitter_uid="'.$uid.'" LIMIT 1');
				if($tmp = $db2->fetch_object()) {
					if( $this->user->login(stripslashes($tmp->email), stripslashes($tmp->password)) ) {
						$this->redirect($C->SITE_URL.'dashboard');
					}
				}
				$this->redirect($C->SITE_URL.'signup/get:twitter');
				exit;
			}
		}
	}
	
	
	$D->submit	= FALSE;
	$D->error	= FALSE;
	$D->errmsg	= '';
	$D->email		= '';
	$D->password	= '';
	$D->rememberme	= FALSE;
	
	if( isset($_POST['email'], $_POST['password']) ) {
		$D->submit	= TRUE;
		$D->email		= trim($_POST['email']);
		$D->password	= trim($_POST['password']);
		$D->rememberme	= isset($_POST['rememberme']) && $_POST['rememberme']==1;
		if( empty($D->email) || empty($D->password) ) {
			$D->error	= TRUE;
			$D->errmsg	= 'signin_form_errmsg';
		}
		else {
			if( $this->user->is_logged ) {
				$this->user->logout();
			}
			$res	= $this->user->login($D->email, md5($D->password), $D->rememberme);
			if( ! $res ) {
				$D->error	= TRUE;
				if( $this->network->id ) {
					$db2->query('SELECT id FROM users WHERE (email="'.$db2->e($D->email).'" OR username="'.$db2->e($D->email).'") AND password="'.$db2->e(md5($D->password)).'" AND active=0 LIMIT 1');
					if( $db2->num_rows() > 0 ) {
						$D->errmsg	= 'signin_form_errmsgsusp';
					}
				}
				if( empty($D->errmsg) ) {
					$D->errmsg	= 'signin_form_errmsg';
				}
			}
			else {
				if( $this->param('fbinfo') && isset($_SESSION[$this->param('fbinfo')]) ) {
					$fbinfo	= $_SESSION[$this->param('fbinfo')];
					$db2->query('UPDATE users SET facebook_uid="'.$db2->e($fbinfo->uid).'" WHERE id="'.intval($this->user->id).'" LIMIT 1');
					unset($_SESSION[$this->param('fbinfo')]);
				}
				if( isset($_SESSION['TWITTER_CONNECTED']) && $_SESSION['TWITTER_CONNECTED'] && $_SESSION['TWITTER_CONNECTED']->id ) {
					$uid	= intval($_SESSION['TWITTER_CONNECTED']->id);
					if( $uid != 0 ) {
						$db2->query('UPDATE users SET twitter_uid="'.$uid.'" WHERE id="'.intval($this->user->id).'" LIMIT 1');
					}
				}
				$this->redirect($C->SITE_URL.'dashboard');
				exit;
			}
		}
	}
	
	$D->num_members	= 0;
	$D->num_posts	= 0;
	if( $this->network->id ) {
		$D->num_members	= intval($db2->fetch_field('SELECT COUNT(id) FROM users WHERE active=1'));
		$D->num_posts	= intval($db2->fetch_field('SELECT COUNT(id) FROM posts WHERE user_id<>0 AND api_id<>2'));
	}
	
	$this->load_template('signin.php');
	
?>