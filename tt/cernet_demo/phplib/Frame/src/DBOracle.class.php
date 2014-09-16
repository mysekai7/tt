<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.0
- 文件名:Oracle.class.php
- 原作者:Justin Vincent
- 整理者:indraw
- 编写日期:2004/11/21
- 简要描述:常用Oracle数据库操作类集,版权属于原作者。indraw只进行了
-          汉化和规范化.并修正了几处发现的bug。
- 运行环境:php4以上，oracle7、8、9
- 修改记录:2004/11/21，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	define("Oracle_DB_USER", "sys");					// 数据库用户名
	define("Oracle_DB_PASSWORD", "change_on_install");	// 数据库密码
	define("Oracle_DB_NAME", "myfriend");				// 数据库名
	$db = new Oracle(Oracle_DB_USER, Oracle_DB_PASSWORD, Oracle_DB_NAME );
														// 初始化数据库操作类
*/
/*
	Oracle($dbuser, $dbpassword, $dbname)
	select($db)
	escape($str)
	print_error($str = "")
	show_errors()
	hide_errors()
	flush()
	query($query)
	get_var($query=null,$x=0,$y=0)
	get_row($query=null,$output=OBJECT,$y=0)
	get_col($query=null,$x=0)
	get_results($query=null, $output = OBJECT)
	get_col_info($info_type="name",$col_offset=-1)
	vardump($mixed='')
	debug()
*/

//=============================================================================
	//预定义常量
	define("SQL_VERSION","1.0");
	define("OBJECT","OBJECT",true);
	define("ARRAY_A","ARRAY_A",true);
	define("ARRAY_N","ARRAY_N",true);

