<?php
	
	$this->load_template('header.php');
	
?>
					<div id="settings">
						<div id="settings_left">
							<?php $this->load_template('settings_leftmenu.php') ?>
						</div>
						<div id="settings_right">
							<div class="ttl"><div class="ttl2">
								<h3><?= $this->lang('settings_twit_opt_intgr_ttl') ?></h3>
							</div></div>
							<?php if($D->integration->twitter){ ?>
								<?= okbox($this->lang('settings_twit_opt_status'), $this->lang('settings_twit_opt_integrated_ok'), FALSE, 'margin-top: 10px;') ?>
								
								<form action="<?= $C->SITE_URL ?>settings/integrations" method="POST">
									<input type="submit" name="twit_intgr_remove" value="<?= $this->lang('settings_twit_opt_intgr_remove') ?>"  style="padding:4px; font-weight:bold;" />
								</form>
							<?php }else{ ?>
								<?= errorbox($this->lang('settings_twit_opt_status'), $this->lang('settings_twit_opt_integrated_err').$D->integration->tw_err, FALSE, 'margin-top: 10px;') ?>
							<?php } ?>
							
							<div class="ttl"><div class="ttl2">
								<h3><?= $this->lang('settings_fb_opt_intgr_ttl') ?></h3>
							</div></div>
							<?php if($D->integration->facebook){ ?>
								<?= okbox($this->lang('settings_fb_opt_status'), $this->lang('settings_fb_opt_integrated_ok'), FALSE, 'margin-top: 10px;') ?>
								
								<form action="<?= $C->SITE_URL ?>settings/integrations" method="POST">
									<input type="submit" name="fb_intgr_remove" value="<?= $this->lang('settings_fb_opt_intgr_remove') ?>"  style="padding:4px; font-weight:bold;" />
								</form>
							<?php }else{ ?>
								<?= errorbox($this->lang('settings_fb_opt_status'), $this->lang('settings_fb_opt_integrated_err').$D->integration->fb_err, FALSE, 'margin-top: 10px;') ?>
							<?php } ?>
						</div>
					</div>
<?php
	
	$this->load_template('footer.php');
	
?>