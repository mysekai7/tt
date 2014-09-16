<?php
	
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	if(  $C->MOBI_DISABLED ) {
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
	
	$D->udtls = $this->network->get_user_details_by_id(intval($this->params->user));
	$D->udtls = ($D->udtls === FALSE)? array() : $D->udtls;
	
	$D->usr	= & $u;
	$D->is_my_profile	= ($this->user->id == $u->id);
	$he_follows 	= $this->network->get_user_follows($u->id, FALSE, 'hefollows')->follow_users;
	$i_follow 	= ( !$D->is_my_profile )? $this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users : $he_follows;
	
	$D->i_follow_him 		= $this->user->is_logged ? isset($i_follow[$u->id]) : FALSE;
	$D->he_follows_me 		= $this->user->is_logged ? ( $D->is_my_profile || isset($he_follows[$this->user->id]) ) : FALSE;
	$D->i_am_network_admin	= ( $this->user->is_logged && $this->user->info->is_network_admin );
	$D->profile_protected 	= ( $u->is_profile_protected && !$D->he_follows_me && !$D->i_am_network_admin );
	$D->posts_protected 	= ( $u->is_posts_protected && !$D->he_follows_me && !$D->i_am_network_admin );
	
	$shows	= array('updates');
	if($D->he_follows_me || !$D->profile_protected){
		$shows[] = 'info';
		$shows[] = 'friends';
		$shows[] = 'groups';
	}
	
	$D->show	= 'updates';
	
	if( $this->param('show') && in_array($this->param('show'),$shows) ) {
		$D->show	= $this->param('show');
	}
	
	if( !$D->is_my_profile ) {
		if( $this->param('do_follow') && !$D->i_follow_him ) {
			$this->user->follow($u->id, TRUE);
			$D->i_follow_him	= TRUE;
		}
		elseif( $this->param('do_unfollow') && $D->i_follow_him ) {
			$this->user->follow($u->id, FALSE);
			$D->i_follow_him	= FALSE;
		}
	}
	
	if($D->he_follows_me || !$D->profile_protected){
		$D->usr_website	= '';
		$D->usr_pemail	= '';
		$D->usr_wphone	= '';
		$D->usr_pphone	= '';

		$D->usr_website	= isset($D->udtls->website)? stripslashes($D->udtls->website) : '';
		if($D->he_follows_me) $D->usr_pemail = isset($D->udtls->website)? stripslashes($u->email) : '';
		$D->usr_wphone	= isset($D->udtls->website)? stripslashes($D->udtls->work_phone) : '';
		$D->usr_pphone	= isset($D->udtls->website)? stripslashes($D->udtls->personal_phone) : '';

		if( $D->usr_website=='http://' || $D->usr_website=='https://' || $D->usr_website=='ftp://' ) {
			$D->usr_website	= '';
		}
		$D->qr = new qrcode();
		if(isset($u->fullname)) $D->qr->fn($u->fullname);
		if(isset($u->username)) $D->qr->nn('@'.$u->username);
		if(!empty($D->usr_pemail) && $D->he_follows_me ) $D->qr->email($D->usr_pemail);
		if(!empty($D->usr_wphone)) $D->qr->work_phone($D->usr_wphone);
		if(!empty($D->usr_pphone)) $D->qr->home_phone($D->usr_pphone);
		$D->qr->url($C->SITE_URL.$u->username);
		$D->qr->finish();
	}
	
	$D->usr_avatar	= md5($D->usr->id.'-'.$D->usr->avatar).'.'.pathinfo($D->usr->avatar,PATHINFO_EXTENSION);
	if( ! file_exists($C->TMP_DIR.$D->usr_avatar) ) {
		require_once($C->INCPATH.'helpers/func_images.php');
		copy_attachment_videoimg($C->IMG_DIR.'avatars/'.$D->usr->avatar, $C->TMP_DIR.$D->usr_avatar, 100);
	}
	
	if( $D->show == 'updates' )
	{
		$D->num_results	= 0;
		$D->start_from	= 0;
		$D->posts_html	= '';
		
		$not_in_groups	= array();
	if( !$this->user->is_logged || !$this->user->info->is_network_admin ) {
		$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
	}
	$not_in_groups	= count($not_in_groups)>0 ? ('AND group_id NOT IN('.implode(', ', $not_in_groups).')') : '';
		
		if($D->is_my_profile || !$u->is_posts_protected || ($u->is_posts_protected && $D->he_follows_me) || ($this->user->is_logged && $this->user->info->is_network_admin)){
			$q1	= 'SELECT COUNT(*) FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups;
			$q2	= 'SELECT *, "public" AS `type` FROM posts WHERE user_id="'.$u->id.'" '.$not_in_groups.' ORDER BY id DESC ';
			$D->num_results	= $db2->fetch_field($q1);
			$D->posts_number	= 0;
			if( 0 < $D->num_results ) {
				$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_POSTS);
				$D->start_from	= $this->param('start_from') ? intval($this->param('start_from')) : 0;
				$D->start_from	= max($D->start_from, 0);
				$D->start_from	= min($D->start_from, $D->num_results);
				$res	= $db2->query($q2.'LIMIT '.$D->start_from.', '.$C->PAGING_NUM_POSTS);
				$D->posts_number	= 0;
				ob_start();
				while($obj = $db2->fetch_object($res)) {
					$D->p	= new post($obj->type, FALSE, $obj);
					if( $D->p->error ) {
						continue;
					}
					$D->posts_number	++;
					$D->p->list_index	= $D->posts_number;
					$this->load_template('mobile_iphone/single_post.php');
				}
				unset($D->p);
				$D->posts_html	= ob_get_contents();
				ob_end_clean();
			}
		}else{
			$D->num_results	= 0;
			$D->posts_number	= 0;
		}
		
		if( $this->param('from') == 'ajax' ) {
			echo 'OK:'.$D->posts_number.':';
			echo $D->posts_html;
			exit;
		}
	}
	elseif( $D->show == 'info' ) {
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
			$D->birthdate	= strftime($this->lang('uinfo_birthdate_dtformat'), $D->birthdate);
		}
		$D->date_register		= strftime($this->lang('uinfo_birthdate_dtformat'), $u->reg_date);
		$D->date_lastlogin	= '';
		$tmp	= intval($u->lastclick_date);
		if( $tmp > 0 ) {
			$D->date_lastlogin	= strftime($this->lang('uinfo_aboutme_lgndtfrmt'), $tmp);
		}
	}
	elseif( $D->show == 'groups' ) {
		$D->num_results	= 0;
		$D->num_pages	= 0;
		$D->pg		= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->groups_html	= '';
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
		if( count($grps) > 0 ) {
			$i	= 0;
			ob_start();
			foreach($grps as $tmp) {
				$D->g	= $tmp;
				$D->g->list_index	= $i++;
				$this->load_template('mobile_iphone/single_group.php');
			}
			$D->groups_html	= ob_get_contents();
			ob_end_clean();
			unset($tmp, $sdf, $grps, $D->g);
		}
	}
	elseif( $D->show == 'friends' ) {
		$D->filter	= 'followers';
		if( $this->param('filter')=='following' ) {
			$D->filter	= 'following';
		}
		$D->num_results	= 0;
		$D->num_pages	= 0;
		$D->pg		= $this->param('pg') ? intval($this->param('pg')) : 1;
		$D->users_html	= '';
		$tmp	= array_keys( $D->filter=='followers' ? $this->network->get_user_follows($D->usr->id, FALSE, 'hisfollowers')->followers : $this->network->get_user_follows($D->usr->id, FALSE, 'hefollows')->follow_users );
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
				$this->load_template('mobile_iphone/single_user.php');
			}
			$D->users_html	= ob_get_contents();
			ob_end_clean();
			unset($tmp, $sdf, $usrs, $D->u);
		}
		$D->num_followers	= count($this->network->get_user_follows($D->usr->id, FALSE, 'hisfollowers')->followers);
		$D->num_following	= count($this->network->get_user_follows($D->usr->id, FALSE, 'hefollows')->follow_users);
	}
	
	$this->load_template('mobile_iphone/user.php');
	
?>