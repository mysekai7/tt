<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:FtpSocket.class.php
- 原作者:TOMO
- 整理者:indraw
- 编写日期:2004/11/1
- 简要描述:ftp操作类,此类库调用socket函数,所以并不需要php支持ftp模块.
- 运行环境:php3或以上
- 修改记录:2005/02/06,indraw,对类的整体进行了重新整理
---------------------------------------------------------------------
*/

/*
	$ftp = new FtpSocket("192.168.0.168","john","111111");
	$ftp->ftp_put("test.rar",1);
	$ftp->ftp_nlist("./");
*/

/*
	FtpSocket($server,$user,$pass,$port=21,$timeout=90)    //连接到ftp服务器
	ftp_connect($server, $port = 21)               //执行连接操作
	ftp_login($user, $pass)                        //执行登陆操作
	ftp_quit()                                     //断开连接

	ftp_pwd()                                      //获取当前工作目录
	ftp_chdir($pathname)                           //切换工作目录
	ftp_cdup()                                     //切换到父目录
	ftp_mkdir($pathname)                           //建立目录
	ftp_rmdir($pathname)                           //删除目录

	ftp_nlist($arg = "", $pathname = "")           //获取目录下文件列表
	ftp_file_exists($pathname)                     //判断文件是否存在
	ftp_delete($pathname)                          //删除文件
	ftp_rename($from, $to)                         //重命名文件
	ftp_get($localfile, $remotefile, $mode = 1)    //下载文件
	ftp_put($remotefile, $localfile, $mode = 1)    //上传文件

	ftp_size($pathname)                            //获取文件大小
	ftp_mdtm($pathname)                            //获取文件的修改时间
	ftp_systype()                                  //返回FTP 服务器的系统类型
	ftp_rawlist($pathname = "")                    //返回目录下文件的详细列表

	ftp_site($command)                             //执行CMD命令
*/

//=============================================================================
class FtpSocket
{
	//
	var $show_errors = true;          //是否error
	var $show_debug  = true;          //是否debug
	var $show_cmd    = false;         //是否显示ftp命令
	//
	VAR $server         = "";         //主机地址
	VAR $port           = 21;         //端口号
	VAR $timeout        = 90;         //超时设置
	VAR $user           = "";         //用户名
	VAR $pwd            = "";         //密码
	//
	var $ftp_sock;                   //连接标识
	var $ftp_resp       ="";         //socket返回标识
	var $umask          = 0022;      //改变当前的umask

