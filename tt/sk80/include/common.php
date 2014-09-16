<?php
/**
 *  文件:   common.php
 *  说明:   程序核心文件
 *  时间:   2010-1-29 下午
 */

//设置常量
define('STARTING_MICROTIME', get_microtime());

define('SYSTEM_ROOT', substr(dirname(__FILE__), 0, -7));    //以'/'结尾

define('INCLUDE_DIR', SYSTEM_ROOT . 'include/');

define('DATA_DIR', SYSTEM_ROOT . 'data/');

//包含配置文件
$config = SYSTEM_ROOT . 'config.php';
if(file_exists($config))
    require_once($config);
else
    //header('Location: install/'); exit();

//设置错误提示
error_reporting((DEBUG ? E_ALL : 0));

//关闭转义
set_magic_quotes_runtime(0);

//设置session
if (!isset($_SESSION))
    session_start();

//设置默认时区
ini_set('date.timezone', DEFAULT_TIMEZONE);
if(function_exists('date_default_timezone_set'))
    date_default_timezone_set(DEFAULT_TIMEZONE);
else
    putenv('TZ='.DEFAULT_TIMEZONE);

header('Content-Type:text/html;charset=utf8');

/**
 *  应用
 */
class Application
{
    public function __construct()
    {
        $this->setMySQL();
        $this->setSmarty();
        $this->setJobs();
        //setting
    }

    public function setMySQL()
    {
        $mysqli = DBMySQLi::getInstance();
        if(!$mysqli->dbh)
            $this->error('系统繁忙，请稍候再试', '/index.php');
        Record::connection($mysqli);
    }

    public function setSmarty()
    {
        require_once(INCLUDE_DIR.'/lib/Smarty/Smarty.class.php');
        global $tpl;
        $tpl = new Smarty;
        $tpl->template_dir = SYSTEM_ROOT . 'templates';
        $tpl->compile_dir = SYSTEM_ROOT . 'data/templates_c';
        $tpl->assign('app_name', 'simpleblog');
        $tpl->left_delimiter = '<{';
        $tpl->right_delimiter = '}>';

        //
        $tpl->assign('tpl_admin', SITE_URL.'templates/admin/');
        $tpl->assign('tpl_front', SITE_URL.'templates/'.DEFAULT_TEMPLATE.'/');
        $tpl->assign('site_url', SITE_URL);
    }

    public function setJobs()
    {
        global $tpl;
        $jobs = array(
            'article' => '文章',
            'category' => '分类',
            'comment' => '评论'
        );
        $tpl->assign('jobs', $jobs);
        $tpl->assign('job', $this->getJob());
    }

    public function getJob()
    {
        $job = isset($_GET['job']) ? trim($_GET['job']) : '';
        if(strpos($job, '_'))
        {
            $job = explode('_', $job);
            $return = $job[1];
        }
        else
        {
            $return = $job;
        }
        return $return;
    }

    /**
     * 防止频繁刷新
     */
    public function refresh()
    {
        if (isset($_COOKIE['lastrequest'])) {
            list($lastrequest,$lastpath) = explode("\t",$_COOKIE['lastrequest']);
            $onlinetime = $_SERVER['REQUEST_TIME' ] - $lastrequest;
        } else {
            $lastrequest = $lastpath = '';
        }
        $REQUEST_URI  = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

        if ($REQUEST_URI == $lastpath && $onlinetime < 2)
            throw new Exception('请求次数过于频繁了!');

        setcookie('lastrequest', $_SERVER['REQUEST_TIME' ]."\t".$REQUEST_URI);
    }

    /**
     * 程序错误处理
     */
    public function error($msg, $url='')
    {
        global $tpl;
        if(!$url)
            $url = $_SERVER['HTTP_REFERER'];
         $tpl->assign('msg', $msg);
         $tpl->assign('url', $url);
         $tpl->display(DEFAULT_TEMPLATE.'/error.html');
         exit;
    }

    /**
     * 程序成功处理
     */
    public function success()
    {

    }
}

