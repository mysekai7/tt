<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/settings.php');
	
	$D->page_title	= $this->lang('settings_privacy_pagetitle', array('#SITE_TITLE#'=>$C->SITE_TITLE));

	$D->submit	= FALSE;
	
	if( isset($_POST['protect_profile']) || isset($_POST['protect_posts']) || isset($_POST['protect_dm']) ) {
		$D->submit	= TRUE;
		
		$protect_profile = (is_numeric($_POST['protect_profile']))? trim($_POST['protect_profile']) : 0;
		$protect_posts = (is_numeric($_POST['protect_posts']))? trim($_POST['protect_posts']) : 0;
		$protect_dm = (is_numeric($_POST['protect_dm']))? trim($_POST['protect_dm']) : 0;
		
		$db2->query('UPDATE users SET is_profile_protected="'.$db2->e($protect_profile).'", is_posts_protected="'.$db2->e($protect_posts).'", is_dm_protected="'.$db2->e($protect_dm).'" WHERE id="'.$this->user->id.'"');
		$this->network->get_user_by_id($this->user->id);
	}
	
	$privacy_settings = $db2->query('SELECT is_profile_protected, is_posts_protected, is_dm_protected FROM users WHERE id="'.$this->user->id.'"');
	$privacy_settings = $db2->fetch_object($privacy_settings);
	
	$D->profile_protect = $privacy_settings->is_profile_protected;
	$D->protect_posts = $privacy_settings->is_posts_protected;
	$D->protect_dm= $privacy_settings->is_dm_protected;
	
	$this->network->get_user_by_id($this->user->id, TRUE);
	$this->network->get_post_protected_user_ids(TRUE);
	$this->user->get_my_post_protected_follower_ids(TRUE);
	
	$this->load_template('settings_privacy.php');
	
?>