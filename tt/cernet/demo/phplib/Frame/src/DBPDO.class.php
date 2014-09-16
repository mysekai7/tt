<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 2.0
- 文件名:DBPDO.class.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2006/01/18
- 简要描述:对pdo的数据库操作进行封装,基本包括现在php可以操作的所有数据库
- 运行环境:php5以上，oracle7,8,9,10;SQLite2,3;MySQL4,5;等
- 修改记录:2006/01/18，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	define("DB_USER", "sys");                // 数据库用户名
	define("DB_PASSWORD", "");               // 数据库密码
	define("DB_NAME", "pdb");                // 数据库名
	$db = new Oracle(DB_USER, DB_PASSWORD, DB_NAME );
											// 初始化数据库操作类
*/
/*
	DBPDO($dbuser, $dbpassword, $dbname)
	select($db)
	escape($str)
	flush()
	query($query)
	get_var($query=null,$x=0,$y=0)
	get_row($query=null,$output=OBJECT,$y=0)
	get_col($query=null,$x=0)
	get_results($query=null, $output = OBJECT)
	get_col_info($info_type="name",$col_offset=-1)
	vardump($mixed='')
	debug()
	show_errors()
	hide_errors()
	print_error($str = "")
*/
//=============================================================================
	//预定义常量
	define("SQL_VERSION","2.0");        //此类库版本

