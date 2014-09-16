<?php
	
	$this->load_template('header.php');
	
?>
	<script type="text/javascript">
		function display_more_info(div_id)
		{
			var disp = document.getElementById(div_id).style.display;
			if(disp == 'none' || disp == '') document.getElementById(div_id).style.display = 'block';
			else document.getElementById(div_id).style.display = 'none';
		}
	</script>
	<div id="settings">
		<div id="settings_left">
			<?php $this->load_template('settings_leftmenu.php') ?>
		</div>
		<div id="settings_right">
			<?php if($D->submit) { ?>
			<?= okbox($this->lang('st_system_ok'), $this->lang('st_profile_okmsg')) ?>
			<?php } ?>
			<div class="ttl"><div class="ttl2">
				<h3><?= $this->lang('settings_system_ttl2') ?></h3>
			</div></div>
			<form method="post" action="">
				<table id="setform" cellspacing="5">
					<tr>
						<td class="setparam" valign="top"><?= $this->lang('settings_opt_prof_name') ?></td>
						<td>
							<label style="display: inline;"><input type="radio" name="protect_profile" value="1" <?= $D->profile_protect==1?'checked="checked"':'' ?> /> <span><?= $this->lang('settings_opt_enabled') ?></span></label>
							<label style="display: inline;"><input type="radio" name="protect_profile" value="0" <?= $D->profile_protect==0?'checked="checked"':'' ?> /> <span><?= $this->lang('settings_opt_disabled') ?></span></label>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="greygrad" style="margin-top:5px;">
								<div class="greygrad2">
									<div class="greygrad3" style="width: 400px; font-size: 10px; margin-top: 0px;">
										<a href="javascript:void(0);" onClick="display_more_info('infoto_1');"> <?= $this->lang('settings_privacy_learn_more') ?> </a>
										<div id="infoto_1" style="display: none;">
											<?= $this->lang('settings_opt_prof_desc') ?>
										</div>
									</div>
								</div>
							</div>
								
						</td>
					</tr>
					<tr>
						<td class="setparam" valign="top"><?= $this->lang('settings_opt_posts_name') ?></td>
						<td>
							<label style="display: inline;"><input type="radio" name="protect_posts" value="1" <?= $D->protect_posts==1?'checked="checked"':'' ?> /> <span><?= $this->lang('settings_opt_enabled') ?></span></label>
							<label style="display: inline;"><input type="radio" name="protect_posts" value="0" <?= $D->protect_posts==0?'checked="checked"':'' ?> /> <span><?= $this->lang('settings_opt_disabled') ?></span></label>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="greygrad" style="margin-top:5px;">
								<div class="greygrad2">
									<div class="greygrad3" style="width: 400px; font-size: 10px; margin-top: 0px;">
										<a href="javascript:void(0);" onClick="display_more_info('infoto_2');"> <?= $this->lang('settings_privacy_learn_more') ?> </a>
										<div id="infoto_2" style="display: none;">
											<?= $this->lang('settings_opt_posts_desc') ?>
										</div>
									</div>
								</div>
							</div>
								
						</td>
					</tr>
					<tr>
						<td class="setparam" valign="top"><?= $this->lang('settings_opt_dm_name') ?></td>
						<td>
							<label style="display: inline;"><input type="radio" name="protect_dm" value="1" <?= $D->protect_dm==1?'checked="checked"':'' ?> /> <span><?= $this->lang('settings_opt_enabled') ?></span></label>
							<label style="display: inline;"><input type="radio" name="protect_dm" value="0" <?= $D->protect_dm==0?'checked="checked"':'' ?> /> <span><?= $this->lang('settings_opt_disabled') ?></span></label>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="greygrad" style="margin-top:5px;">
								<div class="greygrad2">
									<div class="greygrad3" style="width: 400px; font-size: 10px; margin-top: 0px;">
										<a href="javascript:void(0);" onClick="display_more_info('infoto_3');"> <?= $this->lang('settings_privacy_learn_more') ?> </a>
										<div id="infoto_3" style="display: none;">
											<?= $this->lang('settings_opt_dm_desc') ?>
										</div>
									</div>
								</div>
							</div>
								
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