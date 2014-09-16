<?php
/**
* 逻辑：用户相关操作
*
* 封装常用的ajax用户操作部分
*
* @author      indraw<indraw@163.com>
* @version     1.0
* @package     PHPSea Ajax
* @access      public
* @copyright   商业软件,受著作权保护
* @link        http://*
*/

class Module extends InitAjax
{
	/**
	* 构造函数
	*
	* @author
	* @return void
	*/
	function __construct()
	{
		parent::__construct();
	}

	/**
	* 
	* @access public
	* @param
	* @return boolean
	*/
	function listProvince()
	{
		$oDmoProvince = new DMO_Demo_Province();
		$oDmoProvince->setField(array("provinceid","province"));
		$aProvince = $oDmoProvince->getsAll();
		$aResult = array();
		if($aProvince)
		{
			foreach($aProvince as $val)
			{
				$aResult[] = array($val->provinceid, $val->province);
			}
			$html = strFormat($aResult);
			$this->html($html);
		}
		return "";

	}

	/**
	* 
	* @access public
	* @param
	* @return boolean
	*/
	function listCity()
	{
		if($_GET['id'])
		{
			$oDmoCity = new DMO_Demo_City();
			$oDmoCity->setField(array("cityid","city"));
			$aCity = $oDmoCity->getsAll(array("provinceid"=>$_GET['id']));

			$aResult = array();
			if($aCity)
			{
				foreach($aCity as $val)
				{
					$aResult[] = array($val->cityid, $val->city);
				}
				$html = strFormat($aResult);
				$this->html($html);
			}
		}
		return "";
	}

	/**
	* 
	* @access public
	* @param
	* @return boolean
	*/
	function submitTrade()
	{
		if($_GET)
		{
			foreach($_GET as $key=>$val)
			{
				if(strpos($key,"arg") !== false)
					$html .= $val." ";
			}
			$this->html("What you submit is '".$html."'");
			
		}
		return "";
	}

}//end class

function strFormat($data)
{
	foreach($data as $val)
	{
		$html[] = "{$val[0]}^{$val[1]}";
	}
	return implode("@",$html);
}

?>