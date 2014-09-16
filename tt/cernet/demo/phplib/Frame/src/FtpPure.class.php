<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:FtpPure.class.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2004/11/1
- 简要描述:ftp操作类,此类库调用ftp函数,需要php支持ftp模块.
- 运行环境:php4,需要ftp模块支持
- 修改记录:2005/01/28,indraw,增加了几个常用的函数;
---------------------------------------------------------------------
*/

/*
	$ftp = new FtpPure("192.168.0.168","john","111111");
	$ftp->ftp_put("test.rar",1);
	$ftp->ftp_nlist("./");
*/
/*
	FtpPure($server,$user,$pass,$port=21,$timeout=90)    //连接到ftp服务器
	ftp_connect()                   //执行连接
	ftp_quit()                      //断开连接

	ftp_pwd()                       //获取当前工作目录
	ftp_chdir($dirname)             //切换工作目录
	ftp_cdup()                      //切换到父目录
	ftp_mkdir($dirname)             //建立目录
	ftp_rmdir($dirname)             //删除目录

	ftp_nlist($dir)                 //获取目录下文件列表
	ftp_file_exists($pathname)      //判断文件是否存在
	ftp_delete($filename)           //删除文件
	ftp_rename($orig,$dest)         //重命名文件
	ftp_get($filename,$mode)        //下载文件
	ftp_put($filename, $mode=0)     //上传文件

	ftp_site($strCMD)               //执行CMD命令
*/

//=============================================================================
class FtpPure 
{

	VAR $show_errors    = true;       //是否error
	VAR $show_debug     = true;       //是否debug
	//
	VAR $server         = "";         //主机地址
	VAR $port           = 21;         //端口号
	VAR $timeout        = 90;         //超时设置
	VAR $user           = "";         //用户名
	VAR $pass            = "";         //密码
	VAR $type           = 0;          //类型
	VAR $mode           = true;       //被动模式是否打开
	//
	VAR $ftpstream      = 0;          //连接标识
	VAR $connected      = false;      //是否连接成功标识

