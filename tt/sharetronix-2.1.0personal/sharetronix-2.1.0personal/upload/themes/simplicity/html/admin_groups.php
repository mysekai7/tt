<?php
	
	$this->load_template('header.php');
	
?>
					<script type="text/javascript" src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/js/inside_admintools.js"></script>
					<div id="settings">
						<div id="settings_left">
							<?php $this->load_template('admin_leftmenu.php') ?>
						</div>
						<div id="settings_right">
							<div class="ttl">
								<div class="ttl2">
									<h3><?= $this->lang('admgroups_title2') ?></h3>
								</div>
							</div>
							<?php if( $this->param('msg')=='saved' ) { ?>
							<?= okbox($this->lang('admsusp_frm_ok'), $this->lang('admsusp_frm_ok_txt'), TRUE, 'margin-top:5px; margin-bottom:4px;') ?>
							<?php } ?>
							<div class="greygrad" style="margin-top:5px;">
								<div class="greygrad2">
									<div class="greygrad3">
										<?= $this->lang('admgroups_descr') ?>
										
										<table id="setform" cellspacing="5" style="margin-top:5px;">
											<tr>
												<td width="150" class="setparam" valign="top" nowrap="nowrap"><?= $this->lang('admgroups_specials') ?></td>
												<td width="400">
													<div id="group_admins_list">
														<div id="group_admins_link_empty_msg" class="yellowbox" style="border:0px solid; margin:0px;"><?= $this->lang('admgroups_none') ?></div>
													</div>
												</td>
											</tr>
											<tr>
												<td class="setparam"><?= $this->lang('admgroups_add_new') ?></td>
												<td>
													<input type="text" id="addadmin_inp" name="groupname" value="" style="width:200px;" rel="autocomplete" autocompleteoffset="0,3" />
													<input type="button" id="addadmin_btn" onclick="group_specialgroup_add(); return false;" value="<?= $this->lang('admgroups_add_button') ?>" />
												</td>
											</tr>
											<tr>
												<td></td>
												<td>
													<form method="post" name="admform" action="<?= $C->SITE_URL ?>admin/groups">
														<input type="hidden" name="admins" value="" />
														<input type="submit" value="<?= $this->lang('admsusp_frm_sbm') ?>" style="padding:4px; font-weight:bold;" />
													</form>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<script type="text/javascript">
								jserr_add_specialgroup_invalid_name	= "#GROUPNAME# is invalid group name.";
								jsconfirm_specialgroup_remove		= "Remove #GROUPNAME#? Are you shure?";
								<?php foreach($D->special_groups as $group) { ?>
								group_specialgroups_putintolist("<?= $group->title ?>");
								<?php } ?>
							</script>
							
						</div>
					</div>
<?php
	
	$this->load_template('footer.php');
	
?>