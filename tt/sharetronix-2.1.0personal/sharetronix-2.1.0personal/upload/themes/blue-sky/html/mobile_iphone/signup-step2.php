<?php
	
	$this->load_template('mobile_iphone/header.php');
	
?>
		<div id="loginintro">
			<h2><?= $this->lang('m_signup_steps') ?> 2 / 2</h2>
		</div>
		
		<?php if($D->error) { ?>
			<?= errorbox($this->lang('signup_step2_error'), $this->lang($D->errmsg,$D->errmsg_lngkeys)); ?>
		<?php } ?>
		<div id="loginbox">
		<form method="post" name="lf" action="">
				<?php if( $C->USERS_EMAIL_CONFIRMATION ) { ?>
					<div>
						<?= $this->lang('signup_step2_form_email') ?>
						<div class="loginputdiv"><strong><?= $D->email ?> <img src="<?= $C->OUTSIDE_SITE_URL.'themes/'.$C->THEME ?>/imgs/greencheck.gif" alt="" /></strong>
						<?= $this->lang('signup_step2_email_confirmed') ?>
						</div>
					</div>
				<?php } else { ?>
					<div style="margin-top: 2px;">
						<?= $this->lang('signup_step2_form_email') ?>
						<div class="loginputdiv"><input type="text" name="email" value="<?= htmlspecialchars($D->email) ?>" autocomplete="off" /></div>
					</div>
				<?php } ?>
					<div style="margin-top: 2px;">
						<?= $this->lang('signup_step2_form_fullname') ?>
						<div class="loginputdiv"><input type="text" name="fullname" value="<?= htmlspecialchars($D->fullname) ?>" autocomplete="off" /></div>
					</div>
					
					<div style="margin-top: 2px;">
						<?= $this->lang('signup_step2_form_username') ?>
						<div class="loginputdiv"><input type="text" name="username" value="<?= htmlspecialchars($D->username) ?>" autocomplete="off" /></div>
					</div>
					
					<div style="margin-top: 2px;">
						<?= $this->lang('signup_step2_form_password') ?>
						<div class="loginputdiv"><input type="password" name="password" value="<?= htmlspecialchars($D->password) ?>" autocomplete="off" /></div>
					</div>
					
					<div style="margin-top: 2px;">
						<?= $this->lang('signup_step2_form_password2') ?>
						<div class="loginputdiv"><input type="password" name="password2" value="<?= htmlspecialchars($D->password2) ?>" autocomplete="off" /></div>
					</div>
					
					<div style="margin-top: 2px;">
					<?= $this->lang('signup_step2_form_captcha') ?>
					<?php if( !isset($C->GOOGLE_CAPTCHA_PRIVATE_KEY, $C->GOOGLE_CAPTCHA_PUBLIC_KEY) || $C->GOOGLE_CAPTCHA_PRIVATE_KEY == '' || $C->GOOGLE_CAPTCHA_PUBLIC_KEY == '' ){ ?>
						<input type="hidden" name="captcha_key" value="<?= $D->captcha_key ?>" />
						<div>
						<?= $D->captcha_html ?>
						</div>
						<div class="loginputdiv"><input type="text" maxlength="20" name="captcha_word" value="" autocomplete="off" style="width:168px; margin-top:5px;" /></div>
					<?php }else{ ?>
						<div>
						<?= $D->captcha_html ?>
						</div>
					<?php } ?>
					</div>
				
				<?php if( $D->terms_of_use ) { ?>
				<div style="margin-top: 2px;">
						<input type="checkbox" name="accept_terms" value="1" <?= $D->accept_terms?'checked="checked"':'' ?> style="float:left;" />
						<div style="float:left; margin-left:5px; margin-top:3px;"><?= $this->lang('signup_step2_form_terms',array('#SITE_TITLE#'=>$C->SITE_TITLE,'#A2#'=>'</a>','#A1#'=>'<a href="'.$C->SITE_URL.'terms" target="_blank">')) ?></div>
						<div class="klear"></div>
				</div>
				<?php } ?>
				<div style="margin-top: 2px;">
					<a href="javascript:;" id="loginbtn" onclick="document.lf.submit();"><strong><?= $this->lang('m_signup_continue') ?></strong></a>
				</div>
		</form>
		</div>
<?php
	
	$this->load_template('mobile_iphone/footer.php');
	
?>	