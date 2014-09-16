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
	
	$D->page_title	= $this->lang('admpgtitle_suspendapps', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$D->error = false;
	$D->errmsg = '';
	$D->submit = false;
	
	if(isset($_POST['suspend_app'], $_POST['app_id']) && is_numeric($_POST['app_id']))
	{
		$res = $db2->query('UPDATE applications SET suspended=1 WHERE app_id='.intval($_POST['app_id']).' LIMIT 1');
		if(!$db2->affected_rows($res))
		{
			$D->error = true;
			$D->errmsg = $this->lang('admsuspapp_inv_app_id');
		}
		$D->submit = true;
	}elseif(isset($_GET['restore']) && is_numeric($_GET['restore']))
	{
		$res = $db2->query('UPDATE applications SET suspended=0 WHERE app_id='.intval($_GET['restore']).' LIMIT 1');
		if(!$db2->affected_rows($res))
		{
			$D->error = true;
			$D->errmsg = $this->lang('admsuspapp_inv_app_id');
		}
		$D->submit = true;	
	}elseif( isset($_POST['suspend_app'], $_POST['app_id']) && ( empty($_POST['app_id']) || $_POST['app_id'] == '') )
	{
		$D->error = true;
		$D->errmsg = $this->lang('admsuspapp_missing_app_id');
		$D->submit = true;	
	}
	elseif(isset($_POST['suspend_app'], $_POST['app_id']) && !is_numeric($_POST['app_id']) || (isset($_GET['restore']) && !is_numeric($_GET['restore'])) )
	{
		$D->error = true;
		$D->errmsg = $this->lang('admsuspapp_fill_app_id');
		$D->submit = true;	
	}
	
	$D->apps	= array();
	$r	= $db2->query('SELECT app_id, name FROM applications WHERE suspended=1');
	$i = 0;
	while($app = $db2->fetch_object($r))
	{
		$D->apps[$i]['id'] = $app->app_id;
		$D->apps[$i]['name'] = $app->name;
		$i++;
	}
	
	$this->load_template('admin_suspendapps.php');
	
?>