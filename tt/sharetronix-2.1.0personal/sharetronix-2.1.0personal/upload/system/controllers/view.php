<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}elseif($C->PROTECT_OUTSIDE_PAGES && !$this->user->is_logged){
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/view.php');
	
	$post_type	= '';
	$post_id	= '';
	if( $this->param('post') ) {
		$post_type	= 'public';
		$post_id	= intval($this->param('post'));
	}
	elseif( $this->param('priv') ) {
		$post_type	= 'private';
		$post_id	= intval($this->param('priv'));
	}
	else {
		$this->redirect('dashboard');
	}
	
	$D->post	= new post($post_type, $post_id);
	if($D->post->error || $D->post->is_system_post) {
		$this->redirect('dashboard');
	}
	
	if( $post_type == 'private' && ($D->post->post_user->id != $this->user->id && $D->post->to_user->id != $this->user->id)){
		$this->redirect('dashboard');
	}
	if($D->post->post_group && $D->post->post_group->is_private ){
		if( !$this->user->is_logged ){
			$this->redirect('home');
		}
		if( !$this->user->if_follow_group($D->post->post_group->id) ){
			$this->redirect('dashboard');
		}
	}
	
	$D->i_am_network_admin	= ( $this->user->is_logged && $this->user->info->is_network_admin );
	$D->post_is_mine 	= $D->post->post_user->id == $this->user->id;
	if( !$D->post_is_mine ){
		$D->he_follows_me 	= $this->user->is_logged ? $this->user->if_user_follows_me($D->post->post_user->id) : FALSE;
		$D->post_is_protected 	= !$D->post->post_group && $D->post->post_user->is_posts_protected && !$D->he_follows_me && !$D->i_am_network_admin;
	}else{
		$D->he_follows_me 	= TRUE;
		$D->post_is_protected	= FALSE;
	}
	
	if( !$D->post_is_mine && $D->post_is_protected && !$D->he_follows_me && !$D->i_am_network_admin){
		$this->redirect('dashboard');
	}
	
	$D->page_title	= ($D->post->post_user->id==0&&$D->post->post_group ? $D->post->post_group->title : $D->post->post_user->username).': '.$D->post->post_message;
	$D->page_favicon	= $C->IMG_URL.'avatars/thumbs2/'.($D->post->post_user->id==0&&$D->post->post_group ? $D->post->post_group->avatar : $D->post->post_user->avatar);	
	
	
	$D->prevpost	= '';
	$D->nextpost	= '';
	if( $post_type == 'public' && $D->post->post_user->id>0 ) {
		$tmp = $db2->fetch_field('SELECT id FROM posts WHERE id>"'.$D->post->post_id.'" AND user_id="'.$D->post->post_user->id.'" ORDER BY id ASC LIMIT 1');
		if($tmp) {
			$D->prevpost	= $C->SITE_URL.'view/post:'.$tmp;
		}
		$tmp = $db2->fetch_field('SELECT id FROM posts WHERE id<"'.$D->post->post_id.'" AND user_id="'.$D->post->post_user->id.'" ORDER BY id DESC LIMIT 1');
		if($tmp) {
			$D->nextpost	= $C->SITE_URL.'view/post:'.$tmp;
		}
		unset($tmp);
	}
	
	$D->delete_enabled	= $D->post->if_can_delete();
	$D->delete_urlafter	= $post_type=='public' ? $C->SITE_URL.($D->post->post_user->id==0&&$D->post->post_group ? $D->post->post_group->groupname : $D->post->post_user->username).'/tab:updates/msg:deletedpost' : $C->SITE_URL.'dashboard/tab:private/msg:deletedpost';
	
	if( $this->param('from') == 'ajax' ) {
		echo 'OK:';
		$this->load_template('view.php');
		return;
	}
	
	$D->post->reset_new_comments();
	
	$this->load_template('header.php');
	$this->load_template('view.php');
	$this->load_template('footer.php');
?>