	/*
	-----------------------------------------------------------
	函数名称:FtpSocket($server,$user,$pass,$port=21,$timeout=90) 
	简要描述:构造函数,进行主机连接,登陆
	输入:mixed (主机名,用户,密码,端口,超时)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function FtpSocket($server,$user,$pass,$port=21,$timeout=90) 
	{
		$this->server       = $server;
		$this->user         = $user;
		$this->pass         = $pass;
		$this->port         = $port;
		$this->timeout      = $timeout;
		$this->ftp_connect();
		$this->ftp_login();
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_connect()
	简要描述:尝试连接到ftp服务器
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_connect()
	{
		$this->ftp_sock = @fsockopen($this->server, $this->port, $errno, $errstr, $this->timeout);
		if (!$this->ftp_sock || !$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_connect: 不能连接到远程主机: ".$this->server.":".$this->port."");
			$this->print_error("FtpSocket::ftp_connect: fsockopen() ".$errstr." (".$errno.")");
			return FALSE;
		}
		$this->print_debug("FtpSocket::ftp_connect: 连接到远程主机: ".$this->server.":".$this->port);
		return TRUE;
	}
	/*
	-----------------------------------------------------------
	函数名称:ftp_login()
	简要描述:尝试登陆ftp服务器
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_login()
	{
		$this->ftp_putcmd("USER", $this->user);
		if (!$this->ftp_ok()) 
		{
			$this->print_error("FtpSocket::ftp_login: 用户名错误");
			return FALSE;
		}
		$this->ftp_putcmd("PASS", $this->pass);
		if (!$this->ftp_ok()) 
		{
			$this->print_error("FtpSocket::ftp_login: 密码错误");
			return FALSE;
		}
		$this->print_debug("FtpSocket::ftp_login: 成功通过验证,并登陆.");
		return TRUE;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_quit()
	简要描述:关闭一个活动的 FTP 连接
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_quit()
	{
		$this->ftp_putcmd("QUIT");
		if (!$this->ftp_ok() || !fclose($this->ftp_sock)) 
		{
			$this->print_error("FtpSocket::ftp_quit: 关闭ftp服务器连接失败");
			return FALSE;
		}
		$this->print_debug("FtpSocket::ftp_quit: === 成功关闭 FTP: ".$this->server." 连接 ===");
		return TRUE;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_pwd()
	简要描述:返回当前目录名
	输入:void
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/

	function ftp_pwd()
	{
		$this->ftp_putcmd("PWD");
		if (!$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_pwd: 获取ftp主机当前工作目录名称错误");
			return FALSE;
		}
		$res = ereg_replace("^[0-9]{3} \"(.+)\" .+\r\n", "\\1", $this->ftp_resp);

		$this->print_debug("FtpSocket::ftp_pwd: 获取当前工作目录: ".$res." 成功");
		return $res;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_chdir($pathname)
	简要描述:在 FTP 服务器上切换当前目录
	输入:string
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_chdir($pathname)
	{
		$this->ftp_putcmd("CWD", $pathname);
		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_chdir: 切换工作目录: ".$pathname."失败");
		}
		$this->print_debug("FtpSocket::ftp_chdir: 切换工作目录: ".$pathname." 成功");
		return $res;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_cdup()
	简要描述:切换到当前目录的父目录
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_cdup()
	{
		$this->ftp_putcmd("CDUP");
		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_cdup: 切换到当前父目录失败");
		}
		$this->print_debug("FtpSocket::ftp_cdup: 切换当前父目录成功");
		return $res;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_mkdir($pathname)
	简要描述:建立目录
	输入:string
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_mkdir($pathname)
	{
		$this->ftp_putcmd("MKD", $pathname);
		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_mkdir: 建立目录: ".$pathname."失败");
		}
		$this->print_debug("FtpSocket::ftp_mkdir: 建立目录: ".$pathname."成功");
		return $res;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_rmdir($pathname)
	简要描述:删除目录
	输入:string
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_rmdir($pathname)
	{
		$this->ftp_putcmd("RMD", $pathname);
		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_rmdir: 删除目录".$pathname."失败");
		}
		$this->print_debug("FtpSocket::ftp_rmdir: 删除目录".$pathname."成功");
		return $res;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_nlist($arg = "", $pathname = "")
	简要描述:返回给定目录的文件列表
	输入:mixed
	输出:array
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_nlist($arg = "", $pathname = "")
	{
		if (!($string = $this->ftp_pasv()))
		{
			return FALSE;
		}

		if ($arg == "")
			$nlst = "NLST";
		else
			$nlst = "NLST ".$arg;

		$this->ftp_putcmd($nlst, $pathname);

		$sock_data = $this->ftp_open_data($string);

		if (!$sock_data || !$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_nlist: 返回指定目录: ".$pathname."文件列表失败");
			return FALSE;
		}
		while (!feof($sock_data))
		{
			$list[] = ereg_replace("[\r\n]", "", fgets($sock_data, 512));
		}
		$this->ftp_close_data($sock_data);

		if (!$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_nlist: 返回指定目录: ".$pathname."文件列表失败");
			return FALSE;
		}
		else
		{
			$this->print_debug("FtpSocket::ftp_nlist: 成功返回指定目录: ".$pathname."文件列表");
			if ( $this->show_debug )
			{
				echo("<blockquote><pre>");
				var_dump($list);
				echo("</blockquote></pre>");
			}
		}
		return $list;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_file_exists($pathname)
	简要描述:判断文件是否存在
	输入:string
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_file_exists($pathname)
	{
		if (!($remote_list = $this->ftp_nlist("-a")))
		{
			$this->print_error("FtpSocket::ftp_file_exists: 不能获取文件列表");
			return -1;
		}
		reset($remote_list);
		while (list(,$value) = each($remote_list))
		{
			if ($value == $pathname)
			{
				$this->print_debug("FtpSocket::ftp_file_exists: 远程文件: ".$pathname." 存在");
				return true;
			}
		}
		$this->print_debug("FtpSocket::ftp_file_exists: 远程文件: ".$pathname." 不存在");
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_delete($pathname)
	简要描述:删除文件
	输入:string
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_delete($pathname)
	{
		$this->ftp_putcmd("DELE", $pathname);
		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_delete: 删除文件: ".$pathname."失败");
		}
		$this->print_debug("FtpSocket::ftp_delete: 删除文件: ".$pathname." 成功");
		return $res;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_rename($from, $to)
	简要描述:修改文件名
	输入:mixed
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_rename($from, $to)
	{
		$this->ftp_putcmd("RNFR", $from);
		if (!$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_rename: 修改文件: ".$from." 为: ".$to."失败");
			return FALSE;
		}
		$this->ftp_putcmd("RNTO", $to);
		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_rename: 修改文件: ".$from." 为: ".$to."失败");
		}
		$this->print_debug("FtpSocket::ftp_rename: 修改文件: ".$from." 为: ".$to."成功");
		return $res;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_get($localfile, $remotefile, $mode = 1)
	简要描述:从 FTP 服务器上下载一个文件
	输入:mixed
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_get($localfile, $remotefile, $mode = 1)
	{
		umask($this->umask);
		if (@file_exists($localfile))
		{
			$this->print_error("FtpSocket::ftp_get: 警告！本地文件:".$localfile."将会被覆盖");
		}
		$fp = @fopen($localfile, "w");
		if (!$fp)
		{
			$this->print_error("FtpSocket::ftp_get: 不能建立本地文件: ".$localfile."");
			return FALSE;
		}

		if (!$this->ftp_type($mode))
		{
			$this->print_error("FtpSocket::ftp_get: 设置传输模式失败");
			return FALSE;
		}

		if (!($string = $this->ftp_pasv()))
		{
			$this->print_error("FtpSocket::ftp_get: 返回服务器主动、被动模式失败");
			return FALSE;
		}

		$this->ftp_putcmd("RETR", $remotefile);

		$sock_data = $this->ftp_open_data($string);
		if (!$sock_data || !$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_get: 不能连接到远程ftp服务器");
			return FALSE;
		}

		while (!feof($sock_data))
		{
			fputs($fp, fread($sock_data, 4096));
		}
		fclose($fp);

		$this->ftp_close_data($sock_data);

		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_get: 文件: ".$remotefile." 下载失败");
		}
		$this->print_debug("FtpSocket::ftp_get: 成功接收ftp服务器文件: ".$remotefile." 并存为: ".$localfile);
		return $res;
	}
	/*
	-----------------------------------------------------------
	函数名称:ftp_put($remotefile, $localfile, $mode = 1)
	简要描述:上传一个已经打开的文件到 FTP 服务器
	输入:mixed
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_put($remotefile, $localfile, $mode = 1)
	{
		
		if (!@file_exists($localfile))
		{
			$this->print_error("FtpSocket::ftp_put: 文件或目录: ".$localfile."不存在");
			return FALSE;
		}

		$fp = @fopen($localfile, "r");
		if (!$fp)
		{
			$this->print_error("FtpSocket::ftp_put: 不能读取本地文件: ".$localfile);
			return FALSE;
		}

		if (!$this->ftp_type($mode))
		{
			$this->print_error("FtpSocket::ftp_put: 设置传输模式失败");
			return FALSE;
		}

		if (!($string = $this->ftp_pasv()))
		{
			$this->print_error("FtpSocket::ftp_put: 返回服务器主动、被动模式失败");
			return FALSE;
		}
		$this->ftp_putcmd("STOR", $remotefile);

		$sock_data = $this->ftp_open_data($string);
		if (!$sock_data || !$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_put: 上传文件: ".$localfile."失败,文件可能存在或没有上传权限");
			return FALSE;
		}
		while (!feof($fp))
		{
			fputs($sock_data, fread($fp, 4096));
		}
		fclose($fp);

		$this->ftp_close_data($sock_data);

		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_put: 上传文件: ".$localfile."失败,文件可能存在或没有上传权限");
		}
		$this->print_debug("FtpSocket::ftp_put: 将本地文件: ".$localfile." 上传为: ".$remotefile);
		return $res;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_size($pathname)
	简要描述:获得服务器指定目录大小
	输入:string
	输出:int
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_size($pathname)
	{
		$this->ftp_putcmd("SIZE", $pathname);
		if (!$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_size: 获取文件: ".$pathname." 大小失败");
			return false;
		}
		$res = ereg_replace("^[0-9]{3} ([0-9]+)\r\n", "\\1", $this->ftp_resp);
		$this->print_debug("FtpSocket::ftp_size: 获取文件: ".$pathname." 大小为: ".$res."成功");
		return $res;
	}
	/*
	-----------------------------------------------------------
	函数名称:ftp_mdtm($pathname)
	简要描述:返回指定文件的最后修改时间
	输入:string
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_mdtm($pathname)
	{
		$this->ftp_putcmd("MDTM", $pathname);
		if (!$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_mdtm: 返回文件: ".$pathname."最后修改时间失败");
			return -1;
		}
		$mdtm = ereg_replace("^[0-9]{3} ([0-9]+)\r\n", "\\1", $this->ftp_resp);
		$date = sscanf($mdtm, "%4d%2d%2d%2d%2d%2d");
		$timestamp = mktime($date[3], $date[4], $date[5], $date[1], $date[2], $date[0]);
		
		$this->print_debug("FtpSocket::ftp_mdtm: 返回文件: ".$pathname."最后修改时间: ".$timestamp);
		return $timestamp;
	}
	/*
	-----------------------------------------------------------
	函数名称:ftp_systype()
	简要描述:获得服务器操作系统类型
	输入:void
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_systype()
	{
		$this->ftp_putcmd("SYST");
		if (!$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_systype: 获取服务器操作系统类型失败");
			return FALSE;
		}
		$DATA = explode(" ", $this->ftp_resp);
		
		$this->print_debug("FtpSocket::ftp_systype: 获取服务器操作系统类型: ".$DATA[1]);
		return $DATA[1];
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_rawlist($pathname = "")
	简要描述:返回指定目录下文件的详细列表
	输入:string
	输出:array
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_rawlist($pathname = "")
	{
		if (!($string = $this->ftp_pasv()))
		{
			$this->print_error("FtpSocket::ftp_rawlist: 返回服务器主动、被动模式失败");
			return FALSE;
		}
		$this->ftp_putcmd("LIST", $pathname);

		$sock_data = $this->ftp_open_data($string);

		if (!$sock_data || !$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_rawlist: 获取目录: ".$pathname." 文件详细列表失败\n");
			return FALSE;
		}

		while (!feof($sock_data))
		{
			$list[] = ereg_replace("[\r\n]", "", fgets($sock_data, 512));
		}
		$this->print_error(implode("\n", $list));
		$this->ftp_close_data($sock_data);

		if (!$this->ftp_ok())
		{
			$this->print_error("FtpSocket::ftp_rawlist: 获取目录: ".$pathname." 文件详细列表失败\n");
			return FALSE;
		}
		$this->print_debug("FtpSocket::ftp_rawlist: 成功返回目录: ".$pathname." 详细文件列表成功");
		if ( $this->show_debug )
		{
			echo("<blockquote><pre>");
			var_dump($list);
			echo("</blockquote></pre>");
		}
		return $list;
	}

	/*
	-----------------------------------------------------------
	函数名称:ftp_site($command)
	简要描述:向服务器发送 SITE 命令
	输入:string
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function ftp_site($command)
	{
		$this->ftp_putcmd("SITE", $command);
		$res = $this->ftp_ok();
		if (!$res)
		{
			$this->print_error("FtpSocket::ftp_site: SITE 命令发送失败");
		}
		$this->print_debug("FtpSocket::ftp_site: SITE 命令: ".$command);
		return $res;
	}

//=============================================================================
	//设置文件传输模式
	function ftp_type($mode)
	{
		if ($mode)
			$type = "I"; //Binary mode
		else
			$type = "A"; //ASCII mode

		$this->ftp_putcmd("TYPE", $type);
		$res = $this->ftp_ok();
		return $res;
	}
	//设置端口号
	function ftp_port($ip_port)
	{
		$this->ftp_putcmd("PORT", $ip_port);
		$res = $this->ftp_ok();
		return $res;
	}

	//返回当前 FTP 被动模式是否打开
	function ftp_pasv()
	{
		$this->ftp_putcmd("PASV");
		if (!$this->ftp_ok())
		{
			return FALSE;
		}
		$ip_port = ereg_replace("^.+ \\(?([0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]+,[0-9]+)\\)?.*\r\n$", "\\1", $this->ftp_resp);
		return $ip_port;
	}
	//执行cmd命令
	function ftp_putcmd($cmd, $arg = "")
	{
		if ($arg != "")
		{
			$cmd = $cmd." ".$arg;
		}

		fputs($this->ftp_sock, $cmd."\r\n");
		if( $this->show_cmd )
		{
			$this->print_debug("<font color='green'>".$cmd."</font>");
		}
		return TRUE;
	}
	//对返回结果进行分析
	function ftp_ok()
	{
		$this->ftp_resp = "";
		do {
			$res = fgets($this->ftp_sock, 512);
			$this->ftp_resp .= $res;
		} while (substr($res, 3, 1) != " ");
		
		if( $this->show_cmd )
		{
			$this->print_debug("<font color='green'>".str_replace("\r\n", "\n", $this->ftp_resp)."</font>");
		}
		if (!ereg("^[123]", $this->ftp_resp))
		{
			return FALSE;
		}

		return TRUE;
	}
	//数据发送结束
	function ftp_close_data($sock)
	{
		$this->print_debug("FtpSocket::ftp_close_data: 与服务器断开数据传送");
		return fclose($sock);
	}
	//数据发送开始
	function ftp_open_data($ip_port)
	{
		if (!ereg("[0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]+,[0-9]+", $ip_port))
		{
			$this->print_error("FtpSocket::ftp_open_data: 错误的连接端口(".$ip_port.")");
			return FALSE;
		}

		$DATA = explode(",", $ip_port);
		$ipaddr = $DATA[0].".".$DATA[1].".".$DATA[2].".".$DATA[3];
		$port   = $DATA[4]*256 + $DATA[5];
		$this->print_debug("FtpSocket::ftp_open_data: 与服务器".$ipaddr." 开始数据传送...");
		$data_connection = @fsockopen($ipaddr, $port, $errno, $errstr);
		if (!$data_connection)
		{
			$this->print_error("FtpSocket::ftp_open_data: 不能从:  ".$ipaddr.":".$port." 打开数据连接");
			$this->print_error("FtpSocket::ftp_open_data: ".$errstr." (".$errno.")");
			return FALSE;
		}
		return $data_connection;
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
		//$PHPSEA_ERROR['FileSocket_Error'] = $str;
	
		//判断是否显示错误输出..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>\n";
			print "<b>FtpSocket Debug --</b>\n";
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
		//判断是否显示错误输出..
		if ( $this->show_debug )
		{
			print "<blockquote><font face=arial size=2 color=green>\n";
			print "<b>FtpSocket Debug --</b>\n";
			print "[<font color=000077>$str</font>]\n";
			print "</font></blockquote>\n";
		}
	}

}//end class
//=============================================================================
?>