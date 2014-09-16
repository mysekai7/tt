<?php
	//获取客户端IP
	function GetClinetIP(){
		if(isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) and empty($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])){
			$IP=$HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		}elseif(isset($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) and empty($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])){
			$IP=$HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		}elseif(isset($HTTP_SERVER_VARS["REMOTE_ADDR"]) and empty($HTTP_SERVER_VARS["REMOTE_ADDR"])){
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

	//加密IP地址
	function ENIP($ip,$style="1100"){
		if(!empty($ip) and strstr($ip,".")){
			$ip=explode(".",$ip);
			$style=(string)$style;
			for($i=0;$i<4;$i++){
				if(!$style[$i]){
					$ip[$i]="*";
				}
			}
			$ip=implode(".",$ip);
			return $ip;
		}else{
			return 0;
		}
	}

	//跳转
	function JSUrl($url,$times){
		echo "<script type='text/javascript'>";
		echo "setTimeout(\"location='".$url."'\",".$times.");";
		echo "</script>";
	}

	//获得随机ID
	function GetSID(){
		return md5(uniqid(rand().date("U").((double)microtime()*1000000)));
	}

	//检测邮件地址
	function is_EMail($str){
		return preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $str);
	}

	//检测网页地址
	function is_Url($str){
		return preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $str);
	}

	//检测QQ
	function is_QQ($str){
		return preg_match("/^[1-9]\d{4,8}$/", $str);
	}

	//检测邮编
	function is_Zip($str){
		return preg_match("/^[1-9]\d{5}$/", $str);
	}

	//检测身份证
	function is_IDCard($str){
		return preg_match("/^\d{15}(\d{2}[A-Za-z0-9])?$/", $str);
	}

	//检测中文
	function is_Chinese($str){
		return ereg("^[".chr(0xa1)."-".chr(0xff)."]+$",$str);
	}

	//检测英文
	function is_English($str){
		return preg_match("/^[A-Za-z]+$/", $str);
	}

	//检测手机
	function is_Mobile($str){
		return preg_match("/^((\(\d{3}\))|(\d{3}\-))?13\d{9}$/", $str);
	}

	//检测固话
	function is_Phone($str){
		return preg_match("/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/", $str);
	}

	//检测字符串是否安全
	function is_Safe($str){
		return (preg_match("/^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/", $str) != 0);
	}

    ?>