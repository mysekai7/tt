<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}elseif(!$this->user->is_logged){
		$this->redirect('signin');
	}

	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/search.php');
		
	$tabs	= array('posts', 'users', 'groups', 'tags');
	$D->tab	= 'posts';
	if( $this->param('tab') && in_array($this->param('tab'), $tabs) ) {
		$D->tab	= $this->param('tab');
	}
	if( isset($_POST['lookin']) && !empty($_POST['lookin']) && in_array($_POST['lookin'], $tabs) ){
		$D->tab	= trim($_POST['lookin']);
	}
	
	if( $this->param('posttag') ) { 
		$tmp	= str_replace('/', '', urldecode($this->param('posttag')));
		$this->redirect( $C->SITE_URL.'search/tab:tags/s:'.urlencode(trim($tmp)) );
	}
	
	$D->search_string = '';
	
	if( $this->param('usertag') ) {
		$D->tab	= 'users';
		$D->search_string = str_replace('/', '', urldecode(trim($this->param('usertag'))));
	}elseif( isset($_POST['lookfor']) && !empty($_POST['lookfor']) ) {
		$D->search_string = str_replace('/', '', $_POST['lookfor']);
	}elseif( $this->param('s') ){
		$D->search_string	= urldecode(trim($this->param('s')));
		$D->search_string	= preg_replace('/\s+/us', ' ', $D->search_string);
	}

	$D->page_title = $this->lang('srch_title_'.$D->tab, array('#SITE_TITLE#'=>$C->SITE_TITLE));
	$D->search_title = $this->lang( (empty($D->search_string)?'srch_title2_':'srch_title3_').$D->tab, array('#STRING#'=>htmlspecialchars(str_cut($D->search_string,30))));
	
	$D->num_results	= 0;
	$D->num_pages	= 0;
	$D->num_per_page	= 0;
	$D->pg	= 1;
	$D->posts_html	= '';
	$D->users_html	= '';
	$D->groups_html	= '';

	if( $D->tab=='users' )
	{
		$uids	= array();
		if( !empty($D->search_string) ){
			$tmp	= str_replace(array('%','_'), array('\%','\_'), $db2->e($D->search_string));
			
			$db2->query('SELECT id, username, fullname, position, num_followers, num_posts, avatar FROM users WHERE username LIKE "%'.$tmp.'%" OR fullname LIKE "%'.$tmp.'%"  ORDER BY username ASC');
			while($o = $db2->fetch_object()) {
				$uids[$o->id]	= array($o->id, $o->username, $o->fullname, $o->position, $o->num_followers, $o->num_posts, $o->avatar);
			}
			$db2->query('SELECT id, username, fullname, position, num_followers, num_posts, avatar FROM users WHERE tags REGEXP "(^|\,| )'.(preg_quote($tmp)).'($|\,)" ORDER BY username ASC');
			while($o = $db2->fetch_object()) {
				$uids[$o->id]	= array($o->id, $o->username, $o->fullname, $o->position, $o->num_followers, $o->num_posts, $o->avatar);
			}
		}

		$D->num_results	= count($uids);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		if( 0 == $D->num_results ) {
			$D->noposts_box_title	= $this->lang('srch_noresult_users_ttl');
			$D->noposts_box_text	= $this->lang('srch_noresult_users_txt');
			$D->users_html	= $this->load_template('noposts_box.php', FALSE);
		}
		else {
			$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
			$tmp	= array_slice($uids, $from, $C->PAGING_NUM_USERS, true);
			
			$D->if_follow_user = array();
			$user_ids = array_keys($tmp);
			if($this->user->id && count($user_ids)>0 ){ 
				$db2->query('SELECT whom FROM users_followed WHERE whom IN('.implode(',', $user_ids).') AND who="'.$this->user->id.'"');
				while($o = $db2->fetch_object()){
					if( isset($D->if_follow_user[$o->whom]) ){
							continue;
					}
					$D->if_follow_user[$o->whom] = 1;
				}
			}
			
			ob_start();
			foreach($tmp as $k=>$v) {
				$D->u = new stdClass;
				$D->u->id = $v[0];
				$D->u->username = $v[1];
				$D->u->fullname = $v[2];
				$D->u->position = $v[3];
				$D->u->num_followers = $v[4];
				$D->u->num_posts = $v[5];
				$D->u->avatar = (empty($v[6]))? $C->DEF_AVATAR_USER : $v[6];

				$this->load_template('single_user.php');
			}
			
			$D->paging_url	= $C->SITE_URL.'search/tab:users/s:'.urlencode($D->search_string).'/pg:';
			if( $D->num_pages > 1 ) {
				$this->load_template('paging_users.php');
			}
			$D->users_html	= ob_get_contents();
			ob_end_clean();
		}
	}
	elseif( $D->tab=='groups' )
	{
		$gids	= array();
		if( !empty($D->search_string) ){
			$not_in_groups	= array();
			if( !$this->user->is_logged || !$this->user->info->is_network_admin ) {
				$not_in_groups	= array();
				$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
			}
			$tmp	= str_replace(array('%','_'), array('\%','\_'), $db2->e($D->search_string));
			$db2->query('SELECT id, groupname, title, num_followers, num_posts, is_public, about_me, avatar FROM groups WHERE (groupname LIKE "%'.$tmp.'%" OR title LIKE "%'.$tmp.'%") '.((count($not_in_groups)>0)? 'AND id NOT IN('.implode(',', $not_in_groups).')' : '').' ORDER BY title ASC, num_followers DESC');
			while($o = $db2->fetch_object()) {
				$gids[$o->id]	= array($o->id, $o->groupname, $o->title, $o->num_followers, $o->num_posts, $o->is_public, $o->about_me, $o->avatar);
			}
		}
		
		$D->num_results	= count($gids);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_GROUPS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		if( 0 == $D->num_results ) {
			$D->noposts_box_title	= $this->lang('srch_noresult_groups_ttl');
			$D->noposts_box_text	= $this->lang('srch_noresult_groups_txt');
			$D->groups_html	= $this->load_template('noposts_box.php', FALSE);
		}
		else{
			$from	= ($D->pg - 1) * $C->PAGING_NUM_GROUPS;
			$tmp	= array_slice($gids, $from, $C->PAGING_NUM_GROUPS, true);
			ob_start();
			
			$D->if_can_leave 	= array();
			$g_admins 			= array();
			$group_ids			= array_keys($gids);
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
			unset($group_ids);
			
			foreach($tmp as $k=>$v) {
				$g = new stdClass;
				$g->id = $v[0]; 
				$g->groupname = $v[1]; 
				$g->title = $v[2];
				$g->num_followers = $v[3];
				$g->num_posts = $v[4];
				$g->is_public = $v[5];
				$g->is_private = ($g->is_public == 0);
				$g->about_me = $v[6];
				$g->avatar = (empty($v[7]))? $C->DEF_AVATAR_GROUP : $v[7];
				$D->g	= $g;
				$this->load_template('single_group.php');
			}
			
			$D->paging_url	= $C->SITE_URL.'search/tab:group/s:'.urlencode($D->search_string).'/pg:';
			if( $D->num_pages > 1 ) {
				$this->load_template('paging_groups.php');
			}
			$D->groups_html	= ob_get_contents();
			ob_end_clean();
		}
	}
	elseif( $D->tab == 'posts' )
	{
		if( $this->param('saved') ) {
			$tmp	= $db2->e(trim($this->param('saved')));
			$search_url = $db2->fetch_field('SELECT search_url FROM searches WHERE user_id="'.$this->user->id.'" AND search_key="'.$tmp.'" LIMIT 1');
			$this->redirect('http://'.$C->DOMAIN.$search_url);
		}

		$valid_ptypes = array('link','image','video','file','comments','rss','tweets');
		$D->ptypes_choosen = (isset($_POST['ptype']) && is_array($_POST['ptype']))? $_POST['ptype'] : array();
		if( $this->param('ptypes') ){
			$D->ptypes_choosen = explode(',', $this->param('ptypes'));
		}
		
		foreach($D->ptypes_choosen as $k=>$v){
			if(!in_array($v, $valid_ptypes)){
				unset($D->ptypes_choosen[$k]);
			}
		}
		
		$D->saved_searches	= array();
		$D->saved_searches	= $this->user->get_saved_searches();
		
		$D->paging_url = $C->SITE_URL.'search';
		$D->paging_url .= '/ptypes:'.implode(',', $D->ptypes_choosen);
		
		if( isset($_POST['puser']) || $this->param('puser') ){
			$puser = isset($_POST['puser'])? $_POST['puser'] : $this->param('puser');
			$puser = preg_replace('/[^a-z0-9-\_]/iu', '', $puser);
			$D->paging_url .= (!empty($puser))? '/puser:'.$puser : '';
		}else{
			$puser = '';
		}
		
		if( isset($_POST['pgroup']) || $this->param('pgroup') ){
			$pgroup = isset($_POST['pgroup'])? $_POST['pgroup'] : $this->param('pgroup');
			$pgroup = preg_replace('/[^ا-یא-תÀ-ÿ一-龥а-яa-z0-9\-\.\s]/iu', '', $pgroup);
			$D->paging_url .= (!empty($pgroup))? '/pgroup:'.$pgroup : '';
		}else{
			$pgroup = '';
		}
		
		if( isset($_POST['pdate1']) || $this->param('pdate1') ){
			$pdate1	= isset($_POST['pdate1'])? $_POST['pdate1'] : $this->param('pdate1');
			if( is_array($pdate1) ){
				$pdate1	= trim(implode(',', preg_replace('/[^0-9]/iu','',$pdate1)), ',');
			}
			if( ! preg_match('/^[0-9]{1,2}\,[0-9]{1,2}\,[0-9]{4}$/', $pdate1) ) { $pdate1 = ''; }
			$D->paging_url .= (!empty($pdate1))? '/pdate1:'.$pdate1 : '';
		}else{
			$pdate1 = '';
		}
		
		if( isset($_POST['pdate2']) || $this->param('pdate2') ){
			$pdate2	= isset($_POST['pdate2'])? $_POST['pdate2'] : $this->param('pdate2');
			if( is_array($pdate2) ){
				$pdate2	= trim(implode(',', preg_replace('/[^0-9]/iu','',$pdate2)), ',');
			}
			if( ! preg_match('/^[0-9]{1,2}\,[0-9]{1,2}\,[0-9]{4}$/', $pdate2) ) { $pdate2 = ''; }
			$D->paging_url .= (!empty($pdate2))? '/pdate2:'.$pdate2 : '';
		}else{
			$pdate2 = '';
		}
		
		if( isset($_POST['lookfor']) && !empty($_POST['lookfor']) ){
			$D->search_string = str_replace('/', '', $_POST['lookfor']);
			$D->search_string = str_replace('%', '', $_POST['lookfor']);
		}elseif($this->param('s')){
			$D->search_string = urldecode($this->param('s')); 
		}
		
		$D->paging_url .= '/s:'.$D->search_string;
		
		if(isset($_POST['puser']) || isset($_POST['pgroup']) || isset($_POST['pdate1']) || isset($_POST['pdate2']) || isset($_POST['lookfor']) ){
			$this->redirect($D->paging_url);
		}

		$D->form_user	= $puser;
		$D->form_group	= $pgroup;
		
		$D->box_expanded	= (object) array('type'=>FALSE, 'author'=>FALSE, 'group'=>FALSE, 'date'=>FALSE);
		if( count($D->ptypes_choosen) != 7 ) {
			$D->box_expanded->type		= TRUE;
		}
		if( isset($puser) && ! empty($puser) ) {
			$D->box_expanded->author	= TRUE;
		}
		if( isset($pgroup) && ! empty($pgroup) ) {
			$D->box_expanded->group	= TRUE;
		}
		if( (isset($pdate1) && ! empty($pdate1)) || (isset($pdate2) && ! empty($pdate2)) ) {
			$D->box_expanded->date		= TRUE;
		}

		$D->form_date1		= array('d'=>'', 'm'=>'', 'y'=>'');
		$D->form_date2		= array('d'=>'', 'm'=>'', 'y'=>'');
		if( isset($pdate1) && ! empty($pdate1) ) {
			list($D->form_date1['d'], $D->form_date1['m'], $D->form_date1['y']) = explode(',', $pdate1);
		}
		if( isset($pdate2) && ! empty($pdate2) ) {
			list($D->form_date2['d'], $D->form_date2['m'], $D->form_date2['y']) = explode(',', $pdate2);
		}
		$D->form_date1_days	= array('');
		for($i=1; $i<=31; $i++) { $D->form_date1_days[] = $i; }
		$D->form_date1_months	= array('');
		for($i=1; $i<=12; $i++) { $D->form_date1_months[] = $i; }
		$D->form_date1_years	= array();
		$tmp	= intval(date('Y'))-10;
		if( ! $tmp ) { $tmp = intval(date('Y')); }
		for($i=$tmp; $i<=intval(date('Y')); $i++) {
			$D->form_date1_years[]	= $i;
		}
		$D->form_date1_years[]	= '';
		$D->form_date1_years	= array_reverse($D->form_date1_years);
		$D->form_date2_days	= $D->form_date1_days;
		$D->form_date2_months	= $D->form_date1_months;
		$D->form_date2_years	= $D->form_date1_years;
		
		$D->can_be_saved	= FALSE;
		$D->error	= FALSE;
		$D->errmsg	= '';
		
		if( ! empty($D->search_string) ) { 
			$u	= FALSE;
			$g	= FALSE;
			if( !$D->error && !empty($puser) ) {
				if( ! $u = $this->network->get_user_by_username($puser) ) {
					$D->error	= TRUE;
					$D->errmsg	= $this->lang('srch_noresult_posts_invusr', array('#USERNAME#'=>'<b>'.htmlspecialchars($puser).'</b>'));
				}
			}
			if( !$D->error && !empty($pgroup) ) {
				if( ! $g = $this->network->get_group_by_name($pgroup) ) {
					$D->error	= TRUE;
					$D->errmsg	= $this->lang('srch_noresult_posts_invgrp', array('#GROUP#'=>'<b>'.htmlspecialchars($pgroup).'</b>'));
				}
			}
			if( !$D->error && $g && $g->is_private ) {
				if( ! in_array(intval($this->user->id), $this->network->get_group_invited_members($g->id)) ) {
					$g	= FALSE;
					$D->error	= TRUE;
					$D->errmsg	= $this->lang('srch_noresult_posts_invgrp', array('#GROUP#'=>'<b>'.htmlspecialchars($pgroup).'</b>'));
				}
			}
			$t1	= FALSE;
			$t2	= FALSE;
			if( !$D->error && (!empty($pdate1) || !empty($pdate2)) ) {
				if( ! empty($pdate1) ) {
					list($d,$m,$y) = explode(',', $pdate1);
					$t1	= mktime(0, 0, 1, $m, $d, $y);
					if( $t1 > time() ) {
						$D->error	= TRUE;
						$D->errmsg	= $this->lang('srch_noresult_posts_invdt');
					}
				}
				if( ! empty($pdate2) ) {
					list($d,$m,$y) = explode(',', $pdate2);
					$t2	= mktime(23, 59, 59, $m, $d, $y);
				}
				if( !$D->error && $t1 && $t2 && $t1>$t2 ) {
					$D->error	= TRUE;
					$D->errmsg	= $this->lang('srch_noresult_posts_invdt');
				}
			}
			if( !$D->error ) {
				$D->can_be_saved	= TRUE;
				$D->search_saved	= FALSE;
				$search_key	= md5($D->search_string."\n".serialize($D->ptypes_choosen)."\n".$puser."\n".$pgroup."\n".serialize($pdate1)."\n".serialize($pdate2));
				foreach( $D->saved_searches as $k=>$v ){
					if($v->search_key == $search_key){
						$D->search_saved = $v->id;
					}
				}
				
				$search_rss = (in_array('rss', $D->ptypes_choosen))? TRUE : FALSE;
				$search_tweets = (in_array('tweets', $D->ptypes_choosen))? TRUE : FALSE;
				
				if( !$search_rss && !$search_tweets ) $in_where = ' (user_id<>0) '; //search in everything
				elseif( $search_rss && !$search_tweets ) $in_where = '(api_id<>2 AND user_id<>0) ';
				elseif( !$search_rss && $search_tweets ) $in_where = '(api_id<>6 AND user_id<>0) ';
				else $in_where = ' (api_id<>6 AND api_id<>2 AND user_id<>0) ';//only posts, without rss and tweets
				
				$tmp	= str_replace(array('%','_'), array('\%','\_'), $db2->e($D->search_string));
				if( $tmp != '#' ) {
					$tmp	= preg_replace('/^\#/', '', $tmp);
				}
				$in_where	.= ' AND (message LIKE "%'.$tmp.'%"';
				if( mb_strlen($D->search_string)>=3 && FALSE!==strpos($D->search_string,' ') ) {
					$tmp	= preg_replace('/[^ا-یא-תÀ-ÿ一-龥а-яa-z0-9\s]/iu', '', $tmp);
					$tmp	= $db2->e($tmp);
					$tmp	= preg_replace('/\s+/iu', ' ', $tmp);
					$tmp	= preg_replace('/(^|\s)/iu', ' +', $tmp);
					$tmp	= trim($tmp);
					$in_where	.= ' OR MATCH(message) AGAINST("'.$tmp.'" IN BOOLEAN MODE)';
				}
				$search_in_comments	= FALSE;
				if( array_search('comments', $D->ptypes_choosen) !== FALSE ) {
					$search_in_comments	= TRUE;
					//unset($D->ptypes_choosen[$tmpci]);
					//$ptypes	= array_values($D->ptypes_choosen);
				}
				if( $search_in_comments ) {
					$in_where2	= '';
					$tmp	= str_replace(array('%','_'), array('\%','\_'), $db2->e($D->search_string));
					if( $tmp != '#' ) {
						$tmp	= preg_replace('/^\#/', '', $tmp);
					}
					$in_where2	.= 'message LIKE "%'.$tmp.'%"';
					if( mb_strlen($D->search_string)>=3 && FALSE!==strpos($D->search_string,' ') ) {
						$tmp	= preg_replace('/[^ا-یא-תÀ-ÿ一-龥а-яa-z0-9\s]/iu', '', $tmp);
						$tmp	= $db2->e($tmp);
						$tmp	= preg_replace('/\s+/iu', ' ', $tmp);
						$tmp	= preg_replace('/(^|\s)/iu', ' +', $tmp);
						$tmp	= trim($tmp);
						$in_where2	.= ' OR MATCH(message) AGAINST("'.$tmp.'" IN BOOLEAN MODE)';
					}
					$tmppids	= array();
					$db2->query('SELECT post_id FROM posts_comments WHERE '.$in_where2);
					while($tmp = $db2->fetch_object()) {
						$tmppids[]	= $tmp->post_id;
					}
					if( 1 == count($tmppids) ) {
						$in_where	.= ' OR id='.reset($tmppids);
					}
					elseif( 1 < count($tmppids) ) {
						$in_where	.= ' OR id IN('.implode(', ', $tmppids).')';
					}
				}
				$in_where	.= ')';
				if( $u ) {
					$in_where	.= ' AND user_id="'.$u->id.'"';
				}
				if( $g ) {
					$in_where	.= ' AND group_id="'.$g->id.'"';
				}
				else {
					$not_in_groups	= array();
					$without_users 	= array();
					if( !$this->user->is_logged || !$this->user->info->is_network_admin ) {
						$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
						$in_where		.= count($not_in_groups)>0 ? ('AND group_id NOT IN('.implode(', ', $not_in_groups).')') : '';
						
						
						$without_users 	= array_diff( $this->network->get_post_protected_user_ids(), $this->user->get_my_post_protected_follower_ids() ); 
						$in_where 		.= count($without_users)>0 ? (' AND (group_id>0 OR user_id NOT IN('.implode(', ', $without_users).'))') : '';	
					}
				}
				if( $t1 && $t2 ) {
					$in_where	.= ' AND date BETWEEN "'.$t1.'" AND "'.$t2.'"';
				}
				elseif( $t1 ) {
					$in_where	.= ' AND date>="'.$t1.'"';
				}
				elseif( $t2 ) {
					$in_where	.= ' AND date<="'.$t2.'"';
				}
				if( count($D->ptypes_choosen) == 0 ) {
					$in_where	.= ' AND attached=0';
				}
				elseif( count($D->ptypes_choosen) < 4 ) {
					$not_in_att	= array();
					$tmp	= array_flip($D->ptypes_choosen); 
					if( ! isset($tmp['link']) ) { $not_in_att[] = '"link"'; }
					if( ! isset($tmp['image']) ) { $not_in_att[] = '"image"'; }
					if( ! isset($tmp['video']) ) { $not_in_att[] = '"videoembed"'; }
					if( ! isset($tmp['file']) ) { $not_in_att[] = '"file"'; }
					$in_where	.= ' AND (attached=0 OR id NOT IN( SELECT DISTINCT post_id FROM posts_attachments WHERE `type`';
					if( count($not_in_att) == 1 ) {
						$in_where	.= '='.reset($not_in_att);
					}
					else {
						$in_where	.= ' IN('.implode(', ', $not_in_att).')';
					}
					$in_where	.= ' ))';
				}
				$D->num_results	= $db2->fetch_field('SELECT COUNT(*) FROM posts WHERE '.$in_where);
				$tmp_url	= trim($_SERVER['REQUEST_URI'], '/');
				$tmp_url	= preg_replace('/(^|\/)pg\:[^\/]*/iu', '', $tmp_url);
				$tmp_url	= preg_replace('/(^|\/)from\:[^\/]*/iu', '', $tmp_url);
				$tmp_url	= preg_replace('/(^|\/)r\:[^\/]*/iu', '', $tmp_url);
				$tmp_url	= preg_replace('/\/+/', '/', $tmp_url);
				$tmp_url	= '/'.trim($tmp_url, '/');
				$D->ajax_url	= str_replace('/search/', '/search/from:ajax/', $tmp_url);
				$D->paging_url	.= '/pg:'; 

				if( $this->param('from')=='ajax' && isset($_POST['savesearch']) ) {
					if( $_POST['savesearch']=='on' && !$D->search_saved ) {
						$db2->query('INSERT INTO searches SET user_id="'.$this->user->id.'", search_key="'.$search_key.'", search_string="'.$db2->e($D->search_string).'", search_url="'.$db2->e($tmp_url).'", added_date="'.time().'", total_hits=1, last_results="'.$D->num_results.'" ');
						echo 'OK:'.intval($db2->insert_id());
						return;
					}
					elseif( $_POST['savesearch']=='off' && $D->search_saved ) {
						$db2->query('DELETE FROM searches WHERE id="'.$D->search_saved.'" LIMIT 1');
						echo 'OK:0';
						return;
					}
					echo 'ERROR';
					return;
				}
				
				if( $D->num_results == 0 ) {
					$D->error	= TRUE;
					$D->errmsg	= $this->lang('srch_noresult_posts_def');
				}
				else {
					$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_POSTS);
					$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
					$D->pg	= min($D->pg, $D->num_pages);
					$D->pg	= max($D->pg, 1);
					$from	= ($D->pg - 1) * $C->PAGING_NUM_POSTS;
					$res	= $db2->query('SELECT *, "public" AS `type` FROM posts WHERE '.$in_where.' ORDER BY id DESC LIMIT '.$from.', '.$C->PAGING_NUM_POSTS);

					$tmpposts	= array();
					$tmpids	= array();
					$buff		= NULL;
					$postusrs	= array();
					while($obj = $db2->fetch_object($res)) {
						$buff = new post($obj->type, FALSE, $obj);
						if( $buff->error ) {
							continue;
						}
						if( $this->param('from')=='ajax' && $this->param('onlypost')!="" && $this->param('onlypost')!=$buff->post_tmp_id ) {
							continue;
						}
						$D->p	= $tmpposts[] = $buff;
						$tmpids[]	= $buff->post_tmp_id;
						$postusrs[]	= $buff->post_user->id;
					}
					unset($buff);
					$D->do_not_check_new_comments = TRUE;
					
					$D->if_follow_me = array();
					if( count($postusrs)>0 ){
						$r = $db2->query('SELECT whom FROM users_followed WHERE whom IN ('.implode(',', $postusrs).') AND who="'.$this->user->id.'"');
						while($o = $db2->fetch_object($r)){
							if( isset($D->if_follow_me[$o->whom]) ){
								continue;
							}
							$D->if_follow_me[$o->whom] = 1;
						}
					}
					$D->i_follow	= array_fill_keys(array_keys($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users), 1); 
					
					ob_start();
					foreach($tmpposts as $tmp) {
						$D->p	= $tmp;
						$D->post_show_slow	= FALSE;
						if( $this->param('from')=='ajax' && isset($_POST['lastpostdate']) && $D->p->post_date>intval($_POST['lastpostdate']) ) {
							$D->post_show_slow	= TRUE;
						}
						if( $this->param('from')=='ajax' && $this->param('onlypost')!="" && $this->param('onlypost')!=$D->p->post_tmp_id ) {
							continue;
						}
						$D->parsedpost_attlink_maxlen	= 75;
						$D->parsedpost_attfile_maxlen	= 71;
						if( isset($D->p->post_attached['image']) ) {
							$D->parsedpost_attlink_maxlen	-= 10;
							$D->parsedpost_attfile_maxlen	-= 12;
						}
						if( isset($D->p->post_attached['videoembed']) ) {
							$D->parsedpost_attlink_maxlen	-= 10;
							$D->parsedpost_attfile_maxlen	-= 12;
						}
						$right_post_type = (!$D->p->is_system_post && !$D->p->is_feed_post);
						
						$D->show_my_email = FALSE;
						if( isset( $D->if_follow_me[$D->p->post_user->id] ) || $D->p->post_user->id == $this->user->id || $this->user->info->is_network_admin ){
							$D->show_my_email = TRUE;
						}
						
						$D->protected_profile = FALSE;
						if($right_post_type && !$D->show_my_email && $D->p->post_user->is_profile_protected){
							$D->protected_profile = TRUE;
						}
						
						$D->show_reshared_design = ($D->p->post_resharesnum > 0);
						
						$this->load_template('single_post.php');
					}
					unset($D->p, $tmp, $tmpposts, $tmpids);
					
					if( $D->num_pages > 1 && !$this->param('onlypost') ) {
						$this->load_template('paging_posts.php');
					}
					$D->posts_html	= ob_get_contents();
					ob_end_clean();
				}

				if( $this->param('from') == 'ajax' ) {
					echo 'OK:';
					echo $D->posts_html;
					exit;
				}
			}
		}else{
			$D->error	= TRUE;
			$D->errmsg	= $this->lang('srch_noresult_posts_def');
		}
	}elseif($D->tab == 'tags'){
		$D->post_tags	= array();
		$D->post_tags	= $this->network->get_recent_posttags();
		
		$D->saved_searches	= array();
		$D->saved_searches	= $this->user->get_saved_searches();
		
		$D->paging_url = $C->SITE_URL.'search/tab:tags';
		
		if($this->param('s')){
			$D->search_string = urldecode($this->param('s')); 
		}
		
		$D->error	= FALSE;
		$D->errmsg	= '';
		
		if( ! empty($D->search_string) ) { 
			
			$tmp	= str_replace(array('%','_'), array('\%','\_'), $db2->e($D->search_string));
			if( $tmp != '#' ) {
				$tmp	= preg_replace('/^\#/', '', $tmp);
			}
			$D->search_string = $tmp;
			
			$in_where = '';
			$not_in_groups	= array();
			$without_users 	= array();
			if( !$this->user->is_logged || !$this->user->info->is_network_admin ) {
				$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
				$in_where		.= count($not_in_groups)>0 ? ('AND p.group_id NOT IN('.implode(', ', $not_in_groups).')') : '';
				
				$without_users 	= array_diff( $this->network->get_post_protected_user_ids(), $this->user->get_my_post_protected_follower_ids() ); 
				$in_where 		.= count($without_users)>0 ? (' AND (p.group_id>0 OR p.user_id NOT IN('.implode(', ', $without_users).'))') : '';	
			}
			
			$D->num_results	= $db2->fetch_field('SELECT COUNT(DISTINCT t.post_id) FROM post_tags t LEFT JOIN posts p ON p.id=t.post_id WHERE t.tag_name="'.$db2->e($D->search_string).'" '.$in_where.' ');
			$tmp_url	= trim($_SERVER['REQUEST_URI'], '/');
			$tmp_url	= preg_replace('/(^|\/)pg\:[^\/]*/iu', '', $tmp_url);
			$tmp_url	= preg_replace('/(^|\/)from\:[^\/]*/iu', '', $tmp_url);
			$tmp_url	= preg_replace('/(^|\/)r\:[^\/]*/iu', '', $tmp_url);
			$tmp_url	= preg_replace('/\/+/', '/', $tmp_url);
			$tmp_url	= '/'.trim($tmp_url, '/');
			$D->ajax_url	= str_replace('/search/', '/search/from:ajax/', $tmp_url);
			$D->paging_url	.= '/s:'.$D->search_string.'/pg:'; 
			
			if( $D->num_results == 0 ) {
				$D->error	= TRUE;
				$D->errmsg	= $this->lang('srch_noresult_posts_def');
			}
			else {
				$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_POSTS);
				$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
				$D->pg	= min($D->pg, $D->num_pages);
				$D->pg	= max($D->pg, 1);
				$from	= ($D->pg - 1) * $C->PAGING_NUM_POSTS;
				$res	= $db2->query('SELECT *, "public" AS `type` FROM post_tags t LEFT JOIN posts p ON p.id=t.post_id WHERE t.tag_name="'.$db2->e($D->search_string).'" '.$in_where.' GROUP BY t.post_id ORDER BY t.id DESC LIMIT '.$from.', '.$C->PAGING_NUM_POSTS);

				$tmpposts	= array();
				$tmpids	= array();
				$buff		= NULL;
				$postusrs	= array();
				while($obj = $db2->fetch_object($res)) {
					$buff = new post($obj->type, FALSE, $obj);
					if( $buff->error ) {
						continue;
					}
					if( $this->param('from')=='ajax' && $this->param('onlypost')!="" && $this->param('onlypost')!=$buff->post_tmp_id ) {
						continue;
					}
					$D->p	= $tmpposts[] = $buff;
					$tmpids[]	= $buff->post_tmp_id;
					$postusrs[]	= $buff->post_user->id;
				}
				unset($buff);
				$D->do_not_check_new_comments = TRUE;
				
				$D->if_follow_me = array();
				if( count($postusrs)>0 ){
					$r = $db2->query('SELECT whom FROM users_followed WHERE whom IN ('.implode(',', $postusrs).') AND who="'.$this->user->id.'"');
					while($o = $db2->fetch_object($r)){
						if( isset($D->if_follow_me[$o->whom]) ){
							continue;
						}
						$D->if_follow_me[$o->whom] = 1;
					}
				}
				$D->i_follow	= array_fill_keys(array_keys($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users), 1); 
				
				ob_start();
				foreach($tmpposts as $tmp) {
					$D->p	= $tmp;
					$D->post_show_slow	= FALSE;
					if( $this->param('from')=='ajax' && isset($_POST['lastpostdate']) && $D->p->post_date>intval($_POST['lastpostdate']) ) {
						$D->post_show_slow	= TRUE;
					}
					if( $this->param('from')=='ajax' && $this->param('onlypost')!="" && $this->param('onlypost')!=$D->p->post_tmp_id ) {
						continue;
					}
					$D->parsedpost_attlink_maxlen	= 75;
					$D->parsedpost_attfile_maxlen	= 71;
					if( isset($D->p->post_attached['image']) ) {
						$D->parsedpost_attlink_maxlen	-= 10;
						$D->parsedpost_attfile_maxlen	-= 12;
					}
					if( isset($D->p->post_attached['videoembed']) ) {
						$D->parsedpost_attlink_maxlen	-= 10;
						$D->parsedpost_attfile_maxlen	-= 12;
					}
					$right_post_type = (!$D->p->is_system_post && !$D->p->is_feed_post);
					
					$D->show_my_email = FALSE;
					if( isset( $D->if_follow_me[$D->p->post_user->id] ) || $D->p->post_user->id == $this->user->id || $this->user->info->is_network_admin ){
						$D->show_my_email = TRUE;
					}
					
					$D->protected_profile = FALSE;
					if($right_post_type && !$D->show_my_email && $D->p->post_user->is_profile_protected){
						$D->protected_profile = TRUE;
					}
					$D->show_reshared_design = ($D->p->post_resharesnum > 0);
					
					$this->load_template('single_post.php');
				}
				unset($D->p, $tmp, $tmpposts, $tmpids);
				
				if( $D->num_pages > 1 && !$this->param('onlypost') ) {
					$this->load_template('paging_posts.php');
				}
				$D->posts_html	= ob_get_contents();
				ob_end_clean();
			}

			if( $this->param('from') == 'ajax' ) {
				echo 'OK:';
				echo $D->posts_html;
				exit;
			}
		}else{
			$D->error	= TRUE;
			$D->errmsg	= $this->lang('srch_noresult_posts_def');
		}
		
	}else{
			$D->tab = 'posts';
			$D->error	= TRUE;
			$D->errmsg	= $this->lang('srch_noresult_posts_def');
			$D->can_be_saved	= FALSE;
	}
	
		
	$this->load_template('search.php');
	
?>