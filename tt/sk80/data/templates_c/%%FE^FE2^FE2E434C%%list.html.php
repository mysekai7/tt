<?php /* Smarty version 2.6.26, created on 2010-04-13 00:37:09
         compiled from default/list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'default/list.html', 13, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'default/header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '<div id="wrapper-inner"><div id="content"><div id="new-post">'; ?><?php if ($this->_tpl_vars['tag']): ?><?php echo '<h1 class="search-title">你正在浏览的是关键词  <strong>{ '; ?><?php echo $this->_tpl_vars['tag']; ?><?php echo ' }</strong> 的日志归档.</h1>'; ?><?php else: ?><?php echo '<h1 class="search-title">你正在浏览的是分类 <strong>{ '; ?><?php echo $this->_tpl_vars['category']; ?><?php echo ' }</strong> 的日志归档.</h1>'; ?><?php endif; ?><?php echo '<ul>'; ?><?php $_from = $this->_tpl_vars['post_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['post_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['post_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['post_list']['iteration']++;
?><?php echo '<li><span class="fr">'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']->created_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "[%m-%e]") : smarty_modifier_date_format($_tmp, "[%m-%e]")); ?><?php echo '</span><a href="'; ?><?php echo $this->_tpl_vars['site_url']; ?><?php echo 'post/'; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '.html">'; ?><?php echo $this->_tpl_vars['val']->title; ?><?php echo '</a></li>'; ?><?php endforeach; else: ?><?php echo '<p>Nothing</p>'; ?><?php endif; unset($_from); ?><?php echo '</ul>'; ?><?php if ($this->_tpl_vars['page_nav']): ?><?php echo '<p id="page-nav">'; ?><?php echo $this->_tpl_vars['page_nav']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo '</div></div><div id="utilities" class="utilities_side"><dl class="navi"><dt>文章分类</dt><dd><ul class="category">'; ?><?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['categories'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categories']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['categories']['iteration']++;
?><?php echo '<li '; ?><?php if ($this->_tpl_vars['val']->pid != '0'): ?><?php echo 'class="child"'; ?><?php endif; ?><?php echo '><a href="'; ?><?php echo $this->_tpl_vars['val']->url; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo '</a><small>('; ?><?php echo $this->_tpl_vars['val']->count; ?><?php echo ')</small></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul></dd><!--dt>文章归档</dt><dd><ul class="archives"></ul></dd--></dl><!--dl class="other1 others"><dt>搜索</dt><dd></dd><dt>Feeds</dt><dd></dd><dt>E-mail address</dt><dd></dd><dd class="linkInfo"></dd></dl--></div>'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>