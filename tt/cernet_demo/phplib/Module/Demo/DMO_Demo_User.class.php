<?php
/**
* 数据操作类:框架测试表
*
* 定义 框架测试表 的操作
*
* @author     indraw
* @version    1.0
* @package    NTU NEW1.0
* @access     public
* @copyright  商业软件,受著作权保护
* @link       http://***
* @create     2007/04/09
*/

class DMO_Demo_User extends InitDMO
{
	/**
	* 构造函数
	*
	* @access public
	* @param  
	* @return void
	*/
	function __construct()
	{
		parent::__construct();

		$this->_table   = "demo_user";
		$this->_key   = "id";
		$this->field = array("id","username","passwd","email","regtime");

	}



}//End Class
?>