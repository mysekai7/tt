<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>ClassFileCsv.php使用演示</title>
<style type='text/CSS'>
body { 
font-family: "Arial"; font-size: 12px; 
}
pre { 
background-color:EDEDED; color:black ; 
font-size: 12px;padding:10px 10px 10px 10px;
}
</style>
</head>
<body>

<?php
//测试csv文件操作类
//by indraw
//2004/11/4

//-----------------------------------------------------------------------------
//控制器
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$vars = strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? $_GET : $_POST;
require('FileCsv.class.php') ;
$phase = $vars['phase'] ;
$db = new FileCsv();
//$db->dir = "./";
// $db->crypt_key = 'EncryptionKey';		//将所有数据进行加密解密
//echo "ClassFileCsv.php";

if (!isset($phase) || ($phase == '0')) {
	phase0($db);
} elseif ($phase == '1') {
	phase1($db);
} elseif ($phase == '2') {
	phase2_3($db, $var);
} elseif ($phase == '3') {
	phase2_3($db, $var, true);
} elseif ($phase == '4') {
	phase4($db, $vars);
} elseif ($phase == '5') {
	phase5($db, $_SERVER);
} 
//if ($PHPSEA_ERROR)  echo "出现错误，呵呵。$PHPSEA_ERROR[FileCsv_Error]";
//-----------------------------------------------------------------------------
//获取一个数组，并为写入文件做准备；
function phase0($db)
{
	echo "<H2>实例 0 - 填充一个二维数组</H2>";

	$db->new_rows = getdata();
	$db->append();
	echo "<pre>";
	print_r($db->db) ;
	echo "</pre>";
	echo "填充一个二维数组，为写入csv文件做准备。<br />\n";
	echo "<a href='$PHP_SELF?phase=1' > 下一步</a>\n";

	endpage();
} 
//-----------------------------------------------------------------------------
//将一个二维数组写入文件，并读出显示。
function phase1($db)
{
	global $PHPSEA_ERROR;
	echo "<H2> 实例 1 - 将上一步的数组写入csv文件</H2>";
	$db->new_rows = getdata();
	$db->append();
	if ($db->write_csv(false, true)) { // Write database to file forcing overwrite/ file creation
		echo "<pre>";
		$cf = "$db->dir/$db->data_file";
		readfile($cf) ;
		echo "</pre>";
		echo "将一个二维数组写入文件，并读出显示。<br />\n";
		echo "<a href='$PHP_SELF?phase=0' > 上一步</a>\n&nbsp;&nbsp;&nbsp;";
		echo "<a href='$PHP_SELF?phase=2' > 下一步</a>\n";
	} else {
		echo "<Hr>".$PHPSEA_ERROR['FileCsv_Error'];
	} 
	endpage();
} 
//-----------------------------------------------------------------------------
//
function phase2_3($db, $var, $i = false)
{
	$db->assoc = $i; 
	// $db->crypt_key = ($var['cryp'] == 'on' ? 'Encryption' : false) ; // key is 'Encryption'
	echo "<H2> 实例 " . ($i?"3":"2") . " - 从csv文件中读出数据 - " . ($i?"关联":"数字") . " 索引</H2>";
	if ($db->read_csv()) {
		$row = 0;
		echo "<table>";
		foreach($db->db as $d) {
			echo "<tr>\n";
			echo "<td>\n";
			echo sprintf("%8s", "Row  " . $row++ . "  ");
			echo "</td>\n";
			while (list($key, $val) = each($d)) {
				echo "<td>";
				echo sprintf("%8s", $key) . "=>" . sprintf("%12s", $val);
				echo "</td>\n";
			} // while
			echo "</tr>\n";
		} 
		// echo "<tr><td> Encryption</td>";//
		// echo "<td> <input type='checkbox' name='cryp'></td>";
		// echo "</tr>";
		echo "</table>\n";
		echo "<pre>";
		echo "\n";
		$cf = "$db->dir/$db->data_file";
		readfile($cf) ;
		echo "</pre>";
		if ($i) {
			echo "从csv文件中读出数据， \n";
			echo "并以关联数组格式显示。<br />\n";
			echo "注意：存在文件中的数组少了一行，因为第一行被用来存储索引。<br />";
			echo "<a href='$PHP_SELF?phase=2' > 上一步</a>\n&nbsp;&nbsp;&nbsp;";
			echo "<a href='$PHP_SELF?phase=4' > 下一步</a>\n";
		} else {
			echo "从csv文件中读出数据， \n";
			echo "并以数字数组格式显示。 <br />\n";
			echo "<a href='$PHP_SELF?phase=1' > 上一步</a>\n&nbsp;&nbsp;&nbsp;";
			echo "<a href='$PHP_SELF?phase=3' > 下一步</a>\n";
		} 
	} else {
		echo $PHPSEA_ERROR['FileCsv_Error'];
	} 
	endpage();
} 
//-----------------------------------------------------------------------------
//文件查找，更新，删除操作
function phase4($db, $vars)
{
	$db->assoc = true;
	echo "<H2> 实例 4 - 查找，更新，删除</H2>";
	if ($db->read_csv()) { // read DB file
		// 
		$findKey = $vars['fk'];
		$findVal = $vars['fv'];
		$newVal = $vars['nv'];
		$a = array($findKey => $findVal) ; //注意：索引可能是数字
		
		// 按照and查询，如果想使用or，请自己写：）,
		$b = $db->find($a); 
		//查找操作
		if ($vars['fnd']) { // basic find routine
			echo "查找键：<span class='b'>$findKey</span> 值： <span class='b'>$findVal</span><br /> ";
			if ($b) {
				reset($b);
				while (list($key, $val) = each ($b)) {
					// echo "找到一行 <span class='b'>$val</span><br />\n";
					echo "<style type='text/css' > #ID" . $val . "_" . $findKey . " {background-color:aqua;border:blue solid 1px;} </style>\n";
				} // while
			} else {
				echo "没有找到！";
			} 
		} 
		if ($vars['upd']) { 
			if ($b) {
				while (list($key, $val) = each($b)) {
					if ($db->update($val, array($findKey => $newVal))) {
						// echo "Replaced <span class='b'>$findVal</span> with <span class='b'>$newVal</span> in row <span class='b'>" . $b['0'] . "</span><br />";
						echo "<style type='text/css' > #ID" . $val . "_" . $findKey . " {background-color:yellow;border:blue solid 1px;} </style>\n";
					} else {
						$PHPSEA_ERROR['FileCsv_Error'];
					} 
				} 
			} 
		} 
		if ($vars['del']) { // find all matching values and process them all - in reverse order !
			if ($b) {
				rsort($b);
				reset($b);
				while (list($key, $val) = each($b)) {
					if ($db->delete($val)) {
						echo "删除 <span class='b'>" . $b['0'] . "</span> 根据键 <span class='b'>$findKey</span> 值 <span class='b'>$findVal</span><br />";
					} else {
						echo $PHPSEA_ERROR['FileCsv_Error'];
					} 
				} // while
			} 
		} 

		//将更新后的文件写入
		if ($vars['cbx'] == 'on') {
			if ($db->write_csv()) {
				echo "文件写入";
			} else {
				echo $PHPSEA_ERROR['FileCsv_Error'];
			} 
		} 
		$row = 0;
		echo "<form>";
		echo "<input type = 'hidden' name ='phase' value = '4' >";
		echo "<table>";
		foreach($db->db as $d) {
			echo "<tr>\n";
			echo "<td>\n";
			echo sprintf("%8s", "Row  " . $row . "  ");
			echo "</td>\n";
			// $cl = 0;
			while (list($key, $val) = each($d)) {
				echo "<td id='ID" . $row . "_" . $key . "'>"; 
				// $cl++;
				echo sprintf("%8s", $key) . "=>" . sprintf("%12s", $val);
				echo "</td>\n";
			} // while
			$row++;
			echo "</tr>\n";
		} 
		echo "<tr><td colspan=6><hr /></td></tr>";
		echo "<tr>";
		echo "<td colspan =2 class ='l'>";
		echo "<input type = 'submit' name='fnd' value = ' 查找 ' >";
		echo "</td>\n";
		echo "<td colspan =1 class='r'>";
		echo "索引名:";
		echo "</td>\n";
		echo "<td colspan =3 class='l'><input type = 'text' name = 'fk' style='width:120px'>";
		echo "</td>\n";
		echo "</tr>";
		echo "<tr>";
		echo "<td colspan =2 class ='l'>";
		echo "<input type = 'submit' name ='del' value = ' 删除 ' >";
		echo "</td>\n";
		echo "<td colspan =1 class='r'>";
		echo "匹配值:";
		echo "<td colspan =3 class='l'><input type = 'text' name = 'fv' style='width:120px'>";
		echo "</td>\n";
		echo "</tr>";
		echo "<tr>";
		echo "<td colspan =2 class ='l'>";
		echo "<input type = 'submit' name = 'upd' value = ' 更新 ' >";
		echo "</td>\n";
		echo "<td colspan =1 class='r'>";
		echo "新值:";
		echo "<td colspan =3 class='l'><input type = 'text' name = 'nv' style='width:120px'>";
		echo "</td>\n";
		echo "</tr>";
		echo "<tr><td colspan =2 > 写入文件</td>";
		echo "<td> <input type='checkbox' name='cbx'></td>";
		echo "</tr>";
		echo "<tr><td colspan=6><hr /></td></tr>";

		echo "</table><form>\n";
		echo "<a href='$PHP_SELF?phase=1' > 从新加载数组</a>\n";
		echo "<a href='$PHP_SELF?phase=3' > 上一步</a>\n";
		echo "<a href='$PHP_SELF?phase=5' > 下一步</a>\n";
	} else {
		echo $PHPSEA_ERROR['FileCsv_Error'];
	} 
	endpage();
} 

