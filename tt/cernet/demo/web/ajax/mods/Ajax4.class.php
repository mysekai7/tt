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
	* 检测email是否可用
	*
	* @access public
	* @param
	* @return boolean
	*/
	function createUser()
	{
		if(!preg_match("/^[a-z0-9-_.]+@[\da-z][\.\w-]+\.[a-z]{2,4}$/i", $_POST['email']))
		{
			$this->error("请输入合法的email地址");
		}
		else
		{
			$this->success("可以使用(".$_POST['email'].")");
		}

	}


}//end class

?>