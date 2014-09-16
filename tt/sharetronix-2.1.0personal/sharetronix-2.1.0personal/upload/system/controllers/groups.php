<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if($C->PROTECT_OUTSIDE_PAGES && !$this->user->is_logged){
		$this->redirect('home');
	}
	
	require($C->INCPATH.'helpers/func_additional.php');
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/groups.php');
	
	$tabs		= array('all');
	if( $this->user->is_logged ) {	
		$tabs[] 	= 'my';
		$tabs[] 	= 'special';	
	}
	$D->tab	= 'all';
	if( $this->param('tab') && in_array($this->param('tab'), $tabs) ) {
		$D->tab	= $this->param('tab');
	}
	$D->page_title	= $this->lang('groups_page_title_'.$D->tab, array('#COMPANY#'=>$C->COMPANY, '#SITE_TITLE#'=>$C->SITE_TITLE));	
	$D->i_am_network_admin	= ( $this->user->is_logged && $this->user->info->is_network_admin > 0 );	
	
	$not_in_groups	= array();
	if( !$D->i_am_network_admin ) {
		$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() );
	}
	$not_in_groups	= count($not_in_groups)>0 ? ('AND id NOT IN('.implode(', ', $not_in_groups).')') : '';
	
	$D->num_results	= 0;
	$D->num_pages	= 0;
	$D->pg		= 1;
	$D->groups_html	= '';
	
	$D->orderby	= 'date';
	$sql_orderby	= array(
		'name'	=> 'title ASC, num_followers DESC, num_posts DESC, id DESC',
		'date'	=> 'id DESC',
		'users'	=> 'num_followers DESC, num_posts DESC, id DESC',
		'posts'	=> 'num_posts DESC, num_followers DESC, id DESC',
	);
	if( $this->param('orderby') && isset($sql_orderby[$this->param('orderby')]) ) {
		$D->orderby	= $this->param('orderby');
	}
	
	$selected_groups	= array();
	$group_ids 		= array();
	if( $D->tab == 'all' )
	{
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) FROM groups WHERE 1 '.$not_in_groups);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_GROUPS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	=  ($this->user->is_logged)? (($D->pg - 1) * $C->PAGING_NUM_GROUPS) : 0;

		$db2->query('SELECT * FROM groups WHERE 1 '.$not_in_groups.' ORDER BY '.$sql_orderby[$D->orderby].' LIMIT '.$from.', '.$C->PAGING_NUM_GROUPS);
		while($o = $db2->fetch_object()) {
			$selected_groups[] = generate_group_info_obj($o);
			$group_ids[] 	 = $o->id; 
		}
		$D->pg = ($this->user->is_logged)? $D->pg : 1;
	}
	elseif( $D->tab == 'my' )
	{
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) AS u FROM groups_followed WHERE user_id="'.$this->user->id.'"');
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_GROUPS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_GROUPS;
		
		$db2->query('SELECT g.* FROM groups g, groups_followed gf WHERE gf.group_id=g.id AND user_id="'.$this->user->id.'" ORDER BY g.id DESC LIMIT '.$from.', '.$C->PAGING_NUM_USERS);	
		while($o = $db2->fetch_object()) {	
			$selected_groups[] = generate_group_info_obj($o);
			$group_ids[] 	 = $o->id; 
		}
	}elseif( $D->tab == 'special' )
	{
		$D->num_results	= $db2->fetch_field('SELECT COUNT(*) FROM groups WHERE is_special=1');
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_GROUPS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_GROUPS;
		
		$db2->query('SELECT * FROM groups WHERE is_special=1 ORDER BY '.$sql_orderby[$D->orderby].' LIMIT '.$from.', '.$C->PAGING_NUM_GROUPS);
		while($o = $db2->fetch_object()) {
			$selected_groups[] = generate_group_info_obj($o);
			$group_ids[] 	 = $o->id; 
		}
	}
	
	$D->if_can_leave 	= array();
	$g_admins 			= array();
	if( $this->user->id && count($group_ids)>0 ){
		$db2->query('SELECT * FROM groups_admins WHERE group_id IN('.implode(',', $group_ids).')');
		while($o = $db2->fetch_object() ){
			if( isset($g_admins[$o->group_id]) ){
				if( $o->user_id != $this->user->id ){
					$g_admins[$o->group_id] += 1;
				}
			}else{
				$g_admins[$o->group_id] = ($o->user_id == $this->user->id)? 0 : 1;
			}
		}
		foreach($g_admins as $gid => $num_members){
			// -1 == TRUE, you can leave
			//  1 == FALSE, could not leave
			$D->if_can_leave[$gid] = ($num_members>0)? -1 : 1;
		}
		unset($gmembers);
	}
	
	$D->if_follow_group = array();
	if($this->user->id && count($group_ids)>0){
		$db2->query('SELECT * FROM groups_followed WHERE group_id IN('.implode(',', $group_ids).') AND user_id="'.$this->user->id.'"');
		while($o = $db2->fetch_object()){
			if( isset($D->if_follow_group[$o->group_id]) ){
					continue;
			}
			$D->if_follow_group[$o->group_id] = 1;
		}
	}
	
	if( 0 == $D->num_results ) {
		$arr	= array('#SITE_TITLE#'=>htmlspecialchars($C->OUTSIDE_SITE_TITLE));
		$D->noposts_box_title	= $this->lang('nogroups_box_ttl_'.$D->tab, $arr);
		$D->noposts_box_text	= $this->lang('nogroups_box_txt_'.$D->tab, $arr);
		$D->groups_html	= $this->load_template('noposts_box.php', FALSE);
	}
	else {
		ob_start();
		foreach($selected_groups as $tmp) {
			$D->g	= $tmp;
			$this->load_template('single_group.php');
		}
		$D->paging_url	= $C->SITE_URL.'groups/tab:'.$D->tab.'/orderby:'.$D->orderby.'/pg:';
		if( $D->num_pages > 1 ) {
			$this->load_template('paging_groups.php');
		}
		$D->groups_html	= ob_get_contents();
		ob_end_clean();
		unset($tmp, $sdf, $g, $D->g, $D->if_follow_group, $D->if_can_leave);
	}
	
	$this->load_template('groups.php');
	
?>