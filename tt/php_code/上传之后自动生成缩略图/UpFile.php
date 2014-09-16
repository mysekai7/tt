<?php
	include_once("ImageReSize.class.php");
 include_once("UpFileLoad.php");
	if($_POST["submit"])
	{
		$InputName = $_FILES["UpFile"];
		$UpFile = new UpFile($InputName);
		$name = $UpFile->UpLoadFile();
		$MyImage = new ImageReSize($name,"100","80","./images/");
		$MyImage->GoReSize();
		echo $name;
	}
?>
<form enctype="multipart/form-data" action="" method="POST">
	<input type="file" name="UpFile"/>
	<input type="submit" value="�ϴ�" name="submit"/>
	<img src="<?php echo $name;?>">
</form>