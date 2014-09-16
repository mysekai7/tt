<?php
/**
 * All needs file manage system
 * This is a test code
 * return data to ajax
 * by aiens
 * 2010-01-13 19:20
 */

header("Content-type: text/html; charset=utf-8");
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if ($_GET['action'] == 'filelist') { //return file list
	echo '<div class="file_list_t">';	
	$checkpath = str_replace($DOCUMENT_ROOT,'',$_GET['path']);
	if ($checkpath != '/../') {		
		$checkpath = str_replace('../','',$checkpath);
		$path1 = $DOCUMENT_ROOT.str_replace($DOCUMENT_ROOT,'',$_GET['path']);
		$dir = opendir($path1);		
		while ($file = readdir($dir)) {
		   if (!($file == '..')) {		  
			  if ($file == '.') {
				  $path = $path1.'../';				  
				  $file = '返回';	  
				  if ($checkpath != '/') {
					  echo '<li><a href="javascript:void(0);" onclick="javascript:file_list(\''.$path.'\');">'.$file.'</a></li>';
				  }
			  }else{				  
				  $path = $path1.$file.'/';				  
				  $path = iconv('gb2312','utf-8',$path);
				  if (is_dir($path1.$file)) {
				     echo '<li><a href="javascript:void(0);" onclick="javascript:file_list(\''.$path.'\');">'.$file.'</a><span><a href="javascript:void(0);" onclick="javascript:if(confirm(\'确认删除?\')) dir_delete(\''.$path.'\');">删除</a></span></li>';
				  }else{
					 $file = iconv('gb2312','utf-8',$file);
					 $filesize = filesize($path1.$file)/1000;
					 echo '<li>'.$file.' - ('.$filesize.'KB)<span><a href="javascript:void(0);" onclick="javascript:if(confirm(\'确认删除?\')) file_delete(\''.$path1.$file.'\');">删除</a></span></li>'; 
				  }
			  }
		   }		   
		}
		closedir($dir);
	}
	echo '</div>';
}else if ($_GET['action'] == 'inputcontent') { //input data to file
	$name = $_GET['input_name'];
	$content = $_GET['input_content'];
	$data = date('Y-m-d H:i:s');
	$fp = @fopen(dirname(__FILE__).'/'.$name, 'ab');
	$data = "Data:".$data."\t Content:".$content."\n";
	$fw = @fwrite($fp, $data, strlen($data));
	@fclose($fp);
	if ($fw) {
		echo '成功写入';
	}else{
		echo '写入失败';
	}
}else if ($_GET['action'] == 'getcontent') { //get data to file
	$name = $_GET['input_name'];
	$fp = @fopen(dirname(__FILE__).'/'.$name, 'rb');
	if (!$fp) {
		echo '系统所在目录不存在此文件,无法读取';
		exit;
	}
	while (!feof($fp)) {
		$content = fgets($fp);
		echo $content.'<br />';
	}
}else if ($_GET['action'] == 'filemake') { //make dir
	$name = $_GET['input_name'];
	function makedir($path) {  
      if (!file_exists($path)) {
        makedir(dirname($path));  
        return mkdir($path, 0777);    
      }  
    }
	if (makedir($name)) {
		echo '成功创建';
	}else{
		echo '创建失败';
	}
}else if ($_GET['action'] == 'delete_file') { //delete file 
    $file = $_GET['file'];
	unlink($file);
	echo '成功删除';
}else if ($_GET['action'] == 'delete_dir') { //delete dir
	$dir = $_GET['file'];
	function delete_dir($dir) { 
	  if(! is_dir($dir)) {
		  return false;
	  }
		  $handle = @opendir($dir);
		  while(($file = @readdir($handle)) !== false) {
			  if($file != '.' && $file != '..') {
				  $dir = $dir . '/' . $file;
				  is_dir($dir) ? delete_dir($dir) : @unlink($dir);
			  }
		  }
	  closedir($handle);
	  return rmdir($dir) ;
	}
	if (delete_dir($dir)) {
		echo '成功删除';
	}else{
		echo '删除失败';
	}	
}
?>