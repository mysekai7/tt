<?php /* Smarty version 2.6.10, created on 2009-05-16 13:28:56
         compiled from success.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--内容文件-->
<FORM METHOD=POST ACTION="?action=do">
<table cellspacing=0 cellpadding=5 border=0 >
			  <tr>
				<td bgcolor=#CCCCFF><A HREF="index.php">框架测试</a> -> 成功提示</td>
			  </tr>
			</table>
			<hr noshade color=dddddd size=1>
<table cellspacing="3">
			  <tr>
				<td >成功号:<br><font color="green"><?php echo $this->_tpl_vars['iNumber']; ?>
</font><br></td>
			  </tr>
			  <tr>
				<td >成功信息:<br><font color="green"><?php echo $this->_tpl_vars['sMessage']; ?>
</font></td>
			  </tr>
			  <tr>
				<td ><br><A HREF="<?php echo $this->_tpl_vars['sReturnUrl']; ?>
"><b>返回</b></A></td>
			  </tr>
			</table>
</FORM>
<!--包含尾文件-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>