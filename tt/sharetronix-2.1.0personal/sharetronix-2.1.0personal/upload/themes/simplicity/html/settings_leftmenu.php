							<div class="ttl" style="margin-right:12px;"><div class="ttl2"><h3><?= $this->lang('settings_menu_title') ?></h3></div></div>
							<div class="sidenav">
								<a href="<?= $C->SITE_URL ?>settings/profile" class="<?= $this->request[1]=='profile' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_profile') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/contacts"  class="<?= $this->request[1]=='contacts' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_contacts') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/avatar" class="<?= $this->request[1]=='avatar' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_avatar') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/password" class="<?= $this->request[1]=='password' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_password') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/email" class="<?= $this->request[1]=='email' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_email') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/system" class="<?= $this->request[1]=='system' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_system') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/notifications" class="<?= $this->request[1]=='notifications' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_notif') ?></a>
								<?php if( function_exists('curl_init') ) { ?>
								<a href="<?= $C->SITE_URL ?>settings/rssfeeds" class="<?= $this->request[1]=='rssfeeds' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_rssfeeds') ?></a>
								<?php } ?>
								<a href="<?= $C->SITE_URL ?>settings/delaccount" class="<?= $this->request[1]=='delaccount' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_delaccount') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/connections" class="<?= $this->request[1]=='connections' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_conn') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/privacy" class="<?= $this->request[1]=='privacy' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_privacy') ?></a>
								<a href="<?= $C->SITE_URL ?>settings/integrations" class="<?= $this->request[1]=='integrations' ? 'onsidenav' : '' ?>"><?= $this->lang('settings_menu_integr') ?></a>
							</div>