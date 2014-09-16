<?PHP
/**
* 系统结构初始类
*
* 主要是对初始化信息进行封装，包括数据库连接，session设置，错误处理等
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     private
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

class Application
{
	/**
	* 数据库操作对象
	*
	* @var    boolean
	* @access public
	*/
	public $bDebug    = true;

	/**
	* 数据库操作对象
	*
	* @var    int
	* @access public
	*/
	public $iSessDir  = 0;

	/**
	* 构造函数
	*
	* @access public
	* @param  boolean
	* @return void
	*/
	function __construct($bLink=false)
	{
		$this->setSessDir();
		$this->setSmarty();
		if($bLink)
		{
			$this->setMySQL();
		}
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
			$this->error("系统忙，请稍候访问","/index.php");
		}
		$oMySQL->query("SET NAMES 'utf8'");
	}

	/**
	* 设置信息提示模板
	*
	* @access public
	* @param  void
	* @return void
	*/
	function setMessage($sMgs,$sType="error")
	{
		global $sMessageTpl;
		if( $sType=="error" )
		{
			$sMessageTpl['error'] = $sMgs;
		}
		elseif( $sType=="success" )
		{
			$sMessageTpl['success'] = $sMgs;
		}
		
	}

	/**
	* 初始化Session存储路径
	*
	* @access public
	* @param  void
	* @return void
	*/
	function setSessDir()
	{
		if( $this->iSessDir == 2 )
		{
			global $oMySQL;
			InitCommon::loadClass("Session");
			$oSession = new Session($oMySQL,"MySessionName",30,true,false,100);
		}
		elseif( $this->iSessDir == 1 )
		{
			session_save_path(SITE_DIR."tmp");
		}
		else
		{
			Return true;
		}
	}

	/**
	* 初始化smarty
	*
	* @access public
	* @param  void
	* @return void
	*/
	function setSmarty()
	{
		global $oSmarty;
		include_once(CLASS_DIR."ThirdParty/smarty2.67/Smarty.class.php");
		$oSmarty = new Smarty;
		$oSmarty->template_dir = SITE_DIR."templates";
		$oSmarty->compile_dir = SITE_DIR."templates_c";
		//$oSmarty->config_dir = SITE_DIR."configs";
		//$oSmarty->cache_dir = SITE_DIR."cache";
		//$oSmarty->caching = true;
		//$oSmarty->cache_lifetime = ;
		//$oSmarty->debugging = true;
		//$oSmarty->default_template_handler_func = 'MakeTemplate';
		$oSmarty->assign('app_name', 'PHPSea projuct');
		$oSmarty->left_delimiter = '{*';
		$oSmarty->right_delimiter = '*}';
	}

	/**
	* 成功提示方法
	*
	* @access public
	* @param  object
	* @return void
	*/
	function success($sMessage,$sReturnUrl="")
	{
		//include_once(SITE_DIR."message/MsgSuccess.inc.php");
		//$iNumber = $oException->getCode;
		//$sMessage = $oException->getMessage;
		/*
		if($MsgSuccess[$iNumber])
		{
			$sMessage = $MsgSuccess[$iNumber];
		}
		*/
		$iNumber = "000000";
		msgSuccess($iNumber,$sMessage,$sReturnUrl);
		exit;
	}

	/**
	* 错误处理方法
	*
	* @access public
	* @param  object
	* @return void
	*/
	function error($oException,$sReturnUrl="")
	{
		include_once(SITE_DIR."message/MsgError.inc.php");
		if(@get_class($oException)=="MyException")
		{
			$iNumber = $oException->getCode();
			$sMessage = $oException->getMessage();
			if($MsgError[$iNumber])
			{
				$sMessage = $MsgError[$iNumber];
			}
		}
		else
		{
			$iNumber = "000000";
			$sMessage = $oException;
		}
		msgError($iNumber,$sMessage,$sReturnUrl);
		exit;
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
		InitCommon::loadClass($sClassName);
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
		InitCommon::loadFunc($sFuncName);
	}

	/**
	* 设置核心错误处理
	*
	* @access public
	* @param  void
	* @return void
	*/
	function baseError()
	{
		global $PHPSEA_ERROR;
		//如果需要按照项目需求显示核心类库错误，那么在此显示
		//$PHPSEA_ERROR 为核心类错误数组
		if( $bDebug )
		{
			
		}
	}

}//end class

//-------------------------------------------------------------------
//end
?>
