<?php
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	$db2->query('SELECT 1 FROM users WHERE id="'.$this->user->id.'" AND is_network_admin=1 LIMIT 1');
	if( 0 == $db2->num_rows() ) {
		$this->redirect('dashboard');
	}
	
	$this->load_langfile('inside/global.php');
	$this->load_langfile('inside/admin.php');
	
	$s	= new stdClass;
	$db2->query('SELECT word, value FROM settings');
	while($o = $db2->fetch_object()) {
		$s->{stripslashes($o->word)}	= stripslashes($o->value);
	}
	
	$D->delete_posts_types = array();
	if($s->POST_TYPES_TO_AUTODELETE != ''){	
		$D->delete_posts_types = explode('|', $s->POST_TYPES_TO_AUTODELETE);
		$D->delete_posts_types = array_unique($D->delete_posts_types);
		$D->delete_posts_types = array_intersect(  $D->delete_posts_types, array('feed', 'human', 'none') );
	}

	$D->delete_posts_period	= intval($s->POST_TYPES_DELETE_PERIOD);
	$D->delete_posts_period	= ($D->delete_posts_period > 0)?  $D->delete_posts_period:14;

	$D->submit	= FALSE;
	$D->error	= FALSE;
	$D->errmsg	= '';
	if( isset($_POST['sbm']) ) {
		$D->submit	= TRUE;
		
		if( $s->POST_TYPES_TO_AUTODELETE != $_POST['POST_TYPES_TO_AUTODELETE'] ){
			$this->db2->query('UPDATE settings SET value="'.$db2->e($_POST['POST_TYPES_TO_AUTODELETE']).'" WHERE word="POST_TYPES_TO_AUTODELETE" LIMIT 1');
		}
		if( intval($s->POST_TYPES_TO_AUTODELETE) != intval($_POST['POST_TYPES_DELETE_PERIOD']) ){
			$_POST['POST_TYPES_DELETE_PERIOD']	= (intval($_POST['POST_TYPES_DELETE_PERIOD']) > 0)?  intval($_POST['POST_TYPES_DELETE_PERIOD']):14;
			$this->db2->query('UPDATE settings SET value="'.$db2->e($_POST['POST_TYPES_DELETE_PERIOD']).'" WHERE word="POST_TYPES_DELETE_PERIOD" LIMIT 1');
			
		}
		
		$this->redirect('admin/system');
	}
	
	$D->page_title	= $this->lang('admpgtitle_system', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	$this->load_template('admin_system.php');
	
?>