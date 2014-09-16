<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if($C->PROTECT_OUTSIDE_PAGES && !$this->user->is_logged){
		$this->redirect('home');
	}
	
	require($C->INCPATH.'helpers/func_additional.php');
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/members.php');
	
	$tabs	= array('all', 'admins');
	if( $this->user->is_logged ) {
		$tabs[] = 'ifollow';
		$tabs[] = 'followers';
	}
	
	$D->tab		= 'all';
	if( $this->param('tab') && in_array($this->param('tab'), $tabs) ) {
		$D->tab	= $this->param('tab');
	}
	
	$D->page_title	= $this->lang('members_page_title_'.$D->tab, array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$D->num_results	= 0;
	$D->num_pages	= 0;
	$D->pg		= 1;
	$D->users_html	= '';
	
	$selected_users	= array();
	$user_ids		= array();
	$required_fields 	= array('email', 'username', 'fullname', 'avatar', 'num_posts', 'num_followers');
	
	if( $D->tab == 'all' ) {
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) AS u FROM users WHERE active=1');
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	=  ($this->user->is_logged)? (($D->pg - 1) * $C->PAGING_NUM_USERS) : 0;
		
		$required_fields[] = 'id';
		$db2->query('SELECT '.implode(',', $required_fields).' FROM users WHERE active=1 ORDER BY num_followers DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
		
		while($o = $db2->fetch_object()) {
			$selected_users[] = generate_user_info_obj($o);
			$user_ids[]	= $o->id;
		}
		
		$D->pg	= ($this->user->is_logged)? $D->pg : 1;
	}
	elseif( $D->tab == 'ifollow' ) {
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) AS u FROM users_followed WHERE who="'.$this->user->id.'"');
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		
		$required_fields[] = 'u.id';
		$db2->query('SELECT '.implode(',', $required_fields).' FROM users u, users_followed uf WHERE uf.whom=u.id AND who="'.$this->user->id.'" ORDER BY u.id DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);	
		while($o = $db2->fetch_object()) {
			$selected_users[] = generate_user_info_obj($o);
			$user_ids[]	= $o->id;
		}
	}
	elseif( $D->tab == 'followers' ) {
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) AS u FROM users_followed WHERE whom="'.$this->user->id.'"');
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		
		$required_fields[] = 'u.id';
		$db2->query('SELECT '.implode(',', $required_fields).' FROM users u, users_followed uf WHERE uf.who=u.id AND whom="'.$this->user->id.'" ORDER BY u.id DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);	
		while($o = $db2->fetch_object()) {
			$selected_users[] = generate_user_info_obj($o);
			$user_ids[]	= $o->id;
		}
	}
	elseif( $D->tab == 'admins' ) {
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) AS u FROM users WHERE is_network_admin="1"');
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		
		$required_fields[] = 'id';
		$db2->query('SELECT '.implode(',', $required_fields).' FROM users WHERE active=1 AND is_network_admin=1 ORDER BY id DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
		while($o = $db2->fetch_object()) {
			$selected_users[] = generate_user_info_obj($o);
			$user_ids[]	= $o->id;
		}
	}
	
	$D->if_follow_user = array();
	if($this->user->id && count($user_ids)>0 ){ 
		$db2->query('SELECT whom FROM users_followed WHERE whom IN('.implode(',', $user_ids).') AND who="'.$this->user->id.'"');
		while($o = $db2->fetch_object()){
			if( isset($D->if_follow_user[$o->whom]) ){
					continue;
			}
			$D->if_follow_user[$o->whom] = 1;
		}
	}
	
	if( 0 == $D->num_results ) {
		$arr	= array('#SITE_TITLE#'=>htmlspecialchars($C->OUTSIDE_SITE_TITLE));
		$D->noposts_box_title	= $this->lang('nousers_box_ttl_'.$D->tab, $arr);
		$D->noposts_box_text	= $this->lang('nousers_box_txt_'.$D->tab, $arr);
		$D->users_html	= $this->load_template('noposts_box.php', FALSE);
	}
	else {
		ob_start();
		foreach($selected_users as $tmp) {
			$D->u	= $tmp;
			$this->load_template('single_user.php');
		}
		$D->paging_url	= $C->SITE_URL.'members/tab:'.$D->tab.'/pg:';
		if( $D->num_pages > 1 ) {
			$this->load_template('paging_users.php');
		}
		$D->users_html	= ob_get_contents();
		ob_end_clean();
	}
	
	unset($followtmp, $tmp, $sdf, $u, $D->u, $selected_users, $user_ids, $required_fields);
	
	$D->leftcol_title	= $this->lang('os_members_left_title_'.$D->tab);
	$D->leftcol_text	= $this->lang('os_members_left_text_'.$D->tab.($D->num_results==1||$D->num_results==0?$D->num_results:''), array('#NUM#'=>$D->num_results,'#SITE_TITLE#'=>$C->OUTSIDE_SITE_TITLE));
	
	$this->load_template('members.php');
?>