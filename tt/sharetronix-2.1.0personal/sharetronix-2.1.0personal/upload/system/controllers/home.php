<?php
	
	if( $this->user->is_logged ) {
		$this->redirect('dashboard');
	}
	
	if( isset($_SESSION['TWITTER_CONNECTED']) && $_SESSION['TWITTER_CONNECTED'] && $_SESSION['TWITTER_CONNECTED']->id ) {
		$uid	= intval($_SESSION['TWITTER_CONNECTED']->id);
		$db2->query('SELECT email, password FROM users WHERE twitter_uid<>"" AND twitter_uid="'.$uid.'" LIMIT 1');
		if($tmp = $db2->fetch_object()) {
			if( $this->user->login(stripslashes($tmp->email), stripslashes($tmp->password)) ) {
				$this->redirect($C->SITE_URL.'dashboard');
			}
		}
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('outside/global.php');
	$this->load_langfile('inside/dashboard.php');
	$this->load_langfile('outside/home.php');
	
	$D->page_title	= $this->lang('os_home_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	$D->intro_ttl	= $this->lang('os_welcome_ttl', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	$D->intro_txt	= $this->lang('os_welcome_txt', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	if( isset($C->HOME_INTRO_TTL) && !empty($C->HOME_INTRO_TTL) ) {
		$D->page_title	= strip_tags($C->SITE_TITLE.' - '.$C->HOME_INTRO_TTL);
		$D->intro_ttl	= $C->HOME_INTRO_TTL;
	}
	if( isset($C->HOME_INTRO_TXT) && !empty($C->HOME_INTRO_TXT) ) {
		$D->intro_txt	= $C->HOME_INTRO_TXT;
	}
	if( isset($C->FACEBOOK_API_ID, $C->FACEBOOK_API_SECRET) && !empty($C->FACEBOOK_API_ID) && !empty($C->FACEBOOK_API_SECRET) && function_exists('curl_init') && function_exists('json_decode') ){
		require_once( $C->INCPATH.'classes/class_facebook.php');
		$facebook = new Facebook(array(
			'appId'  => $C->FACEBOOK_API_ID,
			'secret' => $C->FACEBOOK_API_SECRET,
		));

		$D->fb_login_url = $facebook->getLoginUrl();
		
	}else{
		$D->fb_login_url = FALSE;
	}
	
	if(isset($C->PROTECT_OUTSIDE_PAGES) && !$C->PROTECT_OUTSIDE_PAGES){
	
		$filters	= array('all', 'videos', 'images', 'links', 'files');
		$filter	= 'all';
		if( $this->param('filter') && in_array($this->param('filter'), $filters) ) {
			$filter	= $this->param('filter');
		}
		$at_tmp	= array('videos'=>'videoembed', 'images'=>'image', 'links'=>'link', 'files'=>'file');
		
		$not_in_groups	= array();
		$not_in_groups 	= $this->network->get_private_groups_ids();
		$not_in_groups	= count($not_in_groups)>0 ? ('AND p.group_id NOT IN('.implode(', ', $not_in_groups).')') : '';
		
		$without_users = array();
		$without_users = $this->network->get_post_protected_user_ids();
		$without_users = count($without_users)>0 ? (' AND (p.group_id>0 OR p.user_id NOT IN('.implode(', ', $without_users).'))') : '';
		
		if($filter == 'all') {
			$q1	= '';
			$q2	= 'SELECT p.*, "public" AS `type` FROM posts p WHERE p.user_id<>0 AND p.api_id<>2 AND p.api_id<>6 '.$not_in_groups.$without_users.' ORDER BY p.id DESC LIMIT 0, '.$C->PAGING_NUM_POSTS;
		}
		else {
			$q1	= '';
			$q2	= 'SELECT p.*, "public" AS `type` FROM posts p, posts_attachments a WHERE p.id=a.post_id AND p.user_id<>0 AND p.api_id<>2 AND p.api_id<>6 '.$not_in_groups.$without_users.' AND a.type="'.$at_tmp[$filter].'" ORDER BY p.id DESC LIMIT 0, '.$C->PAGING_NUM_POSTS;
		}
		
		$D->filter		= $filter;
		$D->num_results	= 0;
		$D->num_pages	= 1;
		$D->pg		= 1;
		$D->posts_html	= '';
		
		$D->num_results	= $C->PAGING_NUM_POSTS; 
		$res	= $db2->query($q2);
		ob_start();
		if($D->num_results>0){
			while($obj = $db2->fetch_object($res)) {
				$D->p	= new post($obj->type, FALSE, $obj);
				if( $D->p->error ) {
					continue;
				}
				if( $this->param('from')=='ajax' && $this->param('onlypost')!="" && $this->param('onlypost')!=$D->p->post_tmp_id ) {
					continue;
				}
				if( $this->param('from')=='ajax' && $this->param('opencomments')!="" && $this->param('opencomments')==$D->p->post_tmp_id ) {
					$D->p->comments_open	= TRUE;
				}
				$D->post_show_slow	= FALSE;
				if( $this->param('from')=='ajax' && isset($_POST['lastpostdate']) && $D->p->post_date>intval($_POST['lastpostdate']) ) {
					$D->post_show_slow	= TRUE;
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
				
				$D->show_reshared_design = ($D->p->post_resharesnum > 0);
				
				$this->load_template('single_post.php');
			}
			unset($D->p);
			$D->paging_url	= $C->SITE_URL.'home/filter:'.$filter.'/pg:';
			if( $D->num_pages>1 && !$this->param('onlypost') ) {
				$this->load_template('paging_posts.php');
			}
		}else{
			$D->noposts_box_title	= $this->lang('os_noposts_ttl'); 
			
			if($filter == 'all'){ 
				$D->noposts_box_text	= $this->lang('os_noposts_unreg_mgs');
			}else{
				$D->noposts_box_text	= $this->lang('os_noposts_match_mgs');
			}
			
			$this->load_template('noposts_box.php');
		}
		unset($D->p);
		$D->posts_html	= ob_get_contents();
		ob_end_clean();
		
		if( $this->param('from') == 'ajax' )
		{
			echo 'OK:';
			echo $D->posts_html;
			exit;
		}
		
		$D->last_online	= array();
		$D->last_online	= $this->network->get_online_users();
		
		$D->post_tags	= array();
		$D->post_tags	= $this->network->get_recent_posttags();
	}
	
	$this->load_template('home.php');
	
?>