final class Dispatcher
{
    private static $request = array();
    private static $params = array();

    /**
     * 派发
     * 以$_REQUEST为参数 传入ssss
     */
    public static function dispatch()
    {
        self::$request = $_GET;

        return self::executeAction(self::getController(), self::getAction(), self::getParams());
    }

    /**
     * 获得控制器名称
     */
    public static function getController()
    {
        // if(参数 == 'job') 然后从初始化的jobs调用
        return isset(self::$request['job']) && !empty(self::$request['job']) ? self::$request['job'] : 'Index';   //以后改成常量
    }

    /**
     * 获得当前动作
     */
    public static function getAction()
    {
        //控制器中的动作
        return isset(self::$request['action']) && !empty(self::$request['action']) ? self::$request['action'] : 'index';
    }

    /**
     * 获得动作参数
     */
    public static function getParams()
    {
        //此条URL规则作废URL http://sk.com/sk80/index.php?job=archive&action=archive&arg=alias_google-out-china&page=1
        //URL http://sk.com/sk80/index.php?job=archive&action=archive&alias=google-out-china&page=1

        //处理有分页的情况

        $parmas = array();
        $params = self::$request;
        if(array_key_exists('job', $params))
            unset($params['job']);
        if(array_key_exists('action', $params))
            unset($params['action']);
        return self::$params = $params;
    }

    /**
     * 执行动作
     */
    public static function executeAction($controller, $action, $params)
    {
        if(strpos($controller, '_'))
        {
            list($c1, $c2) = explode('_', $controller);
            $controller_class_name = ucwords($c1).ucwords($c2) . 'Controller';
        }
        else
        {
            $controller_class_name = ucwords($controller) . 'Controller';
        }
        $action = $action . 'Action';

        // Get an instance of that controller
        if (class_exists($controller_class_name)) {
            $controller = new $controller_class_name();
        } else {
        }
        if ( ! $controller instanceof Controller) {
            throw new MyException("Class '{$controller_class_name}' does not extends Controller class!");
        }

        // Execute the action
        $controller->execute($action, $params);
    }

}

/**
 * 控制类 只能作为父类
 * 这个类包含一些业务逻辑
 */
class Controller
{
    public function execute($action, $params) {
        if (!method_exists($this, $action))
            throw new MyException("Action '{$action}' is not valid!");

        call_user_func(array($this, $action), $params);
    }
}

class Record
{
    public static $__CONN__ = false;

    final public static function connection($connection) {
        self::$__CONN__ = $connection;
    }

    public function __construct($data=false)
    {
        if (is_array($data))
            $this->setFromData($data);
    }

    public function getColumns()
    {
        return array_keys(get_object_vars($this));
    }

    /**
     * 类成员属性赋值
     */
    public function setFromData($data)
    {
        foreach($data as $key => $val)
        {
            $this->$key = trim($val);
        }
    }

    final public static function tableFromClass($class_name) {
        try {
            if (class_exists($class_name) && defined($class_name.'::TABLE_NAME'))
                return TABLE_PREFIX.constant($class_name.'::TABLE_NAME');
        }
        catch (Exception $e) {
            //return TABLE_PREFIX.Inflector::underscore($class_name);
        }
    }

    /**
     * 针对对象实例
     */
    public function save()
    {
        $value_of = array();

        if(empty($this->id))
        {
            //insert
            $columns = $this->getColumns();

            //转义insert SQL
            foreach($columns as $column)
            {
                if(isset($this->$column) && !empty($this->$column))
                    $value_of[$column] = self::$__CONN__->escape($this->$column);
            }

            $sql = "INSERT INTO ".self::tableFromClass(get_class($this)).' ('.join(',', array_keys($value_of)).") VALUES ('".join("','",array_values($value_of))."')";
            $return = self::$__CONN__->query($sql);
            $this->id = self::$__CONN__->insert_id;

        }
        else
        {
            //update
            $columns = $this->getColumns();
            foreach ($columns as $column) {
                if (isset($this->$column)) {
                    $value_of[$column] = $column."='".self::$__CONN__->escape($this->$column)."'";
                }
            }
            unset($value_of['id']);
            $sql = "UPDATE ".self::tableFromClass(get_class($this)).' SET '.join(',', $value_of).' WHERE id='.$this->id;
            $return = self::$__CONN__->query($sql);

        }

        //日志

        return $return;

    }//end func

