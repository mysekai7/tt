<?php	
	$this->load_template('header.php');
?>
	<div id="settings">
		<div id="settings_left">
			<?php $this->load_template('admin_leftmenu.php') ?>
		</div>
		<div id="settings_right">
			<div class="ttl">
				<div class="ttl2">
					<h3><?= $this->lang('admtitle_suspendapps') ?></h3>
				</div>
			</div>
			<?php if( $D->submit && !$D->error) { ?>
			<?= okbox($this->lang('admsusp_frm_ok'), $this->lang('admsusp_frm_ok_txt'), TRUE, 'margin-top:5px; margin-bottom:4px;') ?>
			<?php }elseif($D->submit && $D->error) { ?>
			<?= errorbox($this->lang('admip_error'), $D->errmsg, TRUE, 'margin-top:5px; margin-bottom:4px;') ?>
			<?php } ?>
			<div class="greygrad" style="margin-top:5px;">
				<div class="greygrad2">
					<div class="greygrad3">
						<?= $this->lang('admsusp_descr1') ?>
						
						<table id="setform" cellspacing="5" style="margin-top:5px;">
						<?php
						foreach($D->apps as $app)
						{
						?>	
							<tr>
								<td width="150" class="setparam" valign="top" nowrap="nowrap"><?= $this->lang('admsuspapp_frm_adm') ?></td>
								<td width="400">
									<div id="group_admins_list">
										<div id="group_admins_link_empty_msg" class="yellowbox" style="border:0px solid; margin:0px;">
											<?= $app['name'] ?>
											<a href='<?= $C->SITE_URL?>admin/suspendapps?restore=<?=$app['id']; ?>'>Restore</a>
										</div>
									</div>
								</td>
							</tr>
						<?php
						}
						?>
							<tr>
								<td class="setparam"><?= $this->lang('admsuspapp_frm_add') ?></td>
								<td>
								<form method="post" name="admform" action="<?= $C->SITE_URL ?>admin/suspendapps">	
									<input type="text" id="addadmin_inp" name="app_id" value="" style="width:200px;" />
									<input type="submit" id="addadmin_btn" name='suspend_app' value="<?= $this->lang('admsusp_frm_add_btn') ?>" />
								</form>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	
	$this->load_template('footer.php');
	
?>