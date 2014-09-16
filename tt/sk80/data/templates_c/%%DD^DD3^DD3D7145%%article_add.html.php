<?php /* Smarty version 2.6.26, created on 2010-04-09 03:15:20
         compiled from admin/article_add.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'admin/article_add.html', 77, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php echo '<div id="main"><div id="content"><h1>文章</h1><div id="tip" style="display:none"></div><form method="post" action="index.php?job=admin_article&action=add"><div class="form-area"><p class="title"><label for="article-title">文章标题</label><input id="article-title" class="textbox" name="content[title]" value="'; ?><?php echo $this->_tpl_vars['article']->title; ?><?php echo '" size="255" maxlength="255" type="text" /></p><div id="extended-metadata" style="display:none"><table><tbody><tr><td class="label">Slug</td><td class="field"><input class="textbox" type="text" name="content[slug]" value="'; ?><?php echo $this->_tpl_vars['article']->slug; ?><?php echo '" /></td></tr><tr><td class="label">Keywords</td><td class="field"><input class="textbox" type="text" name="content[keywords]" value="'; ?><?php echo $this->_tpl_vars['article']->keywords; ?><?php echo '" /></td></tr><tr><td class="label">Description</td><td class="field"><input class="textbox" type="text" name="content[description]" value="'; ?><?php echo $this->_tpl_vars['article']->description; ?><?php echo '" /></td></tr><tr><td class="label">Tags</td><td class="field"><input class="textbox" type="text" name="tags" value="'; ?><?php $_from = $this->_tpl_vars['article']->tags; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tag'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tag']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['tag']):
        $this->_foreach['tag']['iteration']++;
?><?php echo ''; ?><?php echo $this->_tpl_vars['tag']; ?><?php echo ''; ?><?php if (! ($this->_foreach['tag']['iteration'] == $this->_foreach['tag']['total'])): ?><?php echo ','; ?><?php endif; ?><?php echo ''; ?><?php endforeach; endif; unset($_from); ?><?php echo '" /></td></tr><tr><td class="label">Password</td><td class="field"><input class="textbox" type="text" name="content[password]" value="'; ?><?php echo $this->_tpl_vars['article']->password; ?><?php echo '" /></td></tr></tbody></table></div><p class="more-or-less"><small><a id="show_btn" href="#">More</a></small></p><label class="article-body" for="article-body">文章内容</label><div id="article-body"><textarea  rows="12" cols="80" id="elm1" name="content_part[content]">'; ?><?php echo $this->_tpl_vars['article']->content; ?><?php echo '</textarea></div><div class="rows"><p><label>分类</label><select name="content[cid]"><option value="0">无分类</option>'; ?><?php $_from = $this->_tpl_vars['cats']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cats'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cats']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['cats']['iteration']++;
?><?php echo '<option value="'; ?><?php echo $this->_tpl_vars['val']->id; ?><?php echo '" '; ?><?php if ($this->_tpl_vars['article']->cid == $this->_tpl_vars['val']->id): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>'; ?><?php echo $this->_tpl_vars['val']->name; ?><?php echo '</option>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</select></p><p><label>类型</label><select name="content[type]"><option value="post" '; ?><?php if ($this->_tpl_vars['article']->type == 'post'): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>文章</option><option value="page" '; ?><?php if ($this->_tpl_vars['article']->type == 'page'): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>页面</option></select></p><p><label>状态</label><select name="content[status]"><option value="publish" '; ?><?php if ($this->_tpl_vars['article']->status == 'publish'): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>发布</option><option value="draft" '; ?><?php if ($this->_tpl_vars['article']->status == 'draft'): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>草稿</option></select></p><p><label>评论</label><select name="content[is_comment]"><option value="1" '; ?><?php if ($this->_tpl_vars['article']->allow_cmt == '1'): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>允许</option><option value="0" '; ?><?php if ($this->_tpl_vars['article']->allow_cmt == '0'): ?><?php echo 'selected'; ?><?php endif; ?><?php echo '>不允许</option></select></p></div>'; ?><?php if ($this->_tpl_vars['article']->updated_date): ?><?php echo '<p><small>Last updated by UID.'; ?><?php echo $this->_tpl_vars['article']->updated_uid; ?><?php echo ' at '; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->updated_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%I:%M:%S %p") : smarty_modifier_date_format($_tmp, "%I:%M:%S %p")); ?><?php echo ' on '; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['article']->updated_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A, %B %e, %Y") : smarty_modifier_date_format($_tmp, "%A, %B %e, %Y")); ?><?php echo '</small></p>'; ?><?php endif; ?><?php echo '</div><p class="buttons"><input name="content[id]" type="hidden" value="'; ?><?php echo $this->_tpl_vars['article']->id; ?><?php echo '" /><input name="add" type="submit" value="创建文章" /><input name="save" type="submit" value="保存并继续编辑" />or<a href="javascript:history.back();">Cancel</a></p></form></div></div>'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['site_url']; ?>
include/js/xheditor/xheditor-zh-cn.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        //编辑器初始化
        var editor = $('#elm1').xheditor(true,{plugins:{Code:{c:'btnCode',t:'插入代码',e:function(){
			var _this=this;
			var htmlCode='<div><select id="xheCodeType"><option value="plain">其它</option><option value="php">PHP</option><option value="C">C</option><option value="Bash">Bash</option><option value="Python">Python</option><option value="Perl">Perl</option><option value="SQL">SQL</option><option value="MySql">MySql</option><option value="ActionScript 3">AS3</option><option value="Javascript">Javascript</option><option value="HTML">HTML</option><option value="div">DIV</option><option value="CSS">CSS</option><option value="text">Text</option></select></div><div><textarea id="xheCodeValue" wrap="soft" spellcheck="false" style="font:normal 12px Consolas, courier new; width:350px;height:150px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
			var jCode=$(htmlCode),jType=jSave=$('#xheCodeType',jCode),jValue=jSave=$('#xheCodeValue',jCode),jSave=$('#xheSave',jCode);
			jSave.click(function(){
				_this.focus();
				_this.pasteText('[code='+jType.val()+']\r\n'+jValue.val()+'\r\n[/code]');
				_this.hidePanel();
				return false;
			});
			_this.showDialog(jCode);
		}}},forcePtag:false,upLinkUrl:"upload.php",upLinkExt:"zip,rar,txt",upImgUrl:"upload.php",upImgExt:"jpg,jpeg,gif,png",upFlashUrl:"upload.php",upFlashExt:"swf",upMediaUrl:"upload.php",upMediaExt:"wmv,avi,wma,mp3,mid"});

		$("#show_btn").text("More").toggle(
			function(){
				$(this).text("Less");
			},
			function(){
				$(this).text("More");
			}
		).click(function(){
			$("#extended-metadata").slideToggle('slow');
		});

        $("form").submit( function () {
            var error_msg='';
            var tip = $("#tip");
            if($("#article-title").val() == '') {
                error_msg += '请输入文章标题<br />';
            }

            if($("input[name='content[slug]']").val() != '') {
                var slugReg = /^[A-Za-z ]{5,29}$/;
                if(!slugReg.test($("input[name='content[slug]']").val()))
                {
                    error_msg += 'slug只允许字母+空格，长度在6-30之间<br />';
                }
            }

            if($("input[name='content[password]']").val() != '') {
                var passwordReg = /^[a-zA-Z0-9]{5,14}$/;
                if(!passwordReg.test($("input[name='content[password]']").val()))
                {
                    error_msg += 'password以字母数字组合，长度在6-15之间<br />';
                }
            }

            if($("input[name='tags']").val() != '') {
                var tagsReg = /[\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\\\/'\"]+/;
                if(tagsReg.test($("input[name='tags']").val()))
                {
                    error_msg += 'tags不能含有特殊字符<br />';
                }
            }
            
            if( editor.getSource().length < 10) {
                error_msg += '文章内容字符不能小于20<br />';
            }
            if($("select[name='content[cid]']").val() == 0) {
                error_msg += '请选择文章分类<br />';
            }
            if(error_msg != ''){
                tip.html(error_msg).slideDown("slow");
              
                return false;
            }
            if(tip.style.display != 'none')
                tip.slideUp('slow');
            return true;
        } );
    });

</script>