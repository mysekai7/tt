<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}elseif($C->PROTECT_OUTSIDE_PAGES && !$this->user->is_logged){
		$this->redirect('home');
	}
	
	$u = $this->network->get_user_by_id(intval($this->params->user));
	if( !$u ){
		$this->redirect('dashboard');
	}
	$D->udtls = $this->network->get_user_details_by_id(intval($this->params->user));
	$D->udtls = ($D->udtls === FALSE || empty($D->udtls))? array() : $D->udtls;
	
	require($C->INCPATH.'helpers/func_additional.php');
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/user.php');
	
	$D->page_title	= $u->username.' - '.$C->SITE_TITLE;
	$D->page_favicon	= $C->IMG_URL.'avatars/thumbs2/'.$u->avatar;
	
	$D->is_my_profile	= ($this->user->is_logged && $u->id==$this->user->id);
	$he_follows 	= $this->network->get_user_follows($u->id, FALSE, 'hefollows')->follow_users;
	if( $this->user->is_logged ){
		$i_follow 	= ( !$D->is_my_profile )? $this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users : $he_follows;
	}else{
		$i_follow 	= array();	
	}
		
	$D->usr					= & $u;
	$D->i_follow_him 		= $this->user->is_logged ? isset($i_follow[$u->id]) : FALSE;
	$D->he_follows_me 		= $this->user->is_logged ? ( $D->is_my_profile || isset($he_follows[$this->user->id]) ) : FALSE;
	$D->i_am_network_admin	= ( $this->user->is_logged && $this->user->info->is_network_admin );
	$D->profile_protected 	= ( $u->is_profile_protected && !$D->he_follows_me && !$D->i_am_network_admin );
	$D->posts_protected 	= ( $u->is_posts_protected && !$D->he_follows_me && !$D->i_am_network_admin );
	
	$not_in_groups = array();	
	if( !$D->i_am_network_admin ) {
		$not_in_groups	= array();
		$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
		//$not_in_groups	= count($not_in_groups)>0 ? ('AND p.group_id NOT IN('.implode(', ', $not_in_groups).')') : '';
	}
	
	$tabs	= array('updates');
	if( !$D->profile_protected ){
		$tabs[] = 'info';
		$tabs[] = 'coleagues';
		$tabs[] = 'groups';
	}
	
	$D->tab	= 'updates';
	if( $this->param('tab') && in_array($this->param('tab'), $tabs) ) {
		$D->tab	= $this->param('tab');
	}
	
	if( $D->is_my_profile ) {
		$D->rss_feeds	= array(
			array( $C->SITE_URL.'rss/my:dashboard',	$this->lang('rss_mydashboard',array('#USERNAME#'=>$this->user->info->username)), ),
			array( $C->SITE_URL.'rss/my:posts',		$this->lang('rss_myposts',array('#USERNAME#'=>$this->user->info->username)), ),
			array( $C->SITE_URL.'rss/my:private',	$this->lang('rss_myprivate',array('#USERNAME#'=>$this->user->info->username)), ),
			array( $C->SITE_URL.'rss/my:mentions',	$this->lang('rss_mymentions',array('#USERNAME#'=>$this->user->info->username)), ),
			array( $C->SITE_URL.'rss/my:bookmarks',	$this->lang('rss_mybookmarks',array('#USERNAME#'=>$this->user->info->username)), ),
		);
	}
	elseif( !$D->posts_protected ) {
		$D->rss_feeds	= array(
			array( $C->SITE_URL.'rss/username:'.$u->username,	$this->lang('rss_usrposts',array('#USERNAME#'=>$u->username)), ),
		);
	}
	
	if( !$D->profile_protected ){
		
		$D->usr->details = array();
		if( isset($D->udtls) ){
			$D->usr->details['website'] 			= ( isset($D->udtls->website) && $D->udtls->website!='' )? stripslashes($D->udtls->website) : '';
			$D->usr->details['personal_email'] 		= ( $D->he_follows_me && isset($D->udtls->personal_email) && $D->udtls->personal_email!='' )?  stripslashes($D->udtls->personal_email) : '';	
			$D->usr->details['work_phone'] 			= ( isset($D->udtls->work_phone) && $D->udtls->work_phone!='' )?  stripslashes($D->udtls->work_phone) : '';	
			$D->usr->details['personal_phone'] 		= ( isset($D->udtls->personal_phone) && $D->udtls->personal_phone!='' )?  stripslashes($D->udtls->personal_phone) : '';
			
			if( $D->usr->details['website']=='http://' || $D->usr->details['website']=='https://' || $D->usr->details['website']=='ftp://' ) {
				$D->usr->details['website']	= '';
			}
		}
		
		$D->qr = new qrcode();
		if(isset($u->fullname)) $D->qr->fn($u->fullname);
		if(isset($u->username)) $D->qr->nn('@'.$u->username);
		if( isset($D->usr->details['personal_email']) && !empty($D->usr->details['personal_email']) && $D->he_follows_me ) $D->qr->email($D->usr->details['personal_email']);
		if( isset($D->usr->details['work_phone']) && !empty($D->usr->details['work_phone'])) $D->qr->work_phone($D->usr->details['work_phone']);
		if( isset($D->usr->details['personal_phone']) && !empty($D->usr->details['personal_phone'])) $D->qr->home_phone($D->usr->details['personal_phone']);
		$D->qr->url($C->SITE_URL.$u->username);
		$D->qr->finish();
		
		$D->post_tags	= array();
		$D->post_tags	= $this->network->get_recent_posttags(10, $u->id, 'user'); 
		
		$D->personal_tags = array();
		$D->personal_tags = $u->tags;
		
		$D->some_following = array();
			if( !($this->param('tab')=='coleagues' && $this->param('filter')=='ifollow') ){
			$tmp	= array_slice(array_keys($he_follows), 0, 3); 
			shuffle($tmp);

			if( count($tmp) > 0 ) { 
				foreach( $tmp as $uid ){ 
					$D->some_following[] = $this->network->get_user_by_id($uid);
				}
			}
		}
	}
	
	if($D->tab == 'updates') {
		$not_in_groups	= count($not_in_groups)==0 ? '' : ('AND group_id NOT IN('.implode(', ', $not_in_groups).')');
		$D->posts_html	= '';
		
		$D->filter		= 'posts';
		$tmp	= array('all', 'posts', 'tweets', 'rss', 'reshares');
		if( $this->param('filter') && in_array($this->param('filter'), $tmp) ) {
			$D->filter	= $this->param('filter');
		}
		$D->filter1_title	= $this->lang('tab_user_all');
		$D->filter2_title	= $this->lang('tab_user_posts');
		$D->filter3_title	= $this->lang('tab_user_tweets');
		$D->filter4_title	= $this->lang('tab_user_feeds');
		$D->filter5_title	= $this->lang('tab_user_reshares');
		
		if( !$D->posts_protected ){
			$reshared	= array();
			if( $D->filter == 'all' || $D->filter == 'reshares' ){
				$db2->query('SELECT post_id FROM posts_reshares WHERE user_id="'.$u->id.'" ');
				while($tmp = $db->fetch_object()) {
					$reshared[]	= intval($tmp->post_id);
				}
			}		
			
			if($D->filter == 'all') {
				$q1	= 'SELECT COUNT(*) FROM posts WHERE ((user_id="'.$u->id.'" '.$not_in_groups.') '.( count($reshared)==0 ? '' : ' OR id IN('.implode(', ',$reshared).')' ).')';
				$q2	= 'SELECT *, "public" AS `type` FROM posts WHERE ((user_id="'.$u->id.'" '.$not_in_groups.') '.( count($reshared)==0 ? '' : ' OR id IN('.implode(', ',$reshared).')' ).') ORDER BY id DESC ';
			}elseif($D->filter == 'posts'){
				$q1	= 'SELECT COUNT(*) FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups.' AND api_id<>2 AND api_id<>6 ';
				$q2	= 'SELECT *, "public" AS `type` FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups.' AND api_id<>2 AND api_id<>6 ORDER BY id DESC ';
			}elseif($D->filter == 'tweets'){
				$q1	= 'SELECT COUNT(*) FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups.' AND api_id=6'; 
				$q2	= 'SELECT *, "public" AS `type` FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups.' AND api_id=6 ORDER BY id DESC ';
			}elseif($D->filter == 'rss'){
				$q1	= 'SELECT COUNT(*) FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups.' AND api_id=2';
				$q2	= 'SELECT *, "public" AS `type` FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups.' AND api_id=2 ORDER BY id DESC ';
			}elseif( $D->filter == 'reshares' ){
				$q1	= 'SELECT '.( count($reshared)==0 ? '0' : ('COUNT(*) FROM posts WHERE id IN('.implode(', ',$reshared).')'.' '.$not_in_groups ) );
				$q2	= 'SELECT *, "public" AS `type` FROM posts WHERE '.( count($reshared)==0 ? '1' : ('id IN('.implode(', ',$reshared).')') ).' '.$not_in_groups.' ORDER BY id DESC ';
			}
			
			$D->num_results	= $db2->fetch_field($q1); 
		}else $D->num_results	= 0;
		
		if( 0 == $D->num_results ) {
			$arr	= array('#USERNAME#'=>$u->username, '#SITE_TITLE#'=>htmlspecialchars($C->OUTSIDE_SITE_TITLE), '#A1#'=>'<a href="javascript:;" onclick="postform_open();">', '#A2#'=>'</a>', );
			if( !$D->posts_protected ){
				$lngkey_ttl	= $D->is_my_profile ? 'noposts_myprofile_ttl' : 'noposts_usrprofile_ttl';
				$lngkey_txt	= $D->is_my_profile ? 'noposts_myprofile_txt' : 'noposts_usrprofile_txt';
			}else{
				$lngkey_ttl	= 'noposts_usrprofileprotected_ttl';
				$lngkey_txt	= 'noposts_usrprofileprotected_txt';
			}
			
			if($D->filter != 'all' && $D->filter != 'posts') {
				$lngkey_ttl	.= '_filter';
				$lngkey_txt	.= '_filter';
			}
			
			$D->noposts_box_title	= $this->lang($lngkey_ttl, $arr);
			$D->noposts_box_text	= $this->lang($lngkey_txt, $arr);
			
			$D->posts_html	= $this->load_template('noposts_box.php', FALSE);
		}
		else {
			$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_POSTS);
			$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
			$D->pg	= min($D->pg, $D->num_pages);
			$D->pg	= max($D->pg, 1);
			$from	= ($D->pg - 1) * $C->PAGING_NUM_POSTS;
			$res	= $db2->query($q2.'LIMIT '.$from.', '.$C->PAGING_NUM_POSTS);
			
			$tmpposts	= array();
			$tmpids	= array();
			$postusrs	= array();
			$buff 	= NULL;
			while($obj = $db2->fetch_object($res)) {
				$buff = new post($obj->type, FALSE, $obj);
				if( $buff->error ) {
					continue;
				}
				if( $this->param('from')=='ajax' && $this->param('onlypost')!="" && $this->param('onlypost')!=$buff->post_tmp_id ) {
					continue;
				}
				$D->p		= $tmpposts[] 	= $buff;
				$tmpids[]	= $buff->post_tmp_id;
				if($buff->post_user->id != $u->id){
					$postusrs[]	= $buff->post_user->id;
				}
			}
			unset($buff);
			
			if( $this->user->is_logged ){
				$D->i_follow	= array_fill_keys(array_keys($i_follow), 1); 
			}
			
			if( $D->tab == 'updates' && ($D->filter == 'reshares' || $D->filter == 'all') ){
				$postuser_follow_me = array();
				if( count($postusrs)>0 ){
					$r = $db2->query('SELECT who FROM users_followed WHERE who IN ('.implode(',', $postusrs).') AND whom="'.$this->user->id.'"');
					while($o = $db2->fetch_object($r)){
						if( isset($postuser_follow_me[$o->who]) ){
							continue;
						}
						$postuser_follow_me[$o->who] = 1;
					}
				}
			}
			
			post::preload_num_new_comments($tmpids);
			ob_start();
			
			$D->show_my_email 		= $D->he_follows_me;
			$D->protected_profile 	= $D->profile_protected;
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
				
				if( $D->tab == 'updates' && ($D->filter == 'reshares' || $D->filter == 'all') ){
					$D->he_follows_me = isset($postuser_follow_me[$D->p->post_user->id]);
				}
				
				$D->show_my_email = FALSE;
				if( $D->he_follows_me || $D->usr->id == $this->user->id || ($this->user->is_logged && $this->user->info->is_network_admin) ){
					$D->show_my_email = TRUE;
				}
				
				$D->protected_profile = FALSE;
				$right_post_type = (!$D->p->is_system_post && !$D->p->is_feed_post);
				
				if($right_post_type && !$D->show_my_email && $D->p->post_user->is_profile_protected){
					$D->protected_profile = TRUE;
				}
				
				$D->show_reshared_design = ( $D->p->post_resharesnum > 0 );

				$this->load_template('single_post.php');
			}
			unset($D->p, $tmp, $tmpposts, $tmpids, $postUsrIds, $reshared);
			
			$D->paging_url	= $C->SITE_URL.$u->username.'/filter:'.$D->filter.'/pg:';
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
	elseif($D->tab == 'coleagues') {
		$filters	= array('ifollow', 'followers');
		$D->show_tab_incommon = FALSE;
		if( !$D->is_my_profile ){
			$filters[] = 'incommon';
			$D->show_tab_incommon = TRUE;
		}
		
		$D->filter	= 'ifollow';
		if( $this->param('filter') && in_array($this->param('filter'), $filters) ) {
			$D->filter	= $this->param('filter');
		}
		$his_followers 	= $this->network->get_user_follows($D->usr->id, FALSE, 'hisfollowers')->followers;
		$D->fnums		= array('ifollow'=>count($he_follows), 'followers'=>count($his_followers));
		
		if($D->show_tab_incommon){
			$D->users_in_common = array_intersect($he_follows, $i_follow);
			$D->num_in_common	= count($D->users_in_common); 
			$D->fnums['incommon'] = $D->num_in_common;
		}
		
		switch($D->filter)
		{
			case 'ifollow': $tmp = array_keys($he_follows);
				break;
			case 'followers': $tmp = array_keys($his_followers);
				break;
			case 'incommon': $tmp = $D->users_in_common;
				break;
		}
		
		$D->num_results	= count($tmp);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		$tmp	= array_slice($tmp, $from, $C->PAGING_NUM_USERS, TRUE);
		$usrs	= array();
		if( count($tmp) > 0 ) {
			$D->if_follow_user = array();
			if($this->user->id ){ 
				$db2->query('SELECT whom FROM users_followed WHERE whom IN('.implode(',', $tmp).') AND who="'.$this->user->id.'"');
				while($o = $db2->fetch_object()){
					if( isset($D->if_follow_user[$o->whom]) ){
							continue;
					}
					$D->if_follow_user[$o->whom] = 1;
				}
			}
			
			foreach( $tmp as $uid ){
				$usrs[] = $this->network->get_user_by_id($uid);
			}
		}
		
		$D->users_html	= '';
		if( count($usrs) > 0 ) {
			ob_start();
			foreach($usrs as $tmp) {
				$D->u	= $tmp;
				$this->load_template('single_user.php');
			}
			$D->paging_url	= $C->SITE_URL.$D->usr->username.'/tab:coleagues/filter:'.$D->filter.'/pg:';
			if( $D->num_pages > 1 ) {
				$this->load_template('paging_users.php');
			}
			$D->users_html	= ob_get_contents();
			ob_end_clean();
			unset($tmp, $sdf, $usrs, $D->u);
		}
		else {
			$arr	= array('#USERNAME#'=>$u->username);
			switch($D->filter)
			{
				case 'ifollow': $lngkey_ttl = 'nousrs_subtab1_ttl'; $lngkey_txt	=  'nousrs_subtab1_txt';
					break;
				case 'followers': $lngkey_ttl = 'nousrs_subtab2_ttl'; $lngkey_txt	=  'nousrs_subtab2_txt';
					break;
				case 'incommon': $lngkey_ttl = 'nousrs_subtab3_ttl'; $lngkey_txt	=  'nousrs_subtab3_txt';
					break;
			}
			if( $D->is_my_profile ) {
				$lngkey_ttl	.= '_me';
				$lngkey_txt .= '_me';
			}
			$D->noposts_box_title	= $this->lang($lngkey_ttl, $arr);
			$D->noposts_box_text	= $this->lang($lngkey_txt, $arr);
			$D->users_html	= $this->load_template('noposts_box.php', FALSE);
		}
		$D->filter1_title	= 'usr_coleagues_subtab1'.(($D->is_my_profile)? '_me':'');
		$D->filter2_title	= 'usr_coleagues_subtab2'.(($D->is_my_profile)? '_me':'');
		$D->filter3_title	= 'usr_coleagues_subtab3'.(($D->is_my_profile)? '_me':'');

		$D->filter1_title	= $this->lang($D->filter1_title, array('#USERNAME#'=>$D->usr->username));
		$D->filter2_title	= $this->lang($D->filter2_title, array('#USERNAME#'=>$D->usr->username));
		$D->filter3_title	= $this->lang($D->filter3_title);
	}
	elseif($D->tab == 'info') {
		$D->i	= new stdClass;
		$D->i->ims	= array();
		$D->i->prs	= array();
		
		if( isset($D->udtls) ) {

			foreach($D->udtls as $k=>$v) {
				$v	= stripslashes($v);
				if( substr($k,0,5) == 'prof_' ) {
					if( preg_match('/^(.*)\#\#\#(.*)$/iu', $v, $m) ) {
						if( empty($m[1]) ) {
							$m[1] = $u->fullname;
						}
						$D->i->$k	= array($m[2], $m[1]);
						$D->i->prs[$k]	= $D->i->$k;
					}
				}
				else {
					$D->i->$k	= $v;
					if( substr($k,0,3) == 'im_' && !empty($v) ) {
						$D->i->ims[$k]	= $D->i->$k;
					}
				}
			}
		}
		$D->birthdate	= '';
		$bd_day	= intval(substr($u->birthdate,8,2));
		$bd_month	= intval(substr($u->birthdate,5,2));
		$bd_year	= intval(substr($u->birthdate,0,4));
		if( $bd_day>0 && $bd_month>0 && $bd_year>0 ) {
			$D->birthdate	= mktime(0, 0, 1, $bd_month, $bd_day, $bd_year);
			$D->birthdate	= strftime($this->lang('usr_info_birthdate_dtformat'), $D->birthdate);
		}
		$D->date_register		= strftime($this->lang('usr_info_birthdate_dtformat'), $u->reg_date);
		$D->date_lastlogin	= '';
		$tmp	= intval($D->usr->lastclick_date);
		if( $tmp > 0 ) {
			$D->date_lastlogin	= strftime($this->lang('usr_info_aboutme_lgndtfrmt'), $tmp);
		}
	}
	elseif( $D->tab=='groups' ) {
		$groups		= array_keys($this->network->get_user_follows($u->id, FALSE, 'hisgroups')->follow_groups);
		$groups 		= array_diff($groups, $not_in_groups);
		
		$D->orderby	= 'name';
		$sql_orderby	= array(
			'name'	=> 'title ASC, num_followers DESC, num_posts DESC, id DESC',
			'date'	=> 'id DESC',
			'users'	=> 'num_followers DESC, num_posts DESC, id DESC',
			'posts'	=> 'num_posts DESC, num_followers DESC, id DESC',
		);
		if( $this->param('orderby') && isset($sql_orderby[$this->param('orderby')]) ) {
			$D->orderby	= $this->param('orderby');
		}
		$D->num_results	= count($groups);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_GROUPS);
		$D->pg	= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_GROUPS;
		
		$selected_groups = array();
		if( $D->num_results>0 ){
			$db2->query('SELECT * FROM groups WHERE id IN('.implode(',',$groups).') ORDER BY '.$sql_orderby[$D->orderby].' LIMIT '.$from.', '.$C->PAGING_NUM_GROUPS);
			while($o = $db2->fetch_object()) {	
				$selected_groups[]	= generate_group_info_obj($o);
			}
		}
		
		$D->if_follow_group = array();
		if($this->user->id && count($groups)>0){
			$db2->query('SELECT * FROM groups_followed WHERE group_id IN('.implode(',', $groups).') AND user_id="'.$this->user->id.'"');
			while($o = $db2->fetch_object()){
				if( isset($D->if_follow_group[$o->group_id]) ){
						continue;
				}
				$D->if_follow_group[$o->group_id] = 1;
			}
		}
		
		$D->if_can_leave 	= array();
		$g_admins 			= array();
		if( $this->user->id && count($groups)>0 ){
			$db2->query('SELECT * FROM groups_admins WHERE group_id IN('.implode(',', $groups).')');
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
		
		$D->groups_html	= '';
		ob_start();
		if($D->num_results>0){
			foreach($selected_groups as $tmp) {
				$D->g	= $tmp;
				$this->load_template('single_group.php');
			}
			$D->paging_url	= $C->SITE_URL.$D->usr->username.'/tab:groups/orderby:'.$D->orderby.'/pg:';
			if( $D->num_pages > 1 ) {
				$this->load_template('paging_groups.php');
			}
		}else{
			$D->noposts_box_title	= $this->lang('nogroups_usr_follow_ttl');
			$D->noposts_box_text	= ($D->is_my_profile)? $this->lang('nogroups_usr_follow_txt_me') : $this->lang('nogroups_usr_follow_txt', array('#USERNAME#' => $u->username));
			$D->posts_html	= $this->load_template('noposts_box.php');
		}
		$D->groups_html	= ob_get_contents();
		ob_end_clean();
		unset($tmp, $sdf, $grps, $D->g);
		$D->groups_title	= $this->lang($D->is_my_profile?'usr_groups_title_me':'usr_groups_title', array('#USERNAME#'=>$D->usr->username));
	}
	
	$this->load_template('user.php');
	
?>