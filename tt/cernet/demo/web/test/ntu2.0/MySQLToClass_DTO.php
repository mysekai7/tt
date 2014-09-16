<?php
/**
 * mysql数据库转换成php数据对象类
 *
 */
class MySQLToClass_DTO
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
						"File"=>"
/**
* 数据传输类:%Comment%
*
* 定义 %Comment%
*
* @author     %ClassAuthor%
* @version    %ClassVersion%
* @package    %ClassModule%
* @access     public
* @copyright  %ClassCopy%
* @link       %ClassLink%
* @create     %ClassDate%
*/",
						"Class"=>
	"/**
	* 构造函数
	*
	* @access public
	* @param  
	* @return void
	*/",
						"MethodSet"=>
	"/**
	* 设置 %Comment%
	*
	* @access public
	* @param  %Type%
	* @return void
	*/",
						"MethodGet"=>
	"/**
	* 获取 %Comment%
	*
	* @access public
	* @return %Type%
	*/",
						"Member"=>"
	/**
	* %Comment%
	*
	* @var    %Type%
	* @access private
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

	public $ClassMark = "";

	/**
	 * 构造函数
	 *
	 * @param string $server
	 * @param string $user
	 * @param string $pass
	 * @param string $database
	 * @return void
	 */
	function MySQLToClass_DTO($server,$user,$pass,$database,$classinfo)
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
			mysql_query("SET NAMES 'utf8'",$this->conn);
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
			//$sql = "SHOW TABLES FROM $this->Database";
			$sql = "SHOW TABLE STATUS FROM $this->Database";
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
		//echo "<pre>";
		//var_dump($this->Tables);
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
				return "DTO_".$this->ClassPre."_".$string[1];
			}
			else
			{
				$this->ClassMark = UcFirst($string[0]);
				//return "DTO_".UcFirst($string[0])."_".$string[1];
				return "DTO_".implode("_", $string);

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
		/*
		$this->ClassPre = "";
		$this->ClassDir = "class";
		$this->ClassAuthor = "indraw";
		$this->ClassVersion = "1.0";
		$this->ClassModule = "NTU NEW1.0";
		$this->ClassCopy = "商业软件,受著作权保护";
		$this->ClassLink = "http://***";
		$this->ClassDate = "2007/04/09";
		*/
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
		//echo "<pre>";
		//var_dump($search);
		//var_dump($replace);
		//echo "</pre>";
		return preg_replace($search,$replace,$string);
	}
	private function AddZero($iNumber)
	{
		$iLenght = strlen($iNumber);
		$iAdd = 3 - $iLenght;
		for($i=0;$i<$iAdd;$i++)
		{
			$iNumber = "0".$iNumber;
		}
		return $iNumber;
	}
	/**
	 * 执行程序
	 *
	 * @param sting $path
	 */
	public function Run()
	{
		if(!$this->conn)
			$this->connect();
		$this->FetchTables();
		$this->FetchFields();
		
		$path = $this->ClassDir."/";
		if(!is_readable($path))
		{
			mkdir($path);
			chmod($path,755);
		}
		//开始
		$iNumber1 = "101";
		foreach ((array)$this->Fields as $table=>$fields)
		{
			$_table = $table;
			if($this->ClassNameCallBack!="")
				$table = call_user_func_array($this->ClassNameCallBack,$table);

			$table = $this->UcFirst($table);
			
			//Class文件名
			$ClassName = $this->UcFirst($table,1);
			$ClassTable = strtolower( $table );
			$ClassComment = explode(";",$this->Tables[$ClassTable]['17']);
			//echo "<pre>";
			//var_dump($table);
			$replace = array($ClassName,"","",date("Y/m/d"),$ClassComment[0]);
			$commentFile = $this->Replace($this->Comment["File"],$replace);

			$fileContent = "<?php";
			$fileContent .= $commentFile."\r\n\r\n";
			
			$fileContent .= "class $ClassName extends InitDTO\r\n";
			$fileContent .= "{\r\n";

			//属性
			foreach ((array)$fields as $field)
			{
				//$field["Field"] = strtolower($field["Field"]);

				$_filed = $field["Field"];
				$_Type = $field["Type"];
				$_Comment = $field["Comment"];
				$replace = array($ClassName,$_filed,$_Type,date("Y/m/d"),$_Comment);
				$commentMethod = $this->Replace($this->Comment["Member"],$replace);
				$fileContent .= "$commentMethod\r\n\tprivate \$_".$field["Field"].";\r\n";
			}
			
			//构造函数
			$replace = array($table,"","",date("Y/m/d"));
			$commentClass = $this->Replace($this->Comment["Class"],$replace);
			$fileContent .= "\t".$commentClass."\r\n";
			$fileContent .= "\tfunction __construct(\$aUser=null)\r\n\t{\r\n\t\tparent::__construct(\$aUser,get_class());\r\n";
			//初始化默认值
			foreach ((array)$fields as $field) {
				//$fileContent .= "\t\t\$this->".$field["Field"]." = \"\";\n";
			}
			$fileContent .= "\r\n\t}\r\n\r\n";
			
			//方法
			$iNumber2 = "1";
			foreach ((array)$fields as $field) {

				//$field["Field"] = strtolower($field["Field"]);

				$_filed = $field["Field"];
				$_Type = $field["Type"];
				$_Comment = $field["Comment"];
				$replace = array($ClassName,$_filed,$_Type,date("Y/m/d"),$_Comment);
				$commentMethod = $this->Replace($this->Comment["MethodSet"],$replace);
				$fileContent .= "\t".$commentMethod."\r\n";
				$fileContent .= "\tpublic function set".$this->UcFirst($_filed)."(\$".$_filed.")\r\n\t{";
				//-----------------------------------------
				$fileContent .= "\r\n\t\t//检测数据合法性\r\n\t\t/**";
				if( eregi("^int",$_Type) )
					 $fileContent .= "\r\n\t\tif(!isNumber(\$".$_filed."))";
				elseif( eregi("^varchar",$_Type) or eregi("^char",$_Type))
				{
					preg_match("~\((\w+)\)~",$_Type,$m);
					 $fileContent .= "\r\n\t\tif(!isLength(\$".$_filed.",0,".$m[1]."))";
				}
				elseif( eregi("^datetime",$_Type) )
				{
					 $fileContent .= "\r\n\t\tif(!isTime(\$".$_filed."))";
				}
				elseif( eregi("^enum",$_Type) )
				{
					preg_match("~'(\w+)','(\w+)'~",$_Type,$m);
					unset($m[0]);
					$sArryList = var_export($m,TRUE);
					 $fileContent .= "\r\n\t\tif(!in_array(\$".$_filed.",$sArryList ))";
				}
				else
					 $fileContent .= "\r\n\t\tif(\$".$_filed."==\"\")";
				$fileContent .= "\r\n\t\t{";
				$fileContent .= "\r\n\t\t\tthrow new MyException(\"$_Comment 设置错误\",".$iNumber1.$this->AddZero($iNumber2).");";
				$fileContent .= "\r\n\t\t}";
				$fileContent .= "\r\n\t\t*/";
				//-----------------------------------------
				$fileContent .= "\r\n\t\t\$this->_".$_filed."=\$".$_filed.";\r\n\t}\r\n\r\n";
				
				//第2个方法
				$iNumber2++;
				$replace = array($table,$_filed,$_Type,date("Y/m/d"),$_Comment);
				$commentMethod = $this->Replace($this->Comment["MethodGet"],$replace);
				$fileContent .= "\t".$commentMethod."\r\n";
				$fileContent .= "\tpublic function get".$this->UcFirst($_filed)."()\r\n\t{\r\n\t\t";
				//-----------------------------------------
				$fileContent .= "\r\n\t\t//判断数据是否被设置\r\n\t\t/**";
				$fileContent .= "\r\n\t\tif(!isset(\$this->_".$_filed."))";
				$fileContent .= "\r\n\t\t{";
				$fileContent .= "\r\n\t\t\tthrow new MyException(\"$_Comment 设置错误\",".$iNumber1.$this->AddZero($iNumber2).");";
				$fileContent .= "\r\n\t\t}";
				$fileContent .= "\r\n\t\t*/";
				//-----------------------------------------
				$fileContent .= "\r\n\t\treturn \$this->_".$_filed.";\r\n\t}\r\n\r\n";

				$this->aField[] = $_filed;
				$iNumber2++;
			}

			$fileContent .= "}//End Class\r\n";
			$fileContent .= "?>";

			//写入文件
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
			$iNumber1++;
		}
	}
}

//类名回调函数<移除前缀>
function removeWapPrefix($string)
{
	//return preg_replace("/^wap3?_/i","",$string);
}
?>