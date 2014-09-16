<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.6
- 文件名:UploadFile.class.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2004/11/11
- 简要描述:文件上传函数类集，为indraw综合了几个上传类制作而成。
- 运行环境:php4或以上
- 修改记录:2004/11/11，indraw，程序创立
- 修改记录:2005/04/11，indraw，将上传操作进行具体结果返回
---------------------------------------------------------------------
*/

/*
	$upload = new UploadFile;
	$upFileName = $upload->upload_file('./', 'md5', 10);
	$upload->img_resize('./up',$aaa[file1], 120, true);
	$upload->display_files('./');
*/
/*
	set_max_width($width)                           //设置图片最大宽度
	set_max_size($size)                             //设置文件最大值
	set_valid_types($type)                          //设置文件允许类型
	check_dir($dir)                                 //检查目录，如果没有自动建立,并返回建立的目录名
	check_gd()                                      //检查GD版本
	delete_file($source_dir, $filename)             //删除文件
	file_rename($source_dir, $filename, $newname)   //文件从新命名
	lantin_encode($str)                             //将中文名转换成拼音
	upload_file($target_dir = '', $encode = 'md5', $name_length = 10)
	                                                //将文件上传到服务器
	display_files($source_dir)                      //显示一个目录下的文件列表
*/

//===================================================================
class UploadFile
{

	var $show_errors = true;            //是否显示错误

	var $error_num = 0;                 //0\1\2\3\4\5
	var $file_name = array();
	var $max_file_size = 1572864;       //1.5Mb，允许上传图片大小
	var $max_image_width = 2048;        //象素，允许上传图片宽度
	var $_files = array();              //上传后文件名字
	var $_types = array('jpg', 'jpeg', 'png','bmp', 'doc', 'txt', 'gif','rar', 'tar', 'zip', 'tgz', 'gz', 'wml');                             //允许上传的文件类型

	/*
	-----------------------------------------------------------
	函数名称:set_max_width($width)
	简要描述:设置图片最大宽度
	输入:int
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_max_width($width)
	{
		$this->max_image_width = $width;
	}
	

	/*
	-----------------------------------------------------------
	函数名称:set_max_size($size)
	简要描述:设置文件最大值
	输入:int
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_max_size($size)
	{
		$this->max_file_size = $size;
	}

	/*
	-----------------------------------------------------------
	函数名称:set_valid_types($type)
	简要描述:设置文件允许类型
	输入:array
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_valid_types($type_array)
	{
		$this->_types = $type_array;
	}

	/*
	-----------------------------------------------------------
	函数名称:check_dir($dir)
	简要描述:检查目录，如果没有自动建立,并返回建立的目录名
	输入:string
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function check_dir($dir)
	{
		if (!is_dir($dir)) {
			mkdir($dir, 0777);
			chmod($dir, 0777);
		}
		return $dir;
	}

	/*
	-----------------------------------------------------------
	函数名称:check_gd()
	简要描述:检查GD版本
	输入:void
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function check_gd()
	{
		$gd_content = get_extension_funcs('gd');
		if (!$gd_content) { 
			$this->print_error('您的空间没有安装GD库！'); 
			
			return false; 
		} else {
			ob_start();
			phpinfo(8);
			$buffer = ob_get_contents();
			ob_end_clean(); 

			if (strpos($buffer, '2.0')) {
				return 'gd2';
			} else {
				return 'gd';
			}
		}
	}	
	

	/*
	-----------------------------------------------------------
	函数名称:delete_file($filename, $source_dir)
	简要描述:删除文件
	输入:mixed (目录，文件名)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function delete_file($source_dir, $filename)
	{
		$source_dir = $this->check_dir($source_dir);
		if (file_exists($source_dir.'/'.$filename)) {
			if (unlink($source_dir.'/'.$filename)) {
				return true;
			}
		}
		else{
			$this->print_error('您要删除的文件不存在'); 
			Return false;
		}
	}


	/*
	-----------------------------------------------------------
	函数名称:file_rename($source_dir, $filename, $newname)
	简要描述:文件从新命名
	输入:mixed (文件目录，原始文件名，新文件名)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function file_rename($source_dir, $filename, $newname)
	{
		$source_dir = $this->check_dir($source_dir);
		if (rename($source_dir.'/'.$filename, $newname)) {
			return true;
		} 
		else {
			$this->print_error('文件重新命名失败'); 
			Return false;
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:lantin_encode($str)
	简要描述:将中文名转换成拼音，以后什么时候需要再具体去设计。这个功能我觉得很实用。
	输入:string
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function lantin_encode($str)
	{
		
		return $str;
	}

	/*
	-----------------------------------------------------------
	函数名称:uploadFile($target_dir = '', $encode = 'md5', $name_length = 10)
	简要描述:将文件上传到服务器
	输入:mixed (路径，命名编码，名字长度)
	输出:string (文件名)
	修改日志:------
	-----------------------------------------------------------
	*/
	function upload_file($target_dir = '', $encode = 'md5', $name_length = 30)
	{
		$target_dir = $this->check_dir($target_dir);
		foreach ($_FILES as $varname => $array) {
			if (!empty($array['name'])) {
				if (is_uploaded_file($array['tmp_name'])) {
					$filename = strtolower(str_replace(' ', '', $array['name']));
					$basefilename = preg_replace("/(.*)\.([^.]+)$/","\\1", $filename);
					$ext = preg_replace("/.*\.([^.]+)$/","\\1", $filename);
					$this->file_name[$varname] = $filename;
					$get_image_size = getimagesize ($array['tmp_name']);
					//echo "文件类型：".$ext;
					if ($array['size'] > $this->max_file_size) {
						$this->print_error('上传文件超过允许上传的限定');
						$this->error_num = 1;
						Return false;
					
					} elseif ($get_image_size[0] > $this->max_image_width) {
						$this->print_error('图象宽度超出允许上传的要求');
						$this->error_num = 2;
						Return false;

					} elseif (!in_array($ext, $this->_types)) {
						$this->print_error('文件类型为禁止上传类型');
						$this->error_num = 3;
						Return false;

					} else {
						switch ($encode) {
						case 'md5':
							$basefilename = substr(md5($basefilename), 0, $name_length);
							$filename = $basefilename.'.'.$ext;
							break;
							
						case 'latin':
							$basefilename = substr($this->lantinEncode($basefilename), 0, $name_length);
							$filename = $basefilename.'.'.$ext;
							break;
						case '':
							$basefilename = substr($basefilename, 0, $name_length);
							$filename = $basefilename.'.'.$ext;
							break;
						default: 
							$basefilename = $encode."_".$varname;
							$filename = $basefilename.'.'.$ext;
						}
						
						if (!move_uploaded_file($array['tmp_name'], $target_dir.'/'.$filename)) {
						//if (!copy($array['tmp_name'], $target_dir.'/'.$filename)) {

							$this->print_error('上传文件失败');
							$this->error_num = 4;

							Return false;
						}
						//echo($target_dir.'/'.$filename."<br>");
						$this->_files[$varname] = $filename;
					}
				} else {
					$this->print_error('不能建立上传临时文件');
					$this->error_num = 5;
					Return false;
				}
			}
		}//end foreach
		return $this->_files;
	}
	
