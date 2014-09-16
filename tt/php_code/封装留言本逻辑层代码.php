<?php
/**
 * 留言本类
 *
 */

 //封装可以理解成，将一些代码片段有组织的合成一个整体功能的类或函数


class GuestBook {

	/**
	 * 数据库查询
	 *
	 * @var object
	 */
	private $db;

	/**
	 * 初始化构造函数
	 *
	 * @param object $db
	 */
	function __construct($db) {
		$this->db = $db;
	}

	/**
	 * 取列表
	 *
	 * @param int $limit 起始行
	 * @param int $lines 每页行数
	 * @return array
	 */
	function getList($limit,$lines=20)
	{
		$result = $this->db->query('SELECT * FROM gb_content order by id desc limit ' .$limit. ','.$lines);//查询数据
		$gblist = array();
		while ($row = $this->db->fetch_array($result)) {// 取一条数据
			$row['username'] = htmlentities($row['username'],ENT_COMPAT,'utf-8');
			$row['content'] = preg_replace('/(http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"\s])*)/i','<a target="_blank" href="$1">$1</a>',htmlentities($row['content'],ENT_COMPAT,'utf-8'));
			$gblist[] = $row;
		}
		return $gblist;
	}

	/**
	 * 保存留言
	 *
	 * @param array $info
	 * @return bool
	 */
	function save($info)
	{
		// 清除空格
		$info['username'] = trim($info['username']);
		$info['content'] = trim($info['content']);
		// 判断表单是否全部填写
		$error = array();
		if ($info['id']) {
			$rs = $this->getInfo($info['id']);
			if ($rs['user_id']!=intval($_SESSION['user_id'])) {// 判断user_id是否相同
				$error[] = '该信息你不能修改，只能修改自己发布的';
			}
		}
		if (!$info['username'] || !$info['content']) {
			$error[] = '请输入用户名和内容！';
		}
		// 判断用户名是否超出长度
		if (strlen($info['username'])>16) {
			$error[] = '用户名超出长度！';
		}
		// 判断内容是否超出长度
		if (strlen($info['content'])>255) {
			$error[] = '内容超出长度！';
		}
		if (!$error) {
			// 上传处理开始
			$uploadFile = '';
			if ($_FILES['user_file']['error']>0 && $_FILES['user_file']['error']!=4)
			{
				switch ($_FILES['user_file']['error'])
				{
				  case 1:
				  case 2:
				  	$error[] =  '文件太大。';
					break;
				  case 3:
				  	$error[] =  '文件没有完全上传。';
					break;
				}
			}

			if (!$error && $_FILES['user_file']['error']!=4) {// 有文件上传
				// 文件类型判断,这里允许zip,gif,jpe三种类型，可以根据需要设置
				$allow = array('zip'=>'application/zip','gif'=>'image/gif','jpg'=>'image/jpeg');
				if (!in_array($_FILES['user_file']['type'],$allow))
				{
					$error[] =  '文件类型不允许。';
				}

				// 上传目录
				$upfile = 'uploads/'.$_FILES['user_file']['name'];
				if (is_uploaded_file($_FILES['user_file']['tmp_name'])) // 是否是上传文件
				{
					// 移动临时文件
					if (!@move_uploaded_file($_FILES['user_file']['tmp_name'], $upfile))
					{
						$error[] =  '不能移动到目标目录。';
					} else {

						$uploadFile = $_FILES['user_file']['name'];
					}
				}
			}
			$updateField = '';
			if ($uploadFile && $info['id']) {//如果有上传把原来的文件删除
				@unlink('uploads/'.$rs['user_file']);
				$updateField = ',user_file=\''.$uploadFile.'\'';
			}

			// 上传处理结束
			if (!$error) {
				if ($info['id']) {
					$sql = "update gb_content set
						username='".$info['username']."',
						content='".$info['content']."'
						" . $updateField . "
						where id=".intval($info['id']);
					$this->db->query($sql);// 执行SQL查询
				} else {
					// insert SQL语句,增加user_id
					$sql = "insert into gb_content (username,content,insert_time,user_id,user_file)
							values ('".$info['username']."','".$info['content']."','".date('Y-m-d H:i:s')."'
							,". intval($_SESSION['user_id']) . ",'". $uploadFile . "')";

					$this->db->query($sql);// 执行SQL查询
				}
				return true;
			}
		}
		throw new Exception(implode(',',$error));//抛出错误

	}

	/**
	 * 删除留言
	 *
	 * @param int $id
	 * @return bool
	 */
	function delete($id)
	{
		$rs = $this->getInfo($id);
		$error = null;
		if ($rs['user_id']!=intval($_SESSION['user_id'])) {// 判断user_id是否相同
			$error = '该信息你不能删除，只能删除自己发布的';
		}
		if (!$error) {
			$this->db->query('delete from gb_content where id='.intval($id));//删除语句
			if ($rs['user_file']) {//删除附件
				@unlink('uploads/'.$rs['user_file']);
			}
			return true;
		}
		throw new Exception($error);//抛出错误
	}

	/**
	 * 取一条记录信息
	 *
	 * @param int $id
	 * @return array
	 */
	function getInfo($id)
	{
		$q = $this->db->query('select * from gb_content where id='.intval($id));
		$rs = $this->db->fetch_array($q);
		return $rs;
	}
}
?>