<?php
	
	$this->load_template('header.php');
	
?>
					<div id="invcenter">
						<h2>
							<div style="float:left"><?= $this->lang('groups_page_ttl2') ?></div>
							<a href="<?= $C->SITE_URL ?>groups/new" class="newgroupbtn"><b><?= $this->lang('groups_page_tab_add') ?></b></a>
							<div class="klear"></div>
						</h2>
						<?php if( $this->param('msg')=='deleted' ) { ?>
						<?= okbox($this->lang('groups_msgbox_deleted_ttl'), $this->lang('groups_msgbox_deleted_txt')) ?>
						<?php } ?>	
						<?php if( $this->user->is_logged ) { ?>
						<div class="htabs" style="margin-bottom:6px; margin-top:0px; overflow:visible;">
							<a href="<?= $C->SITE_URL ?>groups/tab:all" class="<?= $D->tab=='all'?'onhtab':'' ?>"><b><?= $this->lang('groups_page_tab_all') ?> <small><?= ($D->tab=='all')? '('.$D->num_results.')' : '' ?></small></b></a>
							<a href="<?= $C->SITE_URL ?>groups/tab:my" class="<?= $D->tab=='my'?'onhtab':'' ?>"><b><?= $this->lang('groups_page_tab_my') ?> <small><?= ($D->tab=='my')? '('.$D->num_results.')' : '' ?></small></b></a>
							<a href="<?= $C->SITE_URL ?>groups/tab:special" class="<?= $D->tab=='special'?'onhtab':'' ?>"><b><?= $this->lang('groups_page_tab_special') ?> <small><?= ($D->tab=='special')? '('.$D->num_results.')' : '' ?></small></b></a>
							<?php if( $D->num_results > 1 ) { ?>
							<div id="postfilter">
								<a href="javascript:;" onclick="dropdiv_open('postfilteroptions');" id="postfilterselected" onfocus="this.blur();"><span><?= $this->lang('groups_orderby_'.$D->orderby) ?></span></a>
								<div id="postfilteroptions" style="display:none;">
									<a href="<?= $C->SITE_URL ?>groups/tab:<?= $D->tab ?>/orderby:name" style="float:none;"><?= $this->lang('groups_orderby_name') ?></a>
									<a href="<?= $C->SITE_URL ?>groups/tab:<?= $D->tab ?>/orderby:date" style="float:none;"><?= $this->lang('groups_orderby_date') ?></a>
									<a href="<?= $C->SITE_URL ?>groups/tab:<?= $D->tab ?>/orderby:users" style="float:none;"><?= $this->lang('groups_orderby_users') ?></a>
									<a href="<?= $C->SITE_URL ?>groups/tab:<?= $D->tab ?>/orderby:posts" style="float:none; border-bottom:0px;"><?= $this->lang('groups_orderby_posts') ?></a>
								</div>
								<span><?= $this->lang('groups_orderby_ttl') ?></span>
							</div>
							<?php } ?>
						</div>
						<?php } else { ?>
						<div class="htabs" style="margin:0px; margin-bottom:6px; height:1px;"></div>
						<?php } ?>
						<div id="grouplist" class="groupspage">
							<?= $D->groups_html ?>
						</div>
					</div>
<?php
	
	$this->load_template('footer.php');
	
?>