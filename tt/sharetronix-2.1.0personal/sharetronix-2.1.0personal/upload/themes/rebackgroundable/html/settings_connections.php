<?php
	
	$this->load_template('header.php');
	
?>
	<div id="settings">
		<div id="settings_left">
			<?php $this->load_template('settings_leftmenu.php') ?>
		</div>
		<div id="settings_right">
			<?php if($D->submit == 1 && $D->error) { ?>
			<?= errorbox($this->lang('st_avatat_err'), $D->errmsg) ?>
			<?php } elseif($D->submit== 1 && !$D->error) { ?>
			<?= okbox($this->lang('st_avatat_ok'), $D->okmsg) ?>
			<?php } elseif($D->submit== 2 && $D->error) { ?>
			<?= errorbox($this->lang('st_avatat_err'), $D->errmsg) ?>
			<?php } elseif($D->submit== 2 && !$D->error) { ?>
			<?= okbox($this->lang('st_avatat_ok'), $D->okmsg) ?>
			<?php } ?>
			<div class="ttl"><div class="ttl2">
				<h3><?= $this->lang('settings_conn_ttl2') ?></h3>
				<a class="ttlink" href="<?= $C->SITE_URL ?>
					<?= $this->user->info->username ?>/tab:info"><?= $this->lang('settings_viewprofile_link') ?></a>
			</div></div>

			
				<?php
				if(!count($D->connections))
				{	
				?>
					<p style='margin-left: 4px;'>
						<?= $this->lang('st_conn_havent') ?>
					</p>
				<?php
				}else
				{
					foreach($D->connections as $conn)
					{
				?>		
						<div class="app" style="border-bottom:0px;">		
							<img src="<?= $C->IMG_URL ?>avatars/thumbs1/<?= $conn['avatar']; ?>" class="appavatar" style='margin-bottom: 20px;'>
							<div class="appinfo">
								<h2 class="appname"><?= htmlspecialchars($conn['name']); ?> | ID: <?= $conn['app_id'] ?></h2>
								<p><?= htmlspecialchars($conn['description']); ?></p>
								<?php
								if($conn['verified'] == 1)
								{
								?>	
									<a href='<?= $C->SITE_URL; ?>settings/connections?revoke=<?= $conn['oid']; ?>'> 
										<?= $this->lang('st_conn_revoke') ?>
									</a> 
								<?php
								}else
								{
								?>
									<a href='<?= $C->SITE_URL; ?>settings/connections?unrevoke=<?= $conn['oid']; ?>'> 
										<?= $this->lang('st_conn_unrevoke') ?>
									</a> 
								<?php
								}
								?>
							</div>
						</div>
				<?php
					}
				}
				?>
		</div>
	</div>
<?php
	
	$this->load_template('footer.php');
	
?>