//-----------------------------------------------------------------------------
	class DBPDO
	{

		var $debug_all = 0;          //是否显示调试信息
		var $show_errors = 1;        //是否显示错误提示

		var $modify_log = false;          //是否记录更新数据库操作

		var $num_queries = 0;           //初始化查询次数
		var $last_query;                //最后查询记录
		var $col_info;                  //获取字段属性

		var $debug_called;              //判断是否已经debug输出
		var $vardump_called;            //判断是否已经var输出

		var $dbh;                        //数据库连接句柄
		var $func_call;                  //最后调用的方法
		var $result;                     //query返回的结果集
		var $last_result;                //select结果集(数组对象)

		var $num_rows;                   //select返回的记录条数
		var $rows_affected;              //update影响的记录数
		var $insert_id;                  //最后插入的记录id

		/*
		-----------------------------------------------------------
		函数名称:DBPDO($dbuser, $dbpassword, $dbname)
		简要描述:连接到数据库服务器，并且选中数据库以备操作
		输入:mixed (用户名，密码，数据库名)
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function DBPDO($dbuser, $dbpassword, $dbname)
		{
			try {
				$this->dbh = new PDO($dbname, $dbuser, $dbpassword);
				// 初始化连接属性以备以后选择新数据库使用
				$this->dbuser = $dbuser;
				$this->dbpassword = $dbpassword;
				$this->dbname = $dbname;
			}
			catch (PDOException $e) {
				$this->print_error($e->getMessage());
				//var_dump($this->dbh->errorInfo());
			}
		}

		/*
		-----------------------------------------------------------
		函数名称:select($db)
		简要描述:如果需要，可以选择一个新的数据库
		输入:string （数据库名）
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function select($db)
		{
			$this->DBPDO($this->dbuser, $this->dbpassword, $db);
		}

		/*
		-----------------------------------------------------------
		函数名称:escape($str)
		简要描述:转义一个字符串安全用于query 
		输入:string
		输出:string
		修改日志:------
		-----------------------------------------------------------
		*/
		function escape($str)
		{
			return $this->dbh->quote($str);
		}

		/*
		-----------------------------------------------------------
		函数名称:get_insert_id($query) 
		简要描述:获取最后插入数据之id
		输入:string 
		输出:int 
		修改日志:------
		-----------------------------------------------------------
		*/
		function insert_id($seq_name="")
		{
			if($seq_name) {
				//如果开debug，从多行记录中获取值
				if($this->last_result)
				{
					//var_dump($this->last_result);
					return $this->last_result[0];
				}
				return $this->get_var("SELECT $seq_name.nextVal id FROM Dual")-1;
			}
			return @$this->dbh->lastInsertId();
		}

		/*
		-----------------------------------------------------------
		函数名称:flush()
		简要描述:清空缓存
		输入:void
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function flush()
		{
			$this->last_result = null;
			$this->col_info = null;
			$this->last_query = null;
		}

		/*
		-----------------------------------------------------------
		函数名称:query($query)
		简要描述:基本查询操作
		输入:string (输入查询语句)
		输出:int (行数)
		修改日志:------
		-----------------------------------------------------------
		*/
		function query($query)
		{

			//去掉查询语句的前后空格
			$query = trim($query); 
			$return_value = 0;

			//清空缓存..
			$this->flush();

			//记录此函数如何被调用，用于调试...
			$this->func_call = "\$db->query(\"$query\")";

			//跟踪最后查询语句，用于调试..
			$this->last_query = $query;

			//执行insert, delete, update, replace操作
			if ( preg_match('/^(insert|delete|update|create)\s+/i', $query) )
			{
				//执行查询语句
				/*
				if(!$this->rows_affected = $this->dbh->exec($query)) {
					$this->print_error();
				}
				*/
				
				try{
					$this->rows_affected = $this->dbh->exec($query);
				}
				catch (PDOException $e) 
				{
					$this->print_error();
				}

				//记录信息
				$this->num_queries++;
				$return_value = $this->rows_affected;

				//获取最后插入记录id(此id为AUTO_INCREMENT)
				if ( preg_match("/^(insert|replace)\s+/i",$query) )
				{
					$this->insert_id = $this->insert_id();
					//$return_val = $this->insert_id;
				}
				//是否记录日志
				$this->modify_log ? $this->sql_log("success") : null ;
			}
			//执行select操作
			else
			{
				//执行查询语句
				//执行查询语句
				try{
					$this->result = $this->dbh->query($query);
				}
				catch (PDOException $e) 
				{
					$this->print_error();
				}
				if (!$this->result)
				{
					$this->print_error();
					return false;
				}
				//var_dump($this->result->rowCount());
				$this->num_rows = $this->result->rowCount();
				$this->num_queries++;

				//获取字段信息
				if ( $num_cols = $this->result->columnCount() )
				{
					for ( $i = 1; $i <= $num_cols; $i++ )
					{
						$aColInfo = @$this->result->getColumnMeta($i-1);
						//var_dump($aColInfo);
						if($aColInfo) {
							$this->col_info[($i-1)]['name'] = $aColInfo['name'];
							$this->col_info[($i-1)]['type'] = $aColInfo['native_type'];
							$this->col_info[($i-1)]['size'] = $aColInfo['len'];
						}
					}
				}
				//获取查询结果行数
				$return_value = $this->num_rows;
			}

			//是否显示所有的查询信息
			$this->debug_all ? $this->debug() : null ;

			return $return_value;

		}

		/*
		-----------------------------------------------------------
		函数名称:get_var($query=null,$x=0,$y=0)
		简要描述:从数据库获取单个变量
		输入:mixed (查询语句，列数，行数)
		输出:string (字段内容)
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_var($query=null,$x=0,$y=0)
		{

			//记录此函数如何被调用，用于调试...
			$this->func_call = "\$db->get_var(\"$query\",$x,$y)";

			//如果有查询语句那么查询，否则启用缓存...
			if ( $query )
			{
				$this->query($query);
			}
			//如果开debug，从多行记录中获取值
			if($this->last_result)
			{
				//echo "1";
				//var_dump($this->last_result);
				$tmp_array = array_values($this->last_result[$y]);
				return $tmp_array[$x];
			}
			//根据x,y参数从缓存结果集中获取变量
			if ( $this->result )
			{
				@$tmp_array = $this->result->fetchAll(3);
				//var_dump($tmp_array);
				return $tmp_array[$y][$x];
			}
		}

		/*
		-----------------------------------------------------------
		函数名称:get_row($query=null,$output=OBJECT,$y=0)
		简要描述:从数据库获取一行记录
		输入:mixed (查询语句，返回类型，行数)
		输出:array (按照要求返回：对象，关联数组，数字数组)
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_row($query=null,$output="ARRAY_A",$y=0)
		{

			//记录此函数如何被调用，用于调试...
			$this->func_call = "\$db->get_row(\"$query\",$output,$y)";

			//如果有查询语句那么查询，否则启用缓存...
			if ( $query )
			{
				$this->query($query);
			}
			//如果开debug，从多行记录中获取值
			if($this->last_result)
			{
				//var_dump($this->last_result);
				return $this->last_result[$y];
			}
			//从偏移行中按照需要输出结果对象..
			if ( $output == "OBJECT" )
			{
				$return_results = $this->result->fetchAll(1);
			}
			//从偏移行中按照需要输出联合数组..
			elseif ( $output == "ARRAY_A" )
			{
				$return_results = $this->result->fetchAll(2);
			}
			//从偏移行中按照需要输出数组..
			elseif ( $output == "ARRAY_N" )
			{
				$return_results = $this->result->fetchAll(3);
			}
			//如果输出非法，显示错误..
			else
			{
				$this->print_error(" \$db->get_row(string query, output type, int offset) -- 输出类型必须是: OBJECT, ARRAY_A, ARRAY_N");
			}
			$this->num_rows = count($return_results);
			return $return_results[$y];

		}

		/*
		-----------------------------------------------------------
		函数名称:get_col($query=null,$x=0)
		简要描述:从数据库获取一列记录（参数x为第几列）
		输入:mixed (查询语句，第几列)
		输出:array
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_col($query=null,$x=0)
		{

			//如果有查询语句那么查询，否则启用缓存...
			if ( $query )
			{
				$this->query($query);
			}
			//如果开debug，从多行记录中获取值
			if($this->last_result)
			{
				//var_dump($this->last_result);
				foreach( $this->last_result as $old_array )
				{
					$tmp_array = array_values($old_array);
					$new_array[] = $tmp_array[$x];
				}
				return $new_array;
			}
			//提取列值
			$new_array = @$this->result->fetchAll(3);
			for ( $i=0; $i < count($new_array); $i++ )
			{
				$new_array[$i] = $new_array[$i][$x];
			}
			return $new_array;
		}

		/*
		-----------------------------------------------------------
		函数名称:get_results($query=null, $output = OBJECT)
		简要描述:从数据库返回多行查询结果
		输入:mixed (查询语句，返回类型)
		输出:array (按照要求返回对象，关联数组，数字数组)
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_results($query=null, $output = "ARRAY_A")
		{

			//记录此函数如何被调用，用于调试...
			$this->func_call = "\$db->get_results(\"$query\", $output)";
			//如果有查询语句那么查询，否则启用缓存...
			if ( $query )
			{
				$this->query($query);
				//echo "1";
			}
			if($this->last_result)
			{
				return $this->last_result;
			}
			//返回对象数组. 每一行记录为一个对象.
			if ( $output == "OBJECT" )
			{
				$this->last_result = $this->result->fetchAll(1);
			}
			//从偏移行中按照需要输出联合数组..
			elseif ( $output == "ARRAY_A" )
			{
				$this->last_result = @$this->result->fetchAll(2);
			}
			//从偏移行中按照需要输出数组..
			elseif ( $output == "ARRAY_N" )
			{
				$this->last_result = $this->result->fetchAll(3);
			}
			//如果输出非法，显示错误..
			else
			{
				$this->print_error(" \$db->get_results(string query, output type, int offset) -- 输出类型必须是: OBJECT, ARRAY_A, ARRAY_N");
			}
			$this->num_rows = count($this->last_result);

			return $this->last_result;
		}

		/*
		-----------------------------------------------------------
		函数名称:get_col_info($info_type="name",$col_offset=-1)
		简要描述:获取最后查询结果的字段信息
		输入:mixed (字段信息属性，第几个字段)
		输出:array
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_col_info($info_type="name",$col_offset=-1)
		{

			if ( $this->col_info )
			{
				if ( $col_offset == -1 )
				{
					$i=0;
					foreach($this->col_info as $col )
					{
						$new_array[$i] = $col->{$info_type};
						$i++;
					}
					return $new_array;
				}
				else
				{
					return $this->col_info[$col_offset]->{$info_type};
				}

			}

		}

		/*
		-----------------------------------------------------------
		函数名称:vardump($mixed='')
		简要描述:将对象，数组变量以易于理解的格式显示在屏幕上
		输入:mixed
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function vardump($mixed='')
		{

			echo "<p><table><tr><td bgcolor=ffffff><blockquote><font color=000090>";
			echo "<pre><font face=arial>";

			if ( ! $this->vardump_called )
			{
				echo "<font color=800080><b>SQL</b> (v".SQL_VERSION.") <b>Variable Dump..</b></font>\n\n";
			}

			$var_type = gettype ($mixed);
			print_r(($mixed?$mixed:"<font color=red>No Value / False</font>"));
			echo "\n\n<b>变量类型:</b> " . ucfirst($var_type) . "\n";
			echo "<b>最后查询语句</b> [$this->num_queries]<b>:</b> ".($this->last_query?$this->last_query:"NULL")."\n";
			echo "<b>最后调用函数:</b> " . ($this->func_call?$this->func_call:"None")."\n";
			echo "<b>最后返回行数:</b> ".count($this->last_result)."\n";
			echo "</font></pre>".$this->php_sql()."</font></blockquote></td></tr></table>";
			echo "\n<hr size=1 noshade color=dddddd>";

			$this->vardump_called = true;
		}

		/*
		-----------------------------------------------------------
		函数名称:debug()
		简要描述:显示最后一条数据库查询语句；显示一个查回的数据表。
		输入:void
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function debug()
		{
			if($this->debug_all == 2) 
			{
				$this->debug_log("success");
				return true;
			}
			echo "<blockquote>";

			// 只显示一次头信息.
			if ( ! $this->debug_called )
			{
				echo "<font color=800080 face=arial size=2><b>SQL</b> (v".SQL_VERSION.") <b>Debug..&nbsp;".$this->php_sql()."</b></font><p>\n";
			}
			echo "<font face=arial size=2 color=000099><b>查询语句</b> [$this->num_queries] <b>--</b> ";
			echo "[<font color=000000><b>$this->last_query</b></font>]</font><p>";

				echo "<font face=arial size=2 color=000099><b>查询结果...</b></font>";
				echo "<blockquote>";

			//解决不能获取属性的问题
			if( !$this->col_info and eregi("select",$this->last_query)) {
				$this->get_results(null,"ARRAY_A");
				if(is_array($this->last_result[0])) 
				{
					foreach (array_keys($this->last_result[0]) as $key=>$con_name)
					{
						$this->col_info[$key]['name'] = $con_name;
						$this->col_info[$key]['type'] = "未知";
						$this->col_info[$key]['size'] = "0";
					}
				}
			}
			//echo "test:";
			//var_dump($this->last_result[0]);
			if ( $this->col_info )
			{

				// --------------------------------------------------
				// 显示第一行
				echo "<table cellpadding=5 cellspacing=1 bgcolor=555555>";
				echo "<tr bgcolor=eeeeee><td nowrap valign=bottom><font color=555599 face=arial size=2><b>(row)</b></font></td>";
				for ( $i=0; $i < count($this->col_info); $i++ )
				{
					echo "<td nowrap align=left valign=top><font size=1 color=555599 face=arial>{$this->col_info[$i]['type']} {$this->col_info[$i]['size']}</font><br><span style='font-family: arial; font-size: 10pt; font-weight: bold;'>{$this->col_info[$i]['name']}</span></td>";
				}

				echo "</tr>";

				// --------------------------------------------------
				// 显示查询结果

				if ( $this->result )
				{
					$i=0;
					foreach ( $this->get_results(null,"ARRAY_A") as $one_row )
					{
						$i++;
						echo "<tr bgcolor=ffffff><td bgcolor=eeeeee nowrap align=middle><font size=2 color=555599 face=arial>$i</font></td>";

						foreach ( $one_row as $item )
						{
							echo "<td nowrap><font face=arial size=2>$item</font></td>";
						}

						echo "</tr>";
					}
				//-------------------------------------------------------------
				}// 如果结果为空
				else
				{
					echo "<tr bgcolor=ffffff><td colspan=".(count($this->col_info)+1)."><font face=arial size=2>No Results</font></td></tr>";
				}

				echo "</table>";

			}//如果字段属性为空
			else
			{
				echo "<font face=arial size=2>No Results</font>";
			}

			echo "</blockquote></blockquote><hr noshade color=dddddd size=1>";

			$this->debug_called = true;
		}

		/*
		-----------------------------------------------------------
		函数名称:sql_log()
		简要描述:记录sql语句
		输入:void
		输出:void 
		修改日志:------
		-----------------------------------------------------------
		*/
		function sql_log($sResult)
		{
			global $LibSet;
			//$LibSet['LogDir'] = "./";
			$sToday = "sqlog".date("Y-m-d",time());
			//打开文件
			$sFileName = $LibSet['LogDir']."PDB/".$sToday.".xml";
			if( !file_exists($sFileName))
			{
				$handle = @fopen ($sFileName,"w");
			}
			else
			{
				$handle = @fopen ($sFileName,"a");
			}
			//写入数据
			$sTime = date("H:i:s",time());
			$aContent[] = "<sqlitem>";
			$aContent[] = "\t<time>".$sTime."</time>";
			$aContent[] = "\t<ip>".$_SERVER["REMOTE_ADDR"]."</ip>";
			$msg =  @htmlspecialchars($this->last_query);
			$aContent[] = "\t<sql>".$msg."</sql>";
			$aContent[] = "\t<url>".getenv('REQUEST_URI')."</url>";
			$aContent[] = "\t<result>".htmlspecialchars($sResult)."</result>";
			$aContent[] = "</sqlitem>";
			$sContent = join("\n",$aContent);
			@fwrite($handle, $sContent."\n\n");
			//关闭文件
			@fclose($handle);
		}

		/*
		-----------------------------------------------------------
		函数名称:debug_log()
		简要描述:记录Debug语句
		输入:void
		输出:void 
		修改日志:------
		-----------------------------------------------------------
		*/
		function debug_log($sResult)
		{
			global $LibSet;
			//$LibSet['LogDir'] = "./";
			$sToday = "debuglog".date("Y-m-d",time());
			//打开文件
			$sFileName = $LibSet['LogDir']."PDB/".$sToday.".xml";
			if( !file_exists($sFileName))
			{
				$handle = @fopen ($sFileName,"w");
			}
			else
			{
				$handle = @fopen ($sFileName,"a");
			}
			//写入数据
			$sTime = date("H:i:s",time());
			$aContent[] = "<item>";
			$aContent[] = "\t<time>".$sTime."</time>";
			$aContent[] = "\t<ip>".$_SERVER["REMOTE_ADDR"]."</ip>";
			$msg =  @htmlspecialchars($this->last_query);
			$aContent[] = "\t<sql>".$msg."</sql>";
			$aContent[] = "\t<url>".getenv('REQUEST_URI')."</url>";
			$aContent[] = "\t<result>".htmlspecialchars($sResult)."</result>";
			$aContent[] = "</item>";
			$sContent = join("\n",$aContent);
			@fwrite($handle, $sContent."\n\n");
			//关闭文件
			@fclose($handle);
		}

		/*
		-----------------------------------------------------------
		函数名称:show_errors()/hide_errors()
		简要描述:设置错误显示
		输入:void
		输出:void 
		修改日志:------
		-----------------------------------------------------------
		*/
		function show_errors()
		{
			$this->show_errors = true;
		}
		
		function hide_errors()
		{
			$this->show_errors = false;
		}

		/*
		-----------------------------------------------------------
		函数名称:print_error($str = "")
		简要描述:显示数据库操作错误
		输入:string
		输出:echo or fause
		修改日志:------
		-----------------------------------------------------------
		*/
		function print_error($str = "")
		{
			
			//设置全局变量$SQL_ERROR..
			global $SQL_ERROR;
						
			//如果没有截获错误，那么起用Oracle自定义错误..
			if ( !$str )
			{
				$error = $this->dbh->errorInfo();
				$str = $error[0] . "(".$error[1].")-" . $error[2];
			}

			//把错误付值给全局array..
			$SQL_ERROR[] = array 
							(
								"query" => $this->last_query,
								"error_str"  => $str
							);
			if($this->show_errors == 2) 
			{
				$this->debug_log($str);
				return true;
			}
			//判断是否显示错误输出..
			if ( $this->show_errors )
			{
				print "<blockquote><font face=arial size=2 color=ff0000>";
				print "<b>SQL/DB Error --</b> ";
				print "[<font color=green>$this->last_query</font>][<font color=000077>$str</font>]";
				print "</font></blockquote>";
			}
			else
			{
				return false;	
			}
		}

		//调用php和数据库版本
		function php_sql()
		{
			$db_array = explode(":",$this->dbname);
			switch($db_array[0]) {
			case "mysql":
				$db_type = "MySQL";
			break;
			case "oci":
				$db_type = "Oracle";
			break;
			case "sqlite":
				$db_type = "SQLite";
			break;
			default:
				$db_type = "SQL";
			break;
			}
			return "ENV:&nbsp;php ".phpversion()."[$db_type ".@$this->dbh->getAttribute(4)."]";	
		}

	}//end class
//=============================================================================
?>
