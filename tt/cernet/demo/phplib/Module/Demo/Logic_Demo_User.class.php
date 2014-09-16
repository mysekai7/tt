<?PHP
/**
* 逻辑类:用户操作核心逻辑
*
* 定义用户的基本操作
*
* @author     indraw<indraw@163.com>
* @version    1.0
* @package    Authentic Center
* @access     public
* @copyright  商业软件,受著作权保护
* @link       http://***
* @create     2007/2/9 下午
*/

/*
	createUser() 注册用户
	loginUser()  用户登陆
	listUser()   用户列表
	ModifyUser() 修改用户
*/

class Logic_Demo_User extends InitLogic
{

	/**
	* 构造函数
	*
	* @access public
	* @param  void
	* @return void
	*/
	public function __construct()
	{
		parent::__construct(get_class());
	}

	/**
	* 生成一个会员
	*
	* @access public
	* @param  object $oUser 用户传输对象
	* @return boolean
	*/
	public function createUser(DTO_Demo_User $oUser)
	{
		//可以采用默认
		//$oUser->regtime = getTimeFull();

		//判断是否有重复用户
		if($this->getBy($oUser,"username"))
		{
			throw new MyException("用户{$oUser->username}已经存在",801001);
		}
		//将数据写入数据库
		if(!$this->add($oUser))
		{
			throw new MyException("用户{$oUser->username}注册失败",801002);
		}

		return true;
	}

	/**
	* 会员登陆
	*
	* @access public
	* @param  string $sUserName 用户名
	* @param  string $sPasswd 用户密码
	* @return boolean
	*/
	public function loginUser($sUserName,$sPasswd)
	{
		$oUser = new DTO_Demo_User;
		$oUser->username = $sUserName;
		//判断是否有用户
		//_dump($oUser);

		if(!$this->getBy($oUser,"username"))
		{
			throw new MyException("用户{$oUser->username}不存在",801003);
		}
		if($oUser->passwd != $sPasswd)
		{
			throw new MyException("用户{$oUser->username}密码不正确",801004);
		}

		return $oUser;
	}

	/**
	* 分页获取会员列表
	*
	* @access public
	* @param  object $oPage 分页对象
	* @return boolean
	*/
	public function listUser($oPage)
	{
		$oDmoUser = new DMO_Demo_User;
		$aUser = $oDmoUser->gets($oPage->getBegin(),$oPage->PerPage);
		$oPage->Count = $oDmoUser->getRecordAll();
		return $aUser;
	}

	/**
	* 获取会员列表
	*
	* @access public
	* @param  object $oPage 分页对象
	* @return boolean
	*/
	public function topUser($iNumber=100)
	{
		$oDmoUser = new DMO_Demo_User;
		//可以在逻辑中精确获取字段
		$oDmoUser->setField(array("id","username","regtime"));

		//执行查询操作
		$aUser = $oDmoUser->getsAll($iNumber);

		return $aUser;
	}

	/**
	* 修改用户信息
	*
	* @access public
	* @param  object $oUser 用户对象
	* @return boolean
	*/
	public function modifyUser($oUser)
	{
		//判断是否有数据
		$oOldUser = new DTO_Demo_User;
		$oOldUser->username = $oUser->username;
		if(!$this->getBy($oOldUser,"username"))
		{
			throw new MyException("用户{$oUser->username}不存在",811001);
		}

		//将数据写入数据库
		$oOldUser->passwd = $oUser->passwd;
		$oOldUser->email = $oUser->email;
		//$oOldUser->id = $oUser->id;

		if(!$this->modify($oOldUser))
		{
			throw new MyException("用户{$oUser->Username}修改失败",811002);
		}
		return true;
	}

}
//end class
?>