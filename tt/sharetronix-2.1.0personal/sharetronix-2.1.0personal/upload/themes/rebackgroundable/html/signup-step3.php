<?php
		
	$this->load_template('header.php');
	
?>
		<link rel="stylesheet" href="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/css/user_selector.css" type="text/css" />
		<script type="text/javascript" src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/js/user_selector.js"></script>
		

		<div class="inviterttl">
			<b><?= $this->lang('os_signup_step3_selector_ttl', array('#SITE_TITLE#'=>$C->SITE_TITLE)) ?></b>
			<p><?= $this->lang('os_signup_step3_selector_txt', array('#A1#'=>'<a href="'.$C->SITE_URL.'dashboard">', '#A2#'=>'</a>')) ?></p>
		</div>
		<div class="reguserlist" id="reguserlist">
		</div>
	<?php if( $D->num_pages > 1 ) { ?> 
		
		<div class="paging" style="padding: 10px 0 10px 0; margin: 0; background: #F2F6FA;">
			<div class="paging2" style="background: #F2F6FA;">		
				<?php if($D->pg > 3) { ?>
				<a href="<?= $D->paging_url ?><?= $D->pg-1 ?>" class="pp"></a>
				<?php } ?>
				<?php 
				if($D->pg <= 5) {
					$mn	= 1;
					$mx	= min(5, $D->num_pages);
				}
				elseif($D->pg >= $D->num_pages-2) {
					$mn = $D->num_pages - min(5, $D->num_pages) + 1;
					$mx = $D->num_pages;
				}
				else {
					$mn = $D->pg-2;
					$mx = $D->pg+2;
				}
				for($i=$mn; $i<=$mx; $i++) { ?>
				<a href="<?= $D->paging_url ?><?= $i ?>" class="<?= $i==$D->pg?'onpage':'' ?>"><b><?= $i ?></b></a>
				<?php } ?>
				<?php if($D->pg < $D->num_pages-2) { ?>
				<a href="<?= $D->paging_url ?><?= $D->pg+1 ?>" class="np"></a>
				<?php } ?>
			</div>
		</div>
		
	<?php } ?>
		<div class="submitreguserlist" id="submitreguserlist" style="display:none;">
			
		
			<form method="post" action="" name="f">
				<input type="hidden" name="follow_users" value="" />
				<input type="submit" value="<?= $this->lang('signup_step3_selector_submit') ?>" />
				<?= $this->lang('signup_step3_selector_submit_or') ?>
				<a href="<?= $C->SITE_URL ?>dashboard"><b><?= $this->lang('signup_step3_selector_submit_or_skip') ?></b> &raquo;</a>
			</form>
		</div>
		<div class="klear"></div>
		
		<script type="text/javascript">
			siteurl += '<?= 'regid:'.$this->param('regid').'/' ?>';
			window.onload	= function() {
				var u = new UserSelector();
				u.form_input	= document.f.follow_users;
				u.container		= document.getElementById("reguserlist");
				u.avatars_url	= "<?= $C->IMG_URL ?>avatars/thumbs1/";
				u.texts.searchinp	= "<?= $this->lang('userselector_srchinp') ?>";
				u.texts.taball	= "<?= $this->lang('userselector_tab_all') ?>";
				u.texts.tabsel	= "<?= $this->lang('userselector_tab_sel') ?>";
				u.data	= <?= json_encode($D->members) ?>;
				u.onload	= function() {
					d.getElementById("submitreguserlist").style.display	= "block";
				};
				u.init();
			};
			pf_autoopen = false;
		</script>
<?php
	
	$this->load_template('footer.php');
	
?>