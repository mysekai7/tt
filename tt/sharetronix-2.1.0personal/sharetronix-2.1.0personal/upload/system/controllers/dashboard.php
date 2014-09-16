<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/dashboard.php');
	
	$D->page_title	= $this->lang('dashboard_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	if( $this->param('from')=='ajax' && isset($_POST['toggle_whattodo']) ) {
		$tmp	= intval($_POST['toggle_whattodo'])==0 ? 1 : 0;
		$this->db2->query('UPDATE users SET dbrd_whattodo_closed="'.$tmp.'" WHERE id="'.$this->user->id.'" LIMIT 1');
		$this->user->info->dbrd_whattodo_closed	= $tmp;
		$this->network->get_user_by_id($this->user->id, TRUE);
		echo 'OK';
		exit;
	}
	if( $this->param('from')=='ajax' && isset($_POST['toggle_grpmenu']) ) {
		$tmp	= intval($_POST['toggle_grpmenu'])==0 ? 1 : 0;
		$this->db2->query('UPDATE users SET dbrd_groups_closed="'.$tmp.'" WHERE id="'.$this->user->id.'" LIMIT 1');
		$this->user->info->dbrd_groups_closed	= $tmp;
		$this->network->get_user_by_id($this->user->id, TRUE);
		echo 'OK';
		exit;
	}
	if( $this->param('from')=='ajax' && isset($_POST['hide_wrongaddr_warning']) ) {
		setcookie('wrongaddr_warning_'.md5($C->DOMAIN), 'off', time()+7*24*60*60, '/', cookie_domain());
		echo 'OK';
		exit;
	}
	$D->groupsmenu_active	= $this->user->info->dbrd_groups_closed==1 ? FALSE : TRUE;
	
	$D->rss_feeds	= array(
		array( $C->SITE_URL.'rss/my:dashboard',	$this->lang('rss_mydashboard',array('#USERNAME#'=>$this->user->info->username)), ),
		array( $C->SITE_URL.'rss/my:posts',		$this->lang('rss_myposts',array('#USERNAME#'=>$this->user->info->username)), ),
		array( $C->SITE_URL.'rss/my:private',	$this->lang('rss_myprivate',array('#USERNAME#'=>$this->user->info->username)), ),
		array( $C->SITE_URL.'rss/my:mentions',	$this->lang('rss_mymentions',array('#USERNAME#'=>$this->user->info->username)), ),
		array( $C->SITE_URL.'rss/my:bookmarks',	$this->lang('rss_mybookmarks',array('#USERNAME#'=>$this->user->info->username)), ),
		array( $C->SITE_URL.'rss/all:posts',	$this->lang('rss_everybody',array('#SITE_TITLE#'=>$C->SITE_TITLE)), ),
	);
	
	
	$tabs		= array('all', '@me', 'private', 'commented', 'bookmarks', 'everybody', 'group', 'reshares', 'feeds', 'tweets');
	$filters	= array('all', 'videos', 'images', 'links', 'files');
	$at_tmp	= array('videos'=>'videoembed', 'images'=>'image', 'links'=>'link', 'files'=>'file');

	$tab	= 'all';
	if( $this->param('tab') && in_array($this->param('tab'), $tabs) ) {
		$tab	= $this->param('tab');
	}
	$filter	= 'all';
	if( $this->param('filter') && in_array($this->param('filter'), $filters) ) {
		$filter	= $this->param('filter');
	}
	
	$reshtabs 	= array('byme', 'byother');
	$reshtab	= ($tab=='reshares' && $this->param('reshtab')!= '')? $this->param('reshtab'):'byme';
	if(!in_array($reshtab, $reshtabs)) $reshtab = 'byme';
	
	$privtabs	= array('all', 'inbox', 'sent', 'usr');
	$privtab	= 'all';
	$privusr	= FALSE;
	if($tab=='private' && $this->param('privtab') && in_array($this->param('privtab'),$privtabs)) {
		$privtab	= $this->param('privtab');
	}
	if($tab=='private' && $privtab=='usr' && $this->param('usr') && $this->param('usr')!=$this->user->info->username) {
		$privusr	= $this->network->get_user_by_username($this->param('usr'));
	}
	
	$onlygroup	= FALSE;
	if($tab=='group' && $this->param('g')) {
		$onlygroup	= $this->network->get_group_by_name($this->param('g'));
		if( ! $onlygroup ) {
			$tab	= 'all';
			$onlygroup	= FALSE;
		}
		elseif( ! isset( $this->network->get_user_follows($this->user->id, FALSE, 'hisgroups')->follow_groups[$onlygroup->id] ) ) {
			$tab	= 'all';
			$onlygroup	= FALSE;
		}
	}
	
	$not_in_groups	= '';
	$without_users	= '';
	if( !$this->user->is_logged || !$this->user->info->is_network_admin ) {
		$not_in_groups	= array();
		$not_in_groups 	= array_diff( $this->network->get_private_groups_ids(), $this->user->get_my_private_groups_ids() ); 
		$not_in_groups	= count($not_in_groups)>0 ? ('AND p.group_id NOT IN('.implode(', ', $not_in_groups).')') : '';
		
		$without_users = array();
		$without_users = array_diff( $this->network->get_post_protected_user_ids(), $this->user->get_my_post_protected_follower_ids() ); 
		$without_users = count($without_users)>0 ? (' AND (p.group_id>0 OR p.user_id NOT IN('.implode(', ', $without_users).'))') : '';	
	}
	
	$q1	= '';
	$q2	= '';
	switch($tab)
	{
		case 'all':
			if($filter == 'all') {
				$q1	= 'SELECT COUNT(*) FROM post_userbox WHERE user_id="'.$this->user->id.'" ';
				$q2	= 'SELECT p.*, "public" AS `type` FROM post_userbox b LEFT JOIN posts p ON p.id=b.post_id WHERE b.user_id="'.$this->user->id.'" ORDER BY b.id DESC ';
			}
			else {
				$q1	= 'SELECT COUNT(b.post_id) FROM post_userbox b, posts_attachments a FORCE INDEX (post_type_IDX) WHERE b.post_id=a.post_id AND a.type="'.$at_tmp[$filter].'" AND b.user_id="'.$this->user->id.'" ';
				$q2	= 'SELECT p.*, "public" AS `type` FROM post_userbox b LEFT JOIN posts p ON p.id=b.post_id, posts_attachments a WHERE b.post_id=a.post_id AND a.type="'.$at_tmp[$filter].'" AND b.user_id="'.$this->user->id.'" ORDER BY b.id DESC ';
			}
			break;
			
		case 'feeds':
			if($filter == 'all') {
				$q1	= 'SELECT COUNT(*) FROM post_userbox_feeds WHERE user_id="'.$this->user->id.'" ';
				$q2	= 'SELECT p.*, "public" AS `type` FROM post_userbox_feeds b LEFT JOIN posts p ON p.id=b.post_id WHERE b.user_id="'.$this->user->id.'" ORDER BY b.id DESC ';
			}
			else {
				$q1	= 'SELECT COUNT(b.post_id) FROM post_userbox_feeds b, posts_attachments a FORCE INDEX (post_type_IDX) WHERE b.post_id=a.post_id AND a.type="'.$at_tmp[$filter].'" AND b.user_id="'.$this->user->id.'" ';
				$q2	= 'SELECT p.*, "public" AS `type` FROM post_userbox_feeds b LEFT JOIN posts p ON p.id=b.post_id, posts_attachments a WHERE b.post_id=a.post_id AND a.type="'.$at_tmp[$filter].'" AND b.user_id="'.$this->user->id.'" ORDER BY b.id DESC ';
			}
			break;
			
		case 'tweets':
			if($filter == 'all') {
				$q1	= 'SELECT COUNT(*) FROM post_userbox_tweets WHERE user_id="'.$this->user->id.'" ';
				$q2	= 'SELECT p.*, "public" AS `type` FROM post_userbox_tweets b LEFT JOIN posts p ON p.id=b.post_id WHERE b.user_id="'.$this->user->id.'" ORDER BY b.id DESC ';
			}
			else {
				$q1	= 'SELECT COUNT(b.post_id) FROM post_userbox_feeds b, posts_attachments a FORCE INDEX (post_type_IDX) WHERE b.post_id=a.post_id AND a.type="'.$at_tmp[$filter].'" AND b.user_id="'.$this->user->id.'" ';
				$q2	= 'SELECT p.*, "public" AS `type` FROM post_userbox_feeds b LEFT JOIN posts p ON p.id=b.post_id, posts_attachments a WHERE b.post_id=a.post_id AND a.type="'.$at_tmp[$filter].'" AND b.user_id="'.$this->user->id.'" ORDER BY b.id DESC ';
			}
			break;
			
		case '@me':
			if($filter == 'all') {
				$q1	= 'SELECT COUNT(p.id) FROM posts p INNER JOIN (SELECT post_id FROM posts_mentioned WHERE user_id="'.$this->user->id.'" UNION SELECT p.post_id FROM posts_comments p, posts_comments_mentioned c WHERE c.comment_id = p.id AND c.user_id ="'.$this->user->id.'") x ON x.post_id=p.id '.$not_in_groups.$without_users;
				$q2	= 'SELECT DISTINCT p.*, "public" AS `type` FROM posts p INNER JOIN (SELECT pm.post_id, p.date FROM posts_mentioned pm, posts p WHERE pm.user_id="'.$this->user->id.'" AND pm.post_id=p.id UNION SELECT p.post_id, p.date FROM posts_comments p, posts_comments_mentioned c WHERE c.comment_id = p.id AND c.user_id ="'.$this->user->id.'") x ON x.post_id=p.id '.$not_in_groups.$without_users.' ORDER BY x.date DESC ';
			}
			else {
				$q1	= 'SELECT COUNT(p.id) FROM posts p JOIN posts_attachments a FORCE INDEX (post_type_IDX) ON p.id=a.post_id AND a.type="'.$at_tmp[$filter].'" INNER JOIN (SELECT post_id FROM posts_mentioned WHERE user_id="'.$this->user->id.'" UNION SELECT p.post_id FROM posts_comments p, posts_comments_mentioned c WHERE c.comment_id = p.id AND c.user_id ="'.$this->user->id.'") x ON x.post_id=p.id '.$not_in_groups.$without_users.' ';
				$q2	= 'SELECT DISTINCT p.*, "public" AS `type` FROM posts p JOIN posts_attachments a ON p.id=a.post_id AND a.type="'.$at_tmp[$filter].'" INNER JOIN (SELECT post_id FROM posts_mentioned WHERE user_id="'.$this->user->id.'" UNION SELECT p.post_id FROM posts_comments p, posts_comments_mentioned c WHERE c.comment_id = p.id AND c.user_id ="'.$this->user->id.'") x ON x.post_id=p.id '.$not_in_groups.$without_users.' ORDER BY p.id DESC ';
			}
			break;
		
		case 'commented':
			if($filter == 'all') {
				$q1	= '
					SELECT
					(SELECT COUNT(*) FROM posts_comments_watch WHERE user_id="'.$this->user->id.'" AND post_id IN(SELECT id FROM posts WHERE comments>0) )
					+
					(SELECT COUNT(*) FROM posts_pr_comments_watch WHERE user_id="'.$this->user->id.'" AND post_id IN(SELECT id FROM posts_pr WHERE comments>0) )';
				$q2	= '
					(SELECT p.id, p.api_id, p.user_id, p.group_id, "0" AS to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, p.reshares, p.date, p.ip_addr, "0" AS is_recp_del, "public" AS `type`, p.date_lastcomment AS cdt FROM posts_comments_watch w LEFT JOIN posts p ON p.id=w.post_id WHERE w.user_id="'.$this->user->id.'" AND p.comments>0)
					UNION
					(SELECT p.id, p.api_id, p.user_id, "0" AS group_id, p.to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, "0" AS reshares, p.date, p.ip_addr, p.is_recp_del, "private" AS `type`, p.date_lastcomment AS cdt FROM posts_pr_comments_watch w LEFT JOIN posts_pr p ON p.id=w.post_id WHERE w.user_id="'.$this->user->id.'" AND p.comments>0 AND (p.user_id="'.$this->user->id.'" OR (p.to_user="'.$this->user->id.'" && p.is_recp_del=0) ) )
					ORDER BY cdt DESC ';
			}
			else {
				$q1	= '
					SELECT
					(SELECT COUNT(w.post_id) FROM posts_comments_watch w, posts_attachments a WHERE w.post_id=a.post_id AND w.user_id="'.$this->user->id.'" AND a.type="'.$at_tmp[$filter].'")
					+
					(SELECT COUNT(w.post_id) FROM posts_pr_comments_watch w, posts_pr_attachments a WHERE w.post_id=a.post_id AND w.user_id="'.$this->user->id.'" AND a.type="'.$at_tmp[$filter].'")';
				$q2	= '
					(SELECT p.id, p.api_id, p.user_id, p.group_id, "0" AS to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, p.reshares, p.date, p.ip_addr, "0" AS is_recp_del, "public" AS `type`, p.date_lastcomment AS cdt FROM posts_comments_watch w LEFT JOIN posts p ON p.id=w.post_id, posts_attachments a WHERE w.post_id=a.post_id AND w.user_id="'.$this->user->id.'" AND a.type="'.$at_tmp[$filter].'" AND p.comments>0)
					UNION
					(SELECT p.id, p.api_id, p.user_id, "0" AS group_id, p.to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, "0" AS reshares, p.date, p.ip_addr, p.is_recp_del, "private" AS `type`, p.date_lastcomment AS cdt FROM posts_pr_comments_watch w LEFT JOIN posts_pr p ON p.id=w.post_id, posts_pr_attachments a WHERE w.post_id=a.post_id AND w.user_id="'.$this->user->id.'" AND a.type="'.$at_tmp[$filter].'" AND p.comments>0 AND (p.user_id="'.$this->user->id.'" OR (p.to_user="'.$this->user->id.'" && p.is_recp_del=0) ) )
					ORDER BY cdt DESC ';
			}
			break;
		
		case 'everybody':
			if($filter == 'all') {
				$q1	= 'SELECT COUNT(*) FROM posts p WHERE p.user_id<>0 AND p.api_id<>2 AND p.api_id<>6 '.$not_in_groups.$without_users;
				$q2	= 'SELECT p.*, "public" AS `type` FROM posts p WHERE p.user_id<>0 AND p.api_id<>2 AND p.api_id<>6 '.$not_in_groups.$without_users.' ORDER BY p.id DESC ';
			}
			else {
				$q1	= 'SELECT COUNT(p.id) FROM posts p, posts_attachments a WHERE p.id=a.post_id AND p.user_id<>0 AND p.api_id<>2 AND p.api_id<>6 '.$not_in_groups.$without_users.' AND a.type="'.$at_tmp[$filter].'" ';
				$q2	= 'SELECT p.*, "public" AS `type` FROM posts p, posts_attachments a WHERE p.id=a.post_id AND p.user_id<>0 AND p.api_id<>2 AND p.api_id<>6 '.$not_in_groups.$without_users.' AND a.type="'.$at_tmp[$filter].'" ORDER BY p.id DESC ';
			}
			break;
			
		case 'bookmarks':
			if($filter == 'all') {
				$q1	= 'SELECT COUNT(*) FROM post_favs WHERE user_id="'.$this->user->id.'"';
				$q2	= '
					(SELECT p.id, p.api_id, p.user_id, p.group_id, "0" AS to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, p.reshares, p.date, p.ip_addr, "0" AS is_recp_del, "public" AS `type`, f.id AS fid FROM post_favs f LEFT JOIN posts p ON p.id=f.post_id WHERE f.user_id="'.$this->user->id.'" AND f.post_type="public")
					UNION
					(SELECT p.id, p.api_id, p.user_id, "0" AS group_id, p.to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, "0" AS reshares, p.date, p.ip_addr, p.is_recp_del, "private" AS `type`, f.id AS fid FROM post_favs f LEFT JOIN posts_pr p ON p.id=f.post_id WHERE f.user_id="'.$this->user->id.'" AND f.post_type="private")
					ORDER BY fid DESC ';
			}
			else {
				$q1	= '
					SELECT
					(SELECT COUNT(f.post_id) FROM post_favs f, posts_attachments a WHERE f.post_id=a.post_id AND f.user_id="'.$this->user->id.'" AND f.post_type="public" AND a.type="'.$at_tmp[$filter].'")
					+
					(SELECT COUNT(f.post_id) FROM post_favs f, posts_pr_attachments a WHERE f.post_id=a.post_id AND f.user_id="'.$this->user->id.'" AND f.post_type="private" AND a.type="'.$at_tmp[$filter].'")';
				$q2	= '
					(SELECT p.id, p.api_id, p.user_id, p.group_id, "0" AS to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, p.reshares, p.date, p.ip_addr, "0" AS is_recp_del, "public" AS `type`, f.id AS fid FROM post_favs f LEFT JOIN posts p ON p.id=f.post_id, posts_attachments a WHERE f.post_id=a.post_id AND f.user_id="'.$this->user->id.'" AND f.post_type="public" AND a.type="'.$at_tmp[$filter].'")
					UNION
					(SELECT p.id, p.api_id, p.user_id, "0" AS group_id, p.to_user, p.message, p.mentioned, p.attached, p.posttags, p.comments, "0" AS reshares, p.date, p.ip_addr, p.is_recp_del, "private" AS `type`, f.id AS fid FROM post_favs f LEFT JOIN posts_pr p ON p.id=f.post_id, posts_pr_attachments a WHERE f.post_id=a.post_id AND f.user_id="'.$this->user->id.'" AND f.post_type="public" AND a.type="'.$at_tmp[$filter].'")
					ORDER BY fid DESC ';
			}
			break;
		
		case 'reshares':
			if($reshtab == 'byother'){
				$q1 = 'SELECT COUNT(DISTINCT a.post_id) FROM posts_reshares a, posts p WHERE a.post_id=p.id AND p.user_id="'.$this->user->id.'" ';
				$q2 = 'SELECT *, "public" AS `type` FROM posts_reshares a, posts p WHERE a.post_id=p.id AND p.user_id="'.$this->user->id.'" GROUP BY a.post_id ORDER BY a.id DESC ';
			}else{
				$q1 = 'SELECT COUNT(*) FROM posts_reshares WHERE user_id="'.$this->user->id.'"';
				$q2 = 'SELECT *, "public" AS `type` FROM posts_reshares a, posts p WHERE a.post_id=p.id AND a.user_id="'.$this->user->id.'" ORDER BY a.id DESC ';
			}
			break;
			
		case 'private':
			
			if($privtab == 'all') {
				if($filter == 'all') {
					$q1	= 'SELECT COUNT(*) FROM posts_pr WHERE (user_id="'.$this->user->id.'" OR (to_user="'.$this->user->id.'" AND is_recp_del=0))';
					$q2	= 'SELECT *, "private" AS `type` FROM posts_pr WHERE (user_id="'.$this->user->id.'" OR (to_user="'.$this->user->id.'" AND is_recp_del=0)) ORDER BY date_lastcomment DESC, id DESC ';
				}
				else {
					$q1	= 'SELECT COUNT(p.id) FROM posts_pr p, posts_pr_attachments a WHERE p.id=a.post_id AND (p.user_id="'.$this->user->id.'" OR (p.to_user="'.$this->user->id.'" AND p.is_recp_del=0)) AND a.type="'.$at_tmp[$filter].'"';
					$q2	= 'SELECT p.*, "private" AS `type` FROM posts_pr p, posts_pr_attachments a WHERE p.id=a.post_id AND (p.user_id="'.$this->user->id.'" OR (p.to_user="'.$this->user->id.'" AND p.is_recp_del=0)) AND a.type="'.$at_tmp[$filter].'" ORDER BY p.date_lastcomment DESC, p.id DESC ';
				}
			}
			if($privtab == 'inbox') {
				if($filter == 'all') {
					$q1	= 'SELECT COUNT(*) FROM posts_pr WHERE to_user="'.$this->user->id.'" AND is_recp_del=0';
					$q2	= 'SELECT *, "private" AS `type` FROM posts_pr WHERE to_user="'.$this->user->id.'" AND is_recp_del=0 ORDER BY date_lastcomment DESC, id DESC ';
				}
				else {
					$q1	= 'SELECT COUNT(p.id) FROM posts_pr p, posts_pr_attachments a WHERE p.id=a.post_id AND p.to_user="'.$this->user->id.'" AND p.is_recp_del=0 AND a.type="'.$at_tmp[$filter].'"';
					$q2	= 'SELECT p.*, "private" AS `type` FROM posts_pr p, posts_pr_attachments a WHERE p.id=a.post_id AND p.to_user="'.$this->user->id.'" AND p.is_recp_del=0 AND a.type="'.$at_tmp[$filter].'" ORDER BY p.date_lastcomment DESC, p.id DESC ';
				}
			}
			elseif($privtab == 'sent') {
				if($filter == 'all') {
					$q1	= 'SELECT COUNT(*) FROM posts_pr WHERE user_id="'.$this->user->id.'"';
					$q2	= 'SELECT *, "private" AS `type` FROM posts_pr WHERE user_id="'.$this->user->id.'" ORDER BY date_lastcomment DESC, id DESC ';
				}
				else {
					$q1	= 'SELECT COUNT(p.id) FROM posts_pr p, posts_pr_attachments a WHERE p.id=a.post_id AND p.user_id="'.$this->user->id.'" AND a.type="'.$at_tmp[$filter].'"';
					$q2	= 'SELECT p.*, "private" AS `type` FROM posts_pr p, posts_pr_attachments a WHERE p.id=a.post_id AND p.user_id="'.$this->user->id.'" AND a.type="'.$at_tmp[$filter].'" ORDER BY p.date_lastcomment DESC, p.id DESC ';
				}
			}
			elseif($privtab == 'usr' && $privusr) {
				if($filter == 'all') {
					$q1	= 'SELECT COUNT(*) FROM posts_pr WHERE ((user_id="'.$this->user->id.'" AND to_user="'.$privusr->id.'") OR (user_id="'.$privusr->id.'" AND to_user="'.$this->user->id.'" AND is_recp_del=0))';
					$q2	= 'SELECT *, "private" AS `type` FROM posts_pr WHERE ((user_id="'.$this->user->id.'" AND to_user="'.$privusr->id.'") OR (user_id="'.$privusr->id.'" AND to_user="'.$this->user->id.'" AND is_recp_del=0)) ORDER BY date_lastcomment DESC, id DESC ';
				}
				else {
					$q1	= 'SELECT COUNT(p.id) FROM posts_pr p, posts_pr_attachments a WHERE p.id=a.post_id AND ((p.user_id="'.$this->user->id.'" AND p.to_user="'.$privusr->id.'") OR (p.user_id="'.$privusr->id.'" AND p.to_user="'.$this->user->id.'" AND p.is_recp_del=0)) AND a.type="'.$at_tmp[$filter].'"';
					$q2	= 'SELECT p.*, "private" AS `type` FROM posts_pr p, posts_pr_attachments a WHERE p.id=a.post_id AND ((p.user_id="'.$this->user->id.'" AND p.to_user="'.$privusr->id.'") OR (p.user_id="'.$privusr->id.'" AND p.to_user="'.$this->user->id.'" AND p.is_recp_del=0)) AND a.type="'.$at_tmp[$filter].'" ORDER BY p.date_lastcomment DESC, p.id DESC ';
				}
			}
			break;
		
		case 'group':
			if($filter == 'all') {
				$q1	= 'SELECT COUNT(*) FROM posts WHERE group_id="'.$onlygroup->id.'"';
				$q2	= 'SELECT *, "public" AS `type` FROM posts WHERE group_id="'.$onlygroup->id.'" ORDER BY id DESC ';
			}
			else {
				$q1	= 'SELECT COUNT(p.id) FROM posts p, posts_attachments a WHERE p.id=a.post_id AND group_id="'.$onlygroup->id.'" AND a.type="'.$at_tmp[$filter].'" ';
				$q2	= 'SELECT p.*, "public" AS `type` FROM posts p, posts_attachments a WHERE p.id=a.post_id AND group_id="'.$onlygroup->id.'" AND a.type="'.$at_tmp[$filter].'" ORDER BY p.id DESC ';
			}
			break;
	}
	$D->tab		= $tab;
	$D->filter		= $filter;
	$D->reshtab		= $reshtab;
	$D->privtab		= $privtab;
	$D->privusr		= $privusr;
	$D->onlygroup	= $onlygroup;
	$D->num_results	= 0;
	$D->num_pages	= 0;
	$D->pg		= 1;
	$D->posts_html	= '';
	
	if( $q1!='' && $q2!='' ) {
		$D->num_results	= $db2->fetch_field($q1);
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
			$tmpposts[] = $buff;
			if( $this->param('from')=='ajax' && $this->param('onlypost')!="" && $this->param('onlypost')!=$buff->post_tmp_id ) {
				continue;
			}
			$tmpids[]	= $buff->post_tmp_id;
			$postusrs[]	= $buff->post_user->id;
		}
		unset($buff);
		$postusrs = array_unique($postusrs);
		
		post::preload_num_new_comments($tmpids);
		ob_start();
		
		$D->if_follow_me = array();
		if( count($postusrs)>0 ){
			$r = $db2->query('SELECT who FROM users_followed WHERE who IN ('.implode(',', $postusrs).') AND whom="'.$this->user->id.'"');
			while($o = $db2->fetch_object($r)){
				if( isset($D->if_follow_me[$o->who]) ){
					continue;
				}
				$D->if_follow_me[$o->who] = 1;
			}
		}
		$D->i_follow	= array_fill_keys(array_keys($this->network->get_user_follows($this->user->id, FALSE, 'hefollows')->follow_users), 1); 
		$D->i_follow[] 	= $this->user->id; 
		
		foreach($tmpposts as $tmp) {
			$D->p	= $tmp;
			$D->post_show_slow	= FALSE;
			if( $this->param('from')=='ajax' && isset($_POST['lastpostdate']) && $D->p->post_date>intval($_POST['lastpostdate']) ) {
				$D->post_show_slow	= TRUE;
			}
			if( $this->param('from')=='ajax' && $this->param('onlypost')!="" && $this->param('onlypost')!=$D->p->post_tmp_id ) {
				continue;
			}
			$D->parsedpost_attlink_maxlen	= 52;
			$D->parsedpost_attfile_maxlen	= 48;
			if( isset($D->p->post_attached['image']) ) {
				$D->parsedpost_attlink_maxlen	-= 10;
				$D->parsedpost_attfile_maxlen	-= 12;
			}
			if( isset($D->p->post_attached['videoembed']) ) {
				$D->parsedpost_attlink_maxlen	-= 10;
				$D->parsedpost_attfile_maxlen	-= 12;
			}
			$D->show_my_email = FALSE;
			if( isset( $D->if_follow_me[$D->p->post_user->id] ) || $D->p->post_user->id == $this->user->id || $this->user->info->is_network_admin ){
				$D->show_my_email = TRUE;
			}
			
			$D->protected_profile = FALSE;
			$right_post_type = (!$D->p->is_system_post && !$D->p->is_feed_post);
			
			if($right_post_type && !$D->show_my_email && $D->p->post_user->is_profile_protected){
				$D->protected_profile = TRUE;
			}
			
			$D->show_reshared_design = FALSE;
			if($this->param('tab') == 'reshares'){
				$D->show_reshared_design = TRUE;
			}elseif( count( array_intersect(array_keys($D->p->post_reshares) , array_keys($D->i_follow)) )>0 ){
				$D->show_reshared_design = TRUE;
			}
			
			$this->load_template('single_post.php');
		}
		unset($D->p, $tmp, $tmpposts, $tmpids, $right_post_type);
		
		if( $tab == 'private' ) {
			$pr_usr = ($this->param('usr'))? 'usr:'.$this->param('usr').'/':'';
			$D->paging_url	= $C->SITE_URL.'dashboard/tab:private/'.$pr_usr.'privtab:'.$privtab.'/filter:'.$filter.'/pg:';
		}
		elseif( $tab == 'group' ) {
			$D->paging_url	= $C->SITE_URL.'dashboard/tab:group/g:'.$onlygroup->groupname.'/filter:'.$filter.'/pg:';
		}
		else {
			$D->paging_url	= $C->SITE_URL.'dashboard/tab:'.$tab.'/filter:'.$filter.'/pg:';
		}
		if( $D->num_pages>1 && !$this->param('onlypost') ) {
			$this->load_template('paging_posts.php');
		}
		$D->posts_html	= ob_get_contents();
		ob_end_clean();
	}
	
	if( 0 == $D->num_results ) {
		if( ! ($tab=='private' && $privtab=='usr' && !$privusr) ) {
			$arr	= array('#USERNAME#'=>$this->user->info->username, '#SITE_TITLE#'=>htmlspecialchars($C->OUTSIDE_SITE_TITLE), '#A1#'=>'<a href="javascript:;" onclick="postform_open();">', '#A2#'=>'</a>', );
			$lngkey_ttl	= 'noposts_dtb_'.$tab.'_ttl';
			$lngkey_txt	= 'noposts_dtb_'.$tab.'_txt';
			if( $tab == 'private' ) {
				$lngkey_ttl	.= '_'.$privtab;
				$lngkey_txt	.= '_'.$privtab;
				if( $privtab == 'usr' && $privusr ) {
					$arr['#USERNAME2#']	= '<a href="'.$C->SITE_URL.$privusr->username.'">'.$privusr->username.'</a>';
					$arr['#A3#']	= '<a href="javascript:;" onclick="postform_open(({username:\''.$privusr->username.'\'}));">';
					$arr['#A4#']	= '</a>';
				}
			}elseif($tab == 'reshares'){
				$lngkey_ttl	.= '_'.$reshtab;
				$lngkey_txt	.= '_'.$reshtab;
			}else {
				if( $tab == 'group' ) {
					$arr['#GROUP#']	= '<a href="'.$C->SITE_URL.$onlygroup->groupname.'">'.htmlspecialchars($onlygroup->title).'</a>';
					$arr['#A1#']	= '<a href="javascript:;" onclick="postform_open(({groupname:\''.htmlspecialchars($onlygroup->title).'\'}));">';
					$arr['#A2#']	= '</a>';
				}
				if( $filter != 'all' ) {
					$lngkey_ttl	.= '_filter';
					$lngkey_txt	.= '_filter';
				}
			}
			$D->noposts_box_title	= $this->lang($lngkey_ttl, $arr);
			$D->noposts_box_text	= $this->lang($lngkey_txt, $arr);
			$D->posts_html	= $this->load_template('noposts_box.php', FALSE);
		}
	}

	if( $this->param('from') == 'ajax' )
	{
		echo 'OK:';
		echo $D->posts_html;
		exit;
	}
	
	$D->menu_groups	= $this->user->get_top_groups(5);
	
	$D->tabs_state	= $this->network->get_dashboard_tabstate($this->user->id, array('all','@me','private','commented','feeds', 'tweets'), $tab);
	if( isset($D->tabs_state[$tab]) ) {
		$D->tabs_state[$tab]	= 0;
	}
	
	$D->whattodo_active	= FALSE;
	$D->whattodo_minimized	= FALSE;	
	$D->whattodo_lines	= $this->user->what_to_do_block();
	
	if( count($D->whattodo_lines) > 0 ) {
		$D->whattodo_active	= TRUE;
	}
	if( $this->user->info->dbrd_whattodo_closed == 1 ) {
		$D->whattodo_minimized	= TRUE;
	}
	
	$D->last_online	= $this->network->get_online_users();
	
	$D->saved_searches	= array();
	$D->saved_searches	= $this->user->get_saved_searches();
	
	$D->post_tags	= array();
	$D->post_tags	= $this->network->get_recent_posttags();
	
	$D->mobi_site_url = str_cut(preg_replace('/^http\:\/\//iu', '', $C->SITE_URL).'m', 23);
	
	$this->load_template('dashboard.php');
	
?>