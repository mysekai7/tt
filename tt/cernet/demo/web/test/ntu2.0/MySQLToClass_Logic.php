<?php
/**
 * mysql数据库转换成php数据对象类
 *
 */
class MySQLToClass_Logic
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
* 逻辑操作类:%Comment%
*
* 说明:%Comment%，请根据业务需求进行修改。
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
							"DBMethod"=>"/**
	* %Comment%
	* 
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
	function MySQLToClass_Logic($server,$user,$pass,$database,$classinfo)
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
				$tablePre = explode("_",$buffer[0]);
				$this->Tables[$buffer[0]] = $tablePre[0];
			}
			array_unique($this->Tables);
		}
		catch (Exception $oE)
		{
			die($oE->getMessage());
		}
	}

	/**
	 * 将第一个字母转换成大写
	 *
	 * @param string $string
	 * @return string
	 */
	private function UcFirst($string)
	{
		//echo $string;
		$string = explode("_",$string);
		for ($i=0;$i<count($string);$i++)
		{
			$string[$i] = ucfirst($string[$i]);
		}
		return implode("_", $string);
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
		//$replace[] = "逻辑方法";
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
	public function Run()
	{
		if(!$this->conn)
			$this->connect();
		$this->FetchTables();
		$path = $this->ClassDir."/";
		if(!is_readable($path))
		{
			mkdir($path);
			chmod($path,0664);
		}
		//echo $path;
		//开始
		$iNumber1 = "201";
		foreach ($this->Tables as $keyT=>$table)
		{
			$_table = $table;
			//if($this->ClassNameCallBack!="")
				//$table = call_user_func_array($this->ClassNameCallBack,$table);
			$aNumberList[$keyT] = $keyT;

			$table = $this->UcFirst($table);
			
			//Class文件名
			$ClassName = "Logic_$table";
			
			$replace = array($table,"","",date("Y/m/d"),"逻辑方法");
			$commentFile = $this->Replace($this->Comment["File"],$replace);

			$fileContent = "<?php\r\n";
			$fileContent .= $commentFile."\r\n\r\n";
			
			$fileContent .= "class $ClassName extends InitLogic\r\n";
			$fileContent .= "{\r\n";
			
			//方法
			/*
			foreach ((array)$fields as $field) {
				$_filed = $field["Field"];
				$this->aField[] = $_filed;
			}
			*/
			
			//构造函数
			$replace = array($table,"","",date("Y/m/d"),"构造函数");
			$commentClass = $this->Replace($this->Comment["Class"],$replace);
			$fileContent .= "\t".$commentClass."\r\n";
			$fileContent .= "\tpublic function __construct()\r\n\t{\r\n\t\tparent::__construct();\r\n";
			$fileContent .= "\r\n\t}\r\n\r\n";

			for($i=1;$i<2;$i++)
			{
				$replace = array($table,"","",date("Y/m/d"),"逻辑方法");
				$commentClass = $this->Replace($this->Comment["DBMethod"],$replace);
				$fileContent .= "\t".$commentClass."\r\n";
				$fileContent .= "\tfunction method{$i}()\r\n\t{\r\n\t\treturn true;\r\n";
				$fileContent .= "\r\n\t}\r\n\r\n";
			}
			$iNewNumber = $iNumber1."001";
			$aNumberList[$iNewNumber] = "错误提示";

			//基本数据库操作
			/*
			$FieldAll = $this->aField;
			$temp     = $this->aField;
			$FieldStrAll = implode(",",$this->aField);
			$FirstFiled = array_shift($temp);
			$FieldStrNoneFirst = implode(",",$temp);
			*/
			$fileContent .= "}//End Class\r\n";
			$fileContent .= "?>";

			$path = $this->ClassDir."/".$table."/";
			$handle = fopen($path.$ClassName.".class.php","w");
			fwrite($handle,$fileContent);
			fclose($handle);
			$this->aField = array();
			$fileContent  = "";

			$this->ClassList[] = $ClassName.".class.php";
			$iNumber1++;

		}
		//写入错误码
		$handleMsg = fopen($this->ClassDir."/MsgError.inc.php","a");
		foreach($aNumberList as $key=>$value)
		{
			if(!eregi("^[0-9]+$",$key))
				$fileContent="	/*---{$key}提示信息--*/\r\n";
			else
				$fileContent="	//\$MsgError[$key] = \"$value\";\r\n";
			fwrite($handleMsg,$fileContent);
		}
		$MsgFooter = "	/*------------------------------------end-----------------------------*/
?>";
		fwrite($handleMsg,$MsgFooter);
		fclose($handleMsg);

	}
}
//测试
/*
$test = new MySQLToClass("mysql.1cm.mobi","admin","admin","changjiang");
$test->ClassNameCallBack = "removeWapPrefix";
$test->Run();
echo("生成成功，生成目录为您数据库名。");
//类名回调函数<移除前缀>
function removeWapPrefix($string)
{
	return preg_replace("/^wap3?_/i","",$string);
}
*/
?>