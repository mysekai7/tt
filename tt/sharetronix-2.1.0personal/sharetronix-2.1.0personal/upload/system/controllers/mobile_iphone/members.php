<?php
	
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	if( $C->MOBI_DISABLED ) {
		$this->redirect('mobidisabled');
	}
		
	$this->load_langfile('mobile/global.php');
	$this->load_langfile('mobile/members.php');
	
	$D->shows	= array('following', 'followers', 'everybody');
	$D->show_admins	= FALSE;
	if( $this->network->is_public || ($this->network->is_private && $C->IS_CLAIMED) ) {
		$D->shows[]	= 'admins';
		$D->show_admins	= TRUE;
	}
	$D->show	= 'following';
	if( $this->param('show') && in_array($this->param('show'),$D->shows) ) {
		$D->show	= $this->param('show');
	}
	
	$tmp	= $db2->fetch('SELECT COUNT(*) AS u, SUM(is_network_admin=1) AS a FROM users WHERE active=1');
	$i_follow = $this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users;
	$my_followers = $this->network->get_user_follows($this->user->id, FALSE, 'hisfollowers')->followers;
	
	$D->nums	= array();
	$D->nums['following']	= count($i_follow);
	$D->nums['followers']	= count($my_followers);
	$D->nums['everybody']	= $tmp->u;
	$D->nums['admins']	= $tmp->a;
	
	$D->page_title	= $this->lang('members_page_title_'.$D->show, array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$D->num_results	= 0;
	$D->num_pages	= 0;
	$D->pg		= 1;
	$D->users_html	= '';
	
	if( $this->param('pg') ) {
		$D->pg	= intval($this->param('pg'));
		$D->pg	= max(1, $D->pg);
	}
	
	$tmp	= array();
	if( $D->show == 'everybody' ) {
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) FROM users WHERE active=1');
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		$db2->query('SELECT id FROM users WHERE active=1 ORDER BY id DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
		while($o = $db2->fetch_object()) {
			$tmp[]	= $o->id;
		}
	}
	elseif( $D->show == 'following' ) {
		$D->num_results	= count($i_follow);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		$tmp	= array_keys(array_slice($i_follow, $from, $C->PAGING_NUM_USERS, TRUE));
	}
	elseif( $D->show == 'followers' ) {
		$D->num_results	= count($my_followers);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		$tmp	= array_keys(array_slice($my_followers, $from, $C->PAGING_NUM_USERS, TRUE));
	}
	elseif( $D->show == 'admins' ) {
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) FROM users WHERE active=1 AND is_network_admin=1');
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		$db2->query('SELECT id FROM users WHERE active=1 AND is_network_admin=1 ORDER BY id DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);
		while($o = $db2->fetch_object()) {
			$tmp[]	= $o->id;
		}
	}
	unset($i_follow, $my_followers);
	
	$u	= array();
	foreach($tmp as $sdf) {
		if($sdf = $this->network->get_user_by_id($sdf)) {
			$u[]	= $sdf;
		}
	}
	if( count($u) > 0 ) {
		ob_start();
		$i	= 0;
		foreach($u as $tmp) {
			$D->u	= $tmp;
			$D->u->list_index	= $i++;
			$this->load_template('mobile_iphone/single_user.php');
		}
		$D->users_html	= ob_get_contents();
		ob_end_clean();
	}
	
	$this->load_template('mobile_iphone/members.php');
	
?>