    public static function delete($class, $id)
    {
        $sql = "DELETE FROM ".self::tableFromClass($class)." WHERE id = '".self::$__CONN__->escape($id)."'";
        return self::$__CONN__->query($sql);
    }

    public static function deleteBatch($class, $ids)
    {
        $sql = "DELETE FROM ".self::tableFromClass($class)." WHERE id IN (".join(',', $ids).")";
        return self::$__CONN__->query($sql);
    }


    /**
     * 往数据库插入一条数据
     */
    public function insert()
    {
        $value_of = array();

        //insert
        $columns = $this->getColumns();

        //转义insert SQL
        foreach($columns as $column)
        {
            if(isset($this->$column) && !empty($this->$column))
                $value_of[$column] = self::$__CONN__->escape($this->$column);
        }

        $sql = "INSERT INTO ".self::tableFromClass(get_class($this)).' ('.join(',', array_keys($value_of)).") VALUES ('".join("','",array_values($value_of))."')";
        return self::$__CONN__->query($sql);
    }

    /**
     * 从数据库查一行数据出来
     */
    public static function findOneFrom($class, $where, $value)
    {
        /*
        $query = sprintf('select %s from %s where %s = ?',
                         join(', ', $this->getSelectFields()),
                         $this->_table,
                         $field);
                         */
        //$sql = "SELECT * FROM ".TABLE_PREFIX.$table_name." WHERE ".$where." LIMIT 1";

        $sql = sprintf('SELECT * FROM %s WHERE %s LIMIT 1', self::tableFromClass($class), $where);
        $sql = self::quoteInto($sql, $value);
        self::$__CONN__->query($sql);
        if(self::$__CONN__->num_rows == 1)
            return self::$__CONN__->last_result[0];
        return false;
    }

    /**
     * 从数据库获得多条数据
     */
    public static function findAllFrom($class, $where=false, $value=false)
    {
        $sql = "SELECT * FROM ".self::tableFromClass($class).($where ? ' WHERE 1 '.$where : '');
        if($where && $value )$sql = self::quoteInto($sql, $value);
        self::$__CONN__->query($sql);
        if(self::$__CONN__->num_rows > 0)
            return self::$__CONN__->last_result;
        return false;
    }

    //负责对?号的替换及转义
    public static function quoteInto($text, $data)
    {
        if(isset($data) && is_array($data))
        {
            foreach($data as $val)
            {
                $text = preg_replace("/\?{1}/", self::$__CONN__->escape($val), $text, 1);
            }
            return $text;
        }
    }
}//end class


 /**
  * 异常处理类
  */
class MyException extends Exception{}


/**
 * 缓存
 */
class Flash
{

}

function get_microtime()
{
    $time = explode(' ', microtime());
    return doubleval($time[0]) + $time[1];
}

function execution_time() {
    return sprintf("%01.4f", get_microtime() - STARTING_MICROTIME);
}

/**
 *  自动加类库和模块
 */
function __autoload($classname)
{
    //目录扩展 增加/plugins/插件名
    //改进 放到初始化的地方
    $forders = array(
        INCLUDE_DIR . '/controller/',
        INCLUDE_DIR . '/lib/',
        INCLUDE_DIR . '/modle/');

    foreach($forders as $folder)
    {
        $file = $folder . $classname . '.class.php';
        if(file_exists($file)){
            require_once($file);
            return;
        }
    }
}

function go_redirect($url='')
{
    if(!$url)
        $url = $_SERVER['HTTP_REFERER'];
    header("location:$url");
}

