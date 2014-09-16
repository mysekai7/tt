<?php
$user=$_POST['user'];
$password = $_POST['pwd'];
if($user && $password) {
echo <<<eot
注册成功您的帐号是 {$user} 密码是: {$password}
<br >
这只是一个演示.若是真的程序还需要你插入数据库。
eot;
}else{
echo '您的帐号或者密码没有写全啊啊。';
}
?>