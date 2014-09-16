<?php
	
	$this->load_template('header.php');
	
?>
					<div id="settings">
						<div id="settings_left">
							<?php $this->load_template('settings_leftmenu.php') ?>
						</div>
						<div id="settings_right">
							<?php if($D->submit) { ?>
							<?= okbox($this->lang('st_system_ok'), $this->lang('st_system_okmsg')) ?>
							<?php } ?>
							<div class="ttl"><div class="ttl2">
								<h3><?= $this->lang('settings_system_ttl2') ?></h3>
							</div></div>
							<form method="post" action="">
								<table id="setform" cellspacing="5">
									<tr>
										<td class="setparam"><?= $this->lang('st_system_language') ?></td>
										<td>
											<select name="language" class="setselect">
											<?php foreach($D->menu_languages as $k=>$v) { ?>
											<option value="<?= $k ?>"<?= $k==$D->language?' selected="selected"':'' ?>><?= htmlspecialchars($v) ?></option>
											<?php } ?>
											</select>
										</td>
									</tr>
									<?php if( count($D->menu_timezones) > 0 ) { ?>
									<tr>
										<td class="setparam"><?= $this->lang('st_system_timezone') ?></td>
										<td>
											<select name="timezone" class="setselect">
											<?php foreach($D->menu_timezones as $tz=>$txt) { ?>
											<option value="<?= htmlspecialchars($tz) ?>"<?= $tz==$D->timezone?' selected="selected"':'' ?>><?= htmlspecialchars($txt) ?></option>
											<?php } ?>
											</select>
										</td>
									</tr>
									<?php } ?>
									<tr>
										<td class="setparam" valign="top"><?= $this->lang('st_system_jsanim') ?></td>
										<td>
											<label><input type="radio" name="js_anim" value="1" <?= $D->js_anim==1?'checked="checked"':'' ?> /> <span><?= $this->lang('st_system_jsanim_1') ?></span></label>
											<label><input type="radio" name="js_anim" value="0" <?= $D->js_anim==0?'checked="checked"':'' ?> /> <span><?= $this->lang('st_system_jsanim_0') ?></span></label>
										</td>
									</tr>
									<tr>
										<td></td>
										<td><input type="submit" name="sbm" value="<?= $this->lang('st_system_savebtn') ?>" style="padding:4px; font-weight:bold;"/></td>
									</tr>
								</table>
							</form>
						</div>
					</div>
<?php
	
	$this->load_template('footer.php');
	
?>