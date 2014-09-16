<?php
	
	$this->load_template('mobile_iphone/header.php');
	
?>
		<div id="loginintro">
			<h2><?= $this->lang('m_signup_steps') ?> 1 / 2</h2>
		</div>
	
		<?php if( !$D->submit || $D->error ) { ?>
		
			<?php if( $D->error ) { ?>
			<?= errorbox($this->lang('m_signup_error'), $this->lang($D->errmsg,$D->errmsg_lngkeys)) ?>
			<?php } else { ?>
			<div style="line-height:1.4; margin-bottom:5px;"><?= $this->lang('m_signup_using_email') ?></div>
			<?php } ?>
			<div id="loginbox">
				<form method="post" name="lf" action="">
					<?= $this->lang('m_signup_email') ?>
					<div class="loginputdiv"><input type="text" name="email" value="<?= htmlspecialchars($D->email) ?>" /></div>
					<a href="javascript:;" id="loginbtn" onclick="document.lf.submit();"><strong><?= $this->lang('m_signup_continue') ?></strong></a>
				</form>
			</div>
		<?php }else{ 
			echo okbox($this->lang('signup_step1_ok_ttl'), $this->lang('os_signup_step1_ok_txt_nocompany', array('#EMAIL#' => $D->email))); 
		} ?>
<?php
	
	$this->load_template('mobile_iphone/footer.php');
	
?>		