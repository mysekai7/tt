<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/settings.php');
	
	
	$D->page_title	= $this->lang('settings_conn_pagetitle', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$D->error = false;
	$D->errmsg = '';
	$D->okmsg = '';
	$D->submit = 0;
	$D->connections = array();
	$i = 0;
	
	if(isset($_GET['revoke']) && !empty($_GET['revoke']) && is_numeric($_GET['revoke']))
	{
		$D->submit = true;
		
		$q = 'UPDATE oauth_access_token SET user_verified=0 WHERE id='.intval($_GET['revoke']).' AND user_id='.$this->user->id.' LIMIT 1';
		$res = $db2->query($q);
		if(!$db2->affected_rows()) 
		{
			$D->error = true;
			$D->errmsg = $this->lang('st_conn_err');
		}else $D->okmsg = $this->lang('st_conn_ok');
	}elseif(isset($_GET['unrevoke']) && !empty($_GET['unrevoke']) && is_numeric($_GET['unrevoke']))
	{
		$D->submit = true;
		
		$q = 'UPDATE oauth_access_token SET user_verified=1 WHERE id='.intval($_GET['unrevoke']).' AND user_id='.$this->user->id.' LIMIT 1';
		$res = $db2->query($q);
		if(!$db2->affected_rows()) 
		{
			$D->error = true;
			$D->errmsg = $this->lang('st_conn_err1');
		}else $D->okmsg = $this->lang('st_conn_ok1');
	}
	
	$q = 'SELECT oauth_access_token.id AS oid, applications.name, applications.avatar, applications.description, applications.website, ';
	$q .= ' app_website, organization, applications.app_id, ';
	$q .= ' oauth_access_token.user_verified FROM applications, oauth_access_token WHERE applications.app_id = oauth_access_token.app_id ';
	$q .= '  AND applications.user_id = oauth_access_token.user_id AND oauth_access_token.user_id='.$this->user->id;
	$res = $db2->query($q);
	
	if($db2->num_rows($res) > 0) 
		while($connection = $db2->fetch_object($res))
		{
			$D->connections[$i]['oid'] = $connection->oid;
			$D->connections[$i]['app_id'] = $connection->app_id;
			$D->connections[$i]['name'] = $connection->name;
			$D->connections[$i]['avatar'] = $connection->avatar;
			$D->connections[$i]['description'] = $connection->description;
			$D->connections[$i]['website'] = $connection->website;
			$D->connections[$i]['app_website'] = $connection->app_website;
			$D->connections[$i]['organization'] = $connection->organization;
			$D->connections[$i]['verified'] = $connection->user_verified;
			$i++;
		}

	
	$this->load_template('settings_connections.php');
	
?>