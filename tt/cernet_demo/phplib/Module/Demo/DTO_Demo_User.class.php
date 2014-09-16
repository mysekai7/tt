<?php
/**
* 数据传输类:框架测试表
*
* 定义 框架测试表
*
* @author     indraw
* @version    1.0
* @package    NTU NEW1.0
* @access     public
* @copyright  商业软件,受著作权保护
* @link       http://***
* @create     2007/04/09
*/

class DTO_Demo_User extends InitDTO
{

	/**
	* 自增ID
	*
	* @var    int(11)
	* @access private
	*/
	private $_id;

	/**
	* 用户名
	*
	* @var    varchar(20)
	* @access private
	*/
	private $_username;

	/**
	* 登陆密码
	*
	* @var    varchar(20)
	* @access private
	*/
	private $_passwd;

	/**
	* 用户邮箱
	*
	* @var    varchar(60)
	* @access private
	*/
	private $_email;

	/**
	* 注册时间
	*
	* @var    datetime
	* @access private
	*/
	private $_regtime;
	/**
	* 构造函数
	*
	* @access public
	* @param  
	* @return void
	*/
	function __construct($aUser=null)
	{
		parent::__construct($aUser,get_class());

	}

	/**
	* 设置 自增ID
	*
	* @access public
	* @param  int(11)
	* @return void
	*/
	public function setId($id)
	{
		//检测数据合法性
		/**
		if(!isNumber($id))
		{
			throw new MyException("自增ID 设置错误",101001);
		}
		*/
		$this->_id=$id;
	}

	/**
	* 获取 自增ID
	*
	* @access public
	* @return int(11)
	*/
	public function getId()
	{
		
		//判断数据是否被设置
		/**
		if(!isset($this->_id))
		{
			throw new MyException("自增ID 设置错误",101002);
		}
		*/
		return $this->_id;
	}

	/**
	* 设置 用户名
	*
	* @access public
	* @param  varchar(20)
	* @return void
	*/
	public function setUsername($username)
	{
		//检测数据合法性
		if(!isLength($username,3,20))
		{
			throw new MyException("用户名 必须为3-20个字符",101003);
		}
		$this->_username=$username;
	}

	/**
	* 获取 用户名
	*
	* @access public
	* @return varchar(20)
	*/
	public function getUsername()
	{
		
		//判断数据是否被设置
		if(!isset($this->_username))
		{
			throw new MyException("用户名 设置错误",101004);
		}
		return $this->_username;
	}

	/**
	* 设置 登陆密码
	*
	* @access public
	* @param  varchar(20)
	* @return void
	*/
	public function setPasswd($passwd)
	{
		//检测数据合法性
		if(!isLength($passwd,3,20))
		{
			throw new MyException("登陆密码 必须为3-20个字符",101005);
		}
		$this->_passwd=$passwd;
	}

	/**
	* 获取 登陆密码
	*
	* @access public
	* @return varchar(20)
	*/
	public function getPasswd()
	{
		
		//判断数据是否被设置
		if(!isset($this->_passwd))
		{
			throw new MyException("登陆密码 设置错误",101006);
		}
		return $this->_passwd;
	}

	/**
	* 设置 用户邮箱
	*
	* @access public
	* @param  varchar(60)
	* @return void
	*/
	public function setEmail($email)
	{
		//检测数据合法性
		if(!isEmail($email))
		{
			throw new MyException("用户邮箱 格式为：indraw@163.com",101007);
		}
		$this->_email=$email;
	}

	/**
	* 获取 用户邮箱
	*
	* @access public
	* @return varchar(60)
	*/
	public function getEmail()
	{
		
		//判断数据是否被设置
		if(!isset($this->_email))
		{
			throw new MyException("用户邮箱 设置错误",101008);
		}
		return $this->_email;
	}

	/**
	* 设置 注册时间
	*
	* @access public
	* @param  datetime
	* @return void
	*/
	public function setRegtime($regtime)
	{
		//检测数据合法性
		/**
		if(!isTime($regtime))
		{
			throw new MyException("注册时间 设置错误",101009);
		}
		*/
		$this->_regtime=$regtime;
	}

	/**
	* 获取 注册时间
	*
	* @access public
	* @return datetime
	*/
	public function getRegtime()
	{
		
		//判断数据是否被设置
		/**
		if(!isset($this->_regtime))
		{
			throw new MyException("注册时间 设置错误",101010);
		}
		*/
		return $this->_regtime;
	}

}//End Class
?>