<?php /* Smarty version 2.6.26, created on 2010-04-09 03:16:27
         compiled from default/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'default/index.html', 9, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'default/header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '<div id="wrapper-inner"><div id="content">'; ?><?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['list']['iteration']++;
?><?php echo '<div class="post"><h2><a href="'; ?><?php echo $this->_tpl_vars['val']->url; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']->title; ?><?php echo '</a></h2><ul class="info"><li class="date">'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']->created_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y年 %m月%e日  %H:%M") : smarty_modifier_date_format($_tmp, "%Y年 %m月%e日  %H:%M")); ?><?php echo '</li><li class="category"><a href="'; ?><?php echo $this->_tpl_vars['val']->category_url; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']->category; ?><?php echo '</a></li></ul><div class="textbody">'; ?><?php echo $this->_tpl_vars['val']->content; ?><?php echo '</div><ul class="reaction"><li><a rel="nofollow" href="'; ?><?php echo $this->_tpl_vars['val']->url; ?><?php echo '">Permalink</a></li></ul></div>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '<p id="page-nav" class="page">'; ?><?php echo $this->_tpl_vars['page_nav']; ?><?php echo '</p></div><div id="utilities" class="utilities_side"><dl class="navi"><dt>最近文章</dt><dd><ul class="recentEntries">'; ?><?php $_from = $this->_tpl_vars['recent_post']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['recent_post'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['recent_post']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['recent_post']['iteration']++;
?><?php echo '<li><a href="'; ?><?php echo $this->_tpl_vars['val']->url; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']->title; ?><?php echo '</a></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul></dd><dt>文章分类</dt><dd><ul class="category">'; ?><?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['categories'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categories']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['categories']['iteration']++;
?><?php echo '<li '; ?><?php if ($this->_tpl_vars['val']->pid != '0'): ?><?php echo 'class="child"'; ?><?php endif; ?><?php echo '><a href="'; ?><?php echo $this->_tpl_vars['val']->url; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo '</a><small>('; ?><?php echo $this->_tpl_vars['val']->count; ?><?php echo ')</small></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul></dd><!--dt>文章归档</dt><dd><ul class="archives"></ul></dd--><dt>标签</dt><dd><ul class="tags">'; ?><?php $_from = $this->_tpl_vars['tag_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tag_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tag_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['tag_list']['iteration']++;
?><?php echo '<li><a style="font-size: '; ?><?php echo $this->_tpl_vars['val']['size']; ?><?php echo 'px;" href="'; ?><?php echo $this->_tpl_vars['val']['url']; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']['word']; ?><?php echo '</a></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul></dd></dl><!--dl class="other1 others"><dt>搜索</dt><dd></dd><dt>Feeds</dt><dd></dd><dt>E-mail address</dt><dd></dd><dd class="linkInfo"></dd></dl--></div>'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>