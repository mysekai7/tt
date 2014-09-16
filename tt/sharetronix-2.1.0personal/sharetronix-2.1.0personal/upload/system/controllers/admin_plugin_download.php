<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
	if( 0 == $db2->num_rows() ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	
	$D->page_title	= $this->lang('admmenu_pd_pg_ttl', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$sharetronix_web_site = 'http://www.sharetronix.com/sharetronix/addons/api?';
	
	$category = (isset($_POST['plugin_category']) && $_POST['plugin_category'] > 0) ? '&category='.$_POST['plugin_category'] : '';
	
	$req = curl_init();
	curl_setopt($req, CURLOPT_URL, $sharetronix_web_site.'site_url='.urlencode($C->SITE_URL).'&lang='.urlencode($C->LANGUAGE).'&site_version='.urlencode($C->VERSION).$category.'&type='.urlencode('personal'));
	curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
	$D->req_result = curl_exec($req);
	curl_close($req);
	
	if(!$D->req_result){
		$D->req_result = @file_get_contents($sharetronix_web_site.'site_url='.urlencode($C->SITE_URL).'&site_version='.urlencode($C->VERSION).$category.'&type='.urlencode('personal').'&lang='.urlencode($C->LANGUAGE));
	}

	$D->req_result = json_decode($D->req_result);
	$D->req_result = (is_array($D->req_result))? $D->req_result: array();

	$D->selected_option = (isset($_POST['plugin_category']))? $_POST['plugin_category']: 0;
	
	$this->load_template('admin_plugin_download.php');
	
?>