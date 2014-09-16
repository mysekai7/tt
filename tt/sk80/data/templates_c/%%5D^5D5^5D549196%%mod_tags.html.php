<?php /* Smarty version 2.6.26, created on 2010-03-29 23:05:31
         compiled from default/mod_tags.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'default/mod_tags.html', 7, false),)), $this); ?>
<?php if ($this->_tpl_vars['tag_list']): ?>
<?php echo '<li id="tags"><h2>标签</h2><ul>'; ?><?php $_from = $this->_tpl_vars['tag_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tag_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tag_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['tag_list']['iteration']++;
?><?php echo '<li><a style="font-size: '; ?><?php echo $this->_tpl_vars['val']['size']; ?><?php echo 'px;" href="'; ?><?php echo $this->_tpl_vars['site_url']; ?><?php echo 'tag/'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['word'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?><?php echo '/">'; ?><?php echo $this->_tpl_vars['val']['word']; ?><?php echo '</a></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul></li>'; ?>

<?php endif; ?>