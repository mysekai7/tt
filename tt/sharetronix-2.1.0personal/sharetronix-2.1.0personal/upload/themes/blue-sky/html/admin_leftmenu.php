							<div class="ttl" style="margin-right:12px;"><div class="ttl2"><h3><?= $this->lang('adm_menu_title') ?></h3></div></div>
							<div class="sidenav">
								<a href="<?= $C->SITE_URL ?>admin/statistics" class="<?= $this->request[1]=='statistics' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_statistics') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/general" class="<?= $this->request[1]=='general' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_general') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/system" class="<?= $this->request[1]=='system' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_system') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/themes" class="<?= $this->request[1]=='themes' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_themes') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/networkbranding" class="<?= $this->request[1]=='networkbranding' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_networkbranding') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/termsofuse" class="<?= $this->request[1]=='termsofuse' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_termsofuse') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/administrators" class="<?= $this->request[1]=='administrators' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_administrators') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/editusers" class="<?= $this->request[1]=='editusers' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_editusers') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/suspendusers" class="<?= $this->request[1]=='suspendusers'||$this->request[1]=='deleteuser' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_suspendusers') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/suspendapps" class="<?= $this->request[1]=='suspendapps' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_suspendapps') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/groups" class="<?= $this->request[1]=='groups' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_groups') ?></a>
								<a href="<?= $C->SITE_URL ?>admin/plugin_download" class="<?= $this->request[1]=='plugin_download' ? 'onsidenav' : '' ?>"><?= $this->lang('admmenu_plugin_download') ?></a>
							</div>
							
							<div style="background: url('<?= $C->IMG_URL ?>custom/sharetronix-download.png') no-repeat; display: block; width: 195px; height: 56px; margin: 50px 0 0 0;">
								<p style="margin: 20px 0 0 40px; padding: 0; font-weight: bold;">
									<a href="http://sharetronix.com/sharetronix/download" target="_blank" style="font-size: 10px;">
										Download Sharetronix
									</a>	
								</p>
							</div>
							
							<div style="background: url('<?= $C->IMG_URL ?>custom/sharetronix-download.png') no-repeat; display: block; width: 195px; height: 56px; margin: 0;">
								<p style="margin: 20px 0 0 40px; padding: 0; font-weight: bold;">
									<a href="http://sharetronix.com/sharetronix/buyprofessional" target="_blank" style="font-size: 10px;">
										Upgrade to Sharetronix Professional
									</a>	
								</p>
							</div>
							
							<div style="background: url('<?= $C->IMG_URL ?>custom/sharetronix-download.png') no-repeat; display: block; width: 195px; height: 56px; margin: 0;">
								<p style="margin: 20px 0 0 40px; padding: 0; font-weight: bold;">
									<a href="http://sharetronix.com/sharetronix/buypersonalplus" target="_blank" style="font-size: 10px;">
										Upgrade to Sharetronix Plus
									</a>	
								</p>
							</div>
							
							<div style="background: url('<?= $C->IMG_URL ?>custom/sharetronix-addons.png') no-repeat; display: block; width: 195px; height: 56px; margin: 0;">
								<p style="margin: 20px 0 0 40px; padding: 0; font-weight: bold;">
									<a href="http://sharetronix.com/sharetronix/addons" target="_blank" style="font-size: 10px;">
										Sharetronix Add-ons
									</a>	
								</p>
							</div>
							
							<div style="text-align: center; margin: 40px 0 10px 0; font-weight: bold;">
								<?= $C->VERSION ?>v
							</div>