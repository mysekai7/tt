<?php /* Smarty version 2.6.26, created on 2010-03-11 03:05:24
         compiled from admin/category_list.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php echo '<div id="main"><div id="content"><h1>分类</h1><table class="index"><thead><tr><th class="cat_name">Category</th><th class="modify" colspan="2">Modify</th></tr></thead><tbody>'; ?><?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?><?php echo '<tr id="category-'; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '"><td class="cat_name '; ?><?php if ($this->_tpl_vars['val']->parent == '0'): ?><?php echo 'thin'; ?><?php endif; ?><?php echo '">'; ?><?php unset($this->_sections['blank']);
$this->_sections['blank']['name'] = 'blank';
$this->_sections['blank']['loop'] = is_array($_loop=$this->_tpl_vars['val']->level) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['blank']['show'] = true;
$this->_sections['blank']['max'] = $this->_sections['blank']['loop'];
$this->_sections['blank']['step'] = 1;
$this->_sections['blank']['start'] = $this->_sections['blank']['step'] > 0 ? 0 : $this->_sections['blank']['loop']-1;
if ($this->_sections['blank']['show']) {
    $this->_sections['blank']['total'] = $this->_sections['blank']['loop'];
    if ($this->_sections['blank']['total'] == 0)
        $this->_sections['blank']['show'] = false;
} else
    $this->_sections['blank']['total'] = 0;
if ($this->_sections['blank']['show']):

            for ($this->_sections['blank']['index'] = $this->_sections['blank']['start'], $this->_sections['blank']['iteration'] = 1;
                 $this->_sections['blank']['iteration'] <= $this->_sections['blank']['total'];
                 $this->_sections['blank']['index'] += $this->_sections['blank']['step'], $this->_sections['blank']['iteration']++):
$this->_sections['blank']['rownum'] = $this->_sections['blank']['iteration'];
$this->_sections['blank']['index_prev'] = $this->_sections['blank']['index'] - $this->_sections['blank']['step'];
$this->_sections['blank']['index_next'] = $this->_sections['blank']['index'] + $this->_sections['blank']['step'];
$this->_sections['blank']['first']      = ($this->_sections['blank']['iteration'] == 1);
$this->_sections['blank']['last']       = ($this->_sections['blank']['iteration'] == $this->_sections['blank']['total']);
?><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;'; ?><?php endfor; endif; ?><?php echo '<a href="index.php?job=admin_category&action=edit&id='; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo '</a></td><td class="add"><a href="index.php?job=admin_category&action=add">Add</a></td><td class="remove '; ?><?php if ($this->_tpl_vars['val']->parent != '0'): ?><?php echo 'remove-disabled'; ?><?php endif; ?><?php echo '"><a href="index.php?job=admin_category&action=del&id='; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '">Remove</a></td></tr>'; ?><?php endforeach; else: ?><?php echo '<tr id="category-id"><td class="cat_name"><a href="#">无分类</a></td><td class="add"><a href="index.php?job=admin_category&action=add">Add</a></td><td class="remove"></td></tr>'; ?><?php endif; unset($_from); ?><?php echo '</tbody></table></div></div>'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>