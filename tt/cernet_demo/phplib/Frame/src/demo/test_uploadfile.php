<?php

//测试文件上传
//by indraw
//2004/11/10

error_reporting(E_ERROR | E_WARNING | E_PARSE);
include('UploadFile.class.php');
$upload = new UploadFile;

//-----------------------------------------------------------------------------
if (!empty($_POST['action']) && $_POST['action'] == 'upload') {
    $aaa = $upload->upload_file('./', 'md5', 10);
	//如果是图片，可以更改大小。
	//$upload->img_resize('./up',$aaa[file1], 120, true);
	//显示更改后的文件名
	print_r($upload->_files);
}

//$upload->deleteFile('/up', $aaa[file1]);
$upload->display_files('./');

//-----------------------------------------------------------------------------
?>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" name="upload" ENCTYPE="multipart/form-data">
<input type="file" name="file1"><br>
<input type="file" name="file2"><br>
<input type="file" name="file3"><br>

<input type="submit" value="你确定上传吗？">
<input type="hidden" name="action" value="upload">
</form>