<?php /* Smarty version 2.6.26, created on 2010-04-06 17:07:01
         compiled from admin/article_list.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php echo '<div id="main"><div id="content"><div class="clear"><h1 class="fl">文章</h1><a class="fr post-button" href="index.php?job=admin_article&action=add">发布内容</a></div><form name="form1" method="post"><table class="index"><thead><tr><th class="title">Title</th><th class="status">Status</th><th class="modify" colspan="2">Modify</th></tr></thead><tbody>'; ?><?php $_from = $this->_tpl_vars['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['articles'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['articles']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['articles']['iteration']++;
?><?php echo '<tr><td class="title '; ?><?php if ($this->_tpl_vars['val']->status == 'publish'): ?><?php echo 'published'; ?><?php else: ?><?php echo 'draft'; ?><?php endif; ?><?php echo '"><input class="mc" onClick="selectBox(this)" name="checked[]" value="'; ?><?php echo $this->_tpl_vars['val']->res; ?><?php echo '" type="checkbox" /><!--input type="hidden" name="post['; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '][ID]" value="'; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '" /><input type="hidden" name="post['; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '][CID]" value="'; ?><?php echo $this->_tpl_vars['val']->cid; ?><?php echo '" /--><a href="index.php?job=admin_article&action=edit&id='; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']->title; ?><?php echo '</a></td><td class="status">'; ?><?php if ($this->_tpl_vars['val']->status == 'publish'): ?><?php echo 'Published'; ?><?php else: ?><?php echo '<font color="red">Draft</font>'; ?><?php endif; ?><?php echo '</td><td class="remove"><a href="index.php?job=admin_article&action=del&id='; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '&cid='; ?><?php echo $this->_tpl_vars['val']->cid; ?><?php echo '">Remove</a></td></tr>'; ?><?php endforeach; else: ?><?php echo '<tr><td class="title published">暂无文章</td><td class="status"></td><td class="remove"></td></tr>'; ?><?php endif; unset($_from); ?><?php echo '</tbody></table><div class="clear admin-btm"><p class="fl">'; ?><?php if ($this->_tpl_vars['page_nav']): ?><?php echo '<label class="b">选择:</label><button class="mc button"  onclick="select(\'all\'); return false;">全部</button><button class="mc button"  onclick="select(\'none\'); return false;">无</button><label class="b">管理:</label><button class="mc button" onclick="batchOperate(\'remove\'); return false;">删除</button><!--select class="mc" name="changestatus"><option value="">状态修改</option><option value="publish">发布</option><option value="draft">草稿</option></select--><select class="mv" name="newcid" onchange="batchOperate(\'move\'); return false;"><option value="">转移到...</option>'; ?><?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['category'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['category']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['category']['iteration']++;
?><?php echo '<option value="'; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo '</option>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</select><span>&nbsp;</span>'; ?><?php endif; ?><?php echo '<label class="b">显示:</label><select id="cats" onchange="showByCate(); return false;"><option value="0">显示全部</option>'; ?><?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['category'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['category']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['category']['iteration']++;
?><?php echo '<option value="'; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '" '; ?><?php if ($this->_tpl_vars['val']->id == $this->_tpl_vars['cid']): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>'; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo '</option>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</select></p>'; ?><?php if ($this->_tpl_vars['page_nav']): ?><?php echo '<p id="pageNav" class="fr">'; ?><?php echo $this->_tpl_vars['page_nav']; ?><?php echo '</p>'; ?><?php endif; ?><?php echo '</div></form></div></div>'; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <script type="text/javascript">
        function selectBox(obj) {
            if(obj.checked == true) {
                var p = obj.parentNode.parentNode;
                //alert(p.nodeName);
                p.className = 'selected';
            } else {
                var p = obj.parentNode.parentNode;
                //alert(p.nodeName);
                p.className = '';
            }
        }

        function checkedBox(){
            var theNum = 0;
            var checkbox = document.getElementsByName('checked[]');
            for(var i=0; i<checkbox.length; i++) {
                if(checkbox[i].checked) theNum++;
            }
            return theNum;
        }

        function select(type)
        {
            var checkbox = document.getElementsByName('checked[]');
            for(var i=0; i<checkbox.length; i++) {
                if(type == 'all'){
                    checkbox[i].checked = true;
                    selectBox(checkbox[i]);
                } else {
                    checkbox[i].checked = false;
                    selectBox(checkbox[i]);
                }
            }
        }

        function batchOperate(type) {

            if(checkedBox()){
                if(confirm('你确认执行当前操作?')==true) {
                    document.form1.action="index.php?job=admin_article&action=batch_"+type;
                    document.form1.submit();
                }
                return false;
            } else {
                alert('请选择文章');
            }
            return false;
        }

        function showByCate()
        {
            var cat = document.getElementById('cats');
            var url = 'index.php?job=admin_article&action=index&cid='+cat.value;
            window.location.href = url;
            return true;
        }
    </script>