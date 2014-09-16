<?php /* Smarty version 2.6.26, created on 2010-04-08 22:40:39
         compiled from default/header.html */ ?>
<?php echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="generator" content="blog.sk80.com" /><meta name="keywords" content="'; ?><?php $_from = $this->_tpl_vars['tag_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tag_keyword'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tag_keyword']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['tag_keyword']['iteration']++;
?><?php echo ''; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo ' '; ?><?php endforeach; endif; unset($_from); ?><?php echo '" /><meta name="description" content="blog.sk80.com技术文章整理收藏的" /><meta name="author" content="blog.sk80.com" /><meta name="google-site-verification" content="W8lAMAOcorzUOCibxm0q832Z9pVplii9ld9x0L-TOAc" /><link rel="stylesheet" type="text/css" href="'; ?><?php echo $this->_tpl_vars['tpl_front']; ?><?php echo 'style.css" /><title>blog.sk80.com</title></head><body><div id="wrapper"><div id="header"><div id="header-inner"><h1 class="sitename">blog.sk80.com</h1><div class="description">等我有钱了, 咱买棒棒糖, 买2 根, 1 根 你看着我吃, 另1根 我吃给你看。</div>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/mod_menu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '</div></div>'; ?>