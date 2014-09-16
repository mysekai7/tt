<?PHP
/**
* 子系统通用函数
*
* 定义子系统可能的通用函数，
* 但一般情况下核心类库已经够用，不需要再开发自己的函数，此文件最好不要超过10k
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     private
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

	/**
	* 成功提示函数
	*
	* @access public
	* @param  void
	* @return void
	*/
	function msgSuccess($iNumber,$sMessage,$sReturnUrl="")
	{
		global $oSmarty,$sMessageTpl;
		if(!$sReturnUrl)
		{
			$sReturnUrl = $_SERVER['HTTP_REFERER'];
		}
		$oSmarty->assign("iNumber",$iNumber);
		$oSmarty->assign("sMessage",$sMessage);
		$oSmarty->assign("sReturnUrl",$sReturnUrl);
		if(!$sMessageTpl['success'])
			$sMessageTpl['success'] = "success.htm";
		$oSmarty->display($sMessageTpl['success']);
		exit;
	}

	/**
	* 错误提示函数
	*
	* @access public
	* @param  void
	* @return void
	*/
	function msgError($iNumber,$sMessage,$sReturnUrl="")
	{
		global $oSmarty,$sMessageTpl;
		if(!$sReturnUrl)
		{
			$sReturnUrl = $_SERVER['HTTP_REFERER'];
		}
		$oSmarty->assign("iNumber",$iNumber);
		$oSmarty->assign("sMessage",$sMessage);
		$oSmarty->assign("sReturnUrl",$sReturnUrl);
		if(!$sMessageTpl['error'])
			$sMessageTpl['error'] = "error.htm";
		$oSmarty->display($sMessageTpl['error']);
		exit;
	}

//---------------------------------------------------------
?>