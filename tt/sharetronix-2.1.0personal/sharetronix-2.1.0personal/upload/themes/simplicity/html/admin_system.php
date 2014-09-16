<?php
	
	$this->load_template('header.php');
	
?>
					<div id="settings">
						<div id="settings_left">
							<?php $this->load_template('admin_leftmenu.php') ?>
						</div>
						<div id="settings_right">
							<div class="ttl">
								<div class="ttl2">
									<h3><?= $this->lang('admtitle_system') ?></h3>
								</div>
							</div>
							<?php if($D->error) { ?>
							<?= errorbox($this->lang('admgnrl_error'), $this->lang($D->errmsg,array('#SITE_TITLE#'=>$C->OUTSIDE_SITE_TITLE)), TRUE, 'margin-top:5px;margin-bottom:5px;') ?>
							<?php } elseif($D->submit) { ?>
							<?= okbox($this->lang('admgnrl_okay'), $this->lang('admgnrl_okay_txt'), TRUE, 'margin-top:5px;margin-bottom:5px;') ?>
							<?php } ?>
							<div class="greygrad" style="margin-top:5px;">
								<div class="greygrad2">
									<div class="greygrad3" style="padding-top:0px;">
										<form method="post" action="">
											<table id="setform" cellspacing="5">
												<tr>
													<td class="setparam" valign="top"> <?= $this->lang('admsys_post_type') ?> </td>
													<td>
														<label style="float:left; margin-right:5px; clear:none;"><input type="radio" name="POST_TYPES_TO_AUTODELETE" value="feed" <?= (in_array('feed', $D->delete_posts_types))? 'checked="checked"':'' ?> /> <span> <?= $this->lang('admsys_post_type_opt1') ?> </span></label>
														<label style="float:left; clear:none;"><input type="radio" name="POST_TYPES_TO_AUTODELETE" value="human" <?= (in_array('human', $D->delete_posts_types))?'checked="checked"':'' ?> /> <span> <?= $this->lang('admsys_post_type_opt2') ?> </span></label>
														<label style="float:left; clear:none;"><input type="radio" name="POST_TYPES_TO_AUTODELETE" value="feed|human" <?= ( in_array('human', $D->delete_posts_types) && in_array('feed', $D->delete_posts_types) )? 'checked="checked"':'' ?> /> <span><?= $this->lang('admsys_post_type_opt3') ?> </span></label>
														<label style="float:left; clear:none;"><input type="radio" name="POST_TYPES_TO_AUTODELETE" value="none" <?= (in_array('none', $D->delete_posts_types))?'checked="checked"':'' ?> /> <span> <?= $this->lang('admsys_post_type_opt4') ?> </span></label>
													</td>
												</tr>
												
												<tr>
													<td class="setparam" valign="top"><?= $this->lang('admsys_post_period') ?>  </td>
													<td>
														<input type="text" size="3" maxlength="2" name="POST_TYPES_DELETE_PERIOD" value="<?= htmlspecialchars($D->delete_posts_period) ?>"> <?= $this->lang('admsys_days') ?>
													</td>
												</tr>
												
												<tr>
													<td></td>
													<td>
														<div class="greygrad" style="margin-top:5px;">
															<div class="greygrad2">
																<div class="greygrad3" style="font-size: 10px; margin-top: 0px;">
																	With this option you can clear your database from old posts. <br />
																	Setup the post types you want to be deleted from your database (rss, human or both). <br />
																	Also setup the period of time after which these posts will be deleted.
																</div>
															</div>
														</div>
															
													</td>
												</tr>
												
												<tr>
													<td></td>
													<td><input type="submit" name="sbm" value="<?= $this->lang('admgnrl_frm_sbm') ?>" style="padding:4px; font-weight:bold;"/></td>
												</tr>
											</table>
										</form>
									</div>
								</div>
							</div>
							
						</div>
					</div>
<?php
	
	$this->load_template('footer.php');
	
?>