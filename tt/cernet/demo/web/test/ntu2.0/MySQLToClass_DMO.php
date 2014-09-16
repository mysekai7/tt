<?php
/**
 * mysql数据库转换成php数据对象类
 *
 */
class MySQLToClass_DMO
{
	/**
	 * 服务器地址
	 *
	 * @var string
	 */
	private $ServerName = "localhost";
	/**
	 * 数据库用户名
	 *
	 * @var string
	 */
	private $UserName = "root";
	/**
	 * 数据库密码
	 *
	 * @var string
	 */
	private $UserPass = "";
	/**
	 * 数据库名称
	 *
	 * @var string
	 */
	private $Database;
	/**
	 * 数据库连接对象
	 *
	 * @var resource
	 */
	private $conn=null;
	/**
	 * 数据库表数组
	 *
	 * @var array
	 */
	private $Tables;
	/**
	 * 数据库表字段数组
	 *
	 * @var array
	 */
	private $Fields;
	/**
	 * 数据库表字段字符串
	 *
	 * @var array
	 */
	private $aField;
	/**
	 * 文件注册模版
	 *
	 * @var unknown_type
	 */
	private $Comment = array(
						"File"=>"/**
* 数据操作类:%Comment%
*
* 定义 %Comment% 的操作，请根据业务需求进行修改。
*
* @author     %ClassAuthor%
* @version    %ClassVersion%
* @package    %ClassModule%
* @access     public
* @copyright  %ClassCopy%
* @link       %ClassLink%
* @create     %ClassDate%
*/",
						"Class"=>"/**
	* 构造函数
	*
	* @access public
	* @param  
	* @return void
	*/",
						"Member"=>"
	/**
	* %Field%
	*
	* @var    %Type%
	* @access private
	*/",	
						"DBMethod"=>"/**
	* %Field%
	* @access public
	* @param  %Type%
	* @return boolean
	*/",
						);
	/**
	 * 类名处理回调函数
	 *
	 * @var string
	 */
	public $ClassNameCallBack = "";

	public $ClassPre = "";
	public $ClassDir = "class";
	public $ClassAuthor = "indraw";
	public $ClassVersion = "1.0";
	public $ClassModule = "NTU NEW1.0";
	public $ClassCopy = "商业软件,受著作权保护";
	public $ClassLink = "http://***";
	public $ClassDate = "2007/04/09";
	public $ClassList = array();

