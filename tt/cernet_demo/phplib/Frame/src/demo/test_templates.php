<?
//简单smarty实现测试
//by indraw
//2004/11/16早晨

include_once( "./templates.inc.php" );

//-----------------------------------------------------------------------------
//初始化
$PS["template"] = "default";
$PS["cache"]    = "./templates_c";

//显示变量测试
$PS["DATA"]["hello"] = "你好";

//循环测试
for($i=0; $i<5; $i++){
	$getNum["getNewNum"] = $i+100;
	$PS["DATA"]["TEST2"][] = $getNum;
}
//echo "<pre>";
//var_dump($PS["DATA"]["TEST2"]);
//echo "</pre>";
//条件判断
$PS["DATA"]["testIf"] = "ok";

//测试包含
include get_template("index");




//-----------------------------------------------------------------------------
?>