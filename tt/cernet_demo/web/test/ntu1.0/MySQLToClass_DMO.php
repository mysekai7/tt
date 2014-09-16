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
* 定义 %Comment% 的操作
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
				$this->ClassMark = strtoupper($string[0]);
				return "DMO_".strtoupper($string[0])."_".$string[1];
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
			$ClassComment = $this->Tables[$ClassTable]['17'];
			$ClassDTO = eregi_replace("DMO_","DTO_",$ClassName);
			$ClassObject = "o".substr($ClassDTO, strripos($ClassDTO,'_')+1);

			$replace = array($table,"","",date("Y/m/d"),$ClassComment);
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
			$fileContent .= "\r\n\t}\r\n\r\n";

			//基本数据库操作
			$FieldAll = $this->aField;
			$temp     = $this->aField;
			$FieldStrAll = implode(",",$this->aField);
			$FirstFiled = array_shift($temp);
			$FieldStrNoneFirst = implode(",",$temp);

			//ADD
			//事例代码
			$Sample = "\r\n\t* \$$ClassObject = new $ClassDTO;";
			foreach($this->aField as $value)
			{
				$Sample .= "\r\n\t* \${$ClassObject}->set".$this->UcFirst($value)."(\$$value);";
			}
			$Sample .= "\r\n\t* \$oLogic = new Logic();";
			$Sample .= "\r\n\t* \$oLogic->add(\$$ClassObject);";
			$Sample = "\t* \r\n\t* <code>$Sample\r\n\t* </code>";
			$replace = array("Add($ClassName)","插入一条数据\r\n$Sample","object "."$ClassDTO","boolean");
			$commentMethod = $this->Replace($this->Comment["DBMethod"],$replace);
			$fileContent .= "\t".$commentMethod."\r\n";
			$fileContent .= "\tpublic function add($ClassDTO \$$ClassObject)\r\n\t{\r\n\t\t\$sInsert  = \"INSERT INTO ".$_table." (`".implode("`,`",$this->aField)."`) VALUES ('\";\r\n\t\t";
			$StrInsert = "";

			foreach($this->aField as $value)
			{
				$StrInsert .= "\$sInsert .= \${$ClassObject}->get".$this->UcFirst($value)."().";
				$StrInsert .= "\"','\";\r\n\t\t";
			}
			$fileContent .= substr($StrInsert,0,strlen($StrInsert)-8).")\";\r\n";
			$fileContent .= "\t\t//判断是否执行成功\r\n\t\t";
			$fileContent .= "if( !\$this->_db->query( \$sInsert ) )\r\n\t\t";
			$fileContent .= "{\r\n";
			$fileContent .= "\t\t\treturn false;\r\n\t\t";
			$fileContent .= "}\r\n";
			$fileContent .= "\t\treturn true;\r\n\t";
			$fileContent .= "}\r\n";

			//Get
			$Sample = "\r\n\t* \${$ClassObject} = new $ClassDTO;";
			$Sample .= "\r\n\t* \${$ClassObject}->set".$this->UcFirst($this->aField[0])."(\${$this->aField[0]});";
			$Sample .= "\r\n\t* \$oLogic = new Logic();";
			$Sample .= "\r\n\t* \$oLogic->get($ClassDTO \${$ClassObject},\$sKey='');";
			$Sample = "\r\n\t* <code>$Sample\r\n\t* </code>";

			$replace = array("get()","获取一条记录\r\n\t* $Sample","object $ClassDTO \r\n\t* @param  string \$sKey",$ClassDTO);
			$commentMethod = $this->Replace($this->Comment["DBMethod"],$replace);
			$fileContent .= "\t".$commentMethod."\r\n";
			$fileContent .= "\tpublic function get($ClassDTO \${$ClassObject},\$sKey='')\r\n";
			$fileContent .= "\t{\r\n";
			$fileContent .= "\t\tif(!\$sKey)\r\n\t\t{\r\n";
			$fileContent .= "\t\t\t\$sSelect  = \"SELECT `".implode("`,`",$this->aField)."` FROM $_table WHERE `{$this->aField[0]}` = '\";\r\n";
			$fileContent .= "\t\t}\r\n\t\telse\r\n\t\t{\r\n";
			$fileContent .= "\t\t\t\$sSelect  = \"SELECT \$sKey FROM $_table WHERE `{$this->aField[0]}` = '\";\r\n";
			$fileContent .= "\t\t}\r\n";
			$fileContent .= "\t\t\$sSelect .= \${$ClassObject}->get".$this->UcFirst($this->aField[0])."();\r\n";
			$fileContent .= "\t\t\$sSelect .= \"' \";\r\n";
			$fileContent .= "\t\t\$oResult = \$this->_db->get_row( \$sSelect );\r\n";
			$fileContent .= "\t\tif( \$this->_db->num_rows < 1)\r\n";
			$fileContent .= "\t\t{\r\n";
			$fileContent .= "\t\t\treturn false;\r\n";
			$fileContent .= "\t\t}\r\n";
			$fileContent .= "\t\t\$this->copyDto(\${$ClassObject},\$oResult);\r\n";
			$fileContent .= "\t\treturn true;\r\n";
			$fileContent .= "\t}\r\n";


			//GetBy
			$Sample = "\r\n\t* \${$ClassObject} = new $ClassDTO;";
			$Sample .= "\r\n\t* \${$ClassObject}->set".$this->UcFirst($this->aField[0])."(\${$this->aField[0]});";
			$Sample .= "\r\n\t* \$oLogic = new Logic();";
			$Sample .= "\r\n\t* \$oLogic->getBy($ClassDTO \${$ClassObject},\$sKey='');";
			$Sample = "\r\n\t* <code>$Sample\r\n\t* </code>";

			$replace = array("getBy()","获取一条记录\r\n\t* $Sample","object $ClassDTO \r\n\t* @param  string \$sKey",$ClassDTO);
			$commentMethod = $this->Replace($this->Comment["DBMethod"],$replace);
			$fileContent .= "\t".$commentMethod."\r\n";
			$fileContent .= "\tpublic function getBy($ClassDTO \${$ClassObject},\$sKey='')\r\n";
			$fileContent .= "\t{\r\n";
			$fileContent .= "\t\t\$sSelect  = \"SELECT `".implode("`,`",$this->aField)."` FROM $_table WHERE \$sKey = '\";\r\n";
			$fileContent .= "\t\t\$sSelect .= \${$ClassObject}->\$sKey;\r\n";
			$fileContent .= "\t\t\$sSelect .= \"' \";\r\n";
			$fileContent .= "\t\t\$oResult = \$this->_db->get_row( \$sSelect );\r\n";
			$fileContent .= "\t\tif( \$this->_db->num_rows < 1)\r\n";
			$fileContent .= "\t\t{\r\n";
			$fileContent .= "\t\t\treturn false;\r\n";
			$fileContent .= "\t\t}\r\n";
			$fileContent .= "\t\t\$this->copyDto(\${$ClassObject},\$oResult);\r\n";
			$fileContent .= "\t\treturn true;\r\n";
			$fileContent .= "\t}\r\n";

			//Gets
			//事例代码
			$Sample = "\r\n\t* \$oLogic = new Logic;";
			$Sample .= "\r\n\t* \$oLogic->gets(0,20,\"DESC\",'');";
			$Sample = "\r\n\t* <code>$Sample\r\n\t* </code>";
			
			$replace = array("gets(\$iStart=0,\$iNumber=10,\$sOrder='ASC',\$aKey='')","获取多条记录$Sample","int \$iStart 开始行数,\r\n\t* @param  int \$iNumber 记录数,\r\n\t* @param  string \$sOrder 主键排序方式","array");
			$commentMethod = $this->Replace($this->Comment["DBMethod"],$replace);
			$fileContent .= "\t".$commentMethod."\r\n";
			$fileContent .= "\tpublic function gets(\$iStart=0,\$iNumber=10,\$sOrder='ASC',\$aKey='')\r\n\t{\r\n"; 
			$fileContent .= "		\$sSelect  = \"SELECT `".implode("`,`",$this->aField)."` FROM $_table \";\r\n";
			$fileContent .= "		if(is_array(\$aKey))\r\n"; 
			$fileContent .= "		{\r\n"; 
			$fileContent .= "			foreach(\$aKey as \$key=>\$value)\r\n"; 
			$fileContent .= "			{\r\n"; 
			$fileContent .= "				\$aWhere .= \" \$key='\".\$value.\"' \";\r\n"; 
			$fileContent .= "			}\r\n"; 
			$fileContent .= "			\$sWhere = \" WHERE \".join(\" AND \",\$aWhere);\r\n"; 
			$fileContent .= "		}\r\n"; 
			$fileContent .= "		\$sSelect .=  \" \$sWhere ORDER BY ".$this->aField[0]." \$sOrder LIMIT \$iStart,\$iNumber \";\r\n"; 
			$fileContent .= "		\${$ClassObject} = \$this->_db->get_page_results( \$sSelect );\r\n"; 
			$fileContent .= "		if( \$this->_db->num_rows < 1)\r\n"; 
			$fileContent .= "		{\r\n"; 
			$fileContent .= "			return false;\r\n"; 
			$fileContent .= "		}\r\n"; 
			$fileContent .= "		return \${$ClassObject};\r\n"; 
			$fileContent .= "	}\r\n";


			//getsAll
			//事例代码
			$Sample = "\r\n\t* \$oLogic = new Logic;";
			$Sample .= "\r\n\t* \$oLogic->getsAll(0,20,\"DESC\",'');";
			$Sample = "\r\n\t* <code>$Sample\r\n\t* </code>";
			
			$replace = array("getsAll(\$iStart=0,\$iNumber=10,\$sOrder='ASC',\$aKey='')","获取多条记录$Sample","int \$iNumber 记录数,\r\n\t* @param  string \$sOrder 主键排序方式","array");
			$commentMethod = $this->Replace($this->Comment["DBMethod"],$replace);
			$fileContent .= "\t".$commentMethod."\r\n";
			$fileContent .= "\tpublic function getsAll(\$iNumber=10,\$sOrder='ASC',\$aKey='')\r\n\t{\r\n"; 
			$fileContent .= "		\$sSelect  = \"SELECT `".implode("`,`",$this->aField)."` FROM $_table \";\r\n";
			$fileContent .= "		if(is_array(\$aKey))\r\n"; 
			$fileContent .= "		{\r\n"; 
			$fileContent .= "			foreach(\$aKey as \$key=>\$value)\r\n"; 
			$fileContent .= "			{\r\n"; 
			$fileContent .= "				\$aWhere .= \" \$key='\".\$value.\"' \";\r\n"; 
			$fileContent .= "			}\r\n"; 
			$fileContent .= "			\$sWhere = \" WHERE \".join(\" AND \",\$aWhere);\r\n"; 
			$fileContent .= "		}\r\n"; 
			$fileContent .= "		\$sSelect .=  \" \$sWhere ORDER BY ".$this->aField[0]." \$sOrder LIMIT \$iNumber \";\r\n"; 
			$fileContent .= "		\${$ClassObject} = \$this->_db->get_results( \$sSelect );\r\n"; 
			$fileContent .= "		if( \$this->_db->num_rows < 1)\r\n"; 
			$fileContent .= "		{\r\n"; 
			$fileContent .= "			return false;\r\n"; 
			$fileContent .= "		}\r\n"; 
			$fileContent .= "		return \${$ClassObject};\r\n"; 
			$fileContent .= "	}\r\n";

			//Modify
			//事例代码
			$Sample = "\r\n\t* \${$ClassObject} = new $ClassDTO;";
			foreach($FieldAll as $value)
			{
				$Sample .= "\r\n\t* \${$ClassObject}->set".$this->UcFirst($value)."(\$$value);";
			}
			$Sample .= "\r\n\t* \$oLogic = new oLogic;";
			$Sample .= "\r\n\t* \$oLogic->modify(\${$ClassObject},null);";
			$Sample = "\r\n\t* <code>$Sample\r\n\t* </code>";
			//
			$replace = array("modify($ClassDTO \${$ClassObject},\$sKey='')","修改一条记录$Sample"," $ClassDTO \r\n\t* @param  string \$sKey","boolean");
			$commentMethod = $this->Replace($this->Comment["DBMethod"],$replace);
			$fileContent .= "\t".$commentMethod."\r\n";
			$fileContent .= "\tpublic function modify($ClassDTO \${$ClassObject},\$sKey='')\r\n\t{\r\n";
			
			
			$fileContent .= "		if(!\$sKey)\r\n";
			$fileContent .= "		{\r\n";
			$fileContent .= "			\$sUpdate  = \"UPDATE $_table SET `";
			$fileContent .= $this->aField[0]. "` = '\".\${$ClassObject}->get".$this->UcFirst($this->aField[0])."();\r\n";
			$StrFields = "";
			for($j=1;$j < count($this->aField);$j++)
			{
				$StrFields .= "			\$sUpdate .= \"',`".$this->aField[$j]. "` = '\"."; 
				$StrFields .= "\${$ClassObject}->get".$this->UcFirst($this->aField[$j])."();\r\n";
			}
			$fileContent .= $StrFields;
			$fileContent .= "			\$sUpdate .= \"' WHERE `{$this->aField[0]}` = '\";\r\n";
			$fileContent .= "			\$sUpdate .= \${$ClassObject}->get".$this->UcFirst($this->aField[0])."();\r\n";
			$fileContent .= "			\$sUpdate .= \"' \";\r\n";
			$fileContent .= "		}\r\n";
			$fileContent .= "		else\r\n";
			$fileContent .= "		{\r\n";
			$fileContent .= "			\$sUpdate  = \"UPDATE $_table SET `\$sKey` = '\".\${$ClassObject}->\$sKey;\r\n";
			$fileContent .= "			\$sUpdate .= \"' WHERE {$this->aField[0]} = '\";\r\n";
			$fileContent .= "			\$sUpdate .= \${$ClassObject}->get".$this->UcFirst($this->aField[0])."().\"'\";\r\n";
			$fileContent .= "		}\r\n";
			$fileContent .= "		if(\$this->_db->query( \$sUpdate ) === false)\r\n";
			$fileContent .= "		{\r\n";
			$fileContent .= "			return false;\r\n";
			$fileContent .= "		}\r\n";
			$fileContent .= "		if(\$this->_db->rows_affected === 0)\r\n";
			$fileContent .= "		{\r\n";
			$fileContent .= "			return true;\r\n";
			$fileContent .= "		}\r\n";
			$fileContent .= "		return true;\r\n";
			$fileContent .= "	}\r\n";
			$StrFields = "";

			//Remove
			//事例代码
			$Sample = "\r\n\t* \${$ClassObject} = new $ClassDTO;";
			$Sample .= "\r\n\t* \${$ClassObject}->set".$this->UcFirst($this->aField[0])."(\${$this->aField[0]});";
			$Sample .= "\r\n\t* \$oLogic = new Logic;";
			$Sample .= "\r\n\t* \$oLogic->remove(\${$ClassObject},null);";
			$Sample = "\r\n\t* <code>$Sample\r\n\t* </code>";
			
			$replace = array("remove","删除一条记录$Sample","$ClassDTO \r\n\t* @param  string \$sKey","boolbean");

			$commentMethod = $this->Replace($this->Comment["DBMethod"],$replace);
			$fileContent .= "\t".$commentMethod."\r\n";
			$fileContent .= "\tpublic function remove($ClassDTO \${$ClassObject},\$sKey='')\r\n\t{\r\n";
			
			$fileContent .= "		if(!\$sKey)\r\n";
			$fileContent .= "		{\r\n";
			$fileContent .= "			\$sDelete  = \"DELETE FROM $_table WHERE {$this->aField[0]} = '\";\r\n";
			$fileContent .= "			\$sDelete .= \${$ClassObject}->get".$this->UcFirst($this->aField[0])."();\r\n";
			$fileContent .= "			\$sDelete .= \"'\";\r\n";
			$fileContent .= "		}\r\n";
			$fileContent .= "		else\r\n";
			$fileContent .= "		{\r\n";
			$fileContent .= "			\$sDelete  = \"DELETE FROM $_table WHERE `\$sKey` = '\";\r\n";
			$fileContent .= "			\$sDelete .= \${$ClassObject}->\$sKey;\r\n";
			$fileContent .= "			\$sDelete .= \"'\";\r\n";
			$fileContent .= "		}\r\n";
			$fileContent .= "		if(\$this->_db->query( \$sDelete ) === false)\r\n";
			$fileContent .= "		{\r\n";
			$fileContent .= "			return false;\r\n";
			$fileContent .= "		}\r\n";

			$fileContent .= "		if(\$this->_db->rows_affected === 0)\r\n";
			$fileContent .= "		{\r\n";
			$fileContent .= "			return true;\r\n";
			$fileContent .= "		}\r\n";
			$fileContent .= "		return true;\r\n";
			$fileContent .= "	}\r\n";

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