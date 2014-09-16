<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无限级分类(作者:小贤)</title>
<style type="text/css">
* {
	padding:0;
	margin:0;
}
body {
	font: 14px Arial, Helvetica, sans-serif;
	overflow-x: hidden;
	overflow-y:auto;
	color:#444444;
}
#main {
	width:600px;
	margin:20px auto;
}
#main table{
	margin-top:8px;
	width:100%;
	border-collapse:collapse;
	border-spacing:0px;
	BACKGROUND-COLOR: #EFEFEF;
	border:0px;
}
#main table td{
	line-height:20px;
	border:1px solid #FFF;
	padding:5px;
}
#main table thead td {
	background:#C6C6C6;
	FONT-SIZE:15px;
	text-align:center;
	line-height:23px;
	font-weight:bold;
}
.input {
	border-top: 1px inset;
	border-left: 1px inset;
	padding:2px 3px;
}
</style>
</head>
<body>
<div id="main"> <a href="?action=">分类列表</a> <a href="?action=add">添加分类</a>
  <?php

$mysql = new mysql_Class('localhost','root','');

$mysql -> select_db('myde520');

switch($_GET['action']){
	case 'add':
		$class_arr=array();
		$sql = "select * from `class` order by sort asc, id Desc";
		$query = $mysql -> query($sql);
		while($row = $mysql -> fetch_array($query)){
			$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort']);
		}
		?>
  <form action="?action=act_add" method="post">
    <table border="0" cellpadding="0" cellspacing="0" class="table02">
      <thead>
        <tr>
          <td colspan="2"><div align="center">添加分类</div></td>
        </tr>
      </thead>
      <tr>
        <td><div align="right">分类名称：</div></td>
        <td><input name="name" type="text" class="input" id="name" value="" size="40" /></td>
      </tr>
      <tr>
        <td><div align="right">所属分类ID：</div></td>
        <td><select name="classid" id="classid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	dafenglei_select(0,0,0);
			?>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right">排序：</div></td>
        <td><input name="sort" type="text" class="input" id="sort" value="10" size="25" /></td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
            <input type="submit" name="button" id="button" value="添加分类" />
            <input type="reset" name="button2" id="button2" value="重置" />
          </div></td>
      </tr>
    </table>
  </form>
  <?php
		break;
	case 'act_add':
		$sql = "INSERT INTO `class` (`name`,`classid`,`sort`) VALUES('".$_POST['name'];
		$sql .= "',".$_POST['classid'].",".$_POST['sort'].")";
		$mysql -> query($sql);
		msg('添加成功!','?action=');
		break;
	case 'edit':
		$class_arr=array();
		$sql = "select * from `class` order by sort asc, id Desc";
		$query = $mysql -> query($sql);
		while($row = $mysql -> fetch_array($query)){
			$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort']);
		}
	$sql  = "select * from `class` where id=".$_GET['id'];
	$query = $mysql -> query($sql);
	$row = $mysql -> fetch_array($query);
	if($row){
	?>
      <form action="?action=act_edit" method="post">
    <table border="0" cellpadding="0" cellspacing="0" class="table02">
      <thead>
        <tr>
          <td colspan="2"><div align="center">修改分类</div></td>
        </tr>
      </thead>
      <tr>
        <td><div align="right">分类名称：</div></td>
        <td><input name="name" type="text" class="input" id="name" value="<?php echo $row['name'];?>" size="40" /></td>
      </tr>
      <tr>
        <td><div align="right">所属分类ID：</div></td>
        <td><select name="classid" id="classid">
            <option value="0">-----顶级分类-----</option>
            <?php
            	dafenglei_select(0,0,$row['classid']);
			?>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right">排序：</div></td>
        <td><input name="sort" type="text" class="input" id="sort" value="<?php echo $row['sort'];?>" size="25" /></td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
            <input type="submit" name="button" id="button" value="修改分类" />
            <input type="hidden" id="id" name="id" value="<?php echo $_GET['id'];?>" />
            <input type="reset" name="button2" id="button2" value="重置" />
          </div></td>
      </tr>
    </table>
  </form>
    <?php
	}else{
		msg('要修改的记录不存在!','?action=');
	}
		break;
	case 'act_edit':
		$sql  = "select id from `class` where id=".$_POST['id'];
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){
			if($row['id']==$_POST['classid']){
				msg('修改失败,不能自己是自己的子分类!','?action=');
			}else{
				$sql = "update `class` set `name`='".$_POST['name']."',`classid`=".$_POST['classid'];
				$sql .= ",`sort`=".$_POST['classid']." where `id`=".$_POST['id'];
				$mysql -> query($sql);
				msg('修改成功!','?action=');
			}
		}
		break;
	case 'del':
			$sql  = "select * from `class` where id=".$_GET['id'];
			$query = $mysql -> query($sql);
			$row = $mysql -> fetch_array($query);
			if($row){
				$mysql -> query("delete `id` from `class` where id=".$_GET['id']);
				msg('删除成功!','?action=');
			}else{
				msg('记录不存在!','?action=');
			}
		break;
	case '':
		$class_arr=array();
		$sql = "select * from `class` order by sort asc, id Desc";
		$query = $mysql -> query($sql);
		while($row = $mysql -> fetch_array($query)){
			$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort']);
		}
		?>
    <table class="table">
      <thead>
        <tr>
          <td >分类名称</td>
          <td width="60"><div align="center">排序</div></td>
          <td width="80"><div align="center">操作</div></td>
        </tr>
      </thead>
      <?php dafenglei_arr(0,0);?>
    </table>
  <?php
		break;

}
?>
</div>
</body>
</html>
<?php
function msg($msg,$url)
{
	echo "<script type=\"text/javascript\">alert('$msg');window.location.href='$url';</script>";
}

