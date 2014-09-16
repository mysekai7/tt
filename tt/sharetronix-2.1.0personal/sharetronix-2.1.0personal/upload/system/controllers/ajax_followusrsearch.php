<?php
	
	if( !$this->network->id ) {
		echo 'ERROR';
		return;
	}
	
	$text		= isset($_POST['txt']) ? trim($_POST['txt']) : '';
	if( mb_strlen($text) < 3 ) {
		echo '[]';
		return;
	}

	$w	= $this->db2->escape($text);
	$r	= $this->db2->query('SELECT id, fullname, username, avatar FROM users WHERE active=1 AND (username LIKE "'.$w.'%" OR fullname LIKE "'.$w.'%" OR fullname LIKE "% '.$w.'%") AND id<>'.$this->user->id.' ORDER BY num_followers DESC, fullname ASC LIMIT 20');
	
	$members = array();
	while($obj = $this->db2->fetch_object($r)) {
		$fullname	= htmlspecialchars($obj->fullname);
		$username	= htmlspecialchars($obj->username);
		
		$members[]	= array (
			intval($obj->id),
			$username,
			$fullname,
			"", 
			(empty($obj->avatar))? $C->DEF_AVATAR_USER : $obj->avatar,
			0,
			0,//6-none
			0,//7-none
			0,//8-none
			0,//9-none
			$username.", ".$fullname.", "."", //10-c1.c2.c3
		);
	}
	echo json_encode($members);
	return; 
	
	//echo 'ERROR';
	//return;
	
?>