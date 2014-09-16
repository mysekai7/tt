<?PHP
/**
* 系统结构核心逻辑类
*
* 定义核心方法;
* 统一的派生方法:包括逻辑类初始化和常用操作
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     public
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

class InitLogic extends InitCommon
{
	/**
	* 类名称
	*
	* @var    string
	* @access public
	*/
	public $childClass = "InitLogic";

	/**
	* 构造函数
	*
	* @access public
	* @param  void
	* @return void
	*/
	function __construct($childClass)
	{
		$this->childClass = $childClass;

	}

	/**
	* 方法重载函数
	*
	* @access public
	* @param  string $function
	* @param  string $args
	* @return void
	*/
	function __call($func, $args)
	{
		//获取类名
		$sDtoClass = get_class($args[0]);
		$sDmoClass = eregi_replace("DTO","DMO",$sDtoClass);
		//echo $sDmoClass."::<br>";
		/*
		if(substr($sDmoClass, 0,3) != substr($this->childClass, 0,3))
		{
			//echo "没有操作权限";
			return false;
		}
		*/
		if(!in_array($func,get_class_methods($sDmoClass)))
		{
			//echo "类方法不存在";
			return false;
		}
		//include_once(CLASS_DIR."/Module/src/_".$sClassName.".class.php");
		//include_once(CLASS_DIR."/Module/dev/".$sClassName.".class.php");
		$oDMOClass = new $sDmoClass;
		if($args[1])
			return $oDMOClass->$func($args[0],$args[1]);
		else
			return $oDMOClass->$func($args[0]);
	}

}
//-------------------------------------------------------------------
//end class
?>