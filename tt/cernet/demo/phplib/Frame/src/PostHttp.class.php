<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.6
- 文件名:PostHttp.class.php
- 原作者:Tiago Serafim
- 整理者:indraw(wangyzh@dns.com.cn)
- 编写日期:2005/02/18
- 简要描述:采用get或post方法将数据发送到http服务器
- 运行环境:php4或以上
- 修改记录:2004/12/18,indraw,程序创立
- 修改记录:2005/05/08,indraw,添加addfields方法以及加入debug功能
---------------------------------------------------------------------
*/

/*
	$postData[test1] = "hehe";
	$postData[test2] = "haha";
	$postURL = "testPost.php";
	
	$http = new PostHttp();
	$http->clearFields();
	foreach ($postData as $key => $val)
	{
		$http->addField($key, $val);
	}
	$http->postPage($postURL);
	$strPostResult = $http->getContent();
*/

/*
	setReferer($sRef)          //设置来源url
	addField($sName,           //$sValue)  设置一个post变量
	clearFields()              //清除所有的post数据
	checkCookies()             //检查cookie
	setCookies($sName,         //$sValue)  设置cookie变量
	getCookies($sName)         //获取cookie信息
	clearCookies()             //清除cookie
	getContent()               //获取post后的反馈信息
	getHeaders()               //获取header信息
	getHeader($sName)          //获取header信息
	postPage($sURL)            //执行post操作
	getPage($sURL)             //执行get操作
	parseRequest($sURL)        //分析url地址
	HTMLEncode($sHTML)         //html编码
	downloadData($host, $port, $httpHeader)  //抓取get或post的信息
*/

//-------------------------------------------------------------------
class PostHttp
{
	var $show_errors    = true;       //是否error
	var $show_debug     = false;      //是否debug
	var $save_debug     = false;       //是否debug

	var $referer;
	var $postStr;
	var $retStr;
	var $theData;
	var $theCookies;

