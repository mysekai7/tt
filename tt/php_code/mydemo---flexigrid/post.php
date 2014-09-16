<?php
/*
本程序存在bug， 且有未完善的功能， 仅做网友交流使用。

制作：CSSRAIN.CN - 一揪就出来。

http://www.cssrain.cn
*/

function runSQL($rsql) {
$hostname = "localhost";
$username = "root";
$password = "123456";
$dbname = "grid";
$connect = mysql_connect($hostname,$username,$password) or die ("Error: could not connect to database");
$db = mysql_select_db($dbname);

mysql_query("SET NAMES 'UTF8'");
$result = mysql_query($rsql) or die ('test');
return $result;
mysql_close($connect);
}

function countRec($fname,$tname) {
$sql = "SELECT count($fname) FROM $tname ";
$result = runSQL($sql);
while ($row = mysql_fetch_array($result)) {
return $row[0];
} 
}
$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'id';
if (!$sortorder) $sortorder = 'desc';

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "LIMIT $start, $rp";

$sql = "SELECT id,qq_group,name,qq,sex,tel FROM cssrain $sort $limit";
$result = runSQL($sql);

$total = countRec('id','cssrain');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-type: text/x-json");
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while ($row = mysql_fetch_array($result)) {
if ($rc) $json .= ",";
$json .= "\n{";
$json .= "id:'".$row['id']."',";
$json .= "cell:['".$row['id']."'";
$json .= ",'".addslashes($row['qq_group'])."'";
$json .= ",'".addslashes($row['name'])."'";
$json .= ",'".addslashes($row['qq'])."'";
$json .= ",'".addslashes($row['sex'])."'";
$json .= ",'".addslashes($row['tel'])."']";
$json .= "}";
$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
?>