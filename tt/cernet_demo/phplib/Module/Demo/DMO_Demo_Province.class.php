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

class DMO_Demo_Province extends InitDMO
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

		$this->_table   = "cf_province";
		$this->_key   = "provinceid";
		$this->field = array("provinceid","province","iscity","mark");

	}

	public function getsAll()
	{
		$sSelect  = "SELECT {$this->getField()} FROM {$this->_table}";
		$oUser = $this->_db->get_results( $sSelect );
		if( $this->_db->num_rows < 1)
		{
			return false;
		}
		return $oUser;
	}


}//End Class
?>