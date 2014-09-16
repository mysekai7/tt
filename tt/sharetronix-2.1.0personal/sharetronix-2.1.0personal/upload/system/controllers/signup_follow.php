<?php
	
	$D->is_network	= $this->network->id ? TRUE : FALSE;
	
	if( ! $this->param('regid') ) {
		$this->redirect( $C->SITE_URL.'signup' );
	}
	$key	= 'reg_'.$this->param('regid');
	if( ! isset($_SESSION[$key]) ) {
		$this->redirect( $C->SITE_URL.'signup' );
	}
	$nid	= intval($_SESSION[$key]->network_id);
	$uid	= intval($_SESSION[$key]->user_id);
	if( $this->network->id && $nid!=$this->network->id ) {
		$this->redirect( $C->SITE_URL.'signup' );
	}
	if( ! $this->network->id ) {
		$this->network->LOAD_by_id($nid);
	}
	if( ! $this->network->id ) {
		$this->redirect( $C->SITE_URL.'signup' );
	}
	if( ! $u = $this->network->get_user_by_id($uid) ) {
		$this->redirect( $C->SITE_URL.'signup' );
	}
	$this->user->login($u->email, $u->password);
	
	if( isset($_POST['follow_users']) )
	{
		$users	= trim($_POST['follow_users'], ',');
		$users	= str_replace(',,', ',', $users);
		$users	= explode(',', $users);
		if( count($users) > 0 ) {
			$in_groups	= array(0);
			$db2->query('SELECT id FROM groups WHERE is_public=1');
			while($tmp = $db2->fetch_object()) {
				$in_groups[]	= intval($tmp->id);
			}
			$in_groups	= implode(', ', $in_groups);
			
			$protected_members = array_fill_keys($this->network->get_post_protected_user_ids(), 1);
			
			$insert_posts1	= array();
			$insert_posts2	= array();
			foreach($users as $tmp) {
				$tmp	= intval($tmp);
				if( ! $tmp || isset($protected_members[$tmp]) ) { continue; }
				$res	= $this->user->follow($tmp, TRUE);
				if( $res ) {
					$db2->query('SELECT id, api_id FROM posts WHERE user_id="'.$tmp.'" AND group_id IN('.$in_groups.') ');
					while($sdf = $db2->fetch_object()) {
						if( $sdf->api_id == 2 || $sdf->api_id == 6 ) {
							$insert_posts2[]	= intval($sdf->id);
						}
						else {
							$insert_posts1[]	= intval($sdf->id);
						}
					}
				}
			}
			sort($insert_posts1);
			sort($insert_posts2);
			if( count($insert_posts1) > 0 ) {
				$tmp	= array();
				foreach($insert_posts1 as $pid) {
					$tmp[]	= '("'.$this->user->id.'", "'.$pid.'")';
				}
				$tmp	= implode(', ', $tmp);
				$db2->query('INSERT INTO post_userbox (user_id, post_id) VALUES '.$tmp);
			}
			if( count($insert_posts2) > 0 ) {
				$tmp	= array();
				foreach($insert_posts2 as $pid) {
					$tmp[]	= '("'.$this->user->id.'", "'.$pid.'")';
				}
				$tmp	= implode(', ', $tmp);
				$db2->query('INSERT INTO post_userbox_feeds (user_id, post_id) VALUES '.$tmp);
			}
		}
		$this->redirect( $C->SITE_URL.'dashboard' );
	}
	
	$auto_select	= array();
	$db2->query('SELECT DISTINCT user_id FROM users_invitations WHERE recp_email="'.$u->email.'" ');
	while($obj = $db2->fetch_object()) {
		$auto_select[$obj->user_id]	= TRUE;
	}
	if( !isset($this->user->sess['selected_members']) ){
		$this->user->sess['selected_members'] = array();
	}
	$members = array();
	$q1 = 'SELECT COUNT(*) FROM users WHERE id<>"'.$uid.'" AND active=1';
	$q2 = 'SELECT id, username, fullname, avatar FROM users WHERE id<>"'.$uid.'" AND active=1 ORDER BY num_followers DESC ';
	
	foreach( $this->user->sess['selected_members'] as $k=>$v ){
		$auto_select[$k] = $v; 
	}

	$selected_members = array();
	if(count($auto_select) > 0){
		$db2->query('SELECT id, username, fullname, avatar FROM users WHERE id IN('.implode(',', array_keys($auto_select)).') ');
		
		while($obj = $db2->fetch_object()) {
			$selected_members[]	= array(
				intval($obj->id),
				$obj->username,
				$obj->fullname,
				"", 
				(($obj->avatar == '')? '_noavatar_user.gif' : $obj->avatar),
				1,
			);
		}
	}
	
	$D->num_results	= $db2->fetch_field($q1);
	$D->num_pages	= ceil($D->num_results / 45);
	$D->pg	= (isset($_GET['pg']) && is_numeric( trim($_GET['pg']) ))  ? intval($_GET['pg']) : 1;
	$D->pg	= min($D->pg, $D->num_pages);
	$D->pg	= max($D->pg, 1);
	$from	= ($D->pg - 1) * 45;
	$D->paging_url	= $C->SITE_URL.'signup/follow?pg=';
	
	$r	= $db2->query($q2.'LIMIT '.$from.', 45');
	
	$in_the_arr = array();
	while($obj = $db2->fetch_object($r)) {
		$members[]	= array (
			intval($obj->id),
			$obj->username,
			$obj->fullname,
			"", //$obj->position,
			(($obj->avatar == '')? '_noavatar_user.gif' : $obj->avatar),
			isset($auto_select[$obj->id]) ? 1 : 0,
		);
		$in_the_arr[$obj->id] = TRUE;
	}

	foreach($selected_members as $k=>$v){
		if(!isset($in_the_arr[$v[0]])){
			$members[] = $v;
		}
	}
	if( 0 == count($members) ) {
		$this->redirect( $C->SITE_URL.'dashboard' );
	}
	
	$D->members	= & $members;
	
	$this->load_langfile('outside/global.php');
	$this->load_langfile('outside/signup.php');
	$D->page_title	= $this->lang('signup_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$this->load_template('signup-step3.php');
	
?>