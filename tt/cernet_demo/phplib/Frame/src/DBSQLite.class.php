<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:SQLite.class.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2004/11/1
- 简要描述:常用sqlite数据库操作类集。此类为indraw根据Justin Vincent 
-          的mysql格式从新编写；非修改完善版本。
- 运行环境:php4.3或5，SQLite版本要 > 2.811。
- 修改记录:2004/11/1，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	$db = new DBSQLite( "./", "DB" );
*/
/*
	DBSQLite($dbpath, $dbname)                        //初始化数据库连接
	select($db)                                       //选择一个数据库
	escape($str)                                      //安全性过滤
	flush()                                           //清空缓存
	query($query)                                     //执行sql语句
	get_var($query=null,$x=0,$y=0)                    //获取一个字段
	get_row($query=null,$output=OBJECT,$y=0)          //获取一行记录
	get_col($query=null,$x=0)                         //获取一列
	get_results($query=null, $output = OBJECT)        //获取多行记录
	get_col_info($info_type="name",$col_offset=-1)    //获取字段信息

	vardump($mixed='')                                //调试-输出变量信息
	debug()                                           //调试-进行sql查询跟踪
	print_error($str = "")                            //调试-输出错误
	show_errors()                                     //调试-显示错误
	hide_errors()                                     //调试-隐藏错误
*/

//=============================================================================
	//预定义常量
	define("SQL_VERSION","1.0");              //此类库版本
	define("OBJECT","OBJECT",true);           //预定义对象变量
	define("ARRAY_A","ARRAY_A",true);         //预定义关联数组
	define("ARRAY_N","ARRAY_N",true);         //预定义数组

