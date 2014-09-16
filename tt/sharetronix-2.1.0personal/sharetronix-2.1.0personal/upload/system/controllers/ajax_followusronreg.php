<?php
	
	if( !$this->network->id ) {
		echo 'ERROR';
		return;
	}

	if( isset($_POST['uid']) && isset($_POST['type']) && !empty($_POST['type']) )
	{
		if( $_POST['type'] == 'push' ){
			$this->user->sess['selected_members'][intval($_POST['uid'])] = TRUE;
		}else{
			unset($this->user->sess['selected_members'][intval($_POST['uid'])]);
		}
		echo "OK:\n";
		return;
	}
	
	echo 'ERROR';
	return;
	
?>