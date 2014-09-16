<?php
	
	$min_postid_today	= FALSE;
	if( $tmp = $cache->get('cron_actvgrp_'.date('Ymd')) ) {
		$min_postid_today	= $tmp;
	}
	if( ! $min_postid_today ) {
		$min_postid_today		= $db2->fetch_field('SELECT MIN(id) FROM posts WHERE date>"'.gmmktime(0, 0, 1, date('m'), date('d'), date('Y')).'" ');
		if( $min_postid_today ) {
			$cache->set('cron_actvgrp_'.date('Ymd'), $min_postid_today, 24*60*60);
		}
	}
	$notif_posts	= array();
	if( $min_postid_today ) {
		$db2->query('SELECT id, message FROM posts WHERE id>="'.$min_postid_today.'" AND api_id=0 AND user_id=0 ORDER BY id DESC');
		while($tmp = $db2->fetch_object()) {
			$tmp->message	= @unserialize(stripslashes($tmp->message));
			if( !$tmp->message || !isset($tmp->message->type) || $tmp->message->type!='notif' ) {
				continue;
			}
			$notif_posts[intval($tmp->id)]	= clone($tmp->message);
		}	
	}
	
	$for_groups	= array();
	$for_users	= array();
	if( count($notif_posts) ) {
		foreach($notif_posts as $id=>$p) {
			if( $p->to_user_id==0 && $p->in_group_id>0 ) {
				if( ! isset($for_groups[$p->in_group_id]) ) {
					$for_groups[$p->in_group_id]	= array();
				}
				$for_groups[$p->in_group_id][$id]	= clone($p);
			}
			if( $p->in_group_id==0 && $p->to_user_id>0 ) {
				if( ! isset($for_users[$p->to_user_id]) ) {
					$for_users[$p->to_user_id]	= array();
				}
				$for_users[$p->to_user_id][$id]	= clone($p);
			}
		}
		unset($notif_posts);
		foreach($for_groups as $id=>$ps) {
			if( count($ps) == 1 ) {
				unset($for_groups[$id]);
			}
		}
		foreach($for_users as $id=>$ps) {
			if( count($ps) == 1 ) {
				unset($for_users[$id]);
			}
		}
	}
	
	if( count($for_groups) )
	{
		foreach($for_groups as $id=>$ps)
		{
			$join_ones	= array();
			$join_to	= FALSE;
			foreach($ps as $pid=>$p) {
				if( $p->lang_key == 'msg_ntf_grp_if_u_joins' ) {
					$join_ones[$pid]	= isset($p->lang_params['#USERS#']) ? $p->lang_params['#USERS#'] : $p->lang_params['#USER#'];
				}
				if( $p->lang_key == 'ntfcombined_ntf_grp_if_u_joins' ) {
					$join_to	= $pid;
				}
			}
			if( ! count($join_ones) ) { continue; }
			if( ! $join_to ) {
				$join_to	= key($join_ones);
				unset($join_ones[$join_to]);
			}
			if( ! count($join_ones) ) { continue; }
			$p	= $ps[$join_to];
			$p->lang_key	= 'ntfcombined_ntf_grp_if_u_joins';
			if( !is_array($p->from_user_id) ) {
				$p->from_user_id	= array($p->from_user_id);
			}
			if( !isset($p->lang_params['#USERS#']) ) {
				$p->lang_params['#USERS#']	= $p->lang_params['#USER#'];
				unset($p->lang_params['#USER#']);
			}
			foreach($join_ones as $k=>$v) {
				if( ! in_array($ps[$k]->from_user_id, $p->from_user_id) ) {
					$p->lang_params['#USERS#']	= $v.( count($p->from_user_id)==1 ? ' #AND# ' : ', ' ).$p->lang_params['#USERS#'];
					$p->from_user_id[]	= $ps[$k]->from_user_id;
				}
				$user	= (object) array (
					'is_logged'	=> TRUE,
					'id'		=> 0,
					'info'	=> (object) array('is_network_admin' => 1),
				);
				$tmp	= new post('public', $k);
				$tmp->delete_this_post();
			}
			$db2->query('UPDATE posts SET message="'.$db2->e(serialize($p)).'" WHERE id="'.$join_to.'" LIMIT 1');
		}
	}
	unset($for_groups);
	
	if( count($for_users) )
	{
		foreach( array('msg_ntf_me_if_u_follows_me', 'msg_ntf_me_if_u_edt_profl', 'msg_ntf_me_if_u_edt_pictr', 'msg_ntf_me_if_u_joins_grp', 'msg_ntf_me_if_u_registers') as $langword)
		{
			foreach($for_users as $id=>$ps)
			{
				$join_ones	= array();
				$join_to	= FALSE;
				foreach($ps as $pid=>$p) {
					if( $p->lang_key == $langword ) {
						$join_ones[$pid]	= isset($p->lang_params['#USERS#']) ? $p->lang_params['#USERS#'] : $p->lang_params['#USER#'];
					}
					if( $p->lang_key == preg_replace('/^msg_/', 'ntfcombined_', $langword) ) {
						$join_to	= $pid;
					}
				}
				if( ! count($join_ones) ) { continue; }
				if( ! $join_to ) {
					$join_to	= key($join_ones);
					unset($join_ones[$join_to]);
				}
				if( ! count($join_ones) ) { continue; }
				$p	= $ps[$join_to];
				$p->lang_key	= preg_replace('/^msg_/', 'ntfcombined_', $langword);
				if( !is_array($p->from_user_id) ) {
					$p->from_user_id	= array($p->from_user_id);
				}
				if( !isset($p->lang_params['#USERS#']) ) {
					$p->lang_params['#USERS#']	= $p->lang_params['#USER#'];
					unset($p->lang_params['#USER#']);
				}
				foreach($join_ones as $k=>$v) {
					if( ! in_array($ps[$k]->from_user_id, $p->from_user_id) ) {
						$p->lang_params['#USERS#']	= $v.( count($p->from_user_id)==1 ? ' #AND# ' : ', ' ).$p->lang_params['#USERS#'];
						$p->from_user_id[]	= $ps[$k]->from_user_id;
					}
					$user	= (object) array (
						'is_logged'	=> TRUE,
						'id'		=> $id,
						'info'	=> (object) array('is_network_admin' => 1),
					);
					$tmp	= new post('public', $k);
					$tmp->delete_this_post();
				}
				$db2->query('UPDATE posts SET message="'.$db2->e(serialize($p)).'" WHERE id="'.$join_to.'" LIMIT 1');
			}
		}
		foreach( array('msg_ntf_me_if_u_creates_grp', 'msg_ntf_me_if_u_invit_me_grp') as $langword)
		{
			foreach($for_users as $id=>$ps)
			{
				$join_ones	= array();
				$join_to	= FALSE;
				foreach($ps as $pid=>$p) {
					if( $p->lang_key == $langword ) {
						$join_ones[$pid]	= isset($p->lang_params['#GROUPS#']) ? $p->lang_params['#GROUPS#'] : $p->lang_params['#GROUP#'];
					}
					if( $p->lang_key == preg_replace('/^msg_/', 'ntfcombined_', $langword) ) {
						$join_to	= $pid;
					}
				}
				if( ! count($join_ones) ) { continue; }
				if( ! $join_to ) {
					$join_to	= key($join_ones);
					unset($join_ones[$join_to]);
				}
				if( ! count($join_ones) ) { continue; }
				$p	= $ps[$join_to];
				$p->lang_key	= preg_replace('/^msg_/', 'ntfcombined_', $langword);
				if( !isset($p->lang_params['#GROUPS#']) ) {
					$p->lang_params['#GROUPS#']	= $p->lang_params['#GROUP#'];
					unset($p->lang_params['#GROUP#']);
				}
				foreach($join_ones as $k=>$v) {
					$p->lang_params['#GROUPS#']	= $v.( FALSE===strpos($p->lang_params['#GROUPS#'], ' #AND# ') ? ' #AND# ' : ', ' ).$p->lang_params['#GROUPS#'];
					$user	= (object) array (
						'is_logged'	=> TRUE,
						'id'		=> $id,
						'info'	=> (object) array('is_network_admin' => 1),
					);
					$tmp	= new post('public', $k);
					$tmp->delete_this_post();
				}
				$db2->query('UPDATE posts SET message="'.$db2->e(serialize($p)).'" WHERE id="'.$join_to.'" LIMIT 1');
			}
		}
		foreach( array('msg_ntf_me_if_u_follows_u2') as $langword)
		{
			foreach($for_users as $id=>$ps)
			{
				$join_ones	= array();
				$join_to	= FALSE;
				foreach($ps as $pid=>$p) {
					if( $p->lang_key == $langword ) {
						$join_ones[$pid]	= isset($p->lang_params['#USERS2#']) ? $p->lang_params['#USERS2#'] : $p->lang_params['#USER2#'];
					}
					if( $p->lang_key == preg_replace('/^msg_/', 'ntfcombined_', $langword) ) {
						$join_to	= $pid;
					}
				}
				if( ! count($join_ones) ) { continue; }
				if( ! $join_to ) {
					$join_to	= key($join_ones);
					unset($join_ones[$join_to]);
				}
				if( ! count($join_ones) ) { continue; }
				$p	= $ps[$join_to];
				$p->lang_key	= preg_replace('/^msg_/', 'ntfcombined_', $langword);
				if( !isset($p->lang_params['#USERS2#']) ) {
					$p->lang_params['#USERS2#']	= $p->lang_params['#USER2#'];
					unset($p->lang_params['#USER2#']);
				}
				foreach($join_ones as $k=>$v) {
					if( FALSE === strpos($p->lang_params['#USERS2#'], $v) ) {
						$p->lang_params['#USERS2#']	= $v.( FALSE===strpos($p->lang_params['#USERS2#'], ' #AND# ') ? ' #AND# ' : ', ' ).$p->lang_params['#USERS2#'];
					}
					$user	= (object) array (
						'is_logged'	=> TRUE,
						'id'		=> $id,
						'info'	=> (object) array('is_network_admin' => 1),
					);
					$tmp	= new post('public', $k);
					$tmp->delete_this_post();
				}
				$db2->query('UPDATE posts SET message="'.$db2->e(serialize($p)).'" WHERE id="'.$join_to.'" LIMIT 1');
			}
		}
	}
	unset($for_users);
	
?>