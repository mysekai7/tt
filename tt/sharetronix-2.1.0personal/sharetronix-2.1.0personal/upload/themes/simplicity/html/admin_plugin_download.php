<?php
	$this->load_template('header.php');	
?>
<script type="text/javascript" src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/js/inside_admintools.js"></script>
<div id="settings">
	<div id="settings_left">
		<?php $this->load_template('admin_leftmenu.php') ?>
	</div>
	<div id="settings_right">
		<div class="ttl">
			<div class="ttl2">
				<h3><?= $this->lang('admmenu_pd_ttl') ?></h3>
			</div>
		</div>
		<div class="greygrad" style="margin-top:5px;">
			<div class="greygrad2">
				<div class="greygrad3">
					<table id="setform" cellspacing="5" style="margin-top:5px;">
						<form method="post" name="admform" action="<?= $C->SITE_URL?>admin/plugin_download">
						<tr>
							<td width="150" class="setparam" valign="top" nowrap="nowrap"><strong><?= $this->lang('admmenu_pd_category') ?></strong></td>
							<td width="400">
								<select name="plugin_category" style="padding:4px; font-weight:bold;">
									<option value="0" <?= ($D->selected_option == 0)? 'selected':'' ?> ><?= $this->lang('admmenu_pd_cat_all') ?></option>
									<option value="1" <?= ($D->selected_option == 1)? 'selected':'' ?> ><?= $this->lang('admmenu_pd_cat_themes') ?></option>
									<option value="2" <?= ($D->selected_option == 2)? 'selected':'' ?> ><?= $this->lang('admmenu_pd_cat_plugins') ?></option>
									<option value="3" <?= ($D->selected_option == 3)? 'selected':'' ?> ><?= $this->lang('admmenu_pd_cat_apps') ?></option>
									<option value="4" <?= ($D->selected_option == 4)? 'selected':'' ?> ><?= $this->lang('admmenu_pd_cat_scripts') ?></option>
								</select>
							</td>
							<td>
								<input type="submit" value="<?= $this->lang('admmenu_pd_make_req') ?>" style="padding:4px; font-weight:bold;"  />
							</td>
						</tr>
						</form>
					</table>
					<table style="margin-top:5px;" cellspacing="5">
						<tr>
							<div id="plugin_data">
								<?= (count($D->req_result) == 0)? '<p style="text-align: center; width: 600px; margin-top: 40px;"> No add-ons found.</p>': ''; ?>
								
								<?php foreach($D->req_result as $item){ ?>	
									<tr style="border: 1px dashed gray;">
										<td valign="top">
											<?php $item->picture = ($item->picture != '')? $item->picture:'default-addon.png'; ?>
											<img src="http://sharetronix.com/sharetronix/img/addons/<?= $item->picture; ?>" width="32" height="32"><br />
										</td>
										<td>
										<strong><?= $item->name ?></strong><br />
										Description: <?= $item->description ?><br />
										<p style="text-align: center;">
										<strong><a href="http://sharetronix.com/sharetronix/addons/view/<?= $item->id ?>" target="_blank"><?= $this->lang('admmenu_pd_download_page') ?></a></strong>
										</p>
										<hr style="color: #ccc;background-color: #ccc;height: 1px;" />
										</td>	
										
									</tr>
								<?php } ?>
							</div>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	$this->load_template('footer.php');
?>