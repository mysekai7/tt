<?php /* Smarty version 2.6.26, created on 2010-03-10 01:34:10
         compiled from admin/categroy_edit.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php echo '<div id="main"><div id="content"><h1>修改分类</h1><div id="tip" style="display:none"></div><form action="index.php?job=admin_category&action=add" method="post"><div class="form-area"><p class="title"><label for="category-name">分类名字</label><input id="category-name" name="cat[name]" class="textbox" size="255" maxlength="255" type="text" value="'; ?><?php echo $this->_tpl_vars['cat']->name; ?><?php echo '" /></p><p class="title"><label for="category-slug">分类别名(英文小写)</label><input id="category-slug" name="cat[slug]" class="textbox" size="255" maxlength="255" type="text" value="'; ?><?php echo $this->_tpl_vars['cat']->slug; ?><?php echo '" /></p><div class="rows"><p><label>父分类</label><select name="cat[pid]"><option value="0">无分类</option>'; ?><?php $_from = $this->_tpl_vars['cats']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?><?php echo '<option value="'; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '" '; ?><?php if ($this->_tpl_vars['cat']->pid == $this->_tpl_vars['val']->id): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>'; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo '</option>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</select></p></div></div><p class="buttons"><input name="cat[id]" type="hidden" value="'; ?><?php echo $this->_tpl_vars['cat']->id; ?><?php echo '" /><input name="submit" type="submit" value="创建分类" />or<a href="javascript:history.back()">Cancel</a></p></form></div></div>'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("form").submit( function () {
            var error_msg='';
            var tip = $("#tip");
            if($("#category-name").val() == '') {
                error_msg += '请输入分类<br />';
            }

            if($("#category-slug").val() == '') {
                var slugReg = /^[A-Za-z ]{1,29}$/;
                if(!slugReg.test($("#category-slug").val()))
                {
                    error_msg += 'slug只允许字母+空格，长度在1-30之间<br />';
                }
            }

            if(error_msg != ''){
                tip.html(error_msg).slideDown("slow");

                return false;
            }
            if(tip.style.display != 'none')
                tip.slideUp('slow');
            return true;
        } );
    }
</script>