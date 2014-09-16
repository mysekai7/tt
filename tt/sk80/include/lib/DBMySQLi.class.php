<?php
class DBMySQLi
{
    static private $instance;

    public $debug_all = 0; //是否显示调试信息
    public $show_errors = true; //是否显示错误信息

    public $num_queries = 0; //初始化连接次数
    public $last_query; //最后查询记录
    public $col_info; //字段属性

    public $debug_called; //判断是否已经debug输出

    public $dbh; //数据库连接句柄
    public $result; //query返回的结果集
    public $last_result; //select结果集 数组集合

    public $num_rows; //select返回记录条数
    public $rows_affected; //update影响的记录数
    public $insert_id; //最后插入的记录ID

    public $query_start;    //调试用的请求开始时间

    function __construct($db_host, $db_user, $db_pass, $db_name)
    {
        @$this->dbh = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if( mysqli_connect_errno() )
        {
            echo '<pre style="font-size:11px;">';
            echo "\r\nConnect: " . $db['host'];
            echo "\r\nInfo: ".mysqli_connect_error();
            echo "\r\nCode: ".mysqli_connect_errno();
            echo '</pre>';
            exit();
        }
        $this->dbh->query('set names utf8');
    }

    static function getInstance()
    {
        if(!isset(self::$instance))
        {
            self::$instance = new DBMySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
        return self::$instance;
    }

    //清空缓存
    function flush()
    {
        $this->last_result = null;
        $this->last_query = null;
        $this->query_start = null;
        $this->col_info = null;
    }

    //query 在执行开始与结尾处增加时间差
    function query($query)
    {
        $query = trim($query);

        //返回值
        $return_val = 0;

        //清空缓存
        $this->flush();

        //是否显示所有查询信息
        if($this->debug_all)
            $this->query_start = $this->get_microtime();

        //记录最后查询语句, 用于调试
        $this->last_query = $query;

        //获得查询结果
        $this->result = @$this->dbh->query($query);

        //记录查询次数
        $this->num_queries++;

        //如果有错误, 跳出执行, 并显示错误
        if( $this->dbh->errno )
        {
            $this->print_error();
            return false;
        }

        //执行insert, delete, update, replace操作
        if(preg_match("/^(insert|delete|update|replace)\s+/i", $query))
        {
            //获取操作影响的记录数
            $this->rows_affected = $this->dbh->affected_rows;
            $return_val = $this->rows_affected;

            //获取最后插入ID(此ID为auto_increment)
            if(preg_match("/^(insert|replace)\s+/i", $query))
            {
                $this->insert_id = $this->dbh->insert_id;
            }
        }
        //执行select操作
        else if(preg_match("/^(select)\s+/i", $query))
        {
            //获取字段信息
            if($this->debug_all)
            {
                $i = 0;
                while($i < @$this->result->field_count)
                {
                    //获得包含数组的结果对象
                    $this->col_info[$i] = @$this->result->fetch_field();
                    $i++;
                }
            }

            //获取查询结果
            $num_rows = 0;
            //while($row = @$this->result->fetch_array( MYSQLI_ASSOC )) //MYSQLI_BOTH, MYSQLI_ASSOC, MYSQLI_NUM
            while($row = $this->result->fetch_object())
            {
                //获得包含数据的关联数组
                $this->last_result[$num_rows] = $row;
                $num_rows++;
            }
            $this->result->free_result();
            $this->num_rows = $num_rows;

            //返回结果函数
            $return_val = $this->num_rows;
        }

        //是否显示所有查询信息
        $this->debug_all ? $this->debug() : null;

        return $return_val;
    }

    /**
     * 
     */
    function get_page_results($query=null)
    {
        $query = preg_replace("/\n|\n\r|\s/", " ", $query);
        preg_match("~select(.*?) from (.*) limit ~ims", $query, $m);
        //print_r($m);
        $total_query = "SELECT COUNT(*) FROM {$m[2]}";
        $this->num_rows_all = $this->get_var($total_query,$x=0,$y=0);
        $this->query($query);
    }

    function get_var($query=null, $x=0, $y=0)
    {
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
        return (isset($values[$x]) && $values[$x]!=='') ? $values[$x] : null;
    }

    /**
     * 函数名称： escape()
     * 简要描述： sql语句转义
     */
    public function escape($str)
    {
//        if (!get_magic_quotes_gpc())
//        {
            //var_dump('magic closed');
            $string = $this->dbh->real_escape_string($str);
//        }
//        else
//        {
//            $string = $str;
//        }
        return $string;
    }

    /**
     * 函数名称: debug();
     * 简要描述: 显示最后一条查询语句; 显示一个返回结果表
     */
    function debug()
    {

        echo "<blockquote style='font-family:Arial; text-align:left; margin:15px 40px;'>";
        // 只显示一次头信息.
        if ( ! $this->debug_called )
        {
            echo "<p style='margin:15px 0; color:#800080; font-size:12px;><b>SQL</b><b>Debug..</b>&nbsp;".$this->php_sql()."</p>";
        }
        echo "<p style='margin:15px 0; color:#000099; font-size:12px;'><b>查询语句:</b> [$this->num_queries] <b>--</b> ";
        echo "[<span style='color:#000000;'><b>$this->last_query</b></span>]</p>";
        echo "<p style='font-size:12px; color:#000099;'><b>查询结果...</b></p>";
        echo "<blockquote style='margin:15px 40px;'>";
        if ( $this->col_info )
        {
            // --------------------------------------------------
            // 显示第一行
            echo "<table style='border:1px solid #000; background:#555; border-collapse:collapse; border-spacing:0;'>";
            echo "<tr style='background:#eee;'><td style='padding:0.5em; border:1px solid #000; vertical-align:bottom;' nowrap><span style='color:#555999; font-size:12px;'><b>(row)</b></span></td>";

            for ( $i=0; $i < count($this->col_info); $i++ )
            {
                echo "<td style='padding:0.5em; border:1px solid #000; text-align:left; vartical-align:top;' nowrap><p style='font-size:10px; color:#555999;'>{$this->col_info[$i]->type}:{$this->col_info[$i]->max_length}</p><span style='font-size: 12px; font-weight: bold;'>{$this->col_info[$i]->name}</span></td>";
            }
            echo "</tr>";
            // --------------------------------------------------
            // 显示查询结果
            if ( $this->last_result )
            {
                $i=0;
                foreach ( $this->last_result as $one_row )
                {
                    $i++;
                    echo "<tr style='background:#fff;'><td style='padding:0.5em; border:1px solid #000; background:#EEE; vertical-align:middle;' nowrap><span style='font-size:12px; color:#555999;'>$i</span></td>";
                    foreach ( $one_row as $item )
                    {
                        echo "<td style='padding:0.5em; border:1px solid #000;' nowrap><span style='font-size:12px;'>$item</span></td>";
                    }
                    echo "</tr>";
                }
            //---------------------------------------------------
            }//如果结果为空
            else
            {
                echo "<tr style='background:#FFF;'><td style='padding:0.5em; border:1px solid #000;' colspan=".(count($this->col_info)+1)."><span style='font-size:12px;'>No Results</span></td></tr>";
            }
        echo "</table>";

        }//如果字段属性为空
        else
        {
            echo "No Results";
        }
        echo "</blockquote>";
        echo "<p style='margin:15px 0; color:#000099; font-size:12px;'><b>消耗时间:</b> [". $this->execution_time() ."]</p>";
        echo "</blockquote><hr noshade color=dddddd size=1>";
        if($this->execution_time() > 1)
        {
            $fplog = fopen("/data/log/mysql-".date("Ymd").".log","a");
            if($fplog)
            {
                $time = $this->execution_time();
                $str = "query sql --".$this->last_query."\n";
                $str .= "time --".$time."\n";
                $str .= "time now --".date("Y-m-d H:i:s")."\n\n";
                fwrite($fplog,$str);
                fclose($fplog);
            }
        }
        $this->debug_called = true;

    }

    //打印错误信息
    public function print_error($str = '')
    {
        if(!$str)
        {
            $str = $this->dbh->error;
            $error_no = $this->dbh->errno;
        }

        if( $this->show_errors )
        {
            echo "<blockquote style='margin:15px 40px;'>";
            print "<p style='font-size:12px; color:#FF0000;'>";
            print "<b>错误语句: </b>[$this->num_queries]<b> --</b> ";
            print "[<span style='color:#000077;'>$this->last_query</span>]";
            print "</p><p style='margin:15px 0;'><b>错误提示: --</b> ";
            print "[<span style='color:#000077;'>$str</span>]</p>";
            print "</blockquote><hr noshade color=dddddd size=1>";
        }
        else
        {
            return false;
        }
    }//end func

    public function show_errors()
    {
        $this->show_errors = true;
    }

    function execution_time() {
        return sprintf("%01.4f", $this->get_microtime() - $this->query_start);
    }

    function get_microtime()
    {
        $time = explode(' ', microtime());
        return doubleval($time[0]) + $time[1];
    }

    public function show_debug()
    {
        $this->debug_all = true;
    }
    public function php_sql()
    {
        $get_version = @$this->dbh->server_info;
        return "<font color=800080 face=arial size=2><b>ENV</b>&nbsp;(php ".phpversion()." - MySQL ".$get_version.")</font>";
    }
    public function __destruct()
    {
        @$this->dbh->close();
    }
}