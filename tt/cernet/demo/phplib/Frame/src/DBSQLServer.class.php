<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.0
- 文件名:SQLServer.class.php
- 原作者:Justin Vincent
- 整理者:indraw
- 编写日期:2004/11/19
- 简要描述:常用SQLServer数据库操作类集,版权属于原作者。indraw只进行了汉化
-          和规范化。
- 运行环境:php4以上
- 修改记录:2004/11/19，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	define("SQLServer_DB_USER", "");		// 数据库用户名
	define("SQLServer_DB_PASSWORD", "");	// 数据库密码
	define("SQLServer_DB_NAME", "");		// 数据库名
	define("SQLServer_DB_HOST", "");		// 服务器地址
	$db = new SQLServer(SQLServer_DB_USER, SQLServer_DB_PASSWORD, SQLServer_DB_NAME, SQLServer_DB_HOST);
											// 初始化数据库操作类
*/
/*
	SQLServer($dbuser, $dbpassword, $dbname, $dbhost)
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
	define("SQL_VERSION","1.0");			//此类库版本
	define("OBJECT","OBJECT",true);			//预定义对象变量
	define("ARRAY_A","ARRAY_A",true);		//预定义关联数组
	define("ARRAY_N","ARRAY_N",true);		//预定义数组

//-----------------------------------------------------------------------------
	class SQLServer 
	{

		var $debug_all = false;		//是否显示调试信息
		var $show_errors = true;	//是否显示错误提示

		var $num_queries = 0;		//初始化查询次数
		var $last_query;			//最后查询记录
		var $col_info;				//获取字段属性

		var $debug_called;			//判断是否已经debug输出
		var $vardump_called;		//判断是否已经var输出

		/*
		-----------------------------------------------------------
		函数名称:SQLServer($dbuser, $dbpassword, $dbname, $dbhost)
		简要描述:连接到数据库服务器，并且选中数据库以备操作
		输入:mixed (用户名，密码，数据库名，服务器名)
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function SQLServer($dbuser, $dbpassword, $dbname, $dbhost)
		{
			$this->dbh = @mssql_connect($dbhost, $dbuser, $dbpassword);
			if ( ! $this->dbh )
			{
				$this->print_error("<ol><b>错误:不能建立数据库连接！</b><li>是否输入了正确的用户名和密码？<li>是否输入了正确的主机名？<li>数据库服务器是否运行？</ol>");
			}
			$this->select($dbname);
		}//end func

		/*
		-----------------------------------------------------------
		函数名称:select($db)
		简要描述:选种一个数据库以备操作
		输入:string （数据库名）
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function select($db)
		{
			mssql_select_db ($db);
		}//end func

		/*
		-----------------------------------------------------------
		函数名称:escape($str)
		简要描述:转义一个字符串安全用于 mysql_query 
		输入:string 
		输出:string 
		修改日志:------
		-----------------------------------------------------------
		*/
		function escape($str)
		{
			//处理引号
			$str = str_replace("'","''",str_replace("\'","'",$str));
			//ms sql需要处理的一些字符
			$escape = array ( "\n"=>"\\\\012","\r"=>"\\\\015");
			//进行循环替换
			foreach ( $escape as $match => $replace )
			{
				$str = str_replace($match,$replace,$str);
			}
			return $str;
		}//end func

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
			//如果没有截获错误，那么起用mysql自定义错误..
			if ( !$str ) $str = mssql_get_last_message();
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
		}//end func

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
			//清空最后查询结果，字段属性，最后查询记录
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

			//初始化返回值为0
			$return_val = 0;

			//清空缓存..
			$this->flush();

			//记录此函数如何被调用，用于调试...
			$this->func_call = "\$db->query(\"$query\")";

			//跟踪最后查询语句，用于调试..
			$this->last_query = $query;

			//通过mysql_query函数执行查询操作..
			$this->result = @mssql_query($query, $this->dbh);
			$this->num_queries++;
			
			//php现在还不支持从sqlserver服务器获取错误信息
			#这里以后要进行讨论，改进。
			$get_errorcode = "SELECT @@ERROR as errorcode";
			$error_res = @mssql_query($get_errorcode, $this->dbh);
			$errorcode = @mssql_result($error_res, 0, "errorcode");

			//执行insert, delete, update, replace操作
			if ( preg_match("/^(insert|delete|update|replace)\s+/i",$query) )
			{
				$this->rows_affected = @mssql_rows_affected ($this->dbh);
				$return_val = $this->rows_affected;
				//获取操作所影响的记录行数
				if ( preg_match("/^(insert|replace)\s+/i",$query) )
				{
					$get_last_ident = "SELECT @@IDENTITY as id";
					$last_res = @mssql_query($get_last_ident, $this->dbh);
					$this->insert_id = @mssql_result($last_res, 0, "id");
					//$return_val = $this->insert_id;
				}
			}
			if ($errorcode <> 0) {
				//如果有错误
				$this->print_error();
			}
			else
			{
				//获取字段信息
				$i=0;
				while ($i < @mssql_num_fields($this->result))
				{
					$this->col_info[$i]->name = @mssql_field_name($this->result,$i);
					$this->col_info[$i]->type = @mssql_field_type($this->result,$i);
					$this->col_info[$i]->size = @mssql_field_length($this->result,$i);
					$i++;
				}
				//获取查询结果
				$i=0;
				while ( $row = @mssql_fetch_object($this->result) )
				{
					//取得包含数组的结果对象
					$this->last_result[$i] = $row;
					$i++;
				}
				//获取查询结果行数
				$this->num_rows = $i;
				@mssql_free_result($this->result);

				//返回选中的结果行数
				$return_val = $this->num_rows;
			}
			//是否显示所有的查询信息
			$this->debug_all ? $this->debug() : null ;
			return $return_val;

		}//end func

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
				$this->print_error(" \$db->get_row(string query, output type, int offset) -- 输出类型必须是: OBJECT, ARRAY_A, ARRAY_N");
			}

		}//end func

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
			//按照需求返回数组或关联数组
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

		}//end func

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

		}//end func

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
				echo "<font color=800080><b>SQL</b> (v".SQL_VERSION.") <b>Variable Dump..</b></font>&nbsp;".$this->php_sql()."\n\n";
			}

			$var_type = gettype ($mixed);
			print_r(($mixed?$mixed:"<font color=red>No Value / False</font>"));
			echo "\n\n<b>变量类型::</b> " . ucfirst($var_type) . "\n";
			echo "<b>最后查询语句</b> [$this->num_queries]<b>:</b> ".($this->last_query?$this->last_query:"NULL")."\n";
			echo "<b>最后调用函数:</b> " . ($this->func_call?$this->func_call:"None")."\n";
			echo "<b>最后返回行数:</b> ".count($this->last_result)."\n";
			echo "</font></pre></font></blockquote></td></tr></table>";
			echo "\n<hr size=1 noshade color=dddddd>";

			$this->vardump_called = true;

		}//end func

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
			if ( !$this->debug_called )
			{
				echo "<font color=800080 face=arial size=2><b>SQL</b> (v".SQL_VERSION.") <b>Debug..</b></font>&nbsp;".$this->php_sql()."<p>\n";
			}
			echo "<font face=arial size=2 color=000099><b>查询语句:</b> [$this->num_queries] <b>--</b> ";
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
				//---------------------------------------------------
				} // 如果结果为空
				else
				{
					echo "<tr bgcolor=ffffff><td colspan=".(count($this->col_info)+1)."><font face=arial size=2>No Results</font></td></tr>";
				}
			echo "</table>";
			} // 如果字段属性为空
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
			$get_version = "";
			return "<font color=800080 face=arial size=2><b>ENV</b>&nbsp;(php ".phpversion()." - SQLServer ".$get_version.")";	
		}


	}//end class
//=============================================================================
?>
