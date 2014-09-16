<?php
	
	$this->load_langfile('inside/global.php');
	
	echo '<'.'?xml version="1.0" encoding="UTF-8" ?'.'>';
	
	if( !$this->network->id ) {
		echo '<result></result>';
		return;
	}
	
	$word		= isset($_POST['word']) ? trim($_POST['word']) : '';
	if( mb_strlen($word) < 4 ) {
		echo '<result></result>';
		return;
	}

	if(preg_match('/^[a-z.]+$/iu', $word)){
		echo '<result>'.htmlspecialchars($word).'</result>';
		return;
	}
	
	echo '<result></result>';
	return;
	
?>