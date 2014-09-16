<?php
		
	$this->load_template('header.php');
	
?>
	<div id="contacts_left">

		
		<?php  
			if($D->complete) echo okbox('Success', $D->msg, FALSE);
					
			if($D->user_apps && !$D->post_fields_error)
			{	
		?>
				<div class="ttl"><div class="ttl2"><h3><?= $this->lang('api_heading_1'); ?></h3></div></div>
	
				<?php
					for($j=0; $j<count($D->user_apps_info); $j++)
					{
				?>
						<div class="app" style="border-bottom:0px;">
							<a href="<?= $C->SITE_URL?>api/details?app_id=<?= $D->user_apps_info[$j][2] ?>" class="appavatar">
								<img src="<?= $C->IMG_URL ?>avatars/thumbs1/<?= $D->user_apps_info[$j][3]; ?>"></a>
							<div class="appinfo">
								<a href="<?= $C->SITE_URL?>api/details?app_id=<?= $D->user_apps_info[$j][2] ?>" class="appname">
									<?= htmlspecialchars($D->user_apps_info[$j][0]) ?></a>
								<p>
									<?= htmlspecialchars($D->user_apps_info[$j][1]) ?>
								</p>
								<a href="<?= $C->SITE_URL ?>api/app:edit?app_id=<?= $D->user_apps_info[$j][2] ?>" 
									class="editapp"><?= $this->lang('api_edit'); ?></a>
							</div>
						</div>
				<?php
					}
				?>
				
				<?php
				if(!$this->param('app'))
				{
				?>
					<div class="greygrad"><div class="greygrad2"><div class="greygrad3">
						<?= $this->lang('api_more_apps', array("#SITE_TITLE#" => $C->SITE_TITLE)); ?>
						<div class="klear"></div>
						<a href="<?= $C->SITE_URL?>api/app:new" class="ubluebtn"><b><?= $this->lang('api_add'); ?></b></a>
					</div></div></div>
				<?php
				}
				?>

		<?php
			}elseif(!$D->user_apps && !($this->param('app')=='new') && !($this->param('app')=='edit'))
			{
				?>
					<p style='margin-left: 4px;'>
						<?= $this->lang('api_app_havent'); ?>
							<a href='<?= $C->SITE_URL ?>api/app:new' ><?= $this->lang('api_app_create'); ?></a>
					</p>
				<?php
			}
			if(($this->param('app')=='new' || $this->param('app')=='edit') && !$D->complete)
			{
		?>
				<div class="ttl"><div class="ttl2">
					<h3><?= ($this->param('app') == 'new')? $this->lang('api_a'): $this->lang('api_e');?> <?=$this->lang('api_app')?></h3>
					</div></div>
				<div class="greygrad" style="margin-top:5px;"><div class="greygrad2"><div class="greygrad3">
				
				<?= ($D->post_fields_error) ? errorbox($this->lang('api_fld_err'), $D->msg): '' ?>
	
			<table id="setform" cellspacing="5">
			<form action="<?= $C->SITE_URL ?>api/app:<?= $this->param('app') ?><?= 
					isset($_GET['app_id'])? '?app_id='.$_GET['app_id']:'' ?>" enctype="multipart/form-data" method='post'>
				<tr>
					<td class="setparam" valign="top"><?= $this->lang('api_icon_name') ?></td>
					<td>	<img src="<?= $C->IMG_URL ?>avatars/thumbs1/<?= 
					isset($D->data['avatar']) ? $D->data['avatar'] : $C->DEF_AVATAR_USER; ?>">
						<br /><input type="file" class="setinp" name="avatar" />
							<input type="hidden" name="current_avatar" value="<?=
							isset($D->data['avatar']) ? $D->data['avatar'] : $C->DEF_AVATAR_USER; ?>" />
						<div class="inputinfo"><?= $this->lang('api_avatar_mes') ?></div></td>
				</tr>
				<tr>
					<td class="setparam" valign="top"><?= $this->lang('api_app_name') ?></td>
					<td><input type="text" class="setinp" name="app_name"
							
							value="<?= isset($_POST['app_name'])? htmlspecialchars($_POST['app_name']): ''  ?><?=
									isset($D->data['name'])? htmlspecialchars($D->data['name']): ''?>" /></td>
				</tr>
				<tr>
					<td class="setparam" valign="top"><?= $this->lang('api_app_desc') ?></td>
					<td>
					<textarea name="description"><?= (!isset($_POST['description']))? '' : htmlspecialchars($_POST['description']) ?><?=
					isset($D->data['description'])? htmlspecialchars($D->data['description']):''?></textarea>
					</td>
				</tr>
				<tr>
					<td class="setparam" valign="top"><?= $this->lang('api_app_web') ?></td>
					<td>	<input type="text" class="setinp" name="app_website"
							value="<?= isset($_POST['app_website'])? htmlspecialchars($_POST['app_website']): ''  ?><?=
							isset($D->data['app_website'])? htmlspecialchars($D->data['app_website']): '' ?>" />
						<div class="inputinfo"><?= $this->lang('api_app_web_msg') ?></div></td>
				</tr>
				<tr>
					<td class="setparam" valign="top"><?= $this->lang('api_app_org') ?></td>
					<td>	<input type="text" class="setinp" name="organization"
							value="<?= isset($_POST['organization'])? htmlspecialchars($_POST['organization']): ''  ?><?=
							isset($D->data['organization'])? htmlspecialchars($D->data['organization']): ''?>" /></td>
				</tr>
				<tr>
					<td class="setparam" style="padding-top:2px;" valign="top"><?= $this->lang('api_app_type') ?></td>
					<td>
						<label><input type="radio" name="app_type" value="client" 
						onClick="document.forms[2].callback_url.disabled=true; document.forms[2].callback_url.value=''"
						<?= (isset($_POST['app_type']) && $_POST['app_type']=='client')? 'checked' : ''  ?><?= 
						(isset($D->data['app_type']) && $D->data['app_type']=='client')? 'checked' : ''  ?> />
							<span><?= $this->lang('api_app_type_c') ?></span></label>
						<label><input type="radio"  name="app_type" value="browser" 
						onClick="document.forms[2].callback_url.disabled=false"
						<?= (isset($_POST['app_type']) && $_POST['app_type']=='browser')? 'checked' : ''  ?><?= 
						(isset($D->data['app_type']) && $D->data['app_type']=='browser')? 'checked' : ''  ?><?= 
						(!isset($_POST['app_type']) && !isset($D->data['app_type']))? 'checked' : ''  ?> />
								 <span><?= $this->lang('api_app_type_b') ?> </span></label>
						<div class="inputinfo"><?= $this->lang('api_app_type_msg') ?></div>
						<ul>
							<li>
							<div class="inputinfo"><?= $this->lang('api_app_type_b_msg') ?></div>
							</li>
							<li>
							<div class="inputinfo"><?= $this->lang('api_app_type_c_msg') ?></div>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td class="setparam" valign="top"><?= $this->lang('api_app_callback') ?></td>
					<td><input type="text" class="setinp" name="callback_url"
							<?= ((isset($_POST['app_type']) && $_POST['app_type']=='client') 
								|| (isset($D->data['app_type']) && $D->data['app_type']=='client'))? 'disabled=\'true\'':'' ?>
							
							<?= isset($_POST['callback_url'])? 'value="'.htmlspecialchars($_POST['callback_url']).'"': ''?>
							<?= isset($D->data['callback_url'])? 'value="'.htmlspecialchars($D->data['callback_url']).'"': '' ?>" />
					<div class="inputinfo"><?= $this->lang('api_app_callback_msg') ?></div></td>
				</tr>
				<tr>
					<td class="setparam" style="padding-top:2px;" valign="top"><?= $this->lang('api_app_acc_type') ?></td>
					<td>
						<label><input type="radio" name="access_type" value="rw" <?= 
						(isset($_POST['access_type']) && $_POST['access_type']=='rw')? 'checked' : ''  ?><?= 
						(isset($D->data['acc_type']) && $D->data['acc_type']=='rw')? 'checked' : ''  ?> />
								<span><?= $this->lang('api_app_acc_rw') ?> </span>
						</label>
						<label><input type="radio"  name="access_type" value="r" <?= 
						(isset($_POST['access_type']) && $_POST['access_type']=='r')? 'checked' : ''  ?><?= 
						(isset($D->data['acc_type']) && $D->data['acc_type']=='r')? 'checked' : ''  ?><?= 
						(!isset($_POST['access_type']) && !isset($D->data['acc_type']))? 'checked' : ''  ?> />
						 <span><?= $this->lang('api_app_acc_r') ?>  </span>
						 </label>
						<div class="inputinfo"><?= $this->lang('api_app_acc_msg') ?></div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="<?= $this->lang('api_app_save') ?>" style="padding:4px; font-weight:bold;" name='submit' />
						<input type="submit" value="<?= $this->lang('api_app_cnl') ?>" style="padding:4px; font-weight:bold;" name='submit' /></td>
				</tr>
	
			</form>
			</table>
	
				</div></div></div>
		<?php
			}
		?>
	
			</div>
			<div id="contacts_right">
	
					
				<div class="ttl"><div class="ttl2"><h3><?= $this->lang('api_head_2', array('#SITE_TITLE#' => $C->SITE_TITLE)) ?></h3></div></div>
				<div class="greygrad"><div class="greygrad2"><div class="greygrad3">
					<?= $this->lang('api_hlp') ?>
					<div class="klear"></div>
					<a href="<?= $C->SITE_URL ?>api/documentation" class="ubluebtn"><b><?= $this->lang('api_doc') ?></b></a>
				</div></div></div>
	
			</div>
			
	
	
<?php
	
	$this->load_template('footer.php');
	
?>