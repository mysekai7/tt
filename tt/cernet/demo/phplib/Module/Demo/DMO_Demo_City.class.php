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

class DMO_Demo_City extends InitDMO
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

		$this->_table   = "cf_city";
		$this->_key   = "cityid";
		$this->field = array("cityid","provinceid","city","isend","mark");

	}

	public function getsAll($aKey,$sOrder="ASC")
	{
		$sSelect  = "SELECT {$this->getField()} FROM {$this->_table} ";
		if(is_array($aKey))
		{
			foreach($aKey as $key=>$value)
			{
				$aWhere[] = " $key='".$value."' ";
			}
			$sWhere = " WHERE ".join(" AND ",$aWhere);
		}
		$sSelect .=  " $sWhere ORDER BY {$this->_key} $sOrder ";
		$oUser = $this->_db->get_results( $sSelect );
		if( $this->_db->num_rows < 1)
		{
			return false;
		}
		return $oUser;
	}


}//End Class
?>