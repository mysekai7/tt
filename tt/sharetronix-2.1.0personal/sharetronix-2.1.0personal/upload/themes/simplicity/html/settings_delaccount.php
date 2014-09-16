<?php
	
	$this->load_template('header.php');
	
?>
					<div id="settings">
						<div id="settings_left">
							<?php $this->load_template('settings_leftmenu.php') ?>
						</div>
						<div id="settings_right">
							<div class="ttl">
								<div class="ttl2">
									<h3><?= $this->lang('settings_delaccount_ttl2') ?></h3>
								</div>
							</div>
							<?php if($D->error) { ?>
							<?= errorbox($this->lang('st_delaccount_error'), $this->lang($D->errmsg), TRUE, 'margin-top:5px;margin-bottom:5px;') ?>
							<?php } ?>
							<div class="greygrad" style="margin-top:5px;">
								<div class="greygrad2">
									<div class="greygrad3">
										<?= $this->lang('st_delaccount_description') ?>
										
										<form method="post" name="delaccount" onsubmit="return confirm('<?= htmlspecialchars($this->lang('st_delaccount_confirm')) ?>');" action="<?= $C->SITE_URL ?>settings/delaccount" autocomplete="off">
										<table id="setform" cellspacing="5" style="margin-top:5px;">
											<tr>
												<td class="setparam"><?= $this->lang('st_delaccount_password') ?></td>
												<td><input type="password" name="userpass" value="" autocomplete="off" class="setinp" /></td>
											</tr>
											<tr>
												<td></td>
												<td><input type="submit" value="<?= $this->lang('st_delaccount_submit') ?>" style="padding:4px; font-weight:bold;"/></td>
											</tr>
										</table>
										</form>
									</div>
								</div>
							</div>
							
						</div>
					</div>
<?php
	
	$this->load_template('footer.php');
	
?>