<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	if($this->param('app') == 'edit' && !isset($_GET['app_id']))
	{
		$this->redirect('api');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/api.php');
	
	require_once( $C->INCPATH.'helpers/func_api.php' );
	require_once( $C->INCPATH.'helpers/func_images.php' );
	
	$D->msg = '';
	$D->user_apps = false;
	$D->post_fields_error = false;
	$D->complete = false;
	
	if(isset($_POST['submit']) && $_POST['submit']==$this->lang('api_app_cnl')) $this->redirect('api');
	elseif(isset($_POST['submit']) && $_POST['submit']==$this->lang('api_app_save'))
	{		
		if($_POST['app_name'] == '')
		{
			$D->post_fields_error = true;
			$D->msg = $this->lang('api_name_err');	
		}
		elseif($_POST['description'] == '')
		{
			$D->post_fields_error = true;
			$D->msg = $this->lang('api_desc_err');
		}
		elseif($_POST['app_website'] == '' || !is_valid_url($_POST['app_website']))
		{
			$D->post_fields_error = true;
			$D->msg = $this->lang('api_app_web_err');
		}
		elseif($_POST['organization'] == '')
		{
			$D->post_fields_error = true;
			$D->msg = $this->lang('api_org_err');
		}
		elseif($_POST['app_type']=='browser' && ($_POST['callback_url'] == '' || !is_valid_url($_POST['callback_url'])))
		{
			$D->post_fields_error = true;
			$D->msg = $this->lang('api_callback_err');
		}
		if( isset($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name']) ) 
		{
			$f	= (object) $_FILES['avatar'];
			list($w, $h, $tp) = getimagesize($f->tmp_name);
			if( $w==0 || $h==0 ) {
				$D->post_fields_error	= TRUE;
				$D->msg	= $this->lang('api_inv_img');
			}
			elseif( $tp!=IMAGETYPE_GIF && $tp!=IMAGETYPE_JPEG && $tp!=IMAGETYPE_PNG ) {
				$D->error	= TRUE;
				$D->msg	= $this->lang('api_inv_format');
			}
			elseif( $w<$C->AVATAR_SIZE || $h<$C->AVATAR_SIZE ) {
				$D->post_fields_error	= TRUE;
				$D->msg	= $this->lang('api_inv_resolution');
			}
			else{
				$fn	= time().rand(100000,999999).'.png';
				$res	= copy_avatar($f->tmp_name, $fn);
				if( ! $res) {
					$D->post_fields_error	= TRUE;
					$D->msg	= $this->lang('api_copy_err');
			     }
			}
			if($this->param('app')=='edit' && !$D->post_fields_error ) 
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
			}
		}elseif($this->param('app') == 'new') $fn = $C->DEF_AVATAR_USER;
		elseif($this->param('app') == 'edit') $fn = $_POST['current_avatar'];
		if(!$D->post_fields_error)
		{	
			if(!isset($_POST['callback_url'])) $_POST['callback_url']='';
			if($this->param('app') == 'new')
			{	
				$app_id = rand(1000000, 9999999);
				$consumer_key = base64_encode('u='.$this->user->id.'&app_id='.$app_id.'&access='.$_POST['access_type']);
				
				$q = 'INSERT INTO applications(name, total_posts, app_id, user_id, consumer_key, consumer_secret, '; 
				$q .= 'callback_url, avatar, description, app_website, organization, website, app_type, acc_type, ';
				$q .= ' use_for_login, reg_date, reg_ip) VALUES(\''.$db2->e($_POST['app_name']).'\', 0, \''.$app_id.'\', ';
				$q .= ' \''.$this->user->id.'\', \''.$consumer_key.'\', \''.md5(rand().time().rand()).'\', ';
				$q .= ' \''.$db2->e($_POST['callback_url']).'\', \''.$fn.'\', \''.$db2->e($_POST['description']).'\', ';
				$q .= ' \''.$db2->e($_POST['app_website']).'\', \''.$db2->e($_POST['organization']).'\', ';
				$q .= ' "none", \''.$_POST['app_type'].'\', ';
				$q .= ' \''.$_POST['access_type'].'\', 1, \''.time().'\', \''.ip2long($_SERVER['REMOTE_ADDR']).'\')';
			}elseif($this->param('app') == 'edit')
			{
				$consumer_key = base64_encode('u='.$this->user->id.'&app_id='.$_GET['app_id'].'&access='.$_POST['access_type']);
				
				$q = 'UPDATE applications SET name=\''.$db2->e($_POST['app_name']).'\', consumer_key=\''.$consumer_key.'\', ';
				$q .= ' callback_url=\''.$db2->e($_POST['callback_url']).'\', description=\''.$db2->e($_POST['description']).'\', ';
				$q .= ' app_website=\''.$db2->e($_POST['app_website']).'\', organization=\''.$db2->e($_POST['organization']).'\', ';
				$q .= ' website="none", app_type=\''.$_POST['app_type'].'\', ';
				$q .= ' avatar=\''.$fn.'\', ';
				$q .= ' acc_type=\''.$_POST['access_type'].'\', reg_date=\''.time().'\', reg_ip=\''.ip2long($_SERVER['REMOTE_ADDR']).'\'';
				$q .= ' WHERE app_id=\''.$_GET['app_id'].'\' and user_id=\''.$this->user->id.'\'';
			}
			
			$res = $db2->query($q);
			if($db2->affected_rows())
			{
				$D->msg = $this->lang('api_saved_ok');
				$D->complete = true;
			}
			else $D->msg = $this->lang('api_saved_err');
		}
	}else
	{	
		if($this->param('app') == 'edit')
		{
			$res = $db2->query('SELECT * FROM applications WHERE app_id=\''.$_GET['app_id'].'\' AND user_id=\''.$this->user->id.'\'');
			if($db2->num_rows($res) > 0)
			{
				$app = $db2->fetch_object($res);
				$D->data['avatar'] = $app->avatar;
				$D->data['name'] = $app->name;
				$D->data['callback_url'] = $app->callback_url;
				$D->data['description'] = $app->description;
				$D->data['app_website'] = $app->app_website;
				$D->data['organization'] = $app->organization;
				$D->data['website'] = $app->website;
				$D->data['acc_type'] = $app->acc_type;
				$D->data['app_type'] = $app->app_type;
			}else $D->msg = 'Error loading your application!';
		}	
	}
	
	$res = $db2->query('SELECT name, description, app_id, avatar FROM applications WHERE user_id="'.$this->user->id.'" ');
	if($db2->num_rows($res) > 0)
	{
		$D->user_apps = true;
		$D->user_apps_info = array();
		$i = 0;
		while($row = $db2->fetch_object($res))
		{
			$D->user_apps_info[$i][0] = $row->name;
			$D->user_apps_info[$i][1] = $row->description;	
			$D->user_apps_info[$i][2] = $row->app_id;	
			$D->user_apps_info[$i][3] = $row->avatar;	
			$i++;
		}
	}else $D->user_apps = false;
	
	
	$this->load_template('api.php');
	
?>