<?php
//图片大小控制测试
//by indraw
//2004/11/18

include ('ImageControl.class.php');
$imm = new ImageControl('test.jpg', '.');
	
//=============================================================================
//将图片test.jpg生成缩略图，并显示。
if ($imm->exist_image()) {
	echo "<p>image name : " . $imm->get_image_name() . "</p>\n";
	echo "<p>image height : " . $imm->get_image_height() . "</p>\n";
	echo "<p>image whidth : " . $imm->get_image_width() . "</p>\n";
	echo "<p>image path : " . $imm->get_image_path() . "</p>\n";
	echo "<p>image size : " . $imm->get_image_size() . "</p>\n";
	echo "<p>image dimension : " . $imm->get_image_dimension() . "</p>\n";
	echo "<p>image type : " . $imm->get_image_type() . "</p>\n";
	if ($imm->exist_thumb()) {
		echo "<p>Existent thumb : ". $imm->get_thumb_name() . "</p><br>";
	} else {
		$imm->set_back('255','255','255');
		$imm->set_frame('208','208','208');
		$imm->make_thumb("180");

		if ($imm->exist_thumb())
			echo "<p>Created thumb : ". $imm->get_thumb_name() . "</p><br>";
	}
	echo $imm->html_thumb_image();			
} else {
echo "<p>Do not exist!</p>";
}


//-----------------------------------------------------------------------------
?>