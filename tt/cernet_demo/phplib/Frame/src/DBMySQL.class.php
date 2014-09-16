<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:MySQL.class.php
- 原作者:Justin Vincent
- 整理者:indraw
- 编写日期:2004/10/1
- 简要描述:常用mysql数据库操作类集,版权属于原作者。indraw只进行了汉化
-          和规范化,同时进行了3处bug的修复.
- 运行环境:php4或5,mysql版本要 < 4.10.
- 修改记录:2004/10/1,indraw,程序创立
---------------------------------------------------------------------
*/

/*
	$db = new DBMySQL("root", "", "test", "localhost");
*/
/*
	DBMySQL($dbuser, $dbpassword, $dbname, $dbhost)   //初始化数据库连接
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
	define("SQL_VERSION","1.0");        //此类库版本
	define("OBJECT","OBJECT",true);     //预定义对象变量
	define("ARRAY_A","ARRAY_A",true);   //预定义关联数组
	define("ARRAY_N","ARRAY_N",true);   //预定义数组

//-----------------------------------------------------------------------------
	class DBMySQL 
	{

		var $debug_all = false;         //是否显示调试信息
		var $show_errors = false;        //是否显示错误提示
		var $save_log = false;           //是否记录更新数据库操作

		var $escape_all = false;           //是否记录更新数据库操作

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
		函数名称:DBMySQL($dbuser, $dbpassword, $dbname, $dbhost)
		简要描述:连接到数据库服务器,并且选中数据库以备操作
		输入:mixed (用户名,密码,数据库名,服务器名)
		输出:void
		修改日志:------
		-----------------------------------------------------------
		*/
		function DBMySQL($dbuser, $dbpassword, $dbname, $dbhost)
		{
			$this->dbh = @mysql_connect($dbhost, $dbuser, $dbpassword);
			if ( !$this->dbh )
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
			if ( !@mysql_select_db($db, $this->dbh) )
			{
				$this->print_error("<ol><b>错误:不能选择数据库<u>$db</u>!</b><li>是否确定选定的数据库是否存在？<li>是否确定有一个有效的数据库连接？</ol>");
			}
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
			if (!get_magic_quotes_gpc() or $this->escape_all) {
				//echo("1");
				$lastname = mysql_escape_string($str);
			} else {
				//echo("2");
				$lastname = $str;
			}
			return $lastname;
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

			//记录此函数如何被调用,用于调试...
			$this->func_call = "\$db->query(\"$query\")";

			//跟踪最后查询语句,用于调试..
			$this->last_query = $query;

			//通过mysql_query函数执行查询操作..
			$this->result = @mysql_query($query,$this->dbh);

			//记录查询次数,用于调试...
			$this->num_queries++;

			//如果有查询错误,跳出执行,并且显示错误...
			if ( @mysql_error($this->dbh) )
			{
				$this->print_error();
				return false;
			}
			
			//执行insert, delete, update, replace操作
			if ( preg_match("/^(insert|delete|update|replace)\s+/i",$query) )
			{
				//获取操作所影响的记录行数
				$this->rows_affected = mysql_affected_rows($this->dbh);
				$return_val = $this->rows_affected;

				//获取最后插入记录id(此id为AUTO_INCREMENT)
				if ( preg_match("/^(insert|replace)\s+/i",$query) )
				{
					$this->insert_id = mysql_insert_id($this->dbh);	
					//$return_val = $this->insert_id;
				}
				$this->save_log ? $this->sql_log("success") : null ;
			}
			//执行select操作
			else
			{
				//获取字段信息
				if( $this->debug_all )
				{
					$i=0;
					while ($i < @mysql_num_fields($this->result))
					{
						$this->col_info[$i] = @mysql_fetch_field($this->result);
						$i++;
					}
				}
				
				//获取查询结果
				$num_rows=0;
				while ( $row = @mysql_fetch_object($this->result) )
				{
					//取得包含数组的结果对象
					$this->last_result[$num_rows] = $row;
					$num_rows++;
				}
				@mysql_free_result($this->result);

				//获取查询结果行数
				$this->num_rows = $num_rows;
				
				//返回选中的结果行数
				$return_val = $this->num_rows;
			}

			//是否显示所有的查询信息
			$this->debug_all ? $this->debug() : null ;

			return $return_val;

		}

		/*
		-----------------------------------------------------------
		函数名称:get_var($query=null,$x=0,$y=0)
		简要描述:从数据库获取单个变量
		输入:mixed (查询语句,列数,行数)
		输出:string (字段内容)
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_var($query=null,$x=0,$y=0)
		{

			//记录此函数如何被调用,用于调试...
			$this->func_call = "\$db->get_var(\"$query\",$x,$y)";

			//如果有查询语句那么查询,否则启用缓存...
			if ( $query )
			{
				$this->query($query);
			}
			//根据x,y参数从缓存结果集中获取变量
			if ( $this->last_result[$y] )
			{
				$values = array_values(get_object_vars($this->last_result[$y]));
			}
			//如果获取值,那么返回,否则返回空.
			return (isset($values[$x]) && $values[$x]!=='')?$values[$x]:null;
		}

		/*
		-----------------------------------------------------------
		函数名称:get_row($query=null,$output=OBJECT,$y=0)
		简要描述:从数据库获取一行记录
		输入:mixed (查询语句,返回类型,行数)
		输出:array (按照要求返回：对象,关联数组,数字数组)
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_row($query=null,$output=OBJECT,$y=0)
		{

			//记录此函数如何被调用,用于调试...
			$this->func_call = "\$db->get_row(\"$query\",$output,$y)";

			//如果有查询语句那么查询,否则启用缓存...
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
			//如果输出非法,显示错误..
			else
			{
				$this->print_error(" \$db->get_row(string query, output type, int offset) -- 输出类型必须是: OBJECT, ARRAY_A, ARRAY_N");
			}
		}


		/*
		-----------------------------------------------------------
		函数名称:get_row_mem($query=null,$time, $output = OBJECT,$y)
		简要描述:对从数据库获取一行记录进行缓存
		输入:mixed (查询语句,返回类型)
		输出:$data (按照要求返回对象,关联数组,数字数组)
		修改日志:------
		-----------------------------------------------------------
		
		function get_row_mem($query=null,$time=LIMIT_SECOND_CACHE, $output = OBJECT,$y=0) 
		{
			global $oMemCache;
			$key = md5($query);
			if(!($data=$oMemCache->get($key)) )
			{
				$data = $this->get_row($query,$output,$y);
				$oMemCache->add($key,$data,false,$time);
			}
			//$states = $oMemCache->getStats();
			//print_r($states);
			//$memcache = memcache_connect('memcache_host', 11211);getServerStatus
			//echo $oMemCache->getServerStatus($oMemCache, 'memcache_host');
			return $data;
		}
		*/

		/*
		-----------------------------------------------------------
		函数名称:get_col($query=null,$x=0)
		简要描述:从数据库获取一列记录（参数x为第几列）
		输入:mixed (查询语句,第几列)
		输出:array
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_col($query=null,$x=0)
		{

			//如果有查询语句那么查询,否则启用缓存...
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
		输入:mixed (查询语句,返回类型)
		输出:array (按照要求返回对象,关联数组,数字数组)
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_results($query=null, $output = OBJECT)
		{

			//记录此函数如何被调用,用于调试...
			$this->func_call = "\$db->get_results(\"$query\", $output)";

			//如果有查询语句那么查询,否则启用缓存...
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
			}//end elseif
		}


		/*
		-----------------------------------------------------------
		函数名称:get_results_mem($query=null, $output = OBJECT)
		简要描述:从数据库返回多行查询结果
		输入:mixed (查询语句,返回类型)
		输出:array (按照要求返回对象,关联数组,数字数组)
		修改日志:------
		-----------------------------------------------------------
		
		function get_results_mem($query=null,$time=LIMIT_SECOND_CACHE_LOG, $output = OBJECT)
		{
			global $oMemCache;
			$key = md5($query);
			if(!($new_array=$oMemCache->get($key)) )
			{
				$new_array = $this->get_results($query,$output);
				$oMemCache->add($key,$new_array,false,$time);
			}
		}
		*/
		/*
		-----------------------------------------------------------
		函数名称:query_results_mem($query=null,$time, $output = OBJECT)
		简要描述:对从数据库返回多行查询结果进行缓存
		输入:mixed (查询语句,返回类型)
		输出:$data (按照要求返回对象,关联数组,数字数组)
		修改日志:------
		-----------------------------------------------------------
		*/
		function query_results_mem($query=null,$time=LIMIT_SECOND_CACHE_LOG, $output = OBJECT) 
		{
			global $oMemCache;
			$key = md5($query);
			if(!($data=$oMemCache->get($key)) )
			{
				$data = $this->query_results($query,$output);
				$oMemCache->add($key,$data,false,$time);
			}
			return $data;
		}

		/*
		-----------------------------------------------------------
		函数名称:get_page_results($query=null, $output = OBJECT)
		简要描述:从数据库返回多行查询结果
		输入:mixed (查询语句,返回类型)
		输出:array (按照要求返回对象,关联数组,数字数组)
		修改日志:------
		-----------------------------------------------------------
		*/
		function get_page_results($query=null, $output = OBJECT)
		{
			//记录此函数如何被调用,用于调试...
			$this->func_call = "\$db->get_page_results(\"$query\", $output)";
			$query = preg_replace("/\n|\n\r|\s/"," ",$query);
			preg_match("~select(.*?) from (.*) limit ~ims",$query,$m);
			//var_export($m);
			//print_r($m);
			$total_query = "SELECT COUNT(*) FROM {$m[2]}";
			$this->num_rows_all = $this->get_var($total_query,$x=0,$y=0);
			return $this->get_results($query, $output);

		}

		/*
		-----------------------------------------------------------
		函数名称:get_page_results_mem($query=null, $output = OBJECT)
		简要描述:对从数据库返回多行查询结果进行缓存
		输入:mixed (查询语句,返回类型)
		输出:array (按照要求返回对象,关联数组,数字数组)
		修改日志:------
		-----------------------------------------------------------
		
		function get_page_results_mem($query=null,$time=LIMIT_SECOND_CACHE_LOG, $output = OBJECT)
		{
			global $oMemCache;
			$key = md5($query);
			if(!($data=$oMemCache->get($key)) )
			{
				$data = $this->get_page_results($query,$output);
				$oMemCache->add($key,$data,false,$time);
			}
			return $data;
		}
*/
		/*
		-----------------------------------------------------------
		函数名称:get_col_info($info_type="name",$col_offset=-1)
		简要描述:获取最后查询结果的字段信息
		输入:mixed (字段信息属性,第几个字段)
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
		简要描述:将对象,数组变量以易于理解的格式显示在屏幕上
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
		}// end func

		/*
		-----------------------------------------------------------
		函数名称:debug()
		简要描述:显示最后一条数据库查询语句；显示一个查回的数据表.
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
				}//如果结果为空
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
		function print_error($str = "")
		{
			global $PHPSEA_ERROR;
			//如果没有截获错误,那么起用mysql自定义错误..
			if ( !$str )
			{
				$str = @mysql_error($this->dbh);
				$error_no = @mysql_errno($this->dbh);
			}
			$this->save_log ? $this->sql_log($str) : null ;

			//把错误付值给全局array..
			/*
			$PHPSEA_ERROR['DBMySQL'] = array 
							(
								"query"      => $this->last_query,
								"error_str"  => $str,
								"error_no"   => $error_no
							);
			*/
			$PHPSEA_ERROR['DBMySQL'] .= $str;
			//判断是否显示错误输出..
			if ( $this->show_errors )
			{
				echo "<blockquote>";
				if ( ! $this->debug_called )
				{
					echo "<font color=800080 face=arial size=2><b>SQL</b> (v".SQL_VERSION.") <b>Debug..</b></font>&nbsp;".$this->php_sql()."<p>\n";
				}
				print "<font face=arial size=2 color=ff0000>";
				print "<b>错误语句: </b>[$this->num_queries]<b> --</b> ";
				print "[<font color=000077>$this->last_query</font>]";
				print "<br><b>错误提示: --</b> ";
				print "[<font color=000077>$str</font>]";
				print "</font></blockquote><hr noshade color=dddddd size=1>";
			}
			else
			{
				return false;	
			}
		}//end func

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
			$sMonth = date("Ym",time());
			$sTodayDir = LOG_DIR."mysql/".$sMonth;
			$sToday = "sqlog".date("Y-m-d",time());
			$sFileName = $sTodayDir."/".$sToday.".xml";
			if( !file_exists($sTodayDir))
			{
				@mkdir($sTodayDir, 0777);
			}

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
			$aContent[] = "\t<user>".$_SESSION['email']."</user>";
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
		function php_sql()
		{
			$get_version = @mysql_get_server_info();
			return "<font color=800080 face=arial size=2><b>ENV</b>&nbsp;(php ".phpversion()." - MySQL ".$get_version.")</font>";	
		}

	}//end class
//=============================================================================
?>
