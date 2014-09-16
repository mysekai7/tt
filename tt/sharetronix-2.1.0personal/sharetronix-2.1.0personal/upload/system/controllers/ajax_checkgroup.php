<?php
	
	$this->load_langfile('inside/global.php');
	
	echo '<'.'?xml version="1.0" encoding="UTF-8" ?'.'>';
	
	if( !$this->network->id ) {
		echo '<result></result>';
		return;
	}
	
	$grouptype	= isset($_POST['grouptype']) ? trim($_POST['grouptype']) : '';
	if( $grouptype!='public' && $grouptype!='private' ) {
		echo '<result></result>';
		return;
	}
	$word		= isset($_POST['word']) ? trim($_POST['word']) : '';
	if( mb_strlen($word) < 2 ) {
		echo '<result></result>';
		return;
	}
	
	
	if( $grouptype == 'public' )
	{
		$g	= $this->network->get_group_by_name($word);
		if( $g && $g->is_public ) {
			echo '<result>'.htmlspecialchars($g->title).'</result>';
			return;
		}
	}
	elseif( $datatype == 'private' )
	{
		$g	= $this->network->get_group_by_name($word);
		if( $g && !$g->is_public ) {
			echo '<result>'.htmlspecialchars($g->title).'</result>';
			return;
		}
	}
	
	echo '<result></result>';
	return;
	
?>