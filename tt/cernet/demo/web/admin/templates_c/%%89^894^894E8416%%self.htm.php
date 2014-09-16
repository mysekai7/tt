<?php /* Smarty version 2.6.10, created on 2009-05-16 14:02:31
         compiled from self.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--内容文件-->
<FORM METHOD=POST ACTION="?action=do">
<table cellspacing=0 cellpadding=5 border=0 >
			  <tr>
				<td bgcolor=#CCCCFF><A HREF="/index.php">框架测试</a> -> 后台管理</td>
			  </tr>
			</table>
			<hr noshade color=dddddd size=1>
			<table cellspacing="3">
			  <tr>
				<td width="20%"> 用户名：</td><td><?php echo $this->_tpl_vars['user']->username; ?>
</td>
			  </tr>
			  <tr>
				<td > 密码：</td><td><input type="text" name="passwd" size="20" value="<?php echo $this->_tpl_vars['user']->passwd; ?>
"></td>
			  </tr>
			  <tr>
				<td > 邮箱：</td><td><input type="text" name="email" size="20" value="<?php echo $this->_tpl_vars['user']->email; ?>
"></td>
			  </tr>
			  <tr>
				<td colspan="2"> 
				<input type="hidden" name="username" value="<?php echo $this->_tpl_vars['user']->username; ?>
">
				<!--<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['user']->id; ?>
">-->

				<input type="submit" value=" 提交修改 "></td>
			  </tr>
			</table>
<!--包含尾文件-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>