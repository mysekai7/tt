<?php
	
	$this->load_template('header.php');
	
?>
					
							
	<?php if( $this->user->is_logged ) { ?>
	<div class="htabs" style="margin-bottom:6px; margin-top:0px; overflow:visible;">
		
		<a href="<?= $C->SITE_URL ?>leaders/tab:users" class="<?= $D->tab=='users'?'onhtab':'' ?>"><b><?= $this->lang('competitions_tab_users') ?></b></a>
		<a href="<?= $C->SITE_URL ?>leaders/tab:groups" class="<?= $D->tab=='groups'?'onhtab':'' ?>"><b><?= $this->lang('competitions_tab_groups') ?></b></a>
		
	</div>
		
		<?php if( $D->tab=='users' ) { ?>
		
			<?php if( count($D->most_posting_members) > 0 ) { $place = 0;  ?>
			<div class="ttl" style="margin-top:10px; margin-bottom:10px;"><div class="ttl2"><h3><?= $this->lang('competitions_user_competition1') ?></h3></div></div>
			<div class="slimusergroup" style="margin-right:-10px; margin-bottom:5px;">
				<?php foreach($D->most_posting_members as $uid=>$dtls) { ?>
					<a href="<?= userlink($dtls[0]) ?>" class="slimuser" title="<?= htmlspecialchars($dtls[0]) ?>">
						<div class="leaders_icon" style="background: url('<?= $C->IMG_URL ?>avatars/thumbs1/<?= $dtls[1] ?>') no-repeat;">
							<p>
								<?= ++$place; ?> <?= $this->lang('competitions_place') ?>
								
							</p>
							
							
						</div>
						<div class="under_leaders_icon">
							#<?= $dtls[2]; ?>#
						</div>
					</a>
				<?php } ?>
			</div>
			<?php } ?>
			
			<?php if( count($D->most_commenting_members) > 0 ) { $place = 0;  ?>
			<div class="ttl" style="margin-top:10px; margin-bottom:10px;"><div class="ttl2"><h3><?= $this->lang('competitions_user_competition2') ?></h3></div></div>
			<div class="slimusergroup" style="margin-right:-10px; margin-bottom:5px;">
				<?php foreach($D->most_commenting_members as $uid=>$dtls) { ?>
					<a href="<?= userlink($dtls[0]) ?>" class="slimuser" title="<?= htmlspecialchars($dtls[0]) ?>">
						<div class="leaders_icon" style="background: url('<?= $C->IMG_URL ?>avatars/thumbs1/<?= $dtls[1] ?>') no-repeat;">
							<p>
								<?= ++$place; ?> <?= $this->lang('competitions_place') ?>
							</p>
							
						</div>
						<div class="under_leaders_icon">
							#<?= $dtls[2]; ?>#
						</div>
					</a>
				<?php } ?>
			</div>
			<?php } ?>
			
			<?php if( count($D->most_commented_members) > 0 ) { $place = 0;  ?>
			<div class="ttl" style="margin-top:10px; margin-bottom:10px;"><div class="ttl2"><h3><?= $this->lang('competitions_user_competition3') ?></h3></div></div>
			<div class="slimusergroup" style="margin-right:-10px; margin-bottom:5px;">
				<?php foreach($D->most_commented_members as $uid=>$dtls) { ?>
					<a href="<?= userlink($dtls[0]) ?>" class="slimuser" title="<?= htmlspecialchars($dtls[0]) ?>">
						<div class="leaders_icon" style="background: url('<?= $C->IMG_URL ?>avatars/thumbs1/<?= $dtls[1] ?>') no-repeat;">
							<p>
								<?= ++$place; ?> <?= $this->lang('competitions_place') ?>
							</p>
							
						</div>
						<div class="under_leaders_icon">
							#<?= $dtls[2]; ?>#
						</div>
					</a>
				<?php } ?>
			</div>
			<?php } ?>
			
			<?php if( count($D->get_mostfollowing_members) > 0 ) { $place = 0;  ?>
			<div class="ttl" style="margin-top:10px; margin-bottom:10px;"><div class="ttl2"><h3><?= $this->lang('competitions_user_competition4') ?></h3></div></div>
			<div class="slimusergroup" style="margin-right:-10px; margin-bottom:5px;">
				<?php foreach($D->get_mostfollowing_members as $uid=>$dtls) { ?>
					<a href="<?= userlink($dtls[0]) ?>" class="slimuser" title="<?= htmlspecialchars($dtls[0]) ?>">
						<div class="leaders_icon" style="background: url('<?= $C->IMG_URL ?>avatars/thumbs1/<?= $dtls[1] ?>') no-repeat;">
							<p>
								<?= ++$place; ?> <?= $this->lang('competitions_place') ?>
							</p>
						</div>
						<div class="under_leaders_icon">
							#<?= $dtls[2]; ?>#
						</div>
					</a>
				<?php } ?>
			</div>
			<?php } ?>
			
			<?php if( count($D->get_mostfollowed_members) > 0 ) { $place = 0;  ?>
			<div class="ttl" style="margin-top:10px; margin-bottom:10px;"><div class="ttl2"><h3><?= $this->lang('competitions_user_competition5') ?></h3></div></div>
			<div class="slimusergroup" style="margin-right:-10px; margin-bottom:5px;">
				<?php foreach($D->get_mostfollowed_members as $uid=>$dtls) { ?>
					<a href="<?= userlink($dtls[0]) ?>" class="slimuser" title="<?= htmlspecialchars($dtls[0]) ?>">
						<div class="leaders_icon" style="background: url('<?= $C->IMG_URL ?>avatars/thumbs1/<?= $dtls[1] ?>') no-repeat;">
							<p>
								<?= ++$place; ?> <?= $this->lang('competitions_place') ?>
							</p>
							
						</div>
						<div class="under_leaders_icon">
							#<?= $dtls[2]; ?>#
						</div>
					</a>
				<?php } ?>
			</div>
			<?php } ?>
			
		<?php }elseif( $D->tab=='groups' ){ ?>
			
			<?php if( count($D->get_mostactive_groups) > 0 ) { $place = 0; ?>
			<div class="ttl" style="margin-top:10px; margin-bottom:10px;"><div class="ttl2"><h3><?= $this->lang('competitions_group_competition1') ?></h3></div></div>
			<div class="slimusergroup" style="margin-right:-10px; margin-bottom:5px;">
				<?php foreach($D->get_mostactive_groups as $gid=>$dtls) { ?>	
					<a href="<?= userlink($dtls[0]) ?>" class="slimuser" title="<?= htmlspecialchars($dtls[0]) ?>">
						<div class="leaders_icon" style="background: url('<?= $C->IMG_URL ?>avatars/thumbs1/<?= $dtls[1] ?>') no-repeat;">
							<p>
								<?= ++$place; ?> <?= $this->lang('competitions_place') ?>
							</p>
							
						</div>
						<div class="under_leaders_icon">
							#<?= $dtls[2]; ?>#
						</div>
					</a>
				<?php } ?>
			</div>
			<?php } ?>
			
			<?php if( count($D->get_mostfollowed_groups) > 0 ) { $place = 0;  ?>
			<div class="ttl" style="margin-top:10px; margin-bottom:10px;"><div class="ttl2"><h3><?= $this->lang('competitions_group_competition2') ?></h3></div></div>
			<div class="slimusergroup" style="margin-right:-10px; margin-bottom:5px;">
				<?php foreach($D->get_mostfollowed_groups as $gid=>$dtls) { ?>
					<a href="<?= userlink($dtls[0]) ?>" class="slimuser" title="<?= htmlspecialchars($dtls[0]) ?>">
						<div class="leaders_icon" style="background: url('<?= $C->IMG_URL ?>avatars/thumbs1/<?= $dtls[1] ?>') no-repeat;">
							<p>
								<?= ++$place; ?> <?= $this->lang('competitions_place') ?>
							</p>
							
						</div>
						<div class="under_leaders_icon">
							#<?= $dtls[2]; ?>#
						</div>
					</a>
				<?php } ?>
			</div>
			<?php } ?>
			
		<?php } ?>
		
	<?php } else { ?>
	<div class="htabs" style="margin:0px; margin-bottom:6px; height:1px;"></div>
	<?php } ?>
	<div id="grouplist" class="groupspage">
	</div>
					
<?php
	
	$this->load_template('footer.php');
	
?>