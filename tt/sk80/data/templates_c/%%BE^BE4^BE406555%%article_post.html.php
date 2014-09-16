<?php /* Smarty version 2.6.10, created on 2010-02-23 16:45:49
         compiled from admin/article_post.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="generator" content="Editplus4PHP" />
    <meta name="keywords" content="Editplus4PHP" />
    <meta name="description" content="Editplus4PHP" />
    <meta name="author" content="4kychao" />
<!--
    <script type="text/javascript" src="js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="shortcut icon" href="images/favicon.ico" />
-->
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['tpl_path']; ?>
/admin/style.css" />
    <title>Admin Sample</title>
  </head>
  <body>
      <div id="page">
          <div id="header">
              <div id="site-title">Blog Admin</div>
              <div id="site-subtitle">subtitle testing</div>
              <div id="navigation">
                  <strong><a href="#">文章</a></strong>
                  <a href="#">分类</a>
                  <a href="#">评论</a>
              </div>
          </div>
          <div id="main">
              <div id="content">
                  <h1>添加文章</h1>
                  <form action="">
                      <div class="form-area">
                          <p class="title">
                              <label for="article-title">文章标题</label>
                              <input id="article-title" class="textbox" size="255" maxlength="255" type="text" />
                          </p>
                          <div id="extended-metadata"></div>
                          <p class="more-or-less"><small><a href="">More</a></small></p>
                          <label class="article-body" for="article-body">文章内容</label>
                          <div id="article-body">
                              <textarea></textarea>
                          </div>
                          <div class="rows">
                              <p>
                                  <label>分类</label>
                                  <select>
                                      <option>无分类</option>
                                  </select>
                              </p>
                              <p>
                                  <label>类型</label>
                                  <select>
                                      <option>post</option>
                                  </select>
                              </p>
                              <p>
                                  <label>状态</label>
                                  <select>
                                      <option>publish</option>
                                  </select>
                              </p>
                          </div>
                      </div>

                      <p class="buttons">
                          <input type="submit" value="创建文章" />
                          <input type="submit" value="保存并继续编辑" />
                          or
                          <a href="#">Cancel</a>
                      </p>
                  </form>
              </div>
          </div>
          <div id="footer">
              <p>#simpleblog</p>
              <p id="site-links">
                  <a href="">Users</a>
                  <span class="separator">|</span>
                  <a href="">Extension</a>
                  <span class="separator">|</span>
                  <a href="">Log Out</a>
                  <span class="separator">|</span>
                  <a href="">View Site</a>
              </p>
          </div>
      </div>
  </body>
</html>