//-----------------------------------------------------------------------------
	class DBOracle
	{

		var $debug_all = false;			//是否显示调试信息
		var $show_errors = true;		//是否显示错误提示
		
		var $num_queries = 0;			//初始化查询次数
		var $last_query;				//最后查询记录
		var $col_info;					//获取字段属性

		var $debug_called;				//判断是否已经debug输出
		var $vardump_called;			//判断是否已经var输出

		/*
		-----------------------------------------------------------
		函数名称:Oracle($dbuser, $dbpassword, $dbname)
		简要描述:连接到数据库服务器，并且选中数据库以备操作
		输入:mixed (用户名，密码，数据库名)
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function DBOracle($dbuser, $dbpassword, $dbname)
		{

			$this->dbh = OCILogon($dbuser, $dbpassword, $dbname);

			if ( ! $this->dbh )
			{
				$this->print_error("<ol><b>错误:不能建立数据库连接！</b><li>是否输入了正确的用户名和密码？<li>是否输入了正确的主机名？<li>数据库服务器是否运行？<li></ol>");
			}
			else
			{
				// 初始化连接属性以备以后选择新数据库使用
				$this->dbuser = $dbuser;
				$this->dbpassword = $dbpassword;
				$this->dbname = $dbname;
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
			$this->Oracle($this->dbuser, $this->dbpassword, $dbname);
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
			return str_replace("'","''",str_replace("''","'",stripslashes($str)));		
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
				$error = OCIError();
				$str = $error["message"] . "-" . $error["code"];
			}

			//把错误付值给全局array..
			$SQL_ERROR[] = array 
							(
								"query" => $this->last_query,
								"error_str"  => $str
							);

			//判断是否显示错误输出..
			if ( $this->show_errors )
			{
				print "<blockquote><font face=arial size=2 color=ff0000>";
				print "<b>SQL/DB Error --</b> ";
				print "[<font color=000077>$str</font>]";
				print "</font></blockquote>";
			}
			else
			{
				return false;	
			}
		}

		/*
		-----------------------------------------------------------
		函数名称:is_equal_str($str='')
		简要描述:此函数对付oracle查询""的时候不返回null字段内容的情况
		输入:string
		输出:string
		修改日志:------
		-----------------------------------------------------------
		*/
		function is_equal_str($str='')
		{
			return ($str==''?'IS NULL':"= '".$this->escape($str)."'");
		}

		function is_equal_int($int)
		{
			return ($int==''?'IS NULL':'= '.$int);
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
		function insert_id($seq_name)
		{
			return $this->get_var("SELECT $seq_name.nextVal id FROM Dual");
		}

		function sysdate()
		{
			return "SYSDATE";
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

			//解析查询语句
			if ( ! $stmt = OCIParse($this->dbh, $query))
			{
				$this->print_error();
			}

			//执行查询语句
			elseif ( ! $this->result = OCIExecute($stmt))
			{
				$this->print_error();
			}

			$this->num_queries++;

			//执行insert, delete, update, replace操作
			if ( preg_match('/^(insert|delete|update|create)\s+/i', $query) )
			{
				//获取操作所影响的记录行数
				$return_value = $this->rows_affected = OCIRowCount($stmt);
			}
			//执行select操作
			else
			{
				//获取字段信息
				if ( $num_cols = @OCINumCols($stmt) )
				{
	    			for ( $i = 1; $i <= $num_cols; $i++ )
	    			{
	    				$this->col_info[($i-1)]->name = OCIColumnName($stmt,$i);
	    				$this->col_info[($i-1)]->type = OCIColumnType($stmt,$i);
	    				$this->col_info[($i-1)]->size = OCIColumnSize($stmt,$i);
				    }
				}

				//获取查询结果
				if ($this->num_rows = @OCIFetchStatement($stmt,$results))
				{
					//将结果集转变成对象，因为oracle的返回结果比较奇怪：）
					foreach ( $results as $col_title => $col_contents )
					{
						$row_num=0;
						//循环所有的行
						foreach (  $col_contents as $col_content )
						{
							$this->last_result[$row_num]->{$col_title} = $col_content;
							$row_num++;
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
			//根据x,y参数从缓存结果集中获取变量
			if ( $this->last_result[$y] )
			{
				$values = array_values(get_object_vars($this->last_result[$y]));
			}
			//如果获取值，那么返回，否则返回空。
			return (isset($values[$x]) && $values[$x]!=='')?$values[$x]:null;
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
		function get_row($query=null,$output=OBJECT,$y=0)
		{

			//记录此函数如何被调用，用于调试...
			$this->func_call = "\$db->get_row(\"$query\",$output,$y)";

			//如果有查询语句那么查询，否则启用缓存...
			if ( $query )
			{
				$this->query($query);
			}

			//从偏移行中按照需要输出结果对象..
			if ( $output == OBJECT )
			{
				return $this->last_result[$y]?$this->last_result[$y]:null;
			}
			//从偏移行中按照需要输出联合数组..
			elseif ( $output == ARRAY_A )
			{
				return $this->last_result[$y]?get_object_vars($this->last_result[$y]):null;
			}
			//从偏移行中按照需要输出数组..
			elseif ( $output == ARRAY_N )
			{
				return $this->last_result[$y]?array_values(get_object_vars($this->last_result[$y])):null;
			}
			//如果输出非法，显示错误..
			else
			{
				$this->print_error(" \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N");
			}

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
			//提取列值
			for ( $i=0; $i < count($this->last_result); $i++ )
			{
				$new_array[$i] = $this->get_var(null,$x,$i);
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
		function get_results($query=null, $output = OBJECT)
		{

			//记录此函数如何被调用，用于调试...
			$this->func_call = "\$db->get_results(\"$query\", $output)";

			//如果有查询语句那么查询，否则启用缓存...
			if ( $query )
			{
				$this->query($query);
			}

			//返回对象数组. 每一行记录为一个对象.
			if ( $output == OBJECT )
			{
				return $this->last_result;
			}
			elseif ( $output == ARRAY_A || $output == ARRAY_N )
			{
				if ( $this->last_result )
				{
					$i=0;
					foreach( $this->last_result as $row )
					{

						$new_array[$i] = get_object_vars($row);

						if ( $output == ARRAY_N )
						{
							$new_array[$i] = array_values($new_array[$i]);
						}

						$i++;
					}

					return $new_array;
				}
				else
				{
					return null;
				}
			}
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
			echo "</font></pre></font></blockquote></td></tr></table>".$this->php_sql();
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

			if ( $this->col_info )
			{

				// --------------------------------------------------
				// 显示第一行
				echo "<table cellpadding=5 cellspacing=1 bgcolor=555555>";
				echo "<tr bgcolor=eeeeee><td nowrap valign=bottom><font color=555599 face=arial size=2><b>(row)</b></font></td>";


				for ( $i=0; $i < count($this->col_info); $i++ )
				{
					echo "<td nowrap align=left valign=top><font size=1 color=555599 face=arial>{$this->col_info[$i]->type} {$this->col_info[$i]->max_length}</font><br><span style='font-family: arial; font-size: 10pt; font-weight: bold;'>{$this->col_info[$i]->name}</span></td>";
				}

				echo "</tr>";

				// --------------------------------------------------
				// 显示查询结果

				if ( $this->last_result )
				{

					$i=0;
					foreach ( $this->get_results(null,ARRAY_N) as $one_row )
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

		//调用php和数据库版本
		function php_sql()
		{
			$get_version = explode("-",oci_server_version($this->dbh));
			return "ENV:&nbsp;php ".phpversion()."[Oracle ".$get_version[0]."]";	
		}

	}//end class
//=============================================================================
?>