	/*
	-----------------------------------------------------------
	函数名称:img_resize($filename, $source_dir, $dest_width, $duplicate = false)
	简要描述:从写图片大小
	输入:mixed (路径，图片名，宽度，是否覆盖原文件)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function img_resize($source_dir,$filename, $dest_width="", $duplicate = false)
	{
		$source_dir = $this->check_dir($source_dir);
		$full_path = $source_dir.'/'.$filename;
		$basefilename = preg_replace("/(.*)\.([^.]+)$/","\\1", $filename);
		$ext = preg_replace("/.*\.([^.]+)$/","\\1", $filename);

		switch ($ext) {
		case 'png':
			$image = imagecreatefrompng($full_path);
			break;
		case 'gif':
			$image = ImageCreateFromGIF($full_path);
			break;
		case 'jpg':
			$image = imagecreatefromjpeg($full_path);
			break;
		
		case 'jpeg':
			$image = imagecreatefromjpeg($full_path);
			break;
		
		default:
			$this->print_error('您上传的文件类型 '.$ext.' 还没有得到您的GD库版本支持');
			Return false;
			break;
		}

		$image_width = imagesx($image);
		$image_height = imagesy($image);
		
		// resize image pro rata
		if($dest_width == ""){
			$coefficient = ($image_width > $this->max_image_width) ? (real)($this->max_image_width / $image_width) : 1;
		}
		else{
			$coefficient = ($image_width > $dest_width) ? (real)($dest_width / $image_width) : 1;
		}

		$dest_width = (int)($image_width * $coefficient);
		$dest_height = (int)($image_height * $coefficient);
		//echo $dest_width."---".$dest_height;

		if (false !== $duplicate) {
			$filename = $basefilename.'_2.'.$ext;
			copy($full_path, $source_dir.'/'.$filename);
		}
		
		if ('gd2' == $this->check_gd()) { 
			$img_id = imagecreatetruecolor($dest_width, $dest_height);
			imagecopyresampled($img_id, $image, 0, 0, 0, 0, $dest_width + 1, $dest_height + 1, $image_width, $image_height);
		} else {
			$img_id = imagecreate($dest_width, $dest_height);
			imagecopyresized($img_id, $image, 0, 0, 0, 0, $dest_width + 1, $dest_height + 1, $image_width, $image_height);
		}

		switch ($ext) {
		case 'png':
			imagepng($img_id, $source_dir.'/'.$filename);
			break;
		
		case 'jpg':
			imagejpeg($img_id, $source_dir.'/'.$filename);
			break;
		
		case 'jpeg':
			imagejpeg($img_id, $source_dir.'/'.$filename);
			break;
		}
		
		imagedestroy($img_id);
		
		return true;
	}

	/*
	-----------------------------------------------------------
	函数名称:display_files($source_dir)
	简要描述:显示一个目录下的文件列表
	输入:string
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function display_files($source_dir)
	{
		$source_dir = $this->check_dir($source_dir);
		if ($contents = opendir($source_dir)) {
			echo '<blockquote>目录内容：<br>';
			
			while (false !== ($file = readdir($contents))) {
				if (is_dir($file)) continue;

				$filesize = (real)(filesize($source_dir.'/'.$file) / 1024);
				$filesize = number_format($filesize, 3, ',', ' ');

				$ext = preg_replace("/.*\.([^.]+)$/","\\1", $file);
				echo '<p>*&nbsp;'.$file.'&nbsp;('.$filesize.') Kb</p>';
			}
			echo "</blockquote>";
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:print_error($str = "")
	简要描述:显示操作错误信息
	输入:string 
	输出:echo or false
	修改日志:------
	-----------------------------------------------------------
	*/
	function print_error($str = "")
	{
		//设置全局变量$PHPSEA_ERROR..
		global $PHPSEA_ERROR;
		$PHPSEA_ERROR['UpoadFile_Error'] = $str;
	
		//判断是否显示错误输出..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>";
			print "<b>UpoadFile Error --</b> ";
			print "[<font color=000077>$str</font>]";
			print "</font></blockquote>";
		}
		else
		{
			return false;
		}
	}//end func

}//end class
//=============================================================================
?>