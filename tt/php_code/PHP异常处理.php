<?php
/**
* PHP异常处理
*
* PHP 5 添加了类似于其它语言的异常处理模块。在 PHP 代码中所产生的异常可被 throw
* 语句抛出并被 catch 语句捕获。需要进行异常处理的代码都必须放入 try 代码块内，以
* 便捕获可能存在的异常。每一个 try 至少要有一个与之对应的 catch。使用多个 catch
* 可以捕获不同的类所产生的异常。当 try 代码块不再抛出异常或者找不到 catch 能匹配
* 所抛出的异常时，PHP 代码就会在跳转到最后一个 catch 的后面继续执行。当然，PHP
* 允许在 catch 代码块内再次抛出（throw）异常。
* 当一个异常被抛出时，其后（译者注：指抛出异常时所在的代码块）的代码将不会继续
* 执行，而 PHP 就会尝试查找第一个能与之匹配的 catch。如果一个异常没有被捕获，而
* 且又没用使用 set_exception_handler() 作相应的处理的话，那么 PHP 将会产生一
* 个严重的错误，并且输出 Uncaught Exception ... （未捕获异常）的提示信息。
*/
?>
<?php
/**
* Exception.php
*
* ■㈡PHP5内置的异常类的属性与方法
* 以下这段代码只为说明内置异常处理类的结构，它并不是一段有实际意义的可用代码。
*/

class Exception{
protected $message = 'Unknown exception'; // 异常信息
protected $code = 0; // 用户自定义异常代码
protected $file; // 发生异常的文件名
protected $line; // 发生异常的代码行号

function __construct($message = null, $code = 0);
final function getMessage(); // 返回异常信息
final function getCode(); // 返回异常代码（代号）
final function getFile(); // 返回发生异常的文件名
final function getLine(); // 返回发生异常的代码行号
final function getTrace(); // backtrace() 数组
final function getTraceAsString(); // 已格成化成字符串的 getTrace() 信息

//可重载的方法
function __toString(); // 可输出的字符串
}

?>

<?php
/**
* syntax .php
*/

//语法结构以及分析

//PHP有两种抛出异常的格式,如下

//【1】try...catch...
try {
//实行可能有异常的操作，比如数据库错作，文件错作
}catch (Exception $e){
//打印错误信息
}

//【2】throw
$message='我必须被运行在try{}块中，出现异常的话我($message)将被返回(传递)给catch()里的异常对象的实例比如上面的$e';
$code=123; //错误代码号，可在catch块中用$e->getCode();返回我的值 123，这样我就可以自定义错误代码号

throw new Exception($message,$code);

//学JAVA的注意，PHP异常处理没有throws

?>
<?php
/**
* Example.php
*/
//两个实例掌握PHP异常处理


//例【1】用 try...catch
/* PDO连接mysql数据库,如果没看过PDO，先看下PDO的构造函数，要不跳过例1看例2 */
$dsn = 'mysql:host=localhost;dbname=testdb';
$user = 'dbuser';
$password = 'dbpass';

try {
$dbh = new PDO($dsn, $user, $password); //创建数据库连接对象容易出现异常
echo '如果上面出现异常就不能显示我了';
} catch (PDOException $e) {
echo 'Connection failed: ' . $e->__toString();
}
?>

<?php
//例[2] try..cathc 和 throw一起用
try {
$error = '我抛出异常信息，并且跳出try块';
if(is_dir('./tests')){
      echo 'do sth.';
}else{
   throw new Exception($error,12345);
}
echo '上面有异常的话就轮不到我了！～<br />',"\n";
} catch (Exception $e) {
echo '捕获异常: ', $e->getMessage(),$e->getCode(), "\n<br />"; //显示$error和123456
}
echo '继续执行';
?>
