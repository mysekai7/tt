<?php /* Smarty version 2.6.10, created on 2009-05-16 13:28:35
         compiled from register.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--内容文件-->
<FORM METHOD=POST ACTION="?action=do">
<table cellspacing=0 cellpadding=5 border=0 >
			  <tr>
				<td bgcolor=#CCCCFF><A HREF="index.php">框架测试</a> -> 注册会员</td>
			  </tr>
			</table>
			<hr noshade color=dddddd size=1>
			<table cellspacing="3">
			  <tr>
				<td >用户名：</td><td ><INPUT TYPE="text" NAME="username" size=20></td>
			  </tr>
			  <tr>
				<td >密码：</td><td ><INPUT TYPE="text" NAME="passwd" size=20></td>
			  </tr>
			  <tr>
				<td >Email：</td><td ><INPUT TYPE="text" NAME="email" size=20></td>
			  </tr>
			  <tr>
				<td colspan="2"><INPUT TYPE="submit" value=" 注册 "><INPUT TYPE="reset" value=" 取消 "></td>
			  </tr>
			</table>
<!--包含尾文件-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>