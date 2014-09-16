<?php
	$this->load_template('header.php');
?>

<div id="settings">

	<div id="settings_left">				
		<div class="ttl" style="margin-right:12px;"><div class="ttl2"><h3><?= $this->lang('api_doc_menu_title') ?></h3></div></div>
		<div class="sidenav">
			
			<?php
				for($i = 1; $i <= intval($this->lang('api_doc_cat_num')); $i++)
				{
			?>
					<a href="<?= $C->SITE_URL ?>api/documentation/show:<?= $i ?>" <?= ($D->choosen_param == $i)? 'class="onsidenav"': ''; ?>> 
						<?= htmlspecialchars($this->lang('api_doc_cat_'.$i)) ?> 
					</a>	
			<?php
				}
			?>
		
		</div>

		<div class="greygrad" style="margin-top:10px;"><div class="greygrad2"><div class="greygrad3">
			<?= $this->lang('api_doc_cant_understand') ?>
			<div class="klear"></div>
			<a href="<?= $C->SITE_URL ?>contacts" class="ubluebtn"><b><?= $this->lang('api_doc_contact_us') ?></b></a>
		</div></div></div>

	</div>
	<div id="settings_right">
				
		<div class="ttl"><div class="ttl2"><h3><?= htmlspecialchars($this->lang('api_doc_cat_'.$D->choosen_param)) ?></h3></div></div>
			
		<?php
			for($i = 1; $i <= intval($this->lang('api_doc_cat_'.$D->choosen_param.'_post_num')); $i++ )
			{
			?>
				<div class="faqq">
					<h3><?= $D->cat[$i]['title'] ?></h3>
					<div class="greygrad"><div class="greygrad2"><div class="greygrad3" style="padding-bottom:0px;">
						
						<p> <?= $D->cat[$i]['text'] ?> </p>
						
					</div></div></div>
				</div>	
			<?php
			}
		?>
	</div>
	
</div>

<?php
	$this->load_template('footer.php');
?>