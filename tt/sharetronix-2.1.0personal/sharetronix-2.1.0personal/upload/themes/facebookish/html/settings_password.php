<?php
	
	$this->load_template('header.php');
	
?>
					<div id="settings">
						<div id="settings_left">
							<?php $this->load_template('settings_leftmenu.php') ?>
						</div>
						<div id="settings_right">
							<?php if($D->submit && !$D->error) { ?>
							<?= okbox($this->lang('st_password_ok'), $this->lang('st_password_okmsg')) ?>
							<?php } elseif($D->error) { ?>
							<?= errorbox($this->lang('st_password_err'), $this->lang($D->errmsg)) ?>
							<?php } ?>
							<div class="ttl"><div class="ttl2"><h3><?= $this->lang('settings_password_ttl2') ?></h3></div></div>
							<form method="post" action="">
								<table id="setform" cellspacing="5">
									<tr>
										<td class="setparam"><?= $this->lang('st_password_current') ?></td>
										<td><input type="password" name="pass_old" value="<?= htmlspecialchars($D->pass_old) ?>" autocomplete="off" class="setinp" /></td>
									</tr>
									<tr>
										<td class="setparam"><?= $this->lang('st_password_newpass') ?></td>
										<td><input type="password" name="pass_new" value="<?= htmlspecialchars($D->pass_new) ?>" autocomplete="off" class="setinp" /></td>
									</tr>
									<tr>
										<td class="setparam"><?= $this->lang('st_password_newconfirm') ?></td>
										<td><input type="password" name="pass_new2" value="<?= htmlspecialchars($D->pass_new2) ?>" autocomplete="off" class="setinp" /></td>
									</tr>
									<tr>
										<td></td>
										<td><input type="submit" value="<?= $this->lang('st_password_changebtn') ?>" style="padding:4px; font-weight:bold;"/></td>
									</tr>
								</table>
							</form>
						</div>
					</div>
<?php
	
	$this->load_template('footer.php');
	
?>