//-----------------------------------------------------------------------------
function phase5($log, $server)
{
	echo "<H2> 实例 5 - 写入日志</H2>";

	$log->data_file = 'Log.csv';
	$log->new_rows[] = array(date('Y:m:d-H:i:s'), $server['HTTP_USER_AGENT'], $server['HTTP_REFERER']);
	$log->append_csv();
	if ($log->read_csv()) {
		echo "<table>";
		echo "<tr><td> 日期 <hr /></td><td> 浏览器 <hr /></td><td> 操作url <hr /></td></tr>";
		foreach($log->db as $d) {
			echo "<tr>\n";
			while (list($key, $val) = each($d)) {
				echo "<td>";
				echo $val;
				echo "</td>\n";
			} // while
			echo "</tr>\n";
		} 
		echo "</table>\n";
	} else {
		echo $PHPSEA_ERROR['FileCsv_Error'];
	} 
	echo "<a href='$PHP_SELF?phase=5' > 从新执行日志范例</a>\n";
	echo "<a href='$PHP_SELF?phase=4' > 上一步</a>\n";
	echo "<a href='$PHP_SELF?phase=0' > 下一步</a>\n";
	endpage();
} 


//-----------------------------------------------------------------------------
function getdata()
{
	$data1[] = array('car_maker', 'fruit', 'river', 'town', 'county');
	$data1[] = array('Rover', 'orange', 'lea', 'hertford', 'herts');
	$data1[] = array('Vauxhall', 'apple', 'mimram', 'ware', 'essex');
	$data1[] = array('Volkswagen', 'bananna', 'ash', 'London', 'hants');
	$data1[] = array('BMW', 'grape', 'rib', 'Welwyn', 'devon');
	$data1[] = array('ford', 'lemon', 'thames', 'stevenage', 'cornwall');
	return $data1;
} 

function endpage()
{
	echo "</body>";
	echo "</html>";
} 

?>