	/*
	-----------------------------------------------------------
	函数名称:PostHttp()
	简要描述:构造函数
	输入:void
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function PostHttp()
	{
	}
	/*
	-----------------------------------------------------------
	函数名称:setReferer($sRef)
	简要描述:设置来源url
	输入:string
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/
	function setReferer($sRef)
	{
		$this->referer = $sRef;
	}
	/*
	-----------------------------------------------------------
	函数名称:addField($sName, $sValue)
	简要描述:设置一个post变量
	输入:mixed （变量名，变量值）
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/
	function addField($sName, $sValue)
	{
		$this->postStr .= $sName . "=" . $this->HTMLEncode($sValue) . "&";
	}

	/*
	-----------------------------------------------------------
	函数名称:addFields($sValue)
	简要描述:设置post数据
	输入:mixed （变量值）
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/
	function addFields($sValue)
	{
		$this->postStr = $sValue;
	}

	/*
	-----------------------------------------------------------
	函数名称:clearFields()
	简要描述:清除所有的post数据
	输入:void
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/
	function clearFields()
	{
		$this->postStr = "";
	}
	/*
	-----------------------------------------------------------
	函数名称:checkCookies()
	简要描述:检查cookie
	输入:void
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/
	function checkCookies()
	{
		$cookies = explode("Set-Cookie:", $this->theData);
		$i = 0;
		if (count($cookies)-1 > 0)
		{
			while (list($foo, $theCookie) = each($cookies))
			{
				if (!($i == 0))
				{
					@list($theCookie, $foo) = explode(";", $theCookie);
					list($cookieName, $cookieValue) = explode("=", $theCookie);
					@list($cookieValue, $foo) = explode("\r\n", $cookieValue);
					$this->setCookies(trim($cookieName), trim($cookieValue));
				}
				$i++;
			}
		}

	}
	/*
	-----------------------------------------------------------
	函数名称:setCookies($sName, $sValue)
	简要描述:设置cookie变量
	输入:mixed （变量名，变量值）
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/
	function setCookies($sName, $sValue)
	{
		$total = count(explode($sName, $this->theCookies));
		if ($total > 1)
		{
			list($foo, $cValue) = explode($sName, $this->theCookies);
			list($cValue, $foo) = explode(";", $cValue);
			$this->theCookies = str_replace($sName . $cValue . ";", "", $this->theCookies);
		}
		$this->theCookies .= $sName . "=" . $this->HTMLEncode($sValue) . ";";
	}
	/*
	-----------------------------------------------------------
	函数名称:getCookies($sName)
	简要描述:获取cookie信息
	输入:string （变量名）
	输出:string
	修改日志:
	-----------------------------------------------------------
	*/
	function getCookies($sName)
	{
		list($foo, $cValue) = explode($sName, $this->theCookies);
		list($cValue, $foo) = explode(";", $cValue);
		return substr($cValue, 1);
	}
	/*
	-----------------------------------------------------------
	函数名称:clearCookies()
	简要描述:清除cookie
	输入:void
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/
	function clearCookies()
	{
		$this->theCookies = "";
	}
	/*
	-----------------------------------------------------------
	函数名称:getContent()
	简要描述:获取post后的反馈信息
	输入:void
	输出:
	修改日志:
	-----------------------------------------------------------
	*/
	function getContent()
	{
		if( !$this->theData )
		{
			$this->print_error("PostHttp::getContent: 不能获取post的反馈结果");
			Return false;
		}
		list($header, $foo) = explode("\r\n\r\n", $this->theData);
		list($foo, $content) = explode($header, $this->theData);
		return substr($content, 4);
	}
	/*
	-----------------------------------------------------------
	函数名称:getHeaders()
	简要描述:获取header信息
	输入:void
	输出:
	修改日志:
	-----------------------------------------------------------
	*/
	function getHeaders()
	{
		list($header, $foo) = explode("\r\n\r\n", $this->theData);
		list($foo, $content) = explode($header, $this->theData);
		return $header;
	}
	/*
	-----------------------------------------------------------
	函数名称:getHeader($sName)
	简要描述:获取header信息
	输入:void
	输出:
	修改日志:
	-----------------------------------------------------------
	*/
	function getHeader($sName)
	{
		list($foo, $part1) = explode($sName . ":", $this->theData);
		list($sVal, $foo) = explode("\r\n", $part1);
		return trim($sVal);
	}
	/*
	-----------------------------------------------------------
	函数名称:postPage($sURL)
	简要描述:执行post操作
	输入:string
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/

	function postPage($sURL)
	{
		$sInfo = $this->parseRequest($sURL);
		$request = $sInfo['request'];
		$host = $sInfo['host'];
		$port = $sInfo['port'];

		$this->postStr = substr($this->postStr, 0, -1); //retira a ultima &

		$httpHeader = "POST $request HTTP/1.0\r\n";
		$httpHeader .= "Host: $host\r\n";
		$httpHeader .= "Connection: Close\r\n";
		$httpHeader .= "User-Agent: PostHttp/1.5 (phpsea1.5 by dns.com.cn)\r\n";
		$httpHeader .= "Content-type: application/x-www-form-urlencoded\r\n";
		$httpHeader .= "Content-length: " . strlen($this->postStr) . "\r\n";
		$httpHeader .= "Referer: " . $this->referer . "\r\n";

		$httpHeader .= "Cookie: " . $this->theCookies . "\r\n";

		$httpHeader .= "\r\n";
		$httpHeader .= $this->postStr;
		$httpHeader .= "\r\n\r\n";

		$this->theData = $this->downloadData($host, $port, $httpHeader); // envia os dados para o servidor

		$this->checkCookies();

		//做记录
		$this->sSend = $httpHeader;
		$this->sReceive = $this->theData;
		$this->post_log();
	}
	/*
	-----------------------------------------------------------
	函数名称:getPage($sURL)
	简要描述:执行get操作
	输入:string
	输出:void
	修改日志:
	-----------------------------------------------------------
	*/
	function getPage($sURL)
	{
		$sInfo = $this->parseRequest($sURL);
		$request = $sInfo['request'];
		$host = $sInfo['host'];
		$port = $sInfo['port'];

		$httpHeader = "GET $request HTTP/1.1\r\n";
		$httpHeader .= "Host: $host\r\n";
		$httpHeader .= "Connection: Close\r\n";
		$httpHeader .= "User-Agent: PostHttp/1.5 (phpsea1.5 by dns.com.cn)\r\n";
		$httpHeader .= "Referer: " . $this->referer . "\r\n";

		$httpHeader .= "Cookie: " . substr($this->theCookies, 0, -1) . "\r\n";

		$httpHeader .= "\r\n\r\n";

		$this->theData = $this->downloadData($host, $port, $httpHeader); // envia os dados para o servidor
	}
	/*
	-----------------------------------------------------------
	函数名称:parseRequest($sURL)
	简要描述:分析url地址
	输入:string
	输出:array
	修改日志:
	-----------------------------------------------------------
	*/
	function parseRequest($sURL)
	{
		$this->sLogURL = $sURL;
		if( !eregi("^http://",$sURL) and !eregi("^https://",$sURL))
		{
			$this->print_error("PostHttp::parseRequest: 远程URL地址不符合要求: ".$sURL);
			Return false;
		}
		$this->print_debug("PostHttp::parseRequest: 开始解析远程URL:".$sURL);

		list($protocol, $sURL) = explode('://', $sURL); // separa o resto
		list($host, $foo) = explode('/', $sURL);        // pega o host
		list($foo, $request) = explode($host, $sURL);   // pega o request
		@list($host, $port) = explode(':', $host);      // pega a porta

		if (strlen($request) == 0)
			$request = "/";
		if (strlen($port) == 0)
			$port = "80";

		$sInfo = Array();
		$sInfo["host"] = $host;
		$sInfo["port"] = $port;
		$sInfo["protocol"] = $protocol;
		$sInfo["request"] = $request;

		return $sInfo;
	}
	/*
	-----------------------------------------------------------
	函数名称:HTMLEncode($sHTML)
	简要描述:html编码
	输入:string
	输出:string
	修改日志:
	-----------------------------------------------------------
	*/
	/* changed 06/30/2003 */
	function HTMLEncode($sHTML)
	{
		$sHTML = urlencode($sHTML);

		return $sHTML;
	}
	/*
	-----------------------------------------------------------
	函数名称:downloadData($host, $port, $httpHeader)
	简要描述:抓取get或post的信息
	输入:mixed
	输出:string
	修改日志:
	-----------------------------------------------------------
	*/
	function downloadData($host, $port, $httpHeader)
	{
		$fp = @fsockopen($host, $port);
		$retStr = "";
		if ($fp)
		{
			$this->print_debug("PostHttp::downloadData: 成功连接远程HOST:".$host);
			if( !@fwrite($fp, $httpHeader) )
			{
				$this->print_error("PostHttp::downloadData: 不能向远程URL发送数据");
				Return false;
			}
			$this->print_debug("PostHttp::downloadData: 成功向远程URL发送数据",$httpHeader);

			while (!feof($fp))
			{
				$retStr .= fread($fp, 1024);
				//break;
			}
			$this->print_debug("PostHttp::downloadData: 成功从远程URL接收数据",$retStr);
			fclose($fp);
		}
		else
		{
			$this->print_error("PostHttp::downloadData: 不能连接远程HOST:".$host);
			Return false;
		}
		return $retStr;
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
		$PHPSEA_ERROR['PostHttp_Error'] = $str;
		//判断是否显示error输出..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>\n";
			print "<b>PostHttp Error --</b>\n";
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
	function print_debug($str = "",$code = "")
	{
		//判断是否显示debug输出...
		if ( $this->show_debug )
		{
			print "<blockquote><font face=arial size=2 color=green>\n";
			print "<b>PostHttp Debug --</b>\n";
			print "[<font color=000077>$str</font>]<br>\n";
			if( $code ){
				echo("<table cellpadding=5 cellspacing=1 bgcolor=555555><tr bgcolor=eeeeee><td nowrap valign=bottom>");
				$sHighString = highlight_string($code,TRUE);
				echo($sHighString);
				echo("</td></tr></table>");
			}
			print "</font></blockquote>\n";
		}
	}//end func

	/*
	-----------------------------------------------------------
	函数名称:post_log()
	简要描述:记录post语句
	输入:void
	输出:void 
	修改日志:------
	-----------------------------------------------------------
	*/
	function post_log()
	{
		global $LibSet;
		if(!$this->save_debug) {
			return true;
		}
		if(!$this->referer)
		{
			$this->referer = $this->devGetUrl();
		}
		$sMonth = date("Ym",time());
		$sTodayDir = $LibSet['LogDir']."Post/".$sMonth;
		$sToday = "post".date("Y-m-d",time());
		$sFileName = $sTodayDir."/".$sToday.".xml";
		if( !file_exists($sTodayDir))
		{
			@mkdir($sTodayDir, 0777);
		}
		//打开文件
		//$sFileName = $this->save_dir.$sToday.".xml";
		if( !@file_exists($sFileName))
		{
			$handle = @fopen ($sFileName,"w");
		}
		else
		{
			$handle = @fopen ($sFileName,"a");
			
		}
		//写入数据
		$sTime = date("H:i:s",time());
		$aContent[] = "-------------------time:".$sTime."-------------------------------------\n\r";
		$aContent[] = "server:".$this->sLogURL."\n\r";
		$aContent[] = "local:".$this->referer."\n\r";
		$aContent[] = "------\n\r";
		$aContent[] = $this->postStr;
		$aContent[] = "\n\r------\n\r";
		$aContent[] = $this->theData;
		$aContent[] = "\n\r--------------------------------------------------------------------\n\r";
		$sContent = join("",$aContent);
		@fwrite($handle, $sContent."\n\r");
		//关闭文件
		@fclose($handle);
	}

	/**
	* 获取当前url
	* 
	* @author 王艳昭 <wangyzh@dns.com.cn>
	* @return boolean
	*/
	function devGetUrl()
	{
		$server = substr($_ENV["OS"], 0, 3);
		//iis
		if($server == 'Win')
		{
			$protocol = ($_SERVER['HTTPS'] == 'off') ? ('http://') : ('https://');
			$query = ($_SERVER['QUERY_STRING']) ? ('?'.$_SERVER['QUERY_STRING']) : ('');
			$url = $protocol.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].$query;
		}
		//apache
		elseif($_SERVER['SCRIPT_URI'])
		{
			$query = ($_SERVER['QUERY_STRING']) ? ('?'.$_SERVER['QUERY_STRING']) : ('');
			$url = $_SERVER['SCRIPT_URI'].$query;
		}
		//other
		else
		{
			$url = $_SERVER['REQUEST_URI'];
		}
		return $url;
	}

} // class

//-------------------------------------------------------------------
?>