<?php
	
	if( !$this->network->id || !$this->user->is_logged ) {
		$this->redirect('home');
	}
	if( $this->network->id && $C->MOBI_DISABLED ) {
		$this->redirect('mobidisabled');
	}
	
	$this->load_langfile('mobile/global.php');
	$this->load_langfile('mobile/search.php');
	
	if( $this->param('usertag') ) {
		$tmp	= str_replace('/', '', urldecode($this->param('usertag')));
		$this->redirect( $C->SITE_URL.'search/?lookin=users&lookfor='.urlencode(trim($tmp)) );
	}
	if( $this->param('posttag') ) {
		$tmp	= str_replace('/', '', urldecode($this->param('posttag')));
		$this->redirect( $C->SITE_URL.'search/?lookin=posts&lookfor='.urlencode(trim($tmp)) );
	}
	
	$lookins	= array('posts', 'users', 'groups');
	$D->lookin	= 'posts';
	if( isset($_GET['lookin']) && in_array($_GET['lookin'], $lookins) ) {
		$D->lookin	= $_GET['lookin'];
	}
	
	$D->search_string	= isset($_GET['lookfor']) ? urldecode($_GET['lookfor']) : '';
	$D->search_string	= preg_replace('/\s+/us', ' ', $D->search_string);
	$D->search_string	= trim($D->search_string);
	
	$D->page_title	= $this->lang('search_title_'.$D->lookin, array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$D->num_results	= 0;
	$D->num_pages	= 0;
	$D->num_per_page	= 0;
	$D->pg	= isset($_GET['pg']) ? intval($_GET['pg']) : 1;
	$D->paging_url	= $C->SITE_URL.'search/?lookin='.$D->lookin.'&lookfor='.urlencode($D->search_string).'&pg=';
	$D->posts_html	= '';
	$D->users_html	= '';
	$D->groups_html	= '';
	
	if( $D->lookin=='users' && !empty($D->search_string) )
	{
		$uids	= array();
		$tmp	= $db2->e($D->search_string);
		$tmp	= str_replace(array('%','_'), array('\%','\_'), $db2->e($D->search_string));
	
		$db2->query('SELECT id, username, fullname, avatar FROM users WHERE username LIKE "%'.$tmp.'%" OR fullname LIKE "%'.$tmp.'%"  ORDER BY username ASC');
		while($o = $db2->fetch_object()) {
			$uids[$o->id]	= array($o->id, $o->username, $o->fullname, $o->avatar);
		}
		$db2->query('SELECT id, username, fullname, avatar FROM users WHERE tags REGEXP "(^|\,| )'.(preg_quote($tmp)).'($|\,)" ORDER BY username ASC');
		while($o = $db2->fetch_object()) {
			$uids[$o->id]	= array($o->id, $o->username, $o->fullname, $o->avatar);
		}

		$D->num_results	= count($uids);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_USERS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		if( 0 < $D->num_results ) {
			$from	= ($D->pg - 1) * $C->PAGING_NUM_USERS;
			$tmp	= array_slice($uids, $from, $C->PAGING_NUM_USERS, true);
			$i	= 0;
			ob_start();
			
			foreach($tmp as $v) {
				$D->u = new stdClass;
				$D->u->id = $v[0];
				$D->u->username = $v[1];
				$D->u->fullname = $v[2];
				$D->u->avatar = (empty($v[3]))? $C->DEF_AVATAR_USER : $v[3];
				$D->u->list_index	= $i++;

				$this->load_template('mobile/single_user.php');
			}
			
			$D->users_html	= ob_get_contents();
			ob_end_clean();
		}
	}
	elseif( $D->lookin=='groups' && !empty($D->search_string) ) {
		$not_in_groups	= array();
		if( !$this->user->is_logged || !$this->user->info->is_network_admin ) {
			$not_in_groups	= array();
			$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
		}
			
		$gids	= array();
		$tmp	= $db2->e($D->search_string);
		$tmp	= str_replace(array('%','_'), array('\%','\_'), $db2->e($D->search_string));

		$not_in_groups	= (count($not_in_groups)>0)? ' AND id NOT IN('.implode(', ', $not_in_groups).')' : '';
		$db2->query('SELECT id, groupname, title, num_followers, num_posts, is_public, about_me, avatar FROM groups WHERE (groupname LIKE "%'.$tmp.'%" OR title LIKE "%'.$tmp.'%") '.$not_in_groups.' ORDER BY title ASC, num_followers DESC');
		while($o = $db2->fetch_object()) {
			$gids[$o->id]	= array($o->id, $o->groupname, $o->title, $o->num_followers, $o->num_posts, $o->is_public, $o->about_me, $o->avatar);
		}

		$gids	= array_unique($gids);
		$D->num_results	= count($gids);
		$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_GROUPS);
		$D->pg	= min($D->pg, $D->num_pages);
		$D->pg	= max($D->pg, 1);
		if( 0 < $D->num_results ) {
			$from	= ($D->pg - 1) * $C->PAGING_NUM_GROUPS;
			$tmp	= array_slice($gids, $from, $C->PAGING_NUM_GROUPS, true);
			$i	= 0;
			ob_start();

			foreach($tmp as $v) {
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
				$this->load_template('mobile/single_group.php');
			}
			
			$D->groups_html	= ob_get_contents();
			ob_end_clean();
		}
	}
	elseif( $D->lookin=='posts' && !empty($D->search_string) ) {
		$D->error	= FALSE;
		$D->errmsg	= '';
		if( !$D->error ) {
			$in_where	= '(api_id=2 OR user_id<>0)';
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
			$in_where	.= ')';
			$not_in_groups	= array();
			$without_users 	= array();
			if( !$this->user->info->is_network_admin ) {
				$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
				$in_where		.= count($not_in_groups)>0 ? ('AND group_id NOT IN('.implode(', ', $not_in_groups).')') : '';
				
				
				$without_users 	= array_diff( $this->network->get_post_protected_user_ids(), $this->user->get_my_post_protected_follower_ids() ); 
				$in_where 		.= count($without_users)>0 ? (' AND (group_id>0 OR user_id NOT IN('.implode(', ', $without_users).'))') : '';	
			}
			
			//if( preg_match('/^\#[а-яa-z0-9\-_]{1,50}$/iu', $D->search_string) ) {
			//	$in_where	.= ' AND posttags>0';
			//}
			
			$D->num_results 	= $db2->fetch_field('SELECT COUNT(*) FROM posts WHERE '.$in_where);
			
			if( 0 < $D->num_results ) {
				$D->num_pages	= ceil($D->num_results / $C->PAGING_NUM_POSTS);
				$D->pg	= min($D->pg, $D->num_pages);
				$D->pg	= max($D->pg, 1);
				$from	= ($D->pg - 1) * $C->PAGING_NUM_POSTS;
				

				$res	= $db2->query('SELECT *, "public" AS `type` FROM posts WHERE '.$in_where.' ORDER BY id DESC LIMIT '.$from.', '.$C->PAGING_NUM_POSTS);
	
				$i	= 0;
				ob_start();
				while($obj = $db2->fetch_object($res)) {
					$D->p	= new post($obj->type, FALSE, $obj);
					if( $D->p->error ) {
						continue;
					}
					$D->p->list_index	= $i++;
					$this->load_template('mobile/single_post.php');
				}
				$D->posts_html	= ob_get_contents();
				ob_end_clean();
			}
		}
	}
	
	$this->load_template('mobile/search.php');
	
?>