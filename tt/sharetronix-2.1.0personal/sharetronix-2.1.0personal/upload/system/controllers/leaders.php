<?php

	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/global.php');
	
	$D->page_title	= $this->lang('competitions_site_ttl', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$tabs = array('users', 'groups');
	
	$D->tab = 'users';
	if(in_array($this->param('tab'), $tabs)){
		$D->tab = $this->param('tab');	
	}
	
	if($D->tab == 'users')
	{
		$D->most_posting_members 	= $this->network->get_mostactive_users();
		$D->most_commenting_members 	= $this->network->get_mostcommenting_users();
		$D->most_commented_members 	= $this->network->get_mostcommented_users();
		$D->get_mostfollowed_members 	= $this->network->get_mostfollowed_users();
		$D->get_mostfollowing_members	= $this->network->get_mostfollowing_users();
		
	}elseif($D->tab == 'groups')
	{
		$D->get_mostactive_groups	= $this->network->get_mostactive_groups();
		$D->get_mostfollowed_groups	= $this->network->get_mostfollowed_groups();
	}
	
	$this->load_template('leaders.php');
?>