<?php /* Smarty version 2.6.26, created on 2010-03-10 15:14:25
         compiled from default/mod_category.html */ ?>
<?php if ($this->_tpl_vars['categories']): ?>
<?php echo '<li><h2>分类</h2><ul>'; ?><?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['categories'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categories']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['categories']['iteration']++;
?><?php echo '<li><a href="'; ?><?php echo $this->_tpl_vars['site_url']; ?><?php echo 'category/'; ?><?php echo $this->_tpl_vars['val']->slug; ?><?php echo '/">'; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo '</a> <small>('; ?><?php echo $this->_tpl_vars['val']->count; ?><?php echo ')</small></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul></li>'; ?>

<?php endif; ?>