<?php

	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	if(!isset($_GET['app_id'])){
		$this->redirect('api');
	}

	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/api.php');
	
	$D->error = false;
	$D->err_msg = '';
	$D->data = array();
	
	if(!isset($_POST['delete']))
	{
		$res = $db2->query('SELECT * FROM applications WHERE app_id="'.$_GET['app_id'].'" and user_id=\''.$this->user->id.'\'');
		if($db2->num_rows($res) > 0)
		{
			$app = $db2->fetch_object($res);
			$D->data['app_id'] = $app->app_id;
			$D->data['name'] = $app->name;
			$D->data['avatar'] = $app->avatar;
			$D->data['consumer_key'] = $app->consumer_key;
			$D->data['consumer_secret'] = $app->consumer_secret;
			$D->data['callback_url'] = $app->callback_url;
			$D->data['description'] = $app->description;
			$D->data['app_website'] = $app->app_website;
			$D->data['organization'] = $app->organization;
			if($app->acc_type == 'r') $D->data['acc_type'] = 'Read-Only';
			else $D->data['acc_type'] = 'Read & Write';
			$D->data['app_type'] = $app->app_type;
			$D->data['reg_date'] = date('j F Y G\h i\m s\s', $app->reg_date);
			$D->data['reg_ip'] = long2ip($app->reg_ip);
			$D->data['suspended'] = $app->suspended;
		}else
		{
			$D->error = true;	
			$D->err_msg = $this->lang('api_id_err');
		}
	}else
	{
		$q = 'SELECT avatar FROM applications WHERE user_id=\''.$this->user->id.'\' AND app_id=\''.$_GET['app_id'].'\'';
		$res = $db2->query($q);
		$old	= $db2->fetch_object($res);
		
		if( $old->avatar != $C->DEF_AVATAR_USER ) 
		{
			rm( $C->IMG_DIR.'avatars/'.$old->avatar );
			rm( $C->IMG_DIR.'avatars/thumbs1/'.$old->avatar );
			rm( $C->IMG_DIR.'avatars/thumbs2/'.$old->avatar );
			rm( $C->IMG_DIR.'avatars/thumbs3/'.$old->avatar );
		}
		
		$q = 'DELETE FROM applications WHERE app_id=\''.$_GET['app_id'].'\' AND user_id=\''.$this->user->id.'\' LIMIT 1';
		$res = $db2->query($q);
		if($res)
		{
			$D->msg = $this->lang('api_app_del');
			$D->complete = true;	
		}
		else $D->msg = $this->lang('api_app_del_err');	
		
	}
	
	$this->load_template('api_details.php');

?>
