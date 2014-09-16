<?PHP
/**
* 系统结构核心数据操作类类
*
* 定义核心方法;
* 统一的派生方法:包括数据操作类初始化和常用操作
*
* @author     indraw<indraw@163.com>
* @version    2.0
* @package    PHPSea Framework
* @access     public
* @copyright  商业软件,受著作权保护
* @link       http://***
*/

abstract class InitDMO extends InitCommon
{
	/**
	* 数据库操作对象
	*
	* @var    object
	* @access protected
	*/
	protected $_db;

	/**
	* 数据表
	*
	* @var    object
	* @access protected
	*/
	protected $_table;

	/**
	* 数据表主键
	*
	* @var    object
	* @access protected
	*/
	protected $_key;

	/**
	* 字段属性
	*
	* @var    array
	* @access private
	*/
	public $field;

	/**
	* 构造函数
	*
	* @access public
	* @param  void
	* @return void
	*/
	function __construct()
	{
		$this->_setMySQL();
	}

	/**
	* 
	*
	* @access public
	* @param  void
	* @return void
	*/
	function setField($field)
	{
		$this->field = $field;
	}

	/**
	* 
	*
	* @access public
	* @param  void
	* @return void
	*/
	function getField()
	{
		return "`".join("`,`",$this->field)."`";
	}

	/**
	* 数据库连接
	*
	* @access public
	* @param  void
	* @return void
	*/
	private function _setMySQL()
	{
		global $oMySQL;
		$this->_db = $oMySQL;
	}

	/**
	* 数据对象复制，将对象2的属性复制给对象1
	*
	* @access protected
	* @param  object $obj1
	* @param  object $obj2
	* @return void
	*/
	public function copyDto($obj1,$obj2)
	{
		$aVars = get_object_vars($obj2);
		foreach($aVars as $key=>$value)
		{
			if($value)
			{
				$obj1->$key = $obj2->$key;
			}
		}
	}

	/**
	* 获取总条数
	*
	* @access public 
	* @param  void
	* @return void
	*/
	public function getRecordAll()
	{
		return $this->_db->num_rows_all;
	}


	/**
	 * get last insert_id
	 * @param none
	 * @return int $insert_id
	 */
	public function getInsertId()
	{
		return $this->_db->insert_id;
	}

	/**
	* 插入一条数据
	* 
	* @access public
	* @param  object
	* @return boolean
	*/
	public function add($oDTO)
	{
		foreach($this->field as $field)
		{
			$method = "get".ucfirst($field);
			$aField[] = "'".$oDTO->$method()."'";
		}
		$sInsert  = "INSERT INTO {$this->_table} ({$this->getField()}) VALUES (".join(",",$aField).")";
		//判断是否执行成功
		if( !$this->_db->query( $sInsert ) )
		{
			return false;
		}
		return true;
	}

	/**
	* 获取一条记录
	* 
	* @access public
	* @param  object
	* @param  string $sKey
	* @return boolean
	*/
	public function get($oDTO,$sKey='')
	{
		if(!$sKey)
		{
			$sSelect  = "SELECT {$this->getField()} FROM {$this->_table} WHERE `{$this->_key}` = '";
		}
		else
		{
			$sSelect  = "SELECT $sKey FROM {$this->_table} WHERE `{$this->_key}` = '";
		}
		$method = "get".ucfirst($this->_key);
		$sSelect .= $oDTO->$method();
		$sSelect .= "' ";
		//echo $sSelect;
		$oResult = $this->_db->get_row( $sSelect );
		if( $this->_db->num_rows < 1)
		{
			return false;
		}
		$this->copyDto($oDTO,$oResult);
		return true;
	}

	/**
	* 获取一条记录
	* 
	* @access public
	* @param  object 
	* @param  string $sKey
	* @return boolean
	*/
	public function getBy($oDTO,$sKey='')
	{
		$sSelect  = "SELECT {$this->getField()} FROM {$this->_table} WHERE $sKey = '";
		$sSelect .= $oDTO->$sKey;
		$sSelect .= "' ";
		$oResult = $this->_db->get_row( $sSelect );
		if( $this->_db->num_rows < 1)
		{
			return false;
		}
		$this->copyDto($oDTO,$oResult);
		return true;
	}

	/**
	* 获取多条记录
	*
	* @access public
	* @param  int $iStart 开始行数,
	* @param  int $iNumber 记录数,
	* @param  string $sOrder 主键排序方式
	* @return boolean
	*/
	public function gets($iStart=0,$iNumber=10,$sOrder='ASC',$aKey='')
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
		$sSelect .=  " $sWhere ORDER BY {$this->_key} $sOrder LIMIT $iStart,$iNumber ";
		$oUser = $this->_db->get_page_results( $sSelect );
		if( $this->_db->num_rows < 1)
		{
			return false;
		}
		return $oUser;
	}

	/**
	* 获取多条记录
	* <code>
	* $oLogic = new Logic;
	* $oLogic->getsAll(0,20,"DESC",'');
	* </code>
	* @access public
	* @param  int $iNumber 记录数,
	* @param  string $sOrder 主键排序方式
	* @return boolean
	*/
	public function getsAll($iNumber=10,$sOrder='ASC',$aKey='')
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
		$sSelect .=  " $sWhere ORDER BY {$this->_key} $sOrder LIMIT $iNumber ";
		$oUser = $this->_db->get_results( $sSelect );
		if( $this->_db->num_rows < 1)
		{
			return false;
		}
		return $oUser;
	}

	/**
	* 修改一条记录
	* 
	* @access public
	* @param   DTO_Demo_User 
	* @param  string $sKey
	* @return boolean
	*/
	public function modify($oDTO,$sKey='')
	{
		$method = "get".ucfirst($this->_key);
		if(!$sKey)
		{
			foreach($this->field as $field)
			{
				if($field != $this->_key)
				{
					$methodTmp = "get".ucfirst($field);
					$aField[] = " `$field`= '".$oDTO->$methodTmp()."' ";
				}
			}
			$sUpdate  = "UPDATE {$this->_table} SET ".join(",",$aField);
			$sUpdate .= " WHERE `$this->_key` = '{$oDTO->$method()}' ";
		}
		else
		{
			$sUpdate  = "UPDATE {$this->getField()} SET `$sKey` = '".$oDTO->$sKey;
			$sUpdate .= "' WHERE $this->_key = '{$oDTO->$method()}'";
		}
		if($this->_db->query( $sUpdate ) === false)
		{
			return false;
		}
		if($this->_db->rows_affected === 0)
		{
			return true;
		}
		return true;
	}

	/**
	* 删除一条记录
	* 
	* @access public
	* @param  DTO_Demo_User 
	* @param  string $sKey
	* @return boolean
	*/
	public function remove($oDTO,$sKey='')
	{
		$method = "get".ucfirst($this->_key);
		if(!$sKey)
		{
			$sDelete  = "DELETE FROM {$this->_table} WHERE {$this->_key} = '";
			$sDelete .= $oDTO->$method();
			$sDelete .= "'";
		}
		else
		{
			$sDelete  = "DELETE FROM {$this->_table} WHERE `$sKey` = '";
			$sDelete .= $oDTO->$sKey;
			$sDelete .= "'";
		}
		if($this->_db->query( $sDelete ) === false)
		{
			return false;
		}
		if($this->_db->rows_affected === 0)
		{
			return true;
		}
		return true;
	}


}
//-------------------------------------------------------------------
//end class
?>