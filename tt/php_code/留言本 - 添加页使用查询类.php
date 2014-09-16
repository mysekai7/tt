<?php
session_start();
require('common.php');
// session变量判断
if (!$_SESSION['user_id']) {
	echo '请先登录！ <a href="login.php">登录</a>';
	exit;
}

if ($_GET['action']=='save') {
	// 清除空格
	$_POST['username'] = trim($_POST['username']);
	$_POST['content'] = trim($_POST['content']);
	if (!get_magic_quotes_gpc()) {// 如果魔术引号关闭使用addslashes转换
		$_POST['username'] = addslashes($_POST['username']);
		$_POST['content'] = addslashes($_POST['content']);
	}
	// 判断表单是否全部填写
	if (!$_POST['username'] || !$_POST['content']) {
		echo '请输入用户名和内容！';
		exit;
	}
	// 判断用户名是否超出长度
	if (strlen($_POST['username'])>16) {
		echo '用户名超出长度！';
		exit;
	}
	// 判断内容是否超出长度
	if (strlen($_POST['content'])>255) {
		echo '内容超出长度！';
		exit;
	}

	// 上传处理开始
	$uploadFile = '';
	if ($_FILES['user_file']['error']>0 && $_FILES['user_file']['error']!=4)
	{
		echo '出错了: ';
		switch ($_FILES['user_file']['error'])
		{
		  case 1:
		  case 2:
		  	echo '文件太大。';
			break;
		  case 3:
		  	echo '文件没有完全上传。';
			break;
		}
		exit;
	}
	if ($_FILES['user_file']['error']!=4) {// 有文件上传
		// 文件类型判断,这里允许zip,gif,jpe三种类型，可以根据需要设置
		$allow = array('zip'=>'application/zip','gif'=>'image/gif','jpg'=>'image/jpeg');
		if (!in_array($_FILES['user_file']['type'],$allow))
		{
			echo '文件类型不允许。';
			exit;
		}

		// 上传目录
		$upfile = 'uploads/'.$_FILES['user_file']['name'];

		if (is_uploaded_file($_FILES['user_file']['tmp_name'])) // 是否是上传文件
		{
			// 移动临时文件
			if (!@move_uploaded_file($_FILES['user_file']['tmp_name'], $upfile))
			{
				echo '不能移动到目标目录。';
				exit;
			} else {
				$uploadFile = $_FILES['user_file']['name'];
			}
		}
	}
	// 上传处理结束

	// insert SQL语句,增加user_id
	$sql = "insert into gb_content (username,content,insert_time,user_id,user_file)
			values ('".$_POST['username']."','".$_POST['content']."','".date('Y-m-d H:i:s')."'
			,". intval($_SESSION['user_id']) . ",'". $uploadFile . "')";

	$query->query($sql);// 执行SQL查询
	echo '添加成功！ <a href="index.php">查看留言</a>';
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提交留言</title>
<link rel="stylesheet" type="text/css" href="style.css" media="all" />
</head>

<body>
<table width="500" border="0" cellspacing="0" cellpadding="0" class="tb">
  <tr>
    <td class="bg"><b>[提交留言]</b></td>
  </tr>
  <tr>
    <td><form id="form1" name="form1" method="post"  action="add.php?action=save" enctype="multipart/form-data">
        <table width="500" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="12%">用户名：</td>
            <td width="88%"><input type="text" name="username" /></td>
          </tr>
          <tr>
            <td width="12%">内容：</td>
            <td width="88%"><textarea name="content" cols="40" rows="6"></textarea>
            </td>
		  </tr>
           <tr>
            <td width="12%">附件：</td>
            <td width="88%"><input type="file" name="user_file" />
            </td>
		  </tr>
         <tr>
            <td width="12%"></td>
            <td width="88%"><input type="submit" name="submit" value="提 交"  /></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
</body>
</html>