//-----------------------------------------------------------------------------
	class DBSQLite
	{
		var $debug_all = true;            //是否显示调试信息
		var $show_errors = true;          //是否显示错误提示

		var $num_queries = 0;             //初始化查询次数
		var $last_query;                  //最后查询记录
		var $col_info;                    //获取字段属性

		var $debug_called;                //显示
		var $vardump_called;              //变量

		var $dbh;                        //数据库连接句柄
		var $func_call;                  //最后调用的方法
		var $result;                     //query返回的结果集
		var $last_result;                //select结果集(数组对象)

		var $num_rows;                   //select返回的记录条数
		var $rows_affected;              //update影响的记录数
		var $insert_id;                  //最后插入的记录id
		/*
		-----------------------------------------------------------
		函数名称:DBSQLite($dbpath, $dbname)
		简要描述:连接到sqlite数据库文件，并且选中数据库以备操作
		输入:mixed(数据库路径，数据库名)
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function DBSQLite($dbpath, $dbname)
		{
			$this->dbh = @sqlite_open($dbpath.$dbname);
			if ( !$this->dbh )
			{
				$this->print_error("Error","<ol><b>错误:不能建立数据库连接</b><li>是否确定输入了正确的路径？<li>是否确定输入了正确的数据库文件名？<li>数据库服务是否被安装？</ol>");
			}
		}//end func

		/*
		-----------------------------------------------------------
		函数名称:select($dbpath, $dbname)
		简要描述:选中一个数据库以备操作
		输入:mixed(数据库路径，数据库名)
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function select($dbpath, $dbname)
		{
			$this->SQLite($dbpath, $dbname);
		}//end func

		/*
		-----------------------------------------------------------
		函数名称:escape($str)
		简要描述:转义一个字符串安全用于 sqlite_query 
		输入:string
		输出:string
		修改日志:------
		-----------------------------------------------------------
		*/
		function escape($str)
		{
			if (!get_magic_quotes_gpc()) {
				$lastname = addslashes($str);
			} else {
				$lastname = $str;
			}
			return sqlite_escape_string($lastname);
		}//end func

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

			//清空缓存..
			$this->flush();

			//记录此函数如何被调用，用于调试...
			$this->func_call = "\$db->query(\"$query\")";

			//跟踪最后查询语句，用于调试..
			$this->last_query = $query;

			//if(!sqlite_complete($query)){
			//	$this->print_error("Invalid Query String Format",$query);
			//	return false;
			//} 查询前进行错误检查，测试中……
			
			//通过mysql_query函数执行查询操作..
			$this->result = sqlite_query($this->dbh,$query);
			$this->num_queries++;
			
			//执行insert, delete, update, replace操作
			if ( preg_match("/^(insert|delete|update|replace)\s+/i",$query) )
			{
					$this->rows_affected = sqlite_changes($this->dbh);
					$return_val = $this->rows_affected;

					//获取最后插入记录id(此id为AUTO_INCREMENT)
					if ( preg_match("/^(insert|replace)\s+/i",$query) )
					{
						$this->insert_id = sqlite_last_insert_rowid($this->dbh);
						//$return_val = $this->insert_id;
					}
			}
			//执行select操作
			else
			{
				//获取字段信息
				$i=0;
				while($name = @sqlite_field_name($this->result,$i))
				{
					$this->col_info[$i++]->name = $name;
				}
				//获取查询结果
				$num_rows=0;
				while ( $row = sqlite_fetch_object($this->result) )
				{
					//取得包含数组的结果对象
					$this->last_result[$num_rows] = $row;
					$num_rows++;
				}
				//获取查询结果行数
				$this->num_rows = $num_rows;
				
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

		}//end func

		/*
		-----------------------------------------------------------
		函数名称:get_row($query=null,$output=OBJECT,$y=0)
		简要描述:从数据库获取一行记录
		输入:string
		输出:---
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

		}//end func

		/*
		-----------------------------------------------------------
		函数名称:get_results($query=null, $output = OBJECT)
		简要描述:从数据库返回多行查询结果
		输入:string
		输出:array
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
			echo "\n\n<b>变量类型:</b> " . ucfirst($var_type) . "\n";
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
			if ( ! $this->debug_called )
			{
				echo "<font color=800080 face=arial size=2><b>SQL</b> (v".SQL_VERSION.") <b>Debug..</b></font>&nbsp;".$this->php_sql()."<p>\n";
			}
			echo "<font face=arial size=2 color=000099><b>查询语句:</b> [$this->num_queries] <b>--</b> ";
			echo "[<font color=000000><b>$this->last_query</b></font>]</font><p>";

				echo "<font face=arial size=2 color=000099><b>查询结果...</b></font>";
				echo "<blockquote>";

			if ( $this->col_info )
			{

				// =====================================================
				// 显示第一行
				echo "<table cellpadding=5 cellspacing=1 bgcolor=555555>";
				echo "<tr bgcolor=eeeeee><td nowrap valign=bottom><font color=555599 face=arial size=2><b>(row)</b></font></td>";

				for ( $i=0; $i < count($this->col_info); $i++ )
				{
					echo "<td nowrap align=left valign=top><font size=1 color=555599 face=arial>{$this->col_info[$i]->type} {$this->col_info[$i]->max_length}</font><br><span style='font-family: arial; font-size: 10pt; font-weight: bold;'>{$this->col_info[$i]->name}</span></td>";
				}

				echo "</tr>";

				// ======================================================
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

				} //如果结果为空
				else
				{
					echo "<tr bgcolor=ffffff><td colspan=".(count($this->col_info)+1)."><font face=arial size=2>No Results...</font></td></tr>";
				}

			echo "</table>";

			} // 如果字段属性为空
			else
			{
				echo "<font face=arial size=2>No Results</font>";
			}

			echo "</blockquote></blockquote><hr noshade color=dddddd size=1>";

			$this->debug_called = true;
		}//end func

		/*
		-----------------------------------------------------------
		函数名称:print_error($str = "")
		简要描述:显示数据库操作错误
		输入:string
		输出:echo or false
		修改日志:------
		-----------------------------------------------------------
		*/
		function print_error($title = "SQL/DB Error", $str = "")
		{
			//设置全局变量$SQL_ERROR..
			global $PHPSEA_ERROR;
			//如果没有截获错误，那么起用sqlite自定义错误..
			if ( !$str )
			{
				$str = sqlite_error_string($this->dbh);
			}
			//把错误付值给全局array..
			/*
			$PHPSEA_ERROR['DBSQLite'] = array
							(
								"query" => $this->last_query,
								"error_str"  => $str
							);
			*/
			//判断是否显示错误输出..
			$PHPSEA_ERROR['DBSQLite'] = $str;
			if ( $this->show_errors )
			{
				print "<blockquote><font face=arial size=2 color=ff0000>";
				print "<b>$title --</b> ";
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
		简要描述:错误显示开关
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
	
		//调用php和数据库版本
		function php_sql()
		{
			$get_version = sqlite_libversion ();
			return "<font color=800080 face=arial size=2><b>ENV:</b>&nbsp;(php ".phpversion()." - SQLite ".$get_version.")</font>";	
		}


	}//end class
//=============================================================================
?>