function dafenglei_arr($m,$id)
{
	global $class_arr;
	global $classid;
	global $mysql;
	if($id=="") $id=0;
	$n = str_pad('',$m,'-',STR_PAD_RIGHT);
	$n = str_replace("-","&nbsp;&nbsp;",$n);
	for($i=0;$i<count($class_arr);$i++){
		if($class_arr[$i][2]==$id){
		echo "<tr>\n";
		echo "	  <td>".$n."|--<a href=\"?action=edit&amp;id=".$class_arr[$i][0]."\">".$class_arr[$i][1]."</a></td>\n";
		echo "	  <td><div align=\"center\">".$class_arr[$i][3]."</div></td>\n";
		echo "	  <td><div align=\"center\"><a href=\"?action=edit&amp;id=".$class_arr[$i][0]."\">修改</a>";
		echo " <a href=\"?action=del&amp;id=".$class_arr[$i][0]."\">删除</a>";
		echo "</div></td>\n";
		echo "	</tr>\n";		
			dafenglei_arr($m+1,$class_arr[$i][0]);
		}
		
	}
	
}

function dafenglei_select($m,$id,$index)
{	
	global $class_arr;
	$n = str_pad('',$m,'-',STR_PAD_RIGHT);
	$n = str_replace("-","&nbsp;&nbsp;",$n);
	for($i=0;$i<count($class_arr);$i++){
	
		if($class_arr[$i][2]==$id){
			if($class_arr[$i][0]==$index){
				echo "        <option value=\"".$class_arr[$i][0]."\" selected=\"selected\">".$n."|--".$class_arr[$i][1]."</option>\n";
			}else{
				echo "        <option value=\"".$class_arr[$i][0]."\">".$n."|--".$class_arr[$i][1]."</option>\n";
			}
			dafenglei_select($m+1,$class_arr[$i][0],$index);
			
		}
		
	}
	
}


/**
 *-------------------------数据库操作类-----------------------------*
*/
class mySql_Class
{
	function __construct($host, $user, $pass)
	{
 		@mysql_connect($host,$user,$pass) or die("数据库连接失败!");
		mysql_query("SET NAMES 'gbk'");
 	}
	
	function select_db($db)//连接表
	{
		return @mysql_select_db($db);
	}
	
	function query($sql)//执行SQL语句
	{
		return @mysql_query($sql);
	}
	
	function fetch_array($fetch_array)
	{
		return @mysql_fetch_array($fetch_array, MYSQL_ASSOC);
	}
	
	
	function close() //关闭数据库
	{ 
		return @mysql_close();
	}
	
	function insert($table,$arr) //添加记录
	{
		$sql = $this -> query("INSERT INTO `$table` (`".implode('`,`', array_keys($arr))."`) VALUES('".implode("','", $arr)."')");
		return $sql;
	}
}

?>
