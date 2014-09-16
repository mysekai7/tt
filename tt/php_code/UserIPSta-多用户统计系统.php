<?php
	define(_StatisticsPath_,"_UserIPSta/_".$_GET["user"]."/");
	define(_SysStr_,"┍ΡΤ┑");

	//处理日期[PHP5设置弥补][中国]
	//GetDateRPC("Y/m/d H:i:s","2007/01/31 12:09:06")
	function GetDateRPC($Style,$Set=""){
		//检测函数是否存在PHP 4.2以上的版本可行
		if(function_exists("date_default_timezone_set")){
			//PHP5的函数，强制设置时区为中国
			date_default_timezone_set("PRC");
		}
		if($Set!=""){
			//获取参数$Set的内容
			$Datas=explode(" ",$Set);
			$DataTemp=explode("/",$Datas[0]);
			$TimeTemp=explode(":",$Datas[1]);
			$Result=date($Style,mktime($TimeTemp[0],$TimeTemp[1],$TimeTemp[3],$DataTemp[1],$DataTemp[2],$DataTemp[0]));
		}else{
			$Result=date($Style);
		}
		return $Result;
	}

	//处理日期[PHP5设置弥补][中国]
	//GetMKTimeRPC("2007/01/31 12:09:06")
	function GetMKTimeRPC($Style){
		//检测函数是否存在PHP 4.2以上的版本可行
		if(function_exists("date_default_timezone_set")){
			//PHP5的函数，强制设置时区为中国
			date_default_timezone_set("PRC");
		}
		if($Style!=""){
			$Datas=explode(" ",$Style);
			$DataTemp=explode("/",$Datas[0]);
			$TimeTemp=explode(":",$Datas[1]);
			$Result=mktime($TimeTemp[0],$TimeTemp[1],$TimeTemp[3],$DataTemp[1],$DataTemp[2],$DataTemp[0]);
		}
		return $Result;
	}

	//优化创建目录函数
	function CreatDir($DirFileName){
		if(file_exists($DirFileName)){
			if(!is_dir($DirFileName)){
				unlink($DirFileName);
				mkdir($DirFileName, 0777);
			}
		}else{
			mkdir($DirFileName, 0777);
		}
	}

	//优化创建文件函数
	function CreatFile($FileName){
		if(file_exists($FileName)){
			if(is_dir($FileName)){
				rmdir($FileName);
				touch($FileName);
				chmod($FileName,0777);
			}
		}else{
			touch($FileName);
			chmod($FileName,0777);
		}
	}

	//读取文件到数组
	function FileToArray($FileName){
		if(file_exists($FileName)){
			$FileHead=fopen($FileName,"r");
			flock($FileHead,LOCK_SH);
			$FileLen=filesize($FileName);
			if($FileLen<=0){
				$FileLen=1024;
			}
			$Str=fread($FileHead,$FileLen);
			fclose($FileHead);
			$Str=str_replace("\n","\n-≮Ρ临时分割Τ≯-",$Str);
			$TempStr=explode("-≮Ρ临时分割Τ≯-",$Str);
			return $TempStr;
		}
	}

	//获取文件行数
	function FileLine($FileName){
		if(file_exists($FileName)){
			$FileContent=FileToArray($FileName);
			$FileLines=sizeof($FileContent);
			return $FileLines;
		}
	}

	//写入文件
	function FileWrite($FileName,$Method="a",$FileContent){
		$FileHead=fopen($FileName,$Method);
		flock($FileHead,LOCK_EX);
		fwrite($FileHead,$FileContent);
		fclose($FileHead);
	}

	//获取客户端IP
	function GetClinetIP(){
		if($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]){
			$IP=$HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}elseif($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]){
			$IP=$HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}elseif($HTTP_SERVER_VARS["REMOTE_ADDR"]){
			$IP=$HTTP_SERVER_VARS["REMOTE_ADDR"];
		}elseif(getenv("HTTP_X_FORWARDED_FOR")){
			$IP=getenv("HTTP_X_FORWARDED_FOR");
		}elseif(getenv("HTTP_CLIENT_IP")){
			$IP=getenv("HTTP_CLIENT_IP");
		}elseif(getenv("REMOTE_ADDR")){
			$IP=getenv("REMOTE_ADDR");
		}else{
			$IP="Unknown";
		}
		return $IP;
	}

	//获取用户操作系统
	function GetClinetOS(){
		$OS="0";
		$Agent=$_SERVER["HTTP_USER_AGENT"];
		if(stristr($Agent,"win")!=false and stristr($Agent,"95")!=false){
			$OS="1";
		}elseif(stristr($Agent,"win 9x")!=false and stristr($Agent,"4.90")!=false){
			$OS="2";
		}elseif(stristr($Agent,"win")!=false and stristr($Agent,"98")!=false){
			$OS="3";
		}elseif(stristr($Agent,"win")!=false and stristr($Agent,"nt 5.2")!=false){
			$OS="4";
		}elseif(stristr($Agent,"win")!=false and stristr($Agent,"nt 5.1")!=false){
			$OS="5";
		}elseif(stristr($Agent,"win")!=false and stristr($Agent,"nt 5.0")!=false){
			$OS="6";
		}elseif(stristr($Agent,"win")!=false and stristr($Agent,"nt")!=false){
			$OS="7";
		}elseif(stristr($Agent,"win")!=false and stristr($Agent,"32")!=false){
			$OS="8";
		}elseif(stristr($Agent,"win")!=false){
			$OS="9";
		}elseif(stristr($Agent,"linux")!=false){
			$OS="10";
		}elseif(stristr($Agent,"unix")!=false){
			$OS="11";
		}elseif(stristr($Agent,"sun")!=false and stristr($Agent,"os")!=false){
			$OS="12";
		}elseif(stristr($Agent,"ibm")!=false and stristr($Agent,"os")!=false){
			$OS="13";
		}elseif(stristr($Agent,"Mac")!=false and stristr($Agent,"PC")!=false){
			$OS="14";
		}elseif(stristr($Agent,"PowerPC")!=false){
			$OS="15";
		}elseif(stristr($Agent,"AIX")!=false){
			$OS="16";
		}elseif(stristr($Agent,"HPUX")!=false){
			$OS="17";
		}elseif(stristr($Agent,"NetBSD")!=false){
			$OS="18";
		}elseif(stristr($Agent,"BSD")!=false){
			$OS="19";
		}elseif(stristr($Agent,"OSF1")!=false){
			$OS="20";
		}elseif(stristr($Agent,"IRIX")!=false){
			$OS="21";
		}elseif(stristr($Agent,"FreeBSD")!=false){
			$OS="22";
		}
		if($OS==""){ $OS="0"; }
		return $OS;
	}

	//获取用户浏览器
	function GetClinetBrowser(){
		$Browser="0";
		$Agent=$_SERVER["HTTP_USER_AGENT"];
		if(stristr($Agent,"MSIE 6.0")!=false){
			$Browser="1";
		}elseif(stristr($Agent,"MSIE 5.5")!=false){
			$Browser="2";
		}elseif(stristr($Agent,"MSIE 5.0")!=false){
			$Browser="3";
		}elseif(stristr($Agent,"MSIE 4.0")!=false){
			$Browser="4";
		}elseif(stristr($Agent,"MSIE 3.0")!=false){
			$Browser="5";
		}elseif(stristr($Agent,"MSIE")!=false){
			$Browser="6";
		}elseif(stristr($Agent,"Firefox/2.0")!=false){
			$Browser="7";
		}elseif(stristr($Agent,"Firefox")!=false){
			$Browser="8";
		}elseif(stristr($Agent,"Netscape/8.1")!=false){
			$Browser="9";
		}elseif(stristr($Agent,"Netscape/7.1")!=false){
			$Browser="10";
		}elseif(stristr($Agent,"Netscape6/")!=false){
			$Browser="11";
		}elseif(stristr($Agent,"Netscape")!=false){
			$Browser="12";
		}elseif(stristr($Agent,"Mozilla/4")!=false){
			$Browser="13";
		}elseif(stristr($Agent,"Mozilla")!=false){
			$Browser="14";
		}elseif(stristr($Agent,"Firebird")!=false){
			$Browser="15";
		}elseif(stristr($Agent,"Opera/9.1")!=false){
			$Browser="16";
		}elseif(stristr($Agent,"Opera/9.0")!=false){
			$Browser="17";
		}elseif(stristr($Agent,"Opera/8")!=false){
			$Browser="18";
		}elseif(stristr($Agent,"Opera")!=false){
			$Browser="19";
		}
		if($Browser==""){ $Browser="0"; }
		return $Browser;
	}

	function GetRobots(){
		$Robots="0";
		$Agent=$_SERVER["HTTP_USER_AGENT"];
		if(stristr($Agent,"googlebot")!=false){
			$Robots="1";
		}elseif(stristr($Agent,"slurp")!=false){
			$Robots="2";
		}elseif(stristr($Agent,"baiduspider")!=false){
			$Robots="3";
		}elseif(stristr($Agent,"iaskspider")!=false){
			$Robots="4";
		}elseif(stristr($Agent,"W3C_Validator")!=false){
			$Robots="5";
		}elseif(stristr($Agent,"W3C_CSS")!=false){
			$Robots="6";
		}
		if($Robots==""){ $Robots="0"; }
		return $Robots;
	}

	//登记来访信息
	function SaveClinetInfo(){
		//获取IP
		$IPInfo=GetClinetIP();

		//获取系统类型
		$OS=GetClinetOS();

		//获取浏览器类型
		$Browser=GetClinetBrowser();

		//获取蜘蛛类型
		$Robots=GetRobots();

		//获取来访信息头
		$Agent=$_SERVER["HTTP_USER_AGENT"];

		//获取访问来源
		$BackPageInfo=$_SERVER['HTTP_REFERER'];
		if($BackPageInfo==''){
			$BackPageInfo="直接访问";
		}

		//获取当前日期和时间
		$DateInfo=GetDateRPC("Y/m/d");
		$TimeInfo=GetDateRPC("H:i:s");

		//截取日期时间
		$DateClass=explode("/",$DateInfo);

		//获取，年月目录，日记录文件
		$YearDirPath=_StatisticsPath_."_".$DateClass[0]."/";
		$MonthDirPath=_StatisticsPath_."_".$DateClass[0]."/_".$DateClass[1]."/";
		$DayFileName=_StatisticsPath_."_".$DateClass[0]."/_".$DateClass[1]."/_".$DateClass[2].".php";

		//检测年目录，不存在（类别不对）建立
		CreatDir($YearDirPath);

		//检测月目录，不存在（类别不对）建立
		CreatDir($MonthDirPath);

		//检测日记录文件，不存在（类别不对）建立
		CreatFile($DayFileName);

		//获取总统计文件
		$AllStaFileName=_StatisticsPath_."_AllSta.php";
		$YearStaFileName=_StatisticsPath_."_".$DateClass[0]."Sta.php";
		$MonthStaFileName=_StatisticsPath_."_".$DateClass[0]."/_".$DateClass[1]."Sta.php";
		$DayStaFileName=_StatisticsPath_."_".$DateClass[0]."/_".$DateClass[1]."/_".$DateClass[2]."Sta.php";

		//检测总统计文件，不存在（类别不对）建立
		CreatFile($AllStaFileName);

		//检测年统计文件，不存在（类别不对）建立
		CreatFile($YearStaFileName);

		//检测月统计文件，不存在（类别不对）建立
		CreatFile($MonthStaFileName);

		//检测日统计文件，不存在（类别不对）建立
		CreatFile($DayStaFileName);

		//建立保护数据
		$AllStaFileHeadInfo="<?php include_once(\"../../_Include/_404.php\");send404(\$_SERVER[\"PHP_SELF\"],\$_SERVER[\"HTTP_HOST\"],\"_AllSta.php\"); ?>┍ΡΤ┑总计┍ΡΤ┑OS┍ΡΤ┑Browser┍ΡΤ┑Robots┍ΡΤ┑";
		$YearStaFileHeadInfo="<?php include_once(\"../../_Include/_404.php\");send404(\$_SERVER[\"PHP_SELF\"],\$_SERVER[\"HTTP_HOST\"],\"_".$DateClass[0]."Sta.php\"); ?>┍ΡΤ┑总计┍ΡΤ┑OS┍ΡΤ┑Browser┍ΡΤ┑Robots┍ΡΤ┑";
		$MonthStaFileHeadInfo="<?php include_once(\"../../../_Include/_404.php\");send404(\$_SERVER[\"PHP_SELF\"],\$_SERVER[\"HTTP_HOST\"],\"_".$DateClass[1]."Sta.php\"); ?>┍ΡΤ┑总计┍ΡΤ┑OS┍ΡΤ┑Browser┍ΡΤ┑Robots┍ΡΤ┑";
		$DayStaFileHeadInfo="<?php include_once(\"../../../../_Include/_404.php\");send404(\$_SERVER[\"PHP_SELF\"],\$_SERVER[\"HTTP_HOST\"],\"_".$DateClass[2]."Sta.php\"); ?>┍ΡΤ┑总计┍ΡΤ┑OS┍ΡΤ┑Browser┍ΡΤ┑Robots┍ΡΤ┑";
		$DayFileHeadInfo="<?php include_once(\"../../../../_Include/_404.php\");send404(\$_SERVER[\"PHP_SELF\"],\$_SERVER[\"HTTP_HOST\"],\"_".$DateClass[2].".php\"); ?>┍ΡΤ┑日期┍ΡΤ┑时间┍ΡΤ┑IP┍ΡΤ┑系统┍ΡΤ┑浏览器┍ΡΤ┑信息头┍ΡΤ┑来源┍ΡΤ┑";

		//初始统计数据
		function InitStaInfo($FileName){
			//设置初始数据 总统计数 系统类别 浏览器类别 蜘蛛类别
			$InitStaInfo[1]="\n"._SysStr_."0"._SysStr_;
			$InitStaInfo[2]="\n"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_;
			$InitStaInfo[3]="\n"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_;
			$InitStaInfo[4]="\n"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_."0"._SysStr_;

			//初始化数据
			FileWrite($FileName,"a",$InitStaInfo[1]);
			FileWrite($FileName,"a",$InitStaInfo[2]);
			FileWrite($FileName,"a",$InitStaInfo[3]);
			FileWrite($FileName,"a",$InitStaInfo[4]);
		}

		//为数据文件增加保护
		if(FileLine($AllStaFileName)<2){
			FileWrite($AllStaFileName,"a",$AllStaFileHeadInfo);
			InitStaInfo($AllStaFileName);
		}
		if(FileLine($YearStaFileName)<2){
			FileWrite($YearStaFileName,"a",$YearStaFileHeadInfo);
			InitStaInfo($YearStaFileName);
		}
		if(FileLine($MonthStaFileName)<2){
			FileWrite($MonthStaFileName,"a",$MonthStaFileHeadInfo);
			InitStaInfo($MonthStaFileName);
		}
		if(FileLine($DayStaFileName)<2){
			FileWrite($DayStaFileName,"a",$DayStaFileHeadInfo);
			InitStaInfo($DayStaFileName);
		}
		if(FileLine($DayFileName)<2){
			FileWrite($DayFileName,"a",$DayFileHeadInfo);
		}

		//更新统计数据
		function ChangeStaInfo($FileList,$OS,$Browser,$Robots){
			//获取文件数
			$MaxLine=sizeof($FileList);

			//更新数据
			for($i=1;$i<=$MaxLine;$i++){
				$FileContent=FileToArray($FileList[$i]);

				$TempStaNum=explode(_SysStr_,$FileContent[1]);
				$TempStaNum[1]+=1;
				$FileContent[1]=implode(_SysStr_,$TempStaNum);

				$TempStaOS=explode(_SysStr_,$FileContent[2]);
				if($OS!="0"){ $TempStaOS[$OS]+=1; }
				$FileContent[2]=implode(_SysStr_,$TempStaOS);

				$TempStaBrowser=explode(_SysStr_,$FileContent[3]);
				if($Browser!="0"){ $TempStaBrowser[$Browser]+=1; }
				$FileContent[3]=implode(_SysStr_,$TempStaBrowser);

				$TempStaRobots=explode(_SysStr_,$FileContent[4]);
				if($Robots!="0"){ $TempStaRobots[$Robots]+=1; }
				$FileContent[4]=implode(_SysStr_,$TempStaRobots);

				$NewStaInfo=$FileContent[0].$FileContent[1].$FileContent[2].$FileContent[3].$FileContent[4];

				FileWrite($FileList[$i],"w",$NewStaInfo);
			}
		}

		//来访者数据
		$ClinetInfo="\n"._SysStr_.$DateInfo._SysStr_.$TimeInfo._SysStr_.$IPInfo._SysStr_.$OS._SysStr_.$Browser._SysStr_.$Agent._SysStr_.$BackPageInfo._SysStr_;

		//访问者信息记录
		$FileContent=FileToArray($DayFileName);
		$FileLines=FileLine($DayFileName);
		$ClinetSave=0;
		for($i=1;$i<=$FileLines;$i++){
			$FileContents=explode(_SysStr_,$FileContent[$i]);
			if($FileContents[3]==$IPInfo or $IPInfo=="127.0.0.1"){
				$ClinetSave=1;
				break;
			}
		}
		if($ClinetSave==0){
			//写入访问者数据
			FileWrite($DayFileName,"a",$ClinetInfo);

			//建立统计文件列表
			$FileList[1]=$AllStaFileName;
			$FileList[2]=$YearStaFileName;
			$FileList[3]=$MonthStaFileName;
			$FileList[4]=$DayStaFileName;

			//更新统计文件数据
			ChangeStaInfo($FileList,$OS,$Browser,$Robots);
		}
	}

	CreatDir(_StatisticsPath_);
	SaveClinetInfo();

	if($_GET["use"]!=""){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta name="description" content="这是由[PHPText.Net 技术站]提供的多用户记数统计系统，数据显示页面！" />
		<meta name="keywords" content="统计" />
		<meta name="author" content="黄创钦,tine2cx@163.com" />
		<meta name="robots" content="all" />
		<meta name="generator" content="textpad" />
		<meta http-equiv="content-type" content="text/html; charset=gb2312" />
		<meta http-equiv="content-language" content="zh-cn" />

		<title>访问统计</title>
	</head>
	<body style="margin:0px;padding:0px;font-size:12px;font-family:宋体,sans-serif;">
<?php
		$userdir="_UserIPSta/_".$_GET["use"]."/";
		if(file_exists($userdir)){
			//获取当前日期和时间
			$DateInfo=GetDateRPC("Y/m/d");
			$TimeInfo=GetDateRPC("H:i:s");

			//截取日期时间
			$DateClass=explode("/",$DateInfo);

			//获取总统计文件
			$AllStaFileName=$userdir."_AllSta.php";
			$YearStaFileName=$userdir."_".$DateClass[0]."Sta.php";
			$MonthStaFileName=$userdir."_".$DateClass[0]."/_".$DateClass[1]."Sta.php";
			$DayStaFileName=$userdir."_".$DateClass[0]."/_".$DateClass[1]."/_".$DateClass[2]."Sta.php";

			//获取昨天日期
			$TempYearNum=GetDateRPC("Y");
			$TempMonthNum=GetDateRPC("m");
			$TempDayNum=GetDateRPC("d");
			if($TempDayNum!=1){
				$TempDayNum-=1;
			}else{
				if($TempMonthNum!=1){
					$TempMonthNum-=1;
				}else{
					$TempYearNum-=1;
					$TempMonthNum=12;
				}
				$TempDayNum=GetDateRPC("t",$TempYearNum."/".$TempMonthNum."/01 0:0:0");
			}
			$DateStr=$TempYearNum."/".$TempMonthNum."/".$TempDayNum;
			$YearNum=GetDateRPC("Y",$DateStr." 0:0:0");
			$MonthNum=GetDateRPC("m",$DateStr." 0:0:0");
			$DayNum=GetDateRPC("d",$DateStr." 0:0:0");

			$BackDayStaFileName=$userdir."_".$YearNum."/_".$MonthNum."/_".$DayNum."Sta.php";

			$FileContent=FileToArray($AllStaFileName);
			echo "<ul style=\"list-style:none;margin:0px;padding:0px;\"><li style=\"color:#fff;background:red;\">全部统计：</li>";

			echo "<li style=\"background:#e0e0e0;\">访问次数:";
			$TempNum=explode(_SysStr_,$FileContent[1]);
			echo $TempNum[1]."</li>";

			$webConf=FileToArray("_Data/_WebConfig.php");
			$OS=explode(_SysStr_,$webConf[5]);
			$Browser=explode(_SysStr_,$webConf[6]);

			$maxi=sizeof($OS)-1;
			$TempNum=explode(_SysStr_,$FileContent[2]);
			echo "<li style=\"float:left\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$OS[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$maxi=sizeof($Browser)-1;
			$TempNum=explode(_SysStr_,$FileContent[3]);
			echo "<li style=\"background:#e0e0e0;\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$Browser[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$TempNum=explode(_SysStr_,$FileContent[4]);
			echo "<li style=\"background:#e0e0e0;\">Google:<font color=red><b>".$TempNum[1]."</b></font>　　";
			echo "Yahoo:<font color=red><b>".$TempNum[2]."</b></font>　　";
			echo "BaiDu:<font color=red><b>".$TempNum[3]."</b></font>　　";
			echo "IASK:<font color=red><b>".$TempNum[4]."</b></font></li>";

			echo "<li style=\"background:#e0e0e0;\">XHTML验证:<font color=red><b>".$TempNum[5]."</b></font></li>";
			echo "<li style=\"background:#e0e0e0;\">CSS验证:<font color=red><b>".$TempNum[6]."</b></font></li>";
			echo "</ul>";
	//////////////////////////////////////////////////////////////////////////////////
			$FileContent=FileToArray($YearStaFileName);
			echo "<ul style=\"list-style:none;margin:0px;padding:0px;\"><li style=\"color:#fff;background:red;\">今年统计：</li>";

			echo "<li style=\"background:#e0e0e0;\">访问次数:";
			$TempNum=explode(_SysStr_,$FileContent[1]);
			echo $TempNum[1]."</li>";

			$webConf=FileToArray("_Data/_WebConfig.php");
			$OS=explode(_SysStr_,$webConf[5]);
			$Browser=explode(_SysStr_,$webConf[6]);

			$maxi=sizeof($OS)-1;
			$TempNum=explode(_SysStr_,$FileContent[2]);
			echo "<li style=\"float:left\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$OS[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$maxi=sizeof($Browser)-1;
			$TempNum=explode(_SysStr_,$FileContent[3]);
			echo "<li style=\"background:#e0e0e0;\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$Browser[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$TempNum=explode(_SysStr_,$FileContent[4]);
			echo "<li style=\"background:#e0e0e0;\">Google:<font color=red><b>".$TempNum[1]."</b></font>　　";
			echo "Yahoo:<font color=red><b>".$TempNum[2]."</b></font>　　";
			echo "BaiDu:<font color=red><b>".$TempNum[3]."</b></font>　　";
			echo "IASK:<font color=red><b>".$TempNum[4]."</b></font></li>";

			echo "<li style=\"background:#e0e0e0;\">XHTML验证:<font color=red><b>".$TempNum[5]."</b></font></li>";
			echo "<li style=\"background:#e0e0e0;\">CSS验证:<font color=red><b>".$TempNum[6]."</b></font></li>";
			echo "</ul>";
	//////////////////////////////////////////////////////////////////////////////////
			$FileContent=FileToArray($MonthStaFileName);
			echo "<ul style=\"list-style:none;margin:0px;padding:0px;\"><li style=\"color:#fff;background:red;\">本月统计：</li>";

			echo "<li style=\"background:#e0e0e0;\">访问次数:";
			$TempNum=explode(_SysStr_,$FileContent[1]);
			echo $TempNum[1]."</li>";

			$webConf=FileToArray("_Data/_WebConfig.php");
			$OS=explode(_SysStr_,$webConf[5]);
			$Browser=explode(_SysStr_,$webConf[6]);

			$maxi=sizeof($OS)-1;
			$TempNum=explode(_SysStr_,$FileContent[2]);
			echo "<li style=\"float:left\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$OS[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$maxi=sizeof($Browser)-1;
			$TempNum=explode(_SysStr_,$FileContent[3]);
			echo "<li style=\"background:#e0e0e0;\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$Browser[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$TempNum=explode(_SysStr_,$FileContent[4]);
			echo "<li style=\"background:#e0e0e0;\">Google:<font color=red><b>".$TempNum[1]."</b></font>　　";
			echo "Yahoo:<font color=red><b>".$TempNum[2]."</b></font>　　";
			echo "BaiDu:<font color=red><b>".$TempNum[3]."</b></font>　　";
			echo "IASK:<font color=red><b>".$TempNum[4]."</b></font></li>";

			echo "<li style=\"background:#e0e0e0;\">XHTML验证:<font color=red><b>".$TempNum[5]."</b></font></li>";
			echo "<li style=\"background:#e0e0e0;\">CSS验证:<font color=red><b>".$TempNum[6]."</b></font></li>";
			echo "</ul>";
	//////////////////////////////////////////////////////////////////////////////////
			$FileContent=FileToArray($DayStaFileName);
			echo "<ul style=\"list-style:none;margin:0px;padding:0px;\"><li style=\"color:#fff;background:red;\">今日统计：</li>";

			echo "<li style=\"background:#e0e0e0;\">访问次数:";
			$TempNum=explode(_SysStr_,$FileContent[1]);
			echo $TempNum[1]."</li>";

			$webConf=FileToArray("_Data/_WebConfig.php");
			$OS=explode(_SysStr_,$webConf[5]);
			$Browser=explode(_SysStr_,$webConf[6]);

			$maxi=sizeof($OS)-1;
			$TempNum=explode(_SysStr_,$FileContent[2]);
			echo "<li style=\"float:left\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$OS[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$maxi=sizeof($Browser)-1;
			$TempNum=explode(_SysStr_,$FileContent[3]);
			echo "<li style=\"background:#e0e0e0;\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$Browser[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$TempNum=explode(_SysStr_,$FileContent[4]);
			echo "<li style=\"background:#e0e0e0;\">Google:<font color=red><b>".$TempNum[1]."</b></font>　　";
			echo "Yahoo:<font color=red><b>".$TempNum[2]."</b></font>　　";
			echo "BaiDu:<font color=red><b>".$TempNum[3]."</b></font>　　";
			echo "IASK:<font color=red><b>".$TempNum[4]."</b></font></li>";

			echo "<li style=\"background:#e0e0e0;\">XHTML验证:<font color=red><b>".$TempNum[5]."</b></font></li>";
			echo "<li style=\"background:#e0e0e0;\">CSS验证:<font color=red><b>".$TempNum[6]."</b></font></li>";
			echo "</ul>";
	//////////////////////////////////////////////////////////////////////////////////
			$FileContent=FileToArray($BackDayStaFileName);
			echo "<ul style=\"list-style:none;margin:0px;padding:0px;\"><li style=\"color:#fff;background:red;\">昨日统计：</li>";

			echo "<li style=\"background:#e0e0e0;\">访问次数:";
			$TempNum=explode(_SysStr_,$FileContent[1]);
			echo $TempNum[1]."</li>";

			$webConf=FileToArray("_Data/_WebConfig.php");
			$OS=explode(_SysStr_,$webConf[5]);
			$Browser=explode(_SysStr_,$webConf[6]);

			$maxi=sizeof($OS)-1;
			$TempNum=explode(_SysStr_,$FileContent[2]);
			echo "<li style=\"float:left\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$OS[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$maxi=sizeof($Browser)-1;
			$TempNum=explode(_SysStr_,$FileContent[3]);
			echo "<li style=\"background:#e0e0e0;\"><table cellpadding=\"0\" cellspacing=\"0\">";
			for($i=1;$i<$maxi;$i++){
				echo "<tr style=\"background:#e0e0e0;\"><td>".$Browser[$i]."</td><td>　</td><td><font color=red><b>".$TempNum[$i]."</b></font></td><td>　　　</td></tr>";
			}
			echo "</table></li>";

			$TempNum=explode(_SysStr_,$FileContent[4]);
			echo "<li style=\"background:#e0e0e0;\">Google:<font color=red><b>".$TempNum[1]."</b></font>　　";
			echo "Yahoo:<font color=red><b>".$TempNum[2]."</b></font>　　";
			echo "BaiDu:<font color=red><b>".$TempNum[3]."</b></font>　　";
			echo "IASK:<font color=red><b>".$TempNum[4]."</b></font></li>";

			echo "<li style=\"background:#e0e0e0;\">XHTML验证:<font color=red><b>".$TempNum[5]."</b></font></li>";
			echo "<li style=\"background:#e0e0e0;\">CSS验证:<font color=red><b>".$TempNum[6]."</b></font></li>";
			echo "</ul>";
		}else{
			echo "没有该用户统计资料!";
		}
?>
	</body>
</html>
<?php
	}
?>