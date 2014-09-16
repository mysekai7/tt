<?
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:FileDir.class.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2004/11/5
- 简要描述:目录操作类集，包括了所有的目录操作。
- 运行环境: php4或以上
- 修改记录:2004/11/5，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	$newDir = new FileDir();
	$newDir->set_current_dir($test_dir);
*/
/*
	FileDir($current_dir)                    //
	set_current_dir($dir)                    //设置当前工作目录
	create_dir($dirname,$where='')           //建立一个目录
	rename_dir($dirname,$where='')           //重命名目录名
	delete_dir($dirname)                     //删除一个文件
	delete_file($filename)                   //删除一个文件
	empty_dir($Dir)                          //清空一个目录，但保留目录
	get_dir_info()                           //获取一个目录下的文件和文件夹数组
	get_dir_all()                            //获取一个目录文件大小,递归n级操作
	copy_file($filename,$to='',$as='')       //将一个文件copy到另一个文件夹下
	copy_dir($Dir,$NewDirName,$delDir)       //转移一个目录
	get_file_size($file, $round = false)     //获取一个文件大小,如果传递文件，获取大小。如果传递数字返回格式化后数据
	three_dir()                              //将一个目录从一级目录转变成三级目录
*/

//===================================================================
class FileDir 
{

	var $show_errors = true;             //是否显示出错信息

	var $current_dir;                    //当前路径
	var $current_files = array();        //当前文件数量
	var $current_dirs = array();         //当前文件夹数量
	var $current_size = 0;               //当前文件总大小

	function FileDir() 
	{

	}
	/*
	-----------------------------------------------------------
	函数名称:set_current_dir($dir)
	简要描述:设置当前工作目录
	输入:string (相对或绝对路径，结尾没有斜杠。)
	输出:boolean 
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_current_dir($dir) 
	{
		if( @chdir($dir) ) {
			//$this->FileDir($dir);
			return true;
		} else
			$this->print_error("FileDir::set_current_dir: 不能定位到目录\"$dirname\"！");
	}
	/*
	-----------------------------------------------------------
	函数名称:create_dir($dirname)
	简要描述:建立一个目录
	输入:string (相对或绝对路径，结尾没有斜杠)
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function create_dir($dirname) 
	{
		if (!is_dir($dirname)) {
			if(!@mkdir($dirname,777)) 
				$this->print_error("FileDir::create_dir: 不能建立目录！");
		}
		else {
			$this->print_error("FileDir::create_dir: 目录\"$dirname\"已经存在！");
		}
	}
	/*
	-----------------------------------------------------------
	函数名称:rename_dir($dirname)
	简要描述:重命名目录名
	输入:string (相对或绝对路径，结尾没有斜杠)
	输出:booblean
	修改日志:------
	-----------------------------------------------------------
	*/
	function rename_dir($new_name) 
	{
		if($new_name == "" or $new_name == "." or $new_name == ".."){
			$this->print_error("FileDir::rename_dir: 不允许修改程序所在目录！");
		}
		if(@rename ($this->current_dir, $new_name)){
			return true;
		}
		else{
			$this->print_error("FileDir::rename_dir: 目录\"$this->current_dir\"重命名\"$new_name\"失败！");
		}
	}
	/*
	-----------------------------------------------------------
	函数名称:delete_dir($dirname)
	简要描述:删除一个目录
	输入:string (相对或绝对路径，结尾没有斜杠)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function delete_dir($dir_name) 
	{
		if (is_dir($dir_name)) {
			if(@rmdir($dir_name)) 
				return true;
			else
				$this->print_error("FileDir::delete_dir: 不能删除目录 $dirname ，请确认目录是否为空！");
		}
		else {
			$this->print_error("FileDir::delete_dir: 目录 $dirname 不存在！");
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:deleteFile($filename)
	简要描述:删除一个文件。
	输入:string (相对或绝对路径，结尾没有斜杠)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function delete_file($filename) 
	{
		if (file_exists($filename)) {
			if(@unlink($filename)) 
				return true;
			else
				$this->print_error("FileDir::delete_file: 不能删除文件 $filename ！");
		}
		else {
			$this->print_error("FileDir::delete_file: 文件 $filename 不存在！");
		}
	}
	/*
	-----------------------------------------------------------
	函数名称:empty_dir($Dir)
	简要描述:清空一个目录，但保留目录
	输入:string (相对或绝对路径，结尾没有斜杠)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function empty_dir($dir_name) 
	{
		if ($handle = @opendir($dir_name)) {
			while (($file = readdir($handle)) !== false) {
				if ($file == "." || $file == "..") {
					continue;
				}
				if (is_dir($dir_name."/".$file)){
					$this->empty_dir($dir_name."/".$file);
					$this->delete_dir($dir_name."/".$file);
				}else {
					$this->delete_file($dir_name."/".$file);
				}
			}
		}
	   @closedir($handle);
	}
	/*
	-----------------------------------------------------------
	函数名称:get_dir_info() 
	简要描述:获取一个目录下的文件和文件夹数组
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function get_dir_info() 
	{
		$i = 0;
		$j = 0;
		$current_dir_handle = @opendir($this->current_dir);
		while ($contents = readdir($current_dir_handle)) {
			if (is_dir($contents)) {
				if ($contents != '.' && $contents != '..') {
					$this->current_dirs[$j] = $contents;  
					$j++;
				}
			}
			elseif (is_file( $contents )) {
				
				$this->current_files[$i] = $contents;
				$i++;
			}
		}
		closedir($current_dir_handle);
	}
	/*
	-----------------------------------------------------------
	函数名称:get_dir_all()
	简要描述:获取一个目录文件大小,递归n级操作
	输入:string (相对或绝对路径，结尾没有斜杠)
	输出:float (所有文件大小想加后的值：字节)
	修改日志:------
	-----------------------------------------------------------
	*/
	function get_dir_size($dir_name)
	{
		if ($handle = @opendir($dir_name)) {
			while (($file = readdir($handle)) !== false) {

				if ($file == "." || $file == "..") {
					continue;
				}

				if (is_dir($dir_name."/".$file)){
					$this->get_dir_size($dir_name."/".$file);
					$this->current_size += filesize($dir_name."/".$file);
				}else {
					$this->current_size += filesize($dir_name."/".$file);
				}
			}
		}
		@closedir($handle);
	}