function get_request_method() {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
        return 'AJAX';
    else
        return $_SERVER['REQUEST_METHOD'];
}

//关键词处理
function dealWords($str)
{
    $searchword = '';
    preg_match_all ("/([a-z0-9]+)/im",$str, $out, PREG_SET_ORDER);
    foreach ($out as $k=>$v)
    {
        $searchword .= $v[0]." ";
    }
    $searchword = trim($searchword);
    $searchword = strtolower($searchword);
    return $searchword;
}

/*
 * 删除首页的静态页面
 */
function del_cache()
{
    $cache_index = SYSTEM_ROOT.'index.html';
    if(file_exists($cache_index))
        @unlink($cache_index);
}

/**
 * 这个方法将去除魔法转义斜杠， 如果魔法转义打开
 * 自动去除$_GET, $_POST, $_COOKIE
 */
function fix_input_quotes() {
    $in = array(&$_GET, &$_POST, &$_COOKIE);
    while (list($k,$v) = each($in)) {
        foreach ($v as $key => $val) {
            if (!is_array($val)) {
                $in[$k][$key] = stripslashes($val); continue;
            }
            $in[] =& $in[$k][$key];
        }
    }
    unset($in);
} // fix_input_quotes

//如果PHP开启了魔法转移则自动去除转义
if (get_magic_quotes_gpc()) {
    fix_input_quotes();
}

function processContent($sHtml)
{
    return preg_replace_callback('/\[code\s*(?:=\s*((?:(?!")[\s\S])+?)(?:"[\s\S]*?)?)?\]([\s\S]*?)\[\/code\]/i', 'showCode', $sHtml);
}

function showCode($match)
{
    $match[1]=strtolower($match[1]);
    //$match[2] = strip_tags($match[2]);
//            if(!$match[1])$match[1]='plain';
//            $match[2]=preg_replace("/</",'&lt;',$match[2]);
//            $match[2]=preg_replace("/>/",'&gt;',$match[2]);
//            return '<pre class="brush: '.$match[1].';">'.$match[2].'</pre>';
/*
    if($match[1] == 'php') {
        $code = '<?'.$match[1].$match[2].'?>';
    } else {

        $code = trim($match[2]);
    }
 * *
 */
    //$code = str_replace('&lt;br&nbsp;/&gt;&amp;' , '\n',  $code);
    //$code = str_replace('&nbsp;' , ' ',  $code);

    //return '<pre>'.highlight_string($code, true).'</pre>';
    $code = trim($match[2]);
    //$code = strip_tags($code);
    $code = str_replace('&lt;', '<', $code);
    $code = str_replace('&gt;', '>', $code);
    $code = str_replace('&amp;', '&', $code);
    //$code = str_replace('<br />', "\n", $code);
    $code = preg_replace("/<br\s*?\/>/", "\n", $code);
    require_once(INCLUDE_DIR.'/lib/geshi/geshi.php');
    $language = $match[1];
    $code = trim($code);
    $geshi = new GeSHi($code, $language);
    $code = $geshi->parse_code();
    return '<div class="code">'.$code.'</div>';
    //return '<pre class="code">'.highlight_string(trim($code, "\n"), true).'</pre>';


    //$match[2] = str_replace('&lt;', '<', $match[2]);
    //$match[2] = str_replace('&gt;', '>', $match[2]);
    //$match[2] = str_replace('<br />', '\\n', $match[2]);
    //return '<pre>'.highlight_string($match[2], true).'</pre>';


    //return highlight_string($code, true);
}

//自定义异常处理
function my_exception_handler(Exception $e) {
    //print "Uncaught exception of type " . get_class($e) . "\n";
    print($e->getMessage());
    exit;
}

set_exception_handler("my_exception_handler");