	/*
	-----------------------------------------------------------
	函数名称:function FtpPure($server,$user,$pass,$port=21,$timeout=90)
	简要描述:构造函数,同时连接到ftp主机
	输入:mixd (主机名,用户名,密码,端口,超时);
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function FtpPure($server,$user,$pass,$port=21,$timeout=90) 
	{
		$this->server       = $server;
		$this->user         = $user;
		$this->pass         = $pass;
		$this->port         = $port;
		$this->timeout      = $timeout;
		$this->connected    = $this->ftp_connect();
	}//end func

	/*
	-----------------------------------------------------------
	函数名称:ftp_connect()
	简要描述:尝试连接到ftp服务器并登陆
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_connect() 
	{
		$res = false;
		$this->ftpstream = @ftp_connect($this->server,$this->port,$this->timeout);
		if ($this->ftpstream)
		{
			$this->print_debug("FtpPure::ftp_connect: 连接主机成功,尝试登陆以用户: " . $this->user );
			if (@ftp_login($this->ftpstream,$this->user,$this->pass))
			{
				$this->print_debug("FtpPure::ftp_connect: 登陆成功,连接到主机: " . $this->server . " 以用户: " . $this->user );
				ftp_pasv($this->ftpstream,$this->mode) ;
				$res = true;
			}
			else
			{
				$this->print_error("FtpPure::ftp_connect: 登陆失败" );
				$res = false;
			}
		}
		else
		{
			$this->print_error("FtpPure::ftp_connect: 主机 " . $this->server . " 不能被找到");
			$res = false;
		}
		return $res;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_close()
	简要描述:尝试断开ftp连接
	输入:void
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_quit() 
	{
		if ($this->ftpstream) 
			ftp_close($this->ftpstream);
		$this->print_debug("FtpPure::ftp_quit: === 成功关闭 FTP: ".$this->server." 连接 ===" . "\r\n" );
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_pwd ()
	简要描述:返回当前目录名
	输入:void
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_pwd() 
	{
		if ($res = @ftp_pwd($this->ftpstream))
			$this->print_debug("FtpPure::ftp_rmdir: 成功返回当前目录名 " . $res );
		else
			$this->print_error("FtpPure::ftp_rmdir: 不能当前目录名 " . $res );
		return $res;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_chdir($dirname)
	简要描述:在 FTP 服务器上切换当前目录
	输入:string (切换到的目录名称)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_chdir($dirname)
	{
		if ($res = @ftp_chdir($this->ftpstream,$dirname))
			$this->print_debug("FtpPure::ftp_chdir: 切换目录成功,当前目录为: " . $dirname );
		else
			$this->print_error("FtpPure::ftp_chdir: 切换目录失败,不能切换到: " . $dirname );
		return $res ;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_chup($dirname)
	简要描述:切换到当前ftp目录的父目录
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_cdup() 
	{
		if ($res = @ftp_cdup($this->ftpstream))
			$this->print_debug("FtpPure::ftp_chup: 成功切换到父目录" );
		else
			$this->print_error("FtpPure::ftp_chup: 切换到父目录失败" );
		return $res ;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_mkdir($dirname)
	简要描述:在ftp服务器上建立目录
	输入:string (目录名)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_mkdir($dirname) 
	{
		if( $this->ftp_file_exists($dirname) )
			$this->print_debug("FtpPure::ftp_mkdir: 远程目录已经存在,不需要建立: " . $dirname );
		elseif ($res = @ftp_mkdir($this->ftpstream,$dirname))
			$this->print_debug("FtpPure::ftp_mkdir: 成功建立目录: " . $dirname );
		else
			$this->print_error("FtpPure::ftp_mkdir: 没有成功建立目录: " . $dirname );
		return $res;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_rmdir($dirname)
	简要描述:在ftp服务器上删除目录
	输入:string (目录名)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_rmdir($dirname) 
	{
		if ($res = @ftp_rmdir($this->ftpstream,$dirname))
			$this->print_debug("FtpPure::ftp_rmdir: 成功删除目录: " . $dirname );
		else
			$this->print_error("FtpPure::ftp_rmdir: 不能删除目录: " . $dirname );
		return $res;
	}//end func

	
	/*
	-----------------------------------------------------------
	函数名称: ftp_nlist($dirname)
	简要描述:在ftp服务器列一个目录下的文件
	输入:string (目录名)
	输出:array (文件名数组)
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_nlist($dir)
	{
		$l = @ftp_nlist($this->ftpstream,$dir);
		if ($l==false)
		{
			$this->print_error("FtpPure::ftp_nlist: 不能取得文件列表: ".$dir );
		}
		else 
		{
			$this->print_debug("FtpPure::ftp_nlist: 文件列表: ".$dir );
			if ( $this->show_debug )
			{
				echo("<blockquote><pre>");
				var_dump($l);
				echo("</blockquote></pre>");
			}
		}
		Return $l;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称:ftp_file_exists($pathname)
	简要描述:判断文件是否存在
	输入:string (文件名)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_file_exists($pathname)
	{
		if (!($remote_list = $this->ftp_nlist($this->ftp_pwd() )))
		{
			$this->print_error("FtpPure::ftp_file_exists: 不能获取远程ftp服务器文件列表\n");
			return -1;
		}
		if( !$remote_list )
		{
			$this->print_debug("FtpPure::ftp_file_exists: 远程文件: ".$pathname." 不存在\n");
			return false;
		}
		reset($remote_list);
		while (list(,$value) = each($remote_list))
		{
			if ($value == $pathname)
			{
				$this->print_debug("FtpPure::ftp_file_exists: 远程文件: ".$pathname." 存在\n");
				return true;
			}
		}
		$this->print_debug("FtpPure::ftp_file_exists: 远程文件: ".$pathname." 不存在\n");
		return false;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_delete($dirname)
	简要描述:在ftp服务器删除一个文件
	输入:string (文件名)
	输出:boolean 
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_delete($filename) 
	{
		$res = @ftp_delete($this->ftpstream,$filename);
		if ($res) 
			$this->print_debug("FtpPure::ftp_delete: 文件: " . $filename . " 被成功删除" );
		else 
			$this->print_error("FtpPure::ftp_delete: 删除文件: " . $filename . " 失败" );
		return $res;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_rename($dirname)
	简要描述:在ftp服务器修改文件名
	输入:mixed (原始文件,新名称)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_rename($orig,$dest)
	{
		$res = @ftp_rename($this->ftpstream,$orig,$dest);
		if ($res)
			$this->print_debug("FtpPure::ftp_rename: 重命名文件: " . $orig ." 为: " .$dest." 成功" );
		else
			$this->print_error("FtpPure::ftp_rename: 重命名文件: " . $orig ." 为: " .$dest." 失败" );
		return $res;
	}//end func

	
	/*
	-----------------------------------------------------------
	函数名称: ftp_put($dirname)
	简要描述:向ftp服务器上上传文件
	输入:mixed (文件名,上传模式)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_put($filename,$remotefile, $mode=0) 
	{
		$res = false;
		switch ($mode)
		{
			case 0:
				$m = FTP_BINARY;
			break;
			case 1:
				$m = FTP_ASCII;
			break;
		}
		$res = @ftp_put($this->ftpstream,$filename,$remotefile,$m);
		if ($res)
			$this->print_debug("FtpPure::ftp_put: 文件:" . $filename . " 被成功上传" );
		else 
			$this->print_error("FtpPure::ftp_put: 文件:" . $filename . " 上传失败,文件可能存在或没有上传权限" );
		return $res;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_get($dirname,$mode)
	简要描述:从ftp服务器下载
	输入:mixed (文件名,上传模式)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_get($filename,$local_filename,$mode)
	{
		$res = false;
		switch ($mode)
		{
			case 0:
				$m = FTP_BINARY;
			break;
			case 1:
				$m = FTP_ASCII;
			break;
		}
		$res = @ftp_get($this->ftpstream,$local_filename,$filename,$m);
		if ($res)
			$this->print_debug("FtpPure::ftp_get: 成功下载文件: " . $filename );
		else
			$this->print_error("FtpPure::ftp_get: 下载文件: " . $filename . " 失败" );
		return $res;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称: ftp_site($strCMD)
	简要描述:在ftp服务器执行cmd命令
	输入:$strCMD (命令字符串)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_site($strCMD) 
	{
		if (@ftp_site($this->ftpstream, $strCMD))
			$this->print_debug("FtpPure::ftp_site: 成功执行命令:  " . $strCMD ."");
		else
			$this->print_error("FtpPure::ftp_site: 执行命令: " . $strCMD . " 失败" );
	}//end func

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
		$PHPSEA_ERROR['FilePure_Error'] = $str;
		//判断是否显示error输出..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>\n";
			print "<b>FtpPure Error --</b>\n";
			print "[<font color=000077>$str</font>]\n";
			print "</font></blockquote>\n";
		}
		else
		{
			return false;
		}
	}//end func

	/*
	-----------------------------------------------------------
	函数名称:print_debug($str = "")
	简要描述:显示操作信息show_debug
	输入:string 
	输出:echo
	修改日志:------
	-----------------------------------------------------------
	*/
	function print_debug($str = "")
	{
		//判断是否显示debug输出...
		if ( $this->show_debug )
		{
			print "<blockquote><font face=arial size=2 color=green>\n";
			print "<b>FtpPure Debug --</b>\n";
			print "[<font color=000077>$str</font>]\n";
			print "</font></blockquote>\n";
		}
	}//end func

}//end class
//=============================================================================
?>
