<?php /* Smarty version 2.6.26, created on 2010-04-07 22:58:23
         compiled from caomao/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'caomao/index.html', 32, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="keywords" content="PHP开发" />
    <meta name="description" content="PHP开发" />
    <meta name="google-site-verification" content="W8lAMAOcorzUOCibxm0q832Z9pVplii9ld9x0L-TOAc" />
<!--
    <script type="text/javascript" src="js/common.js"></script>
    
    <link rel="shortcut icon" href="images/favicon.ico" />
-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['tpl_front']; ?>
style.css" />
    <title>blog.sk80.com</title>
  </head>
  <body>
    <div id="header">
        <div id="head">
            <h1 class="sitename">blog.sk80.com</h1>
            <p class="sitedesc">等我有钱了, 咱买棒棒糖, 买2 根, 1 根 你看着我吃, 另1根 我吃给你看。</p>
        </div>
    </div>
    <div id="main">
        <div id="content">
            <p id="topic-path">
                <a href="/" rel="nofollow">Home</a>
            </p>
            <?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['list']['iteration']++;
?>
            <div class="entry">
                <h2><a href="<?php echo $this->_tpl_vars['val']->url; ?>
"><?php echo $this->_tpl_vars['val']->title; ?>
</a></h2>
                <ul class="info">
                    <li class="date"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']->created_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%b %e, %Y %H:%M") : smarty_modifier_date_format($_tmp, "%b %e, %Y %H:%M")); ?>
</li>
                    <li class="category"><a href="<?php echo $this->_tpl_vars['category_url']; ?>
/"><?php echo $this->_tpl_vars['val']->category; ?>
</a></li>
                </ul>
                <div class="textbody">
                    <?php echo $this->_tpl_vars['val']->content; ?>

                </div>
                <ul class="reaction">
                    <li><a href="<?php echo $this->_tpl_vars['val']->url; ?>
">Permalink</a></li>
                </ul>
            </div>
            <?php endforeach; endif; unset($_from); ?>
            <p class="page">
                <?php echo $this->_tpl_vars['page_nav']; ?>

            </p>
        </div>
        <div id="utilities" class="utilities_side">
            <dl class="navi">
                <dt>Recent Entries</dt>
                <dd>
                    <ul class="recentEntries">
                      <?php $_from = $this->_tpl_vars['recent_post']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['recent_post'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['recent_post']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['recent_post']['iteration']++;
?>
                      <li><a href="<?php echo $this->_tpl_vars['val']->url; ?>
"><?php echo $this->_tpl_vars['val']->title; ?>
</a></li>
                      <?php endforeach; endif; unset($_from); ?>
                    </ul>
                </dd>
                <dt>Categories</dt>
                <dd>
                    <ul class="category">
                      <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['categories'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categories']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['categories']['iteration']++;
?>
                      <li><a href="<?php echo $this->_tpl_vars['val']->url; ?>
"><?php echo $this->_tpl_vars['val']->name; ?>
</a> <small>(<?php echo $this->_tpl_vars['val']->count; ?>
)</small></li>
                      <?php endforeach; endif; unset($_from); ?>
                    </ul>
                </dd>
                <dt>Archives</dt>
                <dd>
                    <ul class="archives">

                    </ul>
                </dd>
            </dl>
            <dl class="other1 others">
                <dt>Search</dt>
                <dd>

                </dd>
                <dt>Feeds</dt>
                <dd>

                </dd>
                <dt>E-mail address</dt>
                <dd>

                </dd>
                <dd class="linkInfo"></dd>
            </dl>

        </div>
        <p class="return">
            <a href="#header">Return to page top</a>
        </p>
    </div>
    <div id="footer">
        <address>Copyright &copy; sk80 blog All Rights Reserved.</address>
    </div>
  </body>
</html>