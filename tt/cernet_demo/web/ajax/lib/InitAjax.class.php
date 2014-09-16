<?PHP
/**
* 逻辑：ajax父类
*
* 
*
* @author      indraw<indraw@163.com>
* @version     1.0
* @package     PHPSea Ajax
* @access      public
* @copyright   商业软件,受著作权保护
* @link        http://*
*/

//类说明

//-------------------------------------------------------------------
//类代码
class InitAjax extends InitCommon
{

	/*
	-----------------------------------------------------------
	函数名称:InitAjax()
	简要描述:构造函数
	输入:void
	输出:void
	修改日志:---
	-----------------------------------------------------------
	*/
	function __construct()
	{
		$this->error = "";
		$this->success = "";
	}

	/**
	* 初始化mysql数据库操作类
	*
	* @access public
	* @param  void
	* @return void
	*/
	function setMySQL()
	{
		global $oMySQL,$DBSet;
		InitCommon::loadClass("DBMySQL");
		$oMySQL = new DBMySQL($DBSet['user'],$DBSet['pass'],$DBSet['name'],$DBSet['host']);
		if(!$oMySQL->dbh)
		{
			$this->error("数据库系统忙，请稍候访问");
		}
		$oMySQL->query("SET NAMES 'utf8'");
	}

	/*
	-----------------------------------------------------------
	函数名称:Halt()
	简要描述:重载核心父类的错误输出函数
	输入:mixed
	输出:void
	修改日志:---
	-----------------------------------------------------------
	*/
	function Halt($sMessage="sorry!",$iNumber="1000")
	{
		$sVariable['result'] = false;
		$sVariable['message'] = "[".$iNumber."]".$sMessage;
		//echo iconv("GBK","UTF-8",json_encode($sVariable));
		echo json_encode($sVariable);
		exit;
	}

	/*
	-----------------------------------------------------------
	函数名称:Halt()
	简要描述:重载核心父类的错误输出函数
	输入:mixed
	输出:void
	修改日志:---
	-----------------------------------------------------------
	*/
	function Encode($sMessage)
	{
		$sVariable['result'] = true;
		$sVariable['message'] = $sMessage;
		//echo iconv("GBK","UTF-8",json_encode($sVariable));
		echo json_encode($sVariable);
		exit;
	}

	/*
	-----------------------------------------------------------
	函数名称:Print()
	简要描述:重载核心父类的错误输出函数
	输入:mixed
	输出:void
	修改日志:---
	-----------------------------------------------------------
	*/
	function success($sMessage="ok")
	{
		echo $this->success." ".$sMessage;
		exit;
	}

	/*
	-----------------------------------------------------------
	函数名称:Print()
	简要描述:重载核心父类的错误输出函数
	输入:mixed
	输出:void
	修改日志:---
	-----------------------------------------------------------
	*/
	function error($sMessage="ok")
	{
		if(@get_class($sMessage)=="MyException")
		{
			echo $this->error." ".$sMessage->getMessage();
			exit;
		}
		else
		{
			echo $this->error." ".$sMessage;
			exit;
		}
	}
	/*
	-----------------------------------------------------------
	函数名称:Print()
	简要描述:重载核心父类的错误输出函数
	输入:mixed
	输出:void
	修改日志:---
	-----------------------------------------------------------
	*/
	function Html($sMessage="ok")
	{
		echo $sMessage;
		exit;
	}
	

}
//-------------------------------------------------------------------
//end class
?>