//自定义错误处理
function my_error_handler($num, $str, $file, $line) {
    if(error_reporting() == 0) {
        // print " (silenced) ";
        return;
    }


    switch($num) {
        case E_WARNING: case E_USER_WARNING:
            $type = 'Warning';
            break;
        case E_NOTICE: case E_USER_NOTICE:
            $type = 'Notice';
            break;
        default:
            $type = 'Error';
            break;
    }
    $file = basename($file);
    $error = "$type: $file:$line: $str";


    $filename = DATA_DIR.'log/'.date('Ymd').'.log';
    $is_exists = false;
    if(file_exists($filename)) {
        $handle = @fopen($filename, "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle);
                //echo $buffer;
                if(stripos($buffer, $error))
                {
                    $is_exists = true;
                    break;
                }
            }
            fclose($handle);
        }
    }

    if(!$is_exists) {
        $error = date('Y-m-d H:i:s')."\t$type: $file:$line: $str\n";

        $fp = fopen($filename, 'a');
        flock($fp, LOCK_EX);
        fputs($fp, $error);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    @chmod($filename, 0777);

}

set_error_handler("my_error_handler");

/**
 * 输出调试信息.
 *
 * @copyright ninetowns
 * @param mixed $arr 输出对象
 * @param string $title 标题提示信息
 * @param int $T 输出类型
 */
function mprint_r( $arr, $title = '', $T = 0 )
{
	global $count_mprint_r;
	$count_mprint_r++;
	if ( $count_mprint_r == 1 )
	{

		?>
<style type="text/css">
<!--
*{
	margin:0px;
	padding:0px;
}
.m_fileldset {
	margin: 0px;
	padding: 2px;
    background-color: #FFF;
	border: 1px dashed #09c;
	word-break:break-all;
	overflow:auto;
}
.m_legend {
	background-color: #06c;
	margin: 5px;
	padding: 2px;
	border: 1px solid #fff;
	color: #ffe;
	font-weight: bold;
	font-size:12px;
}
.m_button {
	border:1px solid #f96;
	background-color: #ffc;
}
.m_pre {
	text-align:left;
	font-size:11px;
}
-->
</style>
<script>
var m_sign = true;
function m_toggle() {
	var cs = document.getElementsByTagName("pre");
	var r = new Array();
	for(var i = 0;i<cs.length;i++)
	{
		var e = cs[i];
		if("m_pre" == e.className)
		{
			e.style.display = (m_sign == false ? "block" : "none");
			r.push( e);
		}
	}

	var cs = document.getElementsByTagName("button");

	for(var i = 0;i<cs.length;i++)
	{
		var e = cs[i];
		if("m_button" == e.className)
		{
			e.innerHTML = (m_sign == false ? "-" : "+");
		}
	}
	m_sign = !m_sign;
}
</script>
<button onclick="m_toggle()">Expand/Collapse All</button>
<?php
	}
	$temp_name = substr( md5( microtime() . $arr . $title . $T ), 0, 3 );

	?>
<fieldset class="m_fileldset" >
<legend class="m_legend">
<label style="cursor:pointer">
<?=$title?>
<?php
	if ( $arr )
	{

		?>
<button class="m_button" onclick="
	var target = document.getElementById('<?=$temp_name?>');
if (target.style.display != 'none' )
{
  target.style.display = 'none';
  this.innerHTML='+';
}
else
{
  target.style.display = 'block';
  this.innerHTML='-';
}">-</button>
</label>
<?php
	}

	?>
</legend>
<?php

	if ( $arr )
	{

		?>
<pre id="<?=$temp_name?>" class="m_pre"><?php
		if ( 0 == $T )
		{
			print_r ( $arr );
		}
		else
		{
			var_export ( $arr );
		}

		?>
</pre>
<?php
	}

	?>
</fieldset>
<?php
}

//clode tag
function tagClouds($count, $maxcount, $mincount) {
    $maxsize = 25;
    $minsize = 11;
    $offset = ($maxsize-$minsize)/($maxcount-$mincount);
    $weight = ($count-$mincount)*$offset + $minsize;
    return $weight;
}

/**
 * 获取用户真实 IP
 *
 * @Return: string
 */
function getIP()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }

    return $realip;
}