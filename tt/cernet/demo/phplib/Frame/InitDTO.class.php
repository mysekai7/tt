<?PHP
/**
* 系统结构核心数据传输类
*
* 定义核心方法;
* 统一的派生方法:包括数据传输类初始化和常用操作
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     public
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

class InitDTO extends InitCommon
{
	/**
	* 类名称
	*
	* @var    string
	* @access public
	*/
	public $childClass = "InitDTO";

	/**
	* 构造函数
	*
	* @access public
	* @param  void
	* @return void
	*/
	public function __construct($aDTO,$childClass)
	{
		$this->childClass = $childClass;
		$this->_setCheck();
		if (is_array($aDTO))
		{
			foreach ($aDTO as $key => $value)
			{
				$this->__set($key,$value);
			}
		}
	}

	/**
	* 属性重载
	*
	* @access public
	* @param  string $property 属性
	* @param  string $value 属性值
	* @return void
	*/
	public function __set($property, $value)
	{
		//if(property_exists($property))
		$method = "set".ucfirst($property);
		if(method_exists($this->childClass, $method))
		{
			$this->$method($value);
		}
		else
		{
			//throw new MyException("类方法($method)不存在",10100);
			return false;
		}
	}

	/**
	* 属性重载
	*
	* @access public
	* @param  string $property 属性
	* @return void
	*/
	public function __get($property)
	{
		//if(property_exists($this,$property))
		$method = "get".ucfirst($property);
		if(method_exists($this->childClass, $method))
		{
			return $this->$method();
		}
		else
		{
			//throw new MyException("类方法($method)不存在",10100);
			return false;
		}
	}

	/**
	* 通用函数导入
	*
	* @access private
	* @param  void
	* @return void
	*/
	private function _setCheck()
	{
		$this->loadFunc("funcCheck");
	}

}
//-------------------------------------------------------------------
//end class
?>