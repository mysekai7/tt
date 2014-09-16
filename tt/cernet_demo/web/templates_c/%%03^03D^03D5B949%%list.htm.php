<?php /* Smarty version 2.6.10, created on 2009-05-16 13:31:27
         compiled from list.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--内容文件-->
<FORM METHOD=POST ACTION="?action=do">
<table cellspacing=0 cellpadding=5 border=0 >
			  <tr>
				<td bgcolor=#CCCCFF><A HREF="index.php">框架测试</a> -> 会员登陆</td>
			  </tr>
			</table>
			<hr noshade color=dddddd size=1>
			<table cellspacing="3">
				<?php $_from = $this->_tpl_vars['user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
				  <tr>
					<td ><?php echo $this->_tpl_vars['item']->id; ?>
,<?php echo $this->_tpl_vars['item']->username; ?>
,<?php echo $this->_tpl_vars['item']->regtime; ?>
</td>
				  </tr>
				<?php endforeach; endif; unset($_from); ?>
			  <tr>
				<td ><?php echo $this->_tpl_vars['page']; ?>
</td>
			  </tr>
			</table>
<!--包含尾文件-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>