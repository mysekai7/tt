<?php
/*
查看代码执行结果

break跳出代码片段，结束循环
ccontinue结束当前片段，继续下一次循环
exit 结束整个PHP代码
*/


$i = 1;
while (true) { // 这里看上去这个循环会一直执行
	if ($i==2) {// 2跳过不显示
		$i++;
		continue;
	} else if ($i==5) {// 但到这里$i=5就跳出循循环了
		break;
	} else {
		echo $i . '<br>';
	}
	$i++;
}
exit;

echo '这里不输出';
?>