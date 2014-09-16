<?php
	
	if( !$this->network->id || !$this->user->is_logged ) {
		$this->redirect('home');
	}
	if( $this->network->id && $C->MOBI_DISABLED ) {
		$this->redirect('mobidisabled');
	}
	
	$this->load_langfile('mobile/global.php');
	$this->load_langfile('mobile/user.php');
	
	$u	= $this->network->get_user_by_id(intval($this->params->user));
	if( ! $u ) {
		$this->params->user	= $this->user->id;
		$u	= $this->user->info;
	}
	
	$D->page_title	= $u->username.' - '.$C->SITE_TITLE;
	
	$D->usr	= & $u;
	$D->is_my_profile	= $u->id==$this->user->id;
	$D->i_follow_him	= $this->user->if_follow_user($u->id);
	$D->he_follows_me = $this->user->is_logged ? $this->user->if_user_follows_me($u->id) : FALSE;
	$D->i_am_network_admin	= ( $this->user->is_logged && $this->user->info->is_network_admin );
	$D->profile_protected 	= ( $u->is_profile_protected && !$D->he_follows_me && !$D->i_am_network_admin );
	$D->posts_protected 	= ( $u->is_posts_protected && !$D->he_follows_me && !$D->i_am_network_admin );
	
	$shows	= array('updates');
	if($D->he_follows_me || !$D->profile_protected){
		$shows[] = 'info';
		$shows[] = 'followers';
		$shows[] = 'following';
		$shows[] = 'groups';
	}
	
	$D->show	= 'updates';
	
	if( isset($_GET['show']) && in_array($_GET['show'],$shows) ) {
		$D->show	= $_GET['show'];
	}
	
	if( !$D->is_my_profile ) {
		if( isset($_GET['do_follow']) && !$D->i_follow_him ) {
			$this->user->follow($u->id, TRUE);
			$D->i_follow_him	= TRUE;
		}
		elseif( isset($_GET['do_unfollow']) && $D->i_follow_him ) {
			$this->user->follow($u->id, FALSE);
			$D->i_follow_him	= FALSE;
		}
	}
	
	$D->num_results	= 0;
	$D->num_pages	= 0;
	$D->pg		= isset($_GET['pg']) ? intval($_GET['pg']) : 1;
	$D->posts_html	= '';
	$D->users_html	= '';
	$D->groups_html	= '';
	$D->paging_url	= $C->SITE_URL.$u->username.'/?show='.$D->show.'&pg=';
	
	if( $D->show == 'updates' ) {
		$not_in_groups	= array();
		if( ! $this->user->info->is_network_admin ) {
			$not_in_groups	= array();
			$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
		}
		$not_in_groups	= count($not_in_groups)==0 ? '' : ('AND group_id NOT IN('.implode(', ', $not_in_groups).')');
		
		if($D->is_my_profile || !$u->is_posts_protected || ($u->is_posts_protected && $D->he_follows_me) || ($this->user->is_logged && $this->user->info->is_network_admin)){
			$q1	= 'SELECT COUNT(*) FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups;
			$q2	= 'SELECT *, "public" AS `type` FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups.' ORDER BY id DESC ';
			$D->num_results	= $db2->fetch_field($q1);
			if( 0 < $D->num_results ) {
				$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_POSTS);
				$D->pg	= min($D->pg, $D->num_pages);
				$D->pg	= max($D->pg, 1);
				$from	= ($D->pg - 1) * $C->PAGING_NUM_POSTS;
				$res	= $db2->query($q2.'LIMIT '.$from.', '.$C->PAGING_NUM_POSTS);
				$i	= 0;
				ob_start();
				while($obj = $db->fetch_object($res)) {
					$D->p	= new post($obj->type, FALSE, $obj);
					if( $D->p->error ) {
						continue;
					}
					$D->p->list_index	= $i++;
					$this->load_template('mobile/single_post.php');
				}
				unset($D->p);
				$D->posts_html	= ob_get_contents();
				ob_end_clean();
			}
		}else{
			$D->num_results	= 0;
			$D->posts_number	= 0;
		}
	}
	elseif( $D->show == 'followers' ) {
		$tmp	= array_keys($this->network->get_user_follows($D->usr->id, FALSE, 'hisfollowers')->followers);
		$D->num_results	= count($tmp);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		$tmp	= array_slice($tmp, $from, $C->PAGING_NUM_USERS);
		$usrs	= array();
		foreach($tmp as $sdf) {
			if($sdf = $this->network->get_user_by_id($sdf)) {
				$usrs[]	= $sdf;
			}
		}
		if( count($usrs) > 0 ) {
			$i	= 0;
			ob_start();
			foreach($usrs as $tmp) {
				$D->u	= $tmp;
				$D->u->list_index	= $i++;
				$this->load_template('mobile/single_user.php');
			}
			$D->users_html	= ob_get_contents();
			ob_end_clean();
			unset($tmp, $sdf, $usrs, $D->u);
		}
	}
	elseif( $D->show == 'following' ) {
		$tmp	= array_keys($this->network->get_user_follows($D->usr->id, FALSE, 'hefollows')->follow_users);
		$D->num_results	= count($tmp);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
		$tmp	= array_slice($tmp, $from, $C->PAGING_NUM_USERS);
		$usrs	= array();
		foreach($tmp as $sdf) {
			if($sdf = $this->network->get_user_by_id($sdf)) {
				$usrs[]	= $sdf;
			}
		}
		if( count($usrs) > 0 ) {
			$i	= 0;
			ob_start();
			foreach($usrs as $tmp) {
				$D->u	= $tmp;
				$D->u->list_index	= $i++;
				$this->load_template('mobile/single_user.php');
			}
			$D->users_html	= ob_get_contents();
			ob_end_clean();
			unset($tmp, $sdf, $usrs, $D->u);
		}
	}
	elseif( $D->show=='groups' ) {
		$tmp	= array_keys($this->network->get_user_follows($D->usr->id, FALSE, 'hisgroups')->follow_groups);
		$D->num_results	= count($tmp);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_GROUPS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		$from	= ($D->pg - 1) * $C->PAGING_NUM_GROUPS;
		$tmp	= array_slice($tmp, $from, $C->PAGING_NUM_GROUPS);
		$grps	= array();
		foreach($tmp as $sdf) {
			if($sdf = $this->network->get_group_by_id($sdf)) {
				$grps[]	= $sdf;
			}
		}
		$D->groups_html	= '';
		if( count($grps) > 0 ) {
			$i	= 0;
			ob_start();
			foreach($grps as $tmp) {
				$D->g	= $tmp;
				$D->g->list_index	= $i++;
				$this->load_template('mobile/single_group.php');
			}
			$D->groups_html	= ob_get_contents();
			ob_end_clean();
			unset($tmp, $sdf, $grps, $D->g);
		}
	}
	elseif( $D->show == 'info' ) {
		$D->udtls = $this->network->get_user_details_by_id(intval($this->params->user));
		$D->udtls = ($D->udtls === FALSE)? array() : $D->udtls;
	
		$D->i	= new stdClass;
		$D->i->ims	= array();
		$D->i->prs	= array();
		
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
		
		$D->birthdate	= '';
		$bd_day	= intval(substr($u->birthdate,8,2));
		$bd_month	= intval(substr($u->birthdate,5,2));
		$bd_year	= intval(substr($u->birthdate,0,4));
		if( $bd_day>0 && $bd_month>0 && $bd_year>0 ) {
			$D->birthdate	= mktime(0, 0, 1, $bd_month, $bd_day, $bd_year);
			$D->birthdate	= strftime($this->lang('uinfo_birthdate_dtformat'), $D->birthdate);
		}
		$D->date_register		= strftime($this->lang('uinfo_birthdate_dtformat'), $u->reg_date);
		$D->date_lastlogin	= '';
		$tmp	= intval($u->lastclick_date);
		if( $tmp > 0 ) {
			$D->date_lastlogin	= strftime($this->lang('uinfo_aboutme_lgndtfrmt'), $tmp);
		}
		if( ! $this->network->is_private ) {
			$D->usr->email	= '';
		}
	}
	
	$this->load_template('mobile/user.php');
	
?>