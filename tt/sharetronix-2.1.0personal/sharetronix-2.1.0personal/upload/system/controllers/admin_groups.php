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
	
	$D->page_title	= $this->lang('admpgtitle_groups', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	
	if( isset($_POST['admins']) ) {
		$groups	= trim($_POST['admins']);
		$groups	= trim($groups, ',');
		$groups	= trim($groups);
		$groups	= explode(',', $groups);
		$ids	= array();
		foreach($groups as $g) {
			$g	= trim($g);
			if( empty($g) ) { continue; }
			
			$g	= $this->network->get_group_by_name($g, TRUE);
			if( ! $g ) { continue; }
			$ids[]	= intval($g->id);
		}
		$ids	= array_unique($ids);
		$this->db2->query('UPDATE groups SET is_special=0');
		if( count($ids) ) {
			$this->db2->query('UPDATE groups SET is_special=1 WHERE id IN('.implode(', ', $ids).') ');
		}
		foreach($ids as $g) {
			$this->network->get_group_by_id($g, TRUE);
		}
		$this->redirect( $C->SITE_URL.'admin/groups/msg:saved' );
	}
	
	$D->special_groups = array();
	$r	= $db2->query('SELECT id FROM groups WHERE is_special=1 ORDER BY title ASC');
	while($tmp = $db2->fetch_object($r)) {
		if($gr = $this->network->get_group_by_id($tmp->id)) {
			$D->special_groups[]	= $gr;
		}
	}
	
	$this->load_template('admin_groups.php');
	
?>