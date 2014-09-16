<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:funcCheck.inc.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2004/11/21
- 简要描述:常用功能函数集合-输入检查
- 运行环境:---
- 修改记录:2004/11/21，indraw，程序创立
---------------------------------------------------------------------
*/

/*

*/
//-------------------------------------------------------------------
	/*
	-----------------------------------------------------------
	函数名称：isNumber
	简要描述：检查输入的是否为数字
	输入：string
	输出：boolean
	修改日志：------
	-----------------------------------------------------------
	*/
	function isNumber($val)
	{
		if (ereg("^[0-9]+$", $val))
			return true;
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称：isPhone
	简要描述：检查输入的是否为电话
	输入：string
	输出：boolean
	修改日志：------
	-----------------------------------------------------------
	*/
	function isPhone($val)
	{
		if (ereg("^([0-9]{2,3})+(\-)+([0-9]{3,4})+(\-)+([0-9]{7,8})$",$val))
			return true;
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称：isMobile
	简要描述：检查输入的是否为手机号
	输入：string
	输出：boolean
	修改日志：------
	-----------------------------------------------------------
	*/
	function isMobile($val)
	{
		if (ereg("^[0-9]{11}$",$val))
			return true;
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称：isPostcode
	简要描述：检查输入的是否为邮编
	输入：string
	输出：boolean
	修改日志：------
	-----------------------------------------------------------
	*/
	function isPostcode($val)
	{
		if (ereg("^[0-9]{4,6}$",$val))
			return true;
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称：isEmail
	简要描述：邮箱地址合法性检查
	输入：string
	输出：boolean
	修改日志：------
	-----------------------------------------------------------
	*/
	function isEmail($val) 
	{
		//if (preg_match("/^[\da-z][\.\w-]+@[\da-z][\.\w-]+\.[a-z]{2,4}$/i", $email)) {
		if( preg_match("/^[a-z0-9-_.]+@[\da-z][\.\w-]+\.[a-z]{2,4}$/i", $val) )
		{
			return true;
		}
		return false;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称:isDomain($Domain)
	简要描述:检查一个（英文）域名是否合法
	输入:string 域名
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function isDomain($Domain)
	{
		if (!eregi("^[0-9a-z]+[0-9a-z\.-]+[0-9a-z]+$", $Domain))
		{
			Return false;
		}
		if( !eregi("\.", $Domain))
		{
			Return false;
		}
		
		if (eregi("\-\.", $Domain) or eregi("\-\-", $Domain) or eregi("\.\.", $Domain) or eregi("\.\-", $Domain))
		{
			Return false;
		}
		
		$aDomain = explode(".",$Domain);
		if( !eregi("[a-zA-Z]",$aDomain[count($aDomain)-1]) )
		{
			Return false;
		}

		if (strlen($aDomain[0]) > 63 || strlen($aDomain[0]) < 1)
		{
			Return false;
		}
		Return true;
	}

	/*
	-----------------------------------------------------------
	函数名称:isLength($theelement, $min, $max)
	简要描述:检查字符串长度是否符合要求
	输入:mixed (字符串，最小长度，最大长度)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function isLength($theelement, $min, $max)
	{
		$theelement = trim($theelement);
		//if( ereg("[\]",$theelement) )
		if( strstr($theelement, "\\\\") )
		{
			Return false;
		}
		if (strlen($theelement) <= $max && strlen($theelement) >= $min)
			return true;
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称:isNumberLength($theelement, $min, $max)
	简要描述:检查字符串长度是否符合要求
	输入:mixed (字符串，最小长度，最大长度)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function isNumLength($val, $min, $max)
	{
		$theelement = trim($val);
		//if( ereg("[\]",$theelement) )
		if (ereg("^[0-9]{".$min.",".$max."}$",$val))
			return true;
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称:isNumberLength($theelement, $min, $max)
	简要描述:检查字符串长度是否符合要求
	输入:mixed (字符串，最小长度，最大长度)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function isEngLength($val, $min, $max)
	{
		$theelement = trim($val);
		//if( ereg("[\]",$theelement) )
		if (ereg("^[a-zA-Z]{".$min.",".$max."}$",$val))
			return true;
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称：isEnglish
	简要描述：检查输入是否为英文
	输入：string
	输出：boolean
	作者：------
	修改日志：------
	-----------------------------------------------------------
	*/
	function isEnglish($theelement)
	{
		if( ereg("[\x80-\xff].",$theelement) )
		{
			Return false;
		}
		Return true;
	}

	/*
	-----------------------------------------------------------
	函数名称：isChinese
	简要描述：检查是否输入为汉字
	输入：string
	输出：boolean
	修改日志：------
	-----------------------------------------------------------
	*/
	function isChinese($sInBuf)
	{
		$iLen = strlen($sInBuf);
		for($i = 0; $i < $iLen; $i++)
		{
			if(ord($sInBuf{$i})>=0x80)
			{
				if( (ord($sInBuf{$i})>=0x81 && ord($sInBuf{$i})<=0xFE) && ((ord($sInBuf{$i+1})>=0x40 && ord($sInBuf{$i+1}) < 0x7E) || (ord($sInBuf{$i+1}) > 0x7E && ord($sInBuf{$i+1})<=0xFE)) )
				{
					if(ord($sInBuf{$i})>0xA0 && ord($sInBuf{$i})<0xAA)
					{
						//有中文标点
						return false;
					}
				}
				else
				{
					//有日文或其它文字
					return false;
				}
				$i++;
			}
			else
			{
				return false;
			}
		}
		return true;
	}

	/*
	-----------------------------------------------------------
	函数名称：isDate
	简要描述：检查日期是否符合0000-00-00
	输入：string
	输出：boolean
	修改日志：------
	-----------------------------------------------------------
	*/
	function isDate($sDate)
	{
		if( ereg("^[0-9]{4}\-[][0-9]{2}\-[0-9]{2}$",$sDate) )
		{
			Return true;
		}
		else
		{
			Return false;
		}
	}
	/*
	-----------------------------------------------------------
	函数名称：isTime
	简要描述：检查日期是否符合0000-00-00 00:00:00
	输入：string
	输出：boolean
	修改日志：------
	-----------------------------------------------------------
	*/
	function isTime($sTime)
	{
		if( ereg("^[0-9]{4}\-[][0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$",$sTime) )
		{
			Return true;
		}
		else
		{
			Return false;
		}
	}

	/*
	-----------------------------------------------------------
	函数名称:isMoney($val)
	简要描述:检查输入值是否为合法人民币格式
	输入:string
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function isMoney($val)
	{
		if (ereg("^[0-9]{1,}$", $val))
			return true;
		if( ereg("^[0-9]{1,}\.[0-9]{1,2}$", $val) )
			return true;
		return false;
	}

	/*
	-----------------------------------------------------------
	函数名称:isIp($val)
	简要描述:检查输入IP是否符合要求
	输入:string
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function isIp($val)
	{
		return (bool) ip2long($val);
	}

	/*
	-----------------------------------------------------------
	函数名称：safeChars
	简要描述：字符串安全过滤
	输入：string
	输出：string
	修改日志：------
	-----------------------------------------------------------
	*/
	function safeChars($msg) 
	{
		//$msg = trim($msg);
		$msg = str_replace(" ","&nbsp;",$msg); //替换空格替换为&nbsp;
		//$msg = HTMLSpecialChars($msg); //将特殊字元转成 HTML 格式。
		$msg = str_replace(">", "&gt;", $msg);
		$msg = str_replace("<", "&lt;", $msg);
		$msg = nl2br($msg); //将回车替换为<br />
		$msg = str_replace("\r", "", $msg);
		$msg = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $msg);
		return $msg;
	}//end func

	/*
	-----------------------------------------------------------
	函数名称：keepLen
	简要描述：字符串截取
	输入：string
	输出：string
	修改日志：------
	-----------------------------------------------------------
	*/
	function keepLen($str, $len) 
	{
		if ($str == '' or strlen($str) <= $len) {
			return $str;
		}
		$strTmp = substr($str, 0, $len);
		$strTmp2 = preg_replace("/[[:alnum:][:space:][:punct:]]/", "", $strTmp);
		if (!(strlen($strTmp2) % 2)) {
			return $strTmp.'...';
		}
		return substr($strTmp, 0, --$len).'...';
	}//end func

	/*
	-----------------------------------------------------------
	函数名称：keepLenCn
	简要描述：中文截取
	输入：string
	输出：string
	修改日志：------
	-----------------------------------------------------------
	*/
	function keepLenCn($str,$strlen=10,$other=true,$lg="UTF-8") 
	{
		if($lg=="UTF-8")
		{
			$rstr=mb_strimwidth($str, 0, $strlen, '', "UTF-8");
			if (strlen($str)>$strlen && $other) $rstr.='...';
			return $rstr;
		}
		else
		{
			$j = 0;
			for($i=0;$i<$strlen;$i++)
			{
				if(ord(substr($str,$i,1))>0xa0) $j++;
			}
			if($j%2!=0) $strlen--;
			$rstr=substr($str,0,$strlen);
			if (strlen($str)>$strlen && $other) $rstr.='...';
			return $rstr;
		}
	}

	/*
	-----------------------------------------------------------
	函数名称：getPassword
	简要描述：生成随机PW
	输入：string
	输出：string
	修改日志：------
	-----------------------------------------------------------
	*/
	function getPassword($iStrLen=8)
	{
		$aChar = array("1","2","3","4","5","6","7","8","9","0","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		
		$iCharLen = count($aChar) - 1;
		$sRandVal = "";
		for ($i = 0;$i<$iStrLen;$i++)
		{ 
 			srand((double)microtime()*1000000);
			$sRandVal .= $aChar[rand(0,$iCharLen)];
		}
		Return $sRandVal;
	}

	/*
	-----------------------------------------------------------
	函数名称:getTimeFull($datetime="")
	简要描述:获取时间：2005-1-21 12::00
	输入:string
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function getTimeFull($datetime="")
	{
		if( $datetime == "" ) $datetime = time();
		return date("Y-m-d H:i:s",$datetime);

	}

//-----------------------------------------------------------------------------
?>
