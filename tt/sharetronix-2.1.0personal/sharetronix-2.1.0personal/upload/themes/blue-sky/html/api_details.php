<?php		
	$this->load_template('header.php');	
?>

	<div id="contacts_left">

	<?php
		if(!$D->error && !isset($D->complete))
		{
	?>	
		<div class="app" style="border-bottom:0px;">
			<a href="<?= $C->SITE_URL ?>api/details?app_id=<?= $_GET['app_id'] ?>" class="appavatar">
				<img src="<?= $C->IMG_URL ?>avatars/thumbs1/<?= $D->data['avatar']; ?>">
			</a>
			<div class="appinfo">
				<h2 class="appname"><?= htmlspecialchars($D->data['name']); ?></h2>
				<p><?= htmlspecialchars($D->data['description']); ?></p>
				<a href="<?= $C->SITE_URL ?>api/app:edit?app_id=<?= $_GET['app_id'] ?>" class="editapp">
				<?= $this->lang('api_edit') ?></a>
			</div>
		</div>
		
		<div class="ttl"><div class="ttl2"><h3><?= $this->lang('api_app_details') ?></h3></div></div>
		<table cellspacing="7">
			<?php
				if($D->data['suspended'])
				{
			?>
					<tr>
						<td class="appparam" colspan='2' style='color: red; font-weight: bold;'>
							<?= $this->lang('api_app_suspended') ?>
						</td>	
					</tr>
			<?php
				}
			?>
			
			<tr>
				<td class="appparam"><?= $this->lang('api_app_c_key') ?> </td>
				<td><?= htmlspecialchars($D->data['consumer_key']); ?></td>
			</tr>
			<tr>
				<td class="appparam"><?= $this->lang('api_app_c_secret') ?></td>
				<td><?= htmlspecialchars($D->data['consumer_secret']); ?></td>
			</tr>
			<tr>
				<td class="appparam"><?= $this->lang('api_app_callback') ?> </td>
				<td><?= htmlspecialchars($D->data['callback_url']); ?></td>
			</tr>
			<tr>
				<td class="appparam"><?= $this->lang('api_app_web') ?> </td>
				<td><?= htmlspecialchars($D->data['app_website']); ?></td>
			</tr>
			<tr>
				<td class="appparam"><b><?= $this->lang('api_app_org') ?></b></td>
				<td><?= htmlspecialchars($D->data['organization']); ?></td>
			</tr>
			<tr>
				<td class="appparam"><?= $this->lang('api_app_type') ?></td>
				<td><?= $D->data['app_type']; ?></td>
			</tr>
			<tr>
				<td class="appparam"><?= $this->lang('api_app_acc_type') ?></td>
				<td><?= $D->data['acc_type']; ?></td>
			</tr>
			<tr>
				<td class="appparam"><?= $this->lang('api_app_date_reg') ?></td>
				<td><?= $D->data['reg_date']; ?></td>
			</tr>
			<tr>
				<td class="appparam"><?= $this->lang('api_app_ip_reg') ?></td>
				<td><?= $D->data['reg_ip']; ?></td>
			</tr>
			<tr>
				<td></td>
				<form action='<?= $C->SITE_URL ?>api_details?app_id=<?= $D->data['app_id']?>' method='post'>
				<td>
				<input style="padding:4px; font-weight:bold;" type='submit' name='delete' value='<?= $this->lang('api_app_del_msg2') ?>' />
				</td>
				</form>
			</tr>
		</table>
	<?php
		}elseif($D->error) echo errorbox($this->lang('api_err_ttl'), $D->err_msg, FALSE);
		elseif(isset($D->complete)) echo okbox($this->lang('api_ok_ttl'), $D->msg, FALSE);
		
		if(isset($_POST['delete']))
		{
		?>
			<a href="<?= $C->SITE_URL?>api" class="ubluebtn"><b><?= $this->lang('api_app_btn') ?></b></a>	
		<?php
		}
	?>

	</div>
	<div id="contacts_right">
		<div class="greygrad"><div class="greygrad2"><div class="greygrad3">
			<?= $this->lang('api_hlp') ?>
			<div class="klear"></div>
			<a href="<?= $C->SITE_URL?>api/documentation" class="ubluebtn"><b><?= $this->lang('api_doc') ?></b></a>
		</div></div></div>
		<div class="greygrad"><div class="greygrad2"><div class="greygrad3">
			<?= $this->lang('api_more_apps', array('#SITE_TITLE#' => $C->SITE_TITLE)) ?>
			<div class="klear"></div>
			<a href="<?= $C->SITE_URL?>api/app:new" class="ubluebtn"><b><?= $this->lang('api_add') ?></b></a>
		</div></div></div>

	</div>

<?php	
	$this->load_template('footer.php');	
?>