	/**
	 * 构造函数
	 *
	 * @param string $server
	 * @param string $user
	 * @param string $pass
	 * @param string $database
	 * @return void
	 */
	function MySQLToClass_DMO($server,$user,$pass,$database,$classinfo)
	{
		$this->ServerName = $server;
		$this->UserName = $user;
		$this->UserPass = $pass;
		$this->Database = $database;

		if($classinfo['class_pre'])
			$this->ClassPre = $classinfo['class_pre'];
		if($classinfo['class_dir'])
			$this->ClassDir = $classinfo['class_dir'];
		if($classinfo['class_author'])
			$this->ClassAuthor = $classinfo['class_author'];
		if($classinfo['class_version'])
			$this->ClassVersion = $classinfo['class_version'];
		if($classinfo['class_module'])
			$this->ClassModule = $classinfo['class_module'];
		if($classinfo['class_copy'])
			$this->ClassCopy = $classinfo['class_copy'];
		if($classinfo['class_link'])
			$this->ClassLink = $classinfo['class_link'];
		if($classinfo['class_date'])
			$this->ClassDate = $classinfo['class_date'];
	}
	/**
	 * 连接数据库
	 * @return void
	 */
	private function connect()
	{
		try 
		{
			$this->conn = mysql_connect($this->ServerName,$this->UserName,$this->UserPass);
			mysql_select_db($this->Database);
		}
		catch (Exception $oE)
		{
			die($oE->getMessage());
		}
	}
	/**
	 * 获得所有表名
	 * @return void
	 */
	private function FetchTables()
	{
		try
		{
			$sql = "SHOW  TABLE STATUS FROM $this->Database";
			$rs = mysql_query($sql,$this->conn);
			
			while ($buffer = mysql_fetch_array($rs,MYSQL_NUM)) {
				$this->Tables[$buffer['0']] = $buffer;
			}
		}
		catch (Exception $oE)
		{
			die($oE->getMessage());
		}
	}
	/**
	 * 获得所有字段
	 * @return void
	 */
	private function FetchFields()
	{
		foreach ((array)$this->Tables as $table) 
		{
			$sql = "SHOW FULL COLUMNS FROM ".$table['0'];
			$rs = mysql_query($sql,$this->conn);
			while ($buffer = mysql_fetch_array($rs,MYSQL_ASSOC)) {
				$this->Fields[$table['0']][] = $buffer;
			}
		}
	}
	/**
	 * 将第一个字母转换成大写
	 *
	 * @param string $string
	 * @return string
	 */
	private function UcFirst($string,$isTable="0")
	{
		$string = explode("_",$string);
		for ($i=0;$i<count($string);$i++)
		{
			$string[$i] = ucfirst($string[$i]);
		}
		
		if($isTable)
		{
			
			if($this->ClassPre)
			{
				$this->ClassMark = $this->ClassPre;
				return "DMO_".$this->ClassPre."_".$string[1];
			}
			else
			{
				$this->ClassMark = UcFirst($string[0]);
				//return "DTO_".UcFirst($string[0])."_".$string[1];
				return "DMO_".implode("_", $string);

			}
			
		}
		else
		{
			return implode("_", $string);
		}
	}
	/**
	 * 注释模版替换
	 *
	 * @param string $string
	 * @param array $replace
	 * @return string
	 */
	private function Replace($string,$replace)
	{
		$search = array("/%Table%/","/%Field%/","/%Type%/","/%Date%/","/%Comment%/",
		"/%ClassAuthor%/","/%ClassVersion%/","/%ClassModule%/","/%ClassCopy%/","/%ClassLink%/","/%ClassDate%/");
		//$replace[] = $this->ClassPre;
		//$replace[] = $this->ClassDir;
		$replace[] = $this->ClassAuthor;
		$replace[] = $this->ClassVersion;
		$replace[] = $this->ClassModule;
		$replace[] = $this->ClassCopy;
		$replace[] = $this->ClassLink;
		$replace[] = $this->ClassDate;
		return preg_replace($search,$replace,$string);
	}
	/**
	 * 执行程序
	 *
	 * @param sting $path
	 */
	public function Run($path="")
	{
		if(!$this->conn)
			$this->connect();
		$this->FetchTables();
		$this->FetchFields();
		
		$path = $this->ClassDir."/";
		if(!is_readable($path))
		{
			mkdir($path);
			chmod($path,0664);
		}
		//开始
		foreach ((array)$this->Fields as $table=>$fields)
		{
			$_table = $table;
			if($this->ClassNameCallBack!="")
				$table = call_user_func_array($this->ClassNameCallBack,$table);

			$table = $this->UcFirst($table);
			
			//Class文件名
			$ClassName = $this->UcFirst($table,1);
			$ClassTable = strtolower( $table );
			//$ClassComment = $this->Tables[$ClassTable]['17'];
			$ClassComment = explode(";",$this->Tables[$ClassTable]['17']);

			$ClassDTO = eregi_replace("_DMO_","DTO_",$ClassName);
			$ClassObject = "o".substr($ClassDTO, strripos($ClassDTO,'_')+1);

			$replace = array($table,"","",date("Y/m/d"),$ClassComment[0]);
			$commentFile = $this->Replace($this->Comment["File"],$replace);

			$fileContent = "<?php\r\n";
			$fileContent .= $commentFile."\r\n\r\n";
			
			$fileContent .= "class $ClassName extends InitDMO\r\n";
			$fileContent .= "{\r\n";
			
			//方法
			foreach ((array)$fields as $field) {
				$_filed = $field["Field"];
				$this->aField[] = $_filed;
			}
			
			//构造函数
			$replace = array($table,"","",date("Y/m/d"));
			$commentClass = $this->Replace($this->Comment["Class"],$replace);
			$fileContent .= "\t".$commentClass."\r\n";
			$fileContent .= "\tfunction __construct()\r\n\t{\r\n\t\tparent::__construct();\r\n";

			$fileContent .= "\r\n\t\t\$this->_table   = \"$_table\";";
			$fileContent .= "\r\n\t\t\$this->_key   = \"{$this->aField[0]}\";";
			$fileContent .= "\r\n\t\t\$this->field = array('".join("','",$this->aField)."');";

			$fileContent .= "\r\n\t}\r\n\r\n";

			//基本数据库操作
			$FieldAll = $this->aField;
			$temp     = $this->aField;
			$FieldStrAll = implode(",",$this->aField);
			$FirstFiled = array_shift($temp);
			$FieldStrNoneFirst = implode(",",$temp);

			$fileContent .= "}//End Class\r\n";
			$fileContent .= "?>";

			//写入文件
			/*
			$path = $this->ClassDir."/dev/";
			if(!is_readable($path))
			{
				mkdir($path);
				chmod($path,755);
			}
			*/
			$path = $this->ClassDir."/".$this->ClassMark."/";
			if(!is_readable($path))
			{
				mkdir($path);
				chmod($path,755);
			}

			$handle = fopen($path.$ClassName.".class.php","w");
			fwrite($handle,$fileContent);
			fclose($handle);
			$this->aField = array();
			$fileContent  = "";

			$this->ClassList[] = $ClassName.".class.php";

		}
	}
}

//类名回调函数<移除前缀>
/*
function removeWapPrefix($string)
{
	return preg_replace("/^wap3?_/i","",$string);
}
*/
?>