	/*
	-----------------------------------------------------------
	函数名称:copyFile($filename,$to='',$as='')
	简要描述:将一个文件copy到另一个文件夹下。
	输入:mixed (相对或绝对路径，结尾没有斜杠)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function copy_file($filename,$new_filename) {

		if(!@copy($filename,$new_filename)) 
			$this->print_error("FileDir::copy_file: 不能copy文件：$filename");
	}

	/*
	-----------------------------------------------------------
	函数名称:copy_dir()
	简要描述:转移一个目录
	输入:mixed {待转移文件夹，转移位置，是否删除老文件夹}
	输出:---
	修改日志:------
	-----------------------------------------------------------
	*/
	function copy_dir($old_dir,$new_dir,$del_dir = "N") {
		$handle = @opendir($old_dir); 
			while ($file = readdir($handle)) {
				if ($file == "." || $file == "..") {
					continue;
				}
				if(is_dir($old_dir."/".$file)) {
					@mkdir($new_dir."/".$file);
					$this->copy_dir($old_dir."/".$file,$new_dir."/".$file,$del_dir);
					if($del_dir=="Y") rmdir($old_dir."/".$file);
					//$dirNum++;
				}
				else{
					copy($old_dir."/".$file, $new_dir."/".$file);
					if($del_dir=="Y") unlink($old_dir."/".$file);
					//$fileNum++;
				}
			}
		@closedir($handle);
	}
	/*
	-----------------------------------------------------------
	函数名称:get_file_size($file, $round = false)
	简要描述:获取一个文件大小,如果传递文件，获取大小。如果传递数字返回格式化后数据
	输入:mixed (文件夹或字节);
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function get_file_size($file, $round = false)
	{
		$value = 0;
		if (@file_exists($file)) {
			$size = filesize($file);
		}
		else{
			$size = $file;
		}
			if ($size >= 1073741824) {
				$value = round($size/1073741824*100)/100;
				return  ($round) ? round($value) . 'Gb' : "{$value}Gb";
			} else if ($size >= 1048576) {
				$value = round($size/1048576*100)/100;
				return  ($round) ? round($value) . 'Mb' : "{$value}Mb";
			} else if ($size >= 1024) {
				$value = round($size/1024*100)/100;
				return  ($round) ? round($value) . 'kb' : "{$value}kb";
			} else {
				return "$size bytes";
			}
	}

	/*
	-----------------------------------------------------------
	函数名称:three_dir() 
	简要描述:将一个目录从一级目录转变成三级目录
	输入:char (是否删除原目录,Y删除,N保留)
	输出:int (返回成功转化的目录数量)
	修改日志:------
	-----------------------------------------------------------
	*/
	function three_dir($del_dir="N") 
	{
		$this->get_dir_info(); 
		$dirNum = 0;
		for ($i=0; $i<count($this->current_dirs); $i++) 
		{
			$strOldDir = $this->current_dir."/".$this->current_dirs[$i];
			$strNewDir = $this->current_dir."/".$this->current_dirs[$i]{0};
			if ($file == "." || $file == "..") 
			{
				continue;
			}
			elseif(is_dir($strOldDir) and strlen($this->current_dirs[$i])>=3)
			{
				//
				$this->create_dir($strNewDir);
				$this->create_dir($strNewDir."/".$this->current_dirs[$i]{1});
				$this->create_dir($strNewDir."/".$this->current_dirs[$i]{1}."/".$this->current_dirs[$i]{2});
				$this->create_dir($strNewDir."/".$this->current_dirs[$i]{1}."/".$this->current_dirs[$i]{2}."/".$this->current_dirs[$i]);	$this->copy_dir($strOldDir,$strNewDir."/".$this->current_dirs[$i]{1}."/".$this->current_dirs[$i]{2}."/".$this->current_dirs[$i],$del_dir);
				if($del_dir == "Y") $this->delete_dir($strOldDir);
				//
				$dirNum++;
			}
		}//end for
		return $dirNum;
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
		$PHPSEA_ERROR['FileDir_Error'] = $str;
	
		//判断是否显示错误输出..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>";
			print "<b>FileDir Error --</b> ";
			print "[<font color=000077>$str</font>]";
			print "</font></blockquote>";
		}
		else
		{
			return false;	
		}
	}//end func

}//end class
//===================================================================
?>
