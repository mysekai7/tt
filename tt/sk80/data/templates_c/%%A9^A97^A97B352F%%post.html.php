<?php /* Smarty version 2.6.26, created on 2010-04-07 23:05:13
         compiled from caomao/post.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'caomao/post.html', 44, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="generator" content="Editplus4PHP" />
    <meta name="keywords" content="Editplus4PHP" />
    <meta name="description" content="Editplus4PHP" />
    <meta name="author" content="Leo" />
<!--
    <script type="text/javascript" src="js/common.js"></script>
    
    <link rel="shortcut icon" href="images/favicon.ico" />
-->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['tpl_front']; ?>
style.css" />
    <title><?php echo $this->_tpl_vars['post']->title; ?>
</title>
  </head>
  <body>
    <div id="header">
        <div id="head">
            <span class="sitename">blog.sk80.com</span>
            <p class="sitedesc">等我有钱了, 咱买棒棒糖, 买2 根, 1 根 你看着我吃, 另1根 我吃给你看。</p>
        </div>
    </div>
    <div id="main">
        <div id="content" class="detail">
            <p id="topic-path">
                <a href="/" rel="nofollow">Home</a>
                &gt;
                <a href="<?php echo $this->_tpl_vars['site_url']; ?>
category/<?php echo $this->_tpl_vars['post']->category_slug; ?>
/"><?php echo $this->_tpl_vars['post']->category; ?>
</a>
                &gt;
                <strong class="current"><?php echo $this->_tpl_vars['post']->title; ?>
</strong>
            </p>
            <ul id="flip1" class="flip">
                <?php if ($this->_tpl_vars['next']): ?>
                <li class="newer"><a title="<?php echo $this->_tpl_vars['next']->title; ?>
" href="<?php echo $this->_tpl_vars['site_url']; ?>
post/<?php echo $this->_tpl_vars['next']->id; ?>
.html" rel="nofollow">Newer</a></li>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['previous']): ?>
                <li class="older"><a title="<?php echo $this->_tpl_vars['previous']->title; ?>
" href="<?php echo $this->_tpl_vars['site_url']; ?>
post/<?php echo $this->_tpl_vars['previous']->id; ?>
.html" rel="nofollow">Older</a></li>
                <?php endif; ?>
            </ul>
            <h1><?php echo $this->_tpl_vars['post']->title; ?>
</h1>
            <div class="entry">
                <ul class="info">
                    <li class="date"><?php echo ((is_array($_tmp=$this->_tpl_vars['post']->created_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%b %e, %Y %H:%M") : smarty_modifier_date_format($_tmp, "%b %e, %Y %H:%M")); ?>
</li>
                    <li class="category"><a href="<?php echo $this->_tpl_vars['site_url']; ?>
category/<?php echo $this->_tpl_vars['post']->category_slug; ?>
/"><?php echo $this->_tpl_vars['post']->category; ?>
</a></li>
                </ul>
                <div class="textbody">
                    <?php echo $this->_tpl_vars['post']->content; ?>

                </div>
                <ul id="flip2" class="flip">
                    <?php if ($this->_tpl_vars['next']): ?>
                    <li>Newer:<a href="<?php echo $this->_tpl_vars['site_url']; ?>
post/<?php echo $this->_tpl_vars['next']->id; ?>
.html"><?php echo $this->_tpl_vars['next']->title; ?>
</a></li>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['previous']): ?>
                    <li>Older:<a href="<?php echo $this->_tpl_vars['site_url']; ?>
post/<?php echo $this->_tpl_vars['previous']->id; ?>
.html"><?php echo $this->_tpl_vars['previous']->title; ?>
</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div id="utilities" class="utilities_side">
            <dl class="navi">
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
                        <li></li>
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
        <ul class="support">
            <li>Powered by sk80.com</li>
        </ul>
        <address>Copyright &copy; sk80 blog All Rights Reserved.</address>
    </div>
  </body>
</html>