<?php
		
	$this->load_template('header.php');
	
?>	
		<div class="ttl" style="margin-bottom:10px;">
			<div class="ttl2">
				<h3><?= $this->lang('signup_subtitle', array('#SITE_TITLE#'=>$C->SITE_TITLE)) ?></h3>
				<div id="postfilter"><span><?= $this->lang('signup_step') ?> 1 / <?= $D->steps ?></span></div>
			</div>
		</div>
		
		<?php if( !$D->submit || $D->error ) { ?>
		
			<?php if( $D->error ) { ?>
			<?= errorbox($this->lang('signup_error'), $this->lang($D->errmsg,$D->errmsg_lngkeys)) ?>
			<?php } else { ?>
			<div style="line-height:1.4; margin-bottom:5px;"><?= $this->lang('os_signup_step1_form_text_ifcompany',array('#COMPANY#'=>isset($C->COMPANY)?$C->COMPANY:'','#NUM_MEMBERS#'=>$D->network_members)) ?></div>
			<?php } ?>
			<form method="post" action="<?= $C->SITE_URL ?>signup">
				<table id="regform" cellspacing="5">
					<tr>
						<td class="regparam"><?= $this->lang('os_signup_step1_form_email') ?></td>
						<td><input type="text" class="reginp" name="email" value="<?= htmlspecialchars($D->email) ?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="<?= $this->lang('os_signup_step1_form_submit') ?>" style="padding:4px; font-weight:bold;"/></td>
					</tr>
				</table>
			</form>
			<div id="loginlinks">
				<?php if( $D->fb_login_url && $this->param('get')!='facebook' ) { ?>
				<div style="float:left; margin-top:5px;">
					<a id="facebookconnect" href="<?= $D->fb_login_url; ?>"></a>
				</div>
				<?php } ?>
				<?php if( isset($C->TWITTER_CONSUMER_KEY,$C->TWITTER_CONSUMER_SECRET) && !empty($C->TWITTER_CONSUMER_KEY) && !empty($C->TWITTER_CONSUMER_SECRET) && $this->param('get')!='twitter' ) { ?>
				<div style="float:left; margin-top:7px;">
					<a id="twitterconnect" href="<?= $C->SITE_URL ?>twitter-connect?backto=<?= $C->SITE_URL ?>signin/get:twitter" title="Twitter Connect" style="float:left; margin-left:5px; margin-top:1px;"><b>Twitter</b></a>
				</div>
				<?php } ?>
			</div>
		
		<?php } else { ?>
		
			<div class="greenbox">
				<div class="greenbox2">
					<h2><?= $this->lang('os_signup_step1_ok_ttl') ?></h2>
					<?= $this->lang('os_signup_step1_ok_txt_ifcompany',array('#EMAIL#'=>$D->email,'#COMPANY#'=>$C->COMPANY,'#NUM_MEMBERS#'=>$D->network_members)) ?>
					<?php 
						if( $D->network_members == 0 ) {
						}
						else {
							echo '<b>';
							echo $this->lang( 'os_signup_step1_ok_ftr'.($D->network_members==1?1:2).'_ifcompany', array('#COMPANY#'=>$C->COMPANY, '#SITE_TITLE#'=>$C->OUTSIDE_SITE_TITLE, '#NUM#'=>$D->network_members) );
							echo '</b>';
						}
					?>		
				</div>
			</div>
			
		<?php } ?>
<?php
	
	$this->load_template('footer.php');
	
?>