<?PHP
/**
* 系统结构核心父类
*
* 定义核心方法;
* 统一的派生方法:包括通用类库引入以及通用函数库引入
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     public
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

class InitCommon
{
	/**
	* 构造函数
	*
	* @access public
	* @param  void
	* @return void
	*/
	function __construct()
	{
	}

	/**
	* 析构函数
	*
	* @access public
	* @param  void
	* @return void
	*/
	function __destruct()
	{
	}

	/**
	* 类拷贝
	*
	* @access public
	* @param  void
	* @return void
	*/
	function __clone()
	{
	}

	/**
	* 通用类导入
	*
	* @access public
	* @param  string $sClassName 类名称
	* @return void
	*/
	public function loadClass($sClassName)
	{
		include_once(CLASS_DIR."/Frame/src/".$sClassName.".class.php");

	}

	/**
	* 通用函数导入
	*
	* @access public
	* @param  string $sFuncName 函数名称
	* @return void
	*/
	public function loadFunc($sFuncName)
	{
		include_once(CLASS_DIR."/Frame/src/".$sFuncName.".inc.php");

	}
}

	/**
	* 自动加载函数 系统会自动根据类名进行逻辑函数的加载
	*
	* @access public
	* @param  void
	* @return void
	*/
	function __autoload($sClassName)
	{
		$aClassList = explode("_",$sClassName);

		//echo $sClassName."<br>";
		/*
		if(!@get_class() and $aClassName[1]=='DMO')
		{
			echo "class error $sClassName [".get_class()."]";
			return false;
		}
		*/
		include_once(CLASS_DIR."/Module/{$aClassList[1]}/".$sClassName.".class.php");

	}

	/**
	* 打印调式信息
	*
	* @access public
	* @param  mixed $sPara
	* @return void 
	*/
	function _dump($sPara)
	{
		echo "<pre>";
		var_dump($sPara);
		echo "</pre>";
		echo "<hr noshade color=dddddd size=1>";

	}

//-------------------------------------------------------------------
//end class
?>