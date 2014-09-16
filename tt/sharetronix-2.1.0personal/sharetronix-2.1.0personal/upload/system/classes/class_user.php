<?php

	class user
	{
		public $id;
		public $network;
		public $is_logged;
		public $info;
		public $sess;

		public function __construct()
		{
			$this->id	= FALSE;
			$this->network	= & $GLOBALS['network'];
			$this->cache	= & $GLOBALS['cache'];
			$this->db1		= & $GLOBALS['db1'];
			$this->db2		= & $GLOBALS['db2'];
			$this->info		= new stdClass;
			$this->is_logged	= FALSE;
			$this->sess		= array();
		}

		public function LOAD()
		{
			if( ! $this->network->id ) {
				return FALSE;
			}
			global $C;
			$this->_session_start();
			if( isset($this->sess['IS_LOGGED'], $this->sess['LOGGED_USER']) && $this->sess['IS_LOGGED'] && $this->sess['LOGGED_USER'] ) {
				$u	= & $this->sess['LOGGED_USER'];
				$u	= $this->network->get_user_by_id($u->id);
				if( ! $u ) {
					return FALSE;
				}
				if( $this->network->id && $this->network->id == $u->network_id ) {
					$this->is_logged	= TRUE;
					$this->info	= & $u;
					$this->id	= $this->info->id;
					$this->db2->query('UPDATE users SET lastclick_date="'.time().'" WHERE id="'.$this->id.'" LIMIT 1');
					$deflang	= $C->LANGUAGE;
					if( ! empty($this->info->language) ) {
						$C->LANGUAGE	= $this->info->language;
					}
					if( $C->LANGUAGE != $deflang ) {
						$current_language	= new stdClass;
						include($C->INCPATH.'languages/'.$C->LANGUAGE.'/language.php');
						date_default_timezone_set($current_language->php_timezone);
						setlocale(LC_ALL, $current_language->php_locale);
					}
					if( ! empty($this->info->timezone) ) {
						date_default_timezone_set($this->info->timezone);
					}
					if( $this->info->active == 0 ) {
						$this->logout();
						return FALSE;
					}
					return $this->id;
				}
			}
			if( $this->try_autologin() ) {
				$this->LOAD();
			}
			return FALSE;
		}

		private function _session_start()
		{
			if( ! $this->network->id ) {
				return FALSE;
			}
			if( ! isset($_SESSION['NETWORKS_USR_DATA']) ) {
				$_SESSION['NETWORKS_USR_DATA']	= array();
			}
			if( ! isset($_SESSION['NETWORKS_USR_DATA'][$this->network->id]) ) {
				$_SESSION['NETWORKS_USR_DATA'][$this->network->id]	= array();
			}
			$this->sess	= & $_SESSION['NETWORKS_USR_DATA'][$this->network->id];
		}

		public function login($login, $pass, $rememberme=FALSE)
		{
			global $C;
			if( ! $this->network->id ) {
				return FALSE;
			}
			if( $this->is_logged ) {
				return FALSE;
			}
			if( empty($login) ) {
				return FALSE;
			}
			$login	= $this->db2->escape($login);
			$pass		= $this->db2->escape($pass);
			$this->db2->query('SELECT id FROM users WHERE (email="'.$login.'" OR username="'.$login.'") AND password="'.$pass.'" AND active=1 LIMIT 1');
			if( ! $obj = $this->db2->fetch_object() ) {
				return FALSE;
			}
			$this->info	= $this->network->get_user_by_id($obj->id, TRUE);
			if( ! $this->info ) {
				return FALSE;
			}
			$this->is_logged		= TRUE;
			$this->sess['IS_LOGGED']	= TRUE;
			$this->sess['LOGGED_USER']	= & $this->info;
			$this->id	= $this->info->id;

			$ip	= $this->db2->escape( ip2long($_SERVER['REMOTE_ADDR']) );
			$this->db2->query('UPDATE users SET lastlogin_date="'.time().'", lastlogin_ip="'.$ip.'", lastclick_date="'.time().'" WHERE id="'.$this->id.'" LIMIT 1');
			if( TRUE == $rememberme ) {
				$tmp	= $this->id.'_'.md5($this->info->username.'~~'.$this->info->password.'~~'.$_SERVER['HTTP_USER_AGENT']);
				setcookie('rememberme', $tmp, time()+60*24*60*60, '/', cookie_domain());
			}

			$this->sess['total_pageviews']	= 0;
			$this->sess['cdetails']	= $this->db2->fetch('SELECT * FROM users_details WHERE user_id="'.$this->id.'" LIMIT 1');
			return TRUE;
		}

		public function try_autologin()
		{
			if( ! $this->network->id ) {
				return FALSE;
			}
			if( $this->is_logged ) {
				return FALSE;
			}
			if( ! isset($_COOKIE['rememberme']) ) {
				return FALSE;
			}
			$tmp	= explode('_', $_COOKIE['rememberme']);
			$this->db2->query('SELECT username, password, email FROM users WHERE id="'.intval($tmp[0]).'" AND active=1 LIMIT 1');
			if( ! $obj = $this->db2->fetch_object() ) {
				return FALSE;
			}
			$obj->username	= stripslashes($obj->username);
			$obj->password	= stripslashes($obj->password);
			if( $tmp[1] == md5($obj->username.'~~'.$obj->password.'~~'.$_SERVER['HTTP_USER_AGENT']) ) {
				return $this->login($obj->username, $obj->password, TRUE);
			}
			setcookie('rememberme', NULL, time()+30*24*60*60, '/', cookie_domain());
			$_COOKIE['rememberme']	= NULL;
			return FALSE;
		}

		public function logout()
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			setcookie('rememberme', NULL, time()+60*24*60*60, '/', cookie_domain());
			$_COOKIE['rememberme']	= NULL;
			$this->sess['IS_LOGGED']	= FALSE;
			$this->sess['LOGGED_USER']	= NULL;
			unset($this->sess['IS_LOGGED']);
			unset($this->sess['LOGGED_USER']);
			$this->id	= FALSE;
			$this->info	= new stdClass;
			$this->is_logged	= FALSE;
			$_SESSION['TWITTER_CONNECTED']	= FALSE;
		}

		public function follow($whom_id, $how=TRUE)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$whom	= $this->network->get_user_by_id($whom_id);
			if( ! $whom ) {
				return FALSE;
			}
			$f	= $this->network->get_user_follows($this->id, TRUE, 'hefollows')->follow_users;
			if( isset($f[$whom_id]) && $how==TRUE ) {
				return TRUE;
			}
			if( !isset($f[$whom_id]) && $how==FALSE ) {
				return TRUE;
			}
			if( $how == TRUE ) {
				$this->db2->query('INSERT INTO users_followed SET who="'.$this->id.'", whom="'.$whom_id.'", date="'.time().'", whom_from_postid="'.$this->network->get_last_post_id().'" ');
				$this->db2->query('UPDATE users SET num_followers=num_followers+1 WHERE id="'.$whom_id.'" LIMIT 1');

				$n	= intval( $this->network->get_user_notif_rules($this->id)->ntf_them_if_i_follow_usr );
				if( $n == 1 ) {
					global $C, $page;
					$page->load_langfile('inside/notifications.php');
					$page->load_langfile('email/notifications.php');
					$send_post	= TRUE;
					$send_mail	= FALSE;
					$n	= intval( $this->network->get_user_notif_rules($whom_id)->ntf_me_if_u_follows_me );
					if( $n == 2 ) { $send_post = TRUE; } elseif( $n == 3 ) { $send_mail = TRUE; } elseif( $n == 1 ) { $send_post = TRUE; $send_mail = TRUE; }
					if( $send_post ) {
						$lng	= array('#USER#'=>'<a href="'.$C->SITE_URL.$this->info->username.'" title="'.htmlspecialchars($this->info->fullname).'"><span class="mpost_mentioned">@</span>'.$this->info->username.'</a>');
						$this->network->send_notification_post($whom_id, 0, 'msg_ntf_me_if_u_follows_me', $lng, $C->NOTIF_POSTS_HANDLE);
					}
					if( $send_mail ) {
						$ulng	= trim($this->network->get_user_by_id($whom_id)->language);
						$lng_txt	= array('#SITE_TITLE#'=>$C->SITE_TITLE, '#USER#'=>'@'.$this->info->username, '#NAME#'=>$this->info->fullname, '#A0#'=>$C->SITE_URL.$this->info->username);
						$lng_htm	= array('#SITE_TITLE#'=>$C->SITE_TITLE, '#USER#'=>'<a href="'.$C->SITE_URL.$this->info->username.'" title="'.htmlspecialchars($this->info->fullname).'" target="_blank">@'.$this->info->username.'</a>', '#NAME#'=>$this->info->fullname);
						$subject		= $page->lang('emlsubj_ntf_me_if_u_follows_me', $lng_txt, $ulng);
						$message_txt	= $page->lang('emltxt_ntf_me_if_u_follows_me', $lng_txt, $ulng);
						$message_htm	= $page->lang('emlhtml_ntf_me_if_u_follows_me', $lng_htm, $ulng);
						$this->network->send_notification_email($whom_id, 'u_follows_me', $subject, $message_txt, $message_htm);
					}
					$followers	= array_keys($this->network->get_user_follows($this->id, TRUE, 'hisfollowers')->followers);
					foreach($followers as $uid) {
						if( $uid == $whom_id ) { continue; }
						$send_post	= FALSE;
						$send_mail	= FALSE;
						$n	= intval( $this->network->get_user_notif_rules($uid)->ntf_me_if_u_follows_u2 );
						if( $n == 2 ) { $send_post = TRUE; } elseif( $n == 3 ) { $send_mail = TRUE; } elseif( $n == 1 ) { $send_post = TRUE; $send_mail = TRUE; }
						if( $send_post ) {
							$lng	= array('#USER#'=>'<a href="'.$C->SITE_URL.$this->info->username.'" title="'.htmlspecialchars($this->info->fullname).'"><span class="mpost_mentioned">@</span>'.$this->info->username.'</a>', '#USER2#'=>'<a href="'.$C->SITE_URL.$whom->username.'" title="'.htmlspecialchars($whom->fullname).'"><span class="mpost_mentioned">@</span>'.$whom->username.'</a>');
							$this->network->send_notification_post($uid, 0, 'msg_ntf_me_if_u_follows_u2', $lng, $C->NOTIF_POSTS_HANDLE);
						}
						if( $send_mail ) {
							$ulng	= trim($this->network->get_user_by_id($uid)->language);
							$lng_txt	= array('#SITE_TITLE#'=>$C->SITE_TITLE, '#USER#'=>'@'.$this->info->username, '#NAME#'=>$this->info->fullname, '#A0#'=>$C->SITE_URL.$this->info->username, '#USER2#'=>$whom->username, '#NAME2#'=>$whom->fullname);
							$lng_htm	= array('#SITE_TITLE#'=>$C->SITE_TITLE, '#USER#'=>'<a href="'.$C->SITE_URL.$this->info->username.'" title="'.htmlspecialchars($this->info->fullname).'" target="_blank">@'.$this->info->username.'</a>', '#NAME#'=>$this->info->fullname, '#USER2#'=>'<a href="'.$C->SITE_URL.$whom->username.'" title="'.htmlspecialchars($whom->fullname).'" target="_blank">@'.$whom->username.'</a>', '#NAME2#'=>$whom->fullname);
							$subject		= $page->lang('emlsubj_ntf_me_if_u_follows_u2', $lng_txt, $ulng);
							$message_txt	= $page->lang('emltxt_ntf_me_if_u_follows_u2', $lng_txt, $ulng);
							$message_htm	= $page->lang('emlhtml_ntf_me_if_u_follows_u2', $lng_htm, $ulng);
							$this->network->send_notification_email($uid, 'u_follows_u2', $subject, $message_txt, $message_htm);
						}
					}
				}
			}
			else {
				$this->db2->query('DELETE FROM users_followed WHERE who="'.$this->id.'" AND whom="'.$whom_id.'" ');
				$this->db2->query('UPDATE users SET num_followers=num_followers-1 WHERE id="'.$whom_id.'" LIMIT 1');
				$this->db2->query('DELETE FROM post_userbox WHERE user_id="'.$this->id.'" AND post_id IN(SELECT id FROM posts WHERE user_id="'.$whom_id.'")');
				$this->db2->query('DELETE FROM post_userbox_feeds WHERE user_id="'.$this->id.'" AND post_id IN(SELECT id FROM posts WHERE user_id="'.$whom_id.'")');
			}
			//$this->network->get_user_by_id($whom_id, TRUE);
			$this->network->get_user_follows($whom_id, TRUE);
			$this->network->get_user_follows($whom_id, TRUE, 'hisfollowers');

			$this->network->get_user_follows($this->id, TRUE);
			$this->network->get_user_follows($this->id, TRUE, 'hefollows');

			return TRUE;
		}

		public function follow_group($group_id, $how=TRUE)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$group	= $this->network->get_group_by_id($group_id);
			if( ! $group ) {
				return FALSE;
			}
			$priv_members	= array();
			if( $group->is_private && !$this->info->is_network_admin ) {
				$priv_members	= $this->network->get_group_invited_members($group_id);
				if( ! $priv_members ) {
					return FALSE;
				}
				if( ! in_array(intval($this->id), $priv_members) ) {
					return FALSE;
				}
			}
			$f	= $this->network->get_user_follows($this->id, TRUE, 'hisgroups')->follow_groups;
			if( isset($f[$group_id]) && $how==TRUE ) {
				return TRUE;
			}
			if( !isset($f[$group_id]) && $how==FALSE ) {
				return TRUE;
			}
			if( $how == TRUE ) {
				$this->db2->query('INSERT INTO groups_followed SET user_id="'.$this->id.'", group_id="'.$group_id.'", date="'.time().'", group_from_postid="'.$this->network->get_last_post_id().'" ');
				$this->db2->query('UPDATE groups SET num_followers=num_followers+1 WHERE id="'.$group_id.'" LIMIT 1');
				$n	= intval( $this->network->get_user_notif_rules($this->id)->ntf_them_if_i_join_grp );
				if( $n == 1 ) {
					global $C, $page;
					$page->load_langfile('inside/notifications.php');
					$page->load_langfile('email/notifications.php');
					$followers	= array_keys($this->network->get_user_follows($this->id, FALSE, 'hisfollowers')->followers);
					foreach($followers as $uid) {
						$uid	= intval($uid);
						if( $group->is_private && !in_array($uid, $priv_members) ) {
							continue;
						}
						$send_post	= FALSE;
						$send_mail	= FALSE;
						$n	= intval( $this->network->get_user_notif_rules($uid)->ntf_me_if_u_joins_grp );
						if( $n == 2 ) { $send_post = TRUE; } elseif( $n == 3 ) { $send_mail = TRUE; } elseif( $n == 1 ) { $send_post = TRUE; $send_mail = TRUE; }
						if( $send_post ) {
							$lng	= array('#USER#'=>'<a href="'.$C->SITE_URL.$this->info->username.'" title="'.htmlspecialchars($this->info->fullname).'"><span class="mpost_mentioned">@</span>'.$this->info->username.'</a>', '#GROUP#'=>'<a href="'.$C->SITE_URL.$group->groupname.'" title="'.$group->title.'">'.$group->title.'</a>');
							$this->network->send_notification_post($uid, 0, 'msg_ntf_me_if_u_joins_grp', $lng, $C->NOTIF_POSTS_HANDLE);
						}
						if( $send_mail ) {
							$ulng	= trim($this->network->get_user_by_id($uid)->language);
							$lng_txt	= array('#SITE_TITLE#'=>$C->SITE_TITLE, '#USER#'=>'@'.$this->info->username, '#NAME#'=>$this->info->fullname, '#GROUP#'=>$group->title, '#A0#'=>$C->SITE_URL.$group->groupname);
							$lng_htm	= array('#SITE_TITLE#'=>$C->SITE_TITLE, '#USER#'=>'<a href="'.$C->SITE_URL.$this->info->username.'" title="'.htmlspecialchars($this->info->fullname).'" target="_blank">@'.$this->info->username.'</a>', '#NAME#'=>$this->info->fullname, '#GROUP#'=>'<a href="'.$C->SITE_URL.$group->groupname.'" title="'.$group->title.'" target="_blank">'.$group->title.'</a>');
							$subject		= $page->lang('emlsubj_ntf_me_if_u_joins_grp', $lng_txt, $ulng);
							$message_txt	= $page->lang('emltxt_ntf_me_if_u_joins_grp', $lng_txt, $ulng);
							$message_htm	= $page->lang('emlhtml_ntf_me_if_u_joins_grp', $lng_htm, $ulng);
							$this->network->send_notification_email($uid, 'u_joins_grp', $subject, $message_txt, $message_htm);
						}
					}
					$lng	= array('#USER#'=>'<a href="'.$C->SITE_URL.$this->info->username.'" title="'.htmlspecialchars($this->info->fullname).'"><span class="mpost_mentioned">@</span>'.$this->info->username.'</a>', '#GROUP#'=>'<a href="'.$C->SITE_URL.$group->groupname.'" title="'.$group->title.'">'.$group->title.'</a>');
					$this->network->send_notification_post(0, $group_id, 'msg_ntf_grp_if_u_joins', $lng, $C->NOTIF_POSTS_HANDLE);
				}
			}
			else {
				if( ! $this->if_can_leave_group($group_id) ) {
					return FALSE;
				}
				$this->db2->query('DELETE FROM groups_admins WHERE user_id="'.$this->id.'" AND group_id="'.$group_id.'" ');
				$this->db2->query('DELETE FROM groups_followed WHERE user_id="'.$this->id.'" AND group_id="'.$group_id.'" ');
				$this->db2->query('UPDATE groups SET num_followers=num_followers-1 WHERE id="'.$group_id.'" LIMIT 1');
				$not_in_users	= array_keys($this->network->get_user_follows($this->id, FALSE, 'hefollows')->follow_users);
				$not_in_users	= count($not_in_users)==0 ? '' : 'AND user_id NOT IN('.implode(', ', $not_in_users).')';
				$this->db2->query('DELETE FROM post_userbox WHERE user_id="'.$this->id.'" AND post_id IN(SELECT id FROM posts WHERE group_id="'.$group_id.'" AND user_id<>"'.$this->id.'" '.$not_in_users.' )');
				$this->db2->query('DELETE FROM post_userbox_feeds WHERE user_id="'.$this->id.'" AND post_id IN(SELECT id FROM posts WHERE group_id="'.$group_id.'" AND user_id<>"'.$this->id.'" '.$not_in_users.' )');
			}
			//$this->network->get_group_by_id($group_id, TRUE);
			$this->network->get_group_members($group_id, TRUE);
			$this->network->get_user_follows($this->id, TRUE);
			$this->network->get_user_follows($this->id, TRUE, 'hisgroups');
			//$this->get_top_groups(1, TRUE);

			return TRUE;
		}

		public function if_follow_user($user_id)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$res = $this->db2->fetch_field('SELECT id FROM users_followed WHERE who = '.$this->id.' AND whom = '.$user_id.' LIMIT 1');
			return $res? TRUE : FALSE;
		}

		public function if_user_follows_me($user_id)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$res = $this->db2->fetch_field('SELECT id FROM users_followed WHERE whom = '.$this->id.' AND who = '.$user_id.' LIMIT 1');
			return $res? TRUE : FALSE;
		}

		public function if_follow_group($group_id)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$res = $this->db2->fetch_field('SELECT id FROM groups_followed WHERE user_id = "'.$this->id.'" AND group_id = "'.$group_id.'"'.' LIMIT 1');
			return $res? TRUE : FALSE;
		}

		public function if_can_leave_group($group_id)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$r = $this->db2->fetch_field('SELECT COUNT(*) AS c FROM groups_admins WHERE group_id="'.intval($group_id).'" AND user_id<>"'.$this->id.'" LIMIT 1');

			// демек ако има други админи освен мен, мога да куитна
			return ($r > 0)? TRUE : FALSE;
		}

		public function get_top_groups($num)
		{
			if( ! $this->is_logged ) {
				return array();
			}
			$num	= intval($num);
			if( 0 == $num ) {
				return array();
			}

			$data 	= array();
			$tmp	= array_slice($this->network->get_user_follows($this->id, FALSE, 'hisgroups')->follow_groups, 0 , $num, TRUE);
			foreach($tmp as $gid=>$sdf) {
				$g	= $this->network->get_group_by_id($gid);
				if( ! $g ) {
					continue;
				}
				$data[]	= $g;
			}

			return $data;
		}

		public function write_pageview()
		{
			global $C;

			if( ! $this->is_logged || ! $C->write_page_view_is_active ) {
				return FALSE;
			}
			$this->sess['total_pageviews']	++;
			$dt	= date('Y-m-d H');
			$this->db2->query('UPDATE users_pageviews SET pageviews=pageviews+1 WHERE user_id="'.$this->id.'" AND date="'.$dt.'" LIMIT 1');
			if( $this->db2->affected_rows() == 0 ) {
				$this->db2->query('INSERT INTO users_pageviews SET pageviews=1, user_id="'.$this->id.'", date="'.$dt.'" ');
			}
		}

		public function what_to_do_block()
		{
			if( ! $this->is_logged ) {
				return array();
			}

			global $C;
			$data = array();

			if( empty($this->info->position) && empty($this->info->location) && 0==intval($this->info->birthdate) && empty($this->info->gender) && empty($this->info->about_me) && 0==count($this->info->tags) ) {
				$data['prof_info']	= array($C->SITE_URL.'settings/profile', 'os_dbrd_whattodoo_profile');;
			}

			$tmp	= '';
			if( $this->sess['cdetails'] ) {
				unset($this->sess['cdetails']->user_id);
				foreach($this->sess['cdetails'] as $v) { $tmp .= $v; }
			}
			if( empty($tmp) ) {
				$data['cnt_info']	= array($C->SITE_URL.'settings/contacts', 'os_dbrd_whattodoo_contacts');
			}
			if( $this->info->avatar == $C->DEF_AVATAR_USER ) {
				$data['avatar']	= array($C->SITE_URL.'settings/avatar', 'os_dbrd_whattodoo_avatar');
			}
			if( 0 == count($this->network->get_user_follows($this->id, FALSE, 'hefollows')->follow_users) ) {
				$data['followu']	= array($C->SITE_URL.'members', 'os_dbrd_whattodoo_followusr');
			}
			if( $C->show_sent_invites_in_todo ){
				if( ! $this->db2->fetch_field('SELECT id FROM users_invitations WHERE user_id="'.$this->id.'" LIMIT 1') ) {
					$data['invite']	= array($C->SITE_URL.'invite', 'os_dbrd_whattodoo_invite');
				}
			}
			if( 0 == count($this->network->get_user_follows($this->id, FALSE, 'hisgroups')->follow_groups) ) {
				$data['followg']	= array($C->SITE_URL.'groups', 'os_dbrd_whattodoo_followgrp');
			}

			return $data;
		}

		public function get_saved_searches($force_refresh=FALSE)
		{
			if( ! $this->is_logged ) {
				return array();
			}
			global $C;

			if( $C->user_cache_is_activated ){
				$cachekey	= 'n:'.$this->network->id.',usersavedsearches:'.$this->id;
				$data	= $this->cache->get($cachekey);
				if( FALSE!==$data && TRUE!=$force_refresh ) {
					return $data;
				}

				$data = array();
				$this->db2->query('SELECT id, search_key, search_string FROM searches WHERE user_id="'.$this->id.'" ORDER BY id DESC');
				while($tmp = $this->db2->fetch_object()) {
					$tmp->search_key		= stripslashes($tmp->search_key);
					$tmp->search_string	= stripslashes($tmp->search_string);
					$data[$tmp->id]		= $tmp;
				}

				$this->cache->set($cachekey, $data, $GLOBALS['C']->CACHE_EXPIRE);
				return $data;
			}else{
				$data = array();
				$this->db2->query('SELECT id, search_key, search_string FROM searches WHERE user_id="'.$this->id.'" ORDER BY id DESC');
				while($tmp = $this->db2->fetch_object()) {
					$tmp->search_key	= stripslashes($tmp->search_key);
					$tmp->search_string	= stripslashes($tmp->search_string);
					$data[$tmp->id]		= $tmp;
				}

				return $data;
			}
		}

		public function get_my_private_groups_ids($force_refresh = FALSE)
		{
			$private_groups_ids = array();

			if( ! $this->id || ! $this->is_logged ) {
				return $private_groups_ids;
			}
			//global $C;


			/*if( $C->user_cache_is_activated ){
				$cachekey			= 'n:'.$this->network->id.',private_groups,user_id:'.$this->id;
				$private_groups_ids	= $this->cache->get($cachekey);

				if( FALSE !== $private_groups_ids && TRUE!=$force_refresh ) {
					return $private_groups_ids;
				}
			}*/

			$r	= $this->db2->query('SELECT group_id FROM `groups_followed`,`groups` WHERE groups.id=groups_followed.group_id AND groups.is_public=0 AND groups_followed.user_id="'.$this->id.'"
			UNION SELECT group_id FROM `groups_private_members` WHERE user_id="'.$this->id.'"', FALSE);
			while($obj = $this->db2->fetch_object($r)) {
				$private_groups_ids[]	= $obj->group_id;
			}

			/*if( $C->user_cache_is_activated ){
				$this->cache->del($cachekey);
				$this->cache->set($cachekey, $private_groups_ids, $GLOBALS['C']->CACHE_EXPIRE);
			}*/
			return $private_groups_ids;
		}

		public function get_my_post_protected_follower_ids($force_refresh = FALSE)
		{
			if( ! $this->id ) {
				return array();
			}
			global $C;

			$post_protected_user_ids = array();

			/*if( $C->user_cache_is_activated ){
				$cachekey				= 'n:'.$this->network->id.',post_protected_users';
				$post_protected_user_ids	= $this->cache->get($cachekey);

				if( FALSE !== $post_protected_user_ids && TRUE!=$force_refresh ) {
					return $post_protected_user_ids;
				}
			}*/

			$r	= $this->db2->query('SELECT id FROM users WHERE is_posts_protected=1', FALSE);
			while($obj = $this->db2->fetch_object($r)) {
				$post_protected_user_ids[]	= $obj->id;
			}

			$my_followers = array();
			$my_followers = array_keys($this->network->get_user_follows($this->id, FALSE, 'hisfollowers')->followers);
			$my_followers[] = $this->id;

			$post_protected_user_ids = array_intersect($my_followers, $post_protected_user_ids);

			/*if( $C->user_cache_is_activated ){
				$this->cache->del($cachekey);
				$this->cache->set($cachekey, $post_protected_user_ids, $GLOBALS['C']->CACHE_EXPIRE);
			}*/

			return $post_protected_user_ids;
		}
	}

?>