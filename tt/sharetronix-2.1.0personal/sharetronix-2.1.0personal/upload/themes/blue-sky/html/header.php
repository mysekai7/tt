<?php
	
	$this->user->write_pageview();
	
	$hdr_search	= ($this->request[0]=='members' ? 'users' : ($this->request[0]=='groups' ? 'groups' : ($this->request[0]=='search' ? $D->tab : 'posts') ) );
	
	$this->load_langfile('inside/header.php');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title><?= htmlspecialchars($D->page_title) ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="keywords" content="microblogging, sharetronix, blogtronix, enterprise microblogging">
		<link href="<?= $C->SITE_URL ?>themes/blue-sky/css/inside.css?v=<?= $C->VERSION ?>" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/inside.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/inside_autocomplete.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/inside_postform.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/inside_posts.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/swfobject.js"></script>
		<?php if($this->request[0]=='view'){ ?><script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><?php } ?>
		<base href="<?= $C->SITE_URL ?>" />
		<script type="text/javascript"> var siteurl = "<?= $C->SITE_URL ?>"; </script>
		
		<?php if( isset($D->page_favicon) ) { ?>
		<link href="<?= $D->page_favicon ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 1 ) { ?>
		<link href="<?= $C->SITE_URL.'themes/blue-sky/imgs/favicon.ico' ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 2 ) { ?>
		<link href="<?= $C->IMG_URL.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_FAVICON ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } ?>
		<?php if(isset($D->rss_feeds)) { foreach($D->rss_feeds as &$f) { ?>
		<link rel="alternate" type="application/atom+xml" title="<?= $f[1] ?>" href="<?= $f[0] ?>" />
		<?php }} ?>
		<?php if( $this->user->is_logged && $this->user->info->js_animations == "0" ) { ?>
		<script type="text/javascript"> disable_animations = true; </script>
		<?php } ?>
		<?php if( $C->SPAM_CONTROL ){ ?>
		<script type="text/javascript"> spam_control_check = true; spam_control_message = "<?= $this->lang('newpost_spam_filter_msg') ?>"; </script>
		<?php } ?>
		<?php if( $this->lang('global_html_direction') == 'rtl' ) { ?>
		<style type="text/css"> #site { direction:rtl; } </style>
		<?php } ?>
	</head>
	<body>
		<div id="site">
			<div id="wholesite">
				<div id="header">
					<div id="header2">
						<?php if( $C->HDR_SHOW_LOGO==2&&!empty($C->HDR_CUSTOM_LOGO) ) { 
							$logo_width = @getimagesize($C->IMG_DIR.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_LOGO); $logo_width = @intval($logo_width[0]);
							?>
							<a href="<?= $C->SITE_URL ?>dashboard" style="width:<?= $logo_width ?>px; background-image:url('<?= $C->IMG_URL.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_LOGO ?>');" id="logolink" title="<?= htmlspecialchars($C->SITE_TITLE) ?>"><b><?= htmlspecialchars($C->SITE_TITLE) ?></b></a>
						<?php } else { ?>
							<a href="<?= $C->SITE_URL ?>dashboard" id="logolink" title="<?= htmlspecialchars($C->SITE_TITLE) ?>"><b><?= htmlspecialchars($C->SITE_TITLE) ?></b></a>
						<?php } ?>
						<?php if( $this->user->is_logged ) { ?>
							<div id="userstuff">
								<a href="<?= $C->SITE_URL ?><?= $this->user->info->username ?>" id="username"><span><?= $this->user->info->username ?></span></a>
								<div id="userlinks">
									<a href="<?= $C->SITE_URL ?>settings"><b><?= $this->lang('hdr_nav_settings') ?></b></a>
									<a href="<?= $C->SITE_URL ?>leaders"><b><?= $this->lang('hdr_search_competitions') ?></b></a>
									<a href="<?= $C->SITE_URL ?>signout"><b><?= $this->lang('hdr_nav_signout') ?></b></a>
								</div>
							</div>
							<a href="<?= $C->SITE_URL ?>invite" id="hdrinvitelink"><?= $this->lang('os_hdr_nav_invite') ?></a>
						<?php } else { ?>	
							<div id="userstuff">
								<div id="userlinks">
									<a href="<?= $C->SITE_URL ?>signin"><b><?= $this->lang('hdr_nav_signin') ?></b></a>
									<a href="<?= $C->SITE_URL ?>signup"><b><?= $this->lang('hdr_nav_signup') ?></b></a>	
								</div>
							</div>
						<?php } ?>
						<div id="nav" style="<?= $C->HDR_SHOW_LOGO==2&&!empty($C->HDR_CUSTOM_LOGO) ? ('width:'.(779-$logo_width+161).'px') : '' ?>">
							<div id="navv">
								<div class="navitem">
									<a href="<?= $C->SITE_URL ?>dashboard" onmouseover="hdrmenu_hidesub2();" class="theitem home <?= $this->request[0]=='dashboard'||$this->request[0]=='home'?'onnav ':'' ?> homelink"><?= $this->lang('hdr_nav_home') ?></a>
								</div>
								<div class="navitem">
									<?php if(!$C->PROTECT_OUTSIDE_PAGES || $this->user->is_logged){ ?>
									<a href="<?= $C->SITE_URL ?>members" onmouseover="hdrmenu_dropsub('members');" onmouseout="hdrmenu_hidesub('members');" class="theitem <?= $this->request[0]=='members'?'onnav':'' ?>"><?= $this->lang('hdr_nav_users') ?></a>
									<?php } ?>
									<?php if( $this->user->is_logged ) { ?>
									<div id="hdr_drop_members" style="display:none;" class="subitems">
										<a href="<?= $C->SITE_URL ?>members"><?= $this->lang('hdr_navv_members_all') ?></a>
										<a href="<?= $C->SITE_URL ?>members/tab:ifollow"><?= $this->lang('hdr_navv_members_ifollow') ?></a>
										<a href="<?= $C->SITE_URL ?>members/tab:followers"><?= $this->lang('hdr_navv_members_followme') ?></a>
										<a href="<?= $C->SITE_URL ?>members/tab:admins"><?= $this->lang('hdr_navv_members_admins') ?></a>
									</div>
									<?php } ?>
								</div>
								<div class="navitem">
									<?php if(!$C->PROTECT_OUTSIDE_PAGES || $this->user->is_logged){ ?>
									<a href="<?= $C->SITE_URL ?>groups" onmouseover="hdrmenu_dropsub('groups');" onmouseout="hdrmenu_hidesub('groups');" class="theitem <?= $this->request[0]=='groups'?'onnav':'' ?>"><?= $this->lang('hdr_nav_groups') ?></a>
									<?php } ?>
									<?php if( $this->user->is_logged ) { ?>
									<div id="hdr_drop_groups" style="display:none;" class="subitems">
										<a href="<?= $C->SITE_URL ?>groups/tab:my"><?= $this->lang('hdr_navv_groups_all') ?></a>
										<a href="<?= $C->SITE_URL ?>groups/tab:all"><?= $this->lang('hdr_navv_groups_my') ?></a>
										<a href="<?= $C->SITE_URL ?>groups/new"><?= $this->lang('hdr_navv_groups_new') ?></a>
									</div>
									<?php } ?>
								</div>
								<?php if( $this->user->is_logged && $this->user->info->is_network_admin == 1 ) { ?>
								<div class="navitem">
									<a href="<?= $C->SITE_URL ?>admin" onmouseover="hdrmenu_hidesub2();" class="theitem <?= $this->request[0]=='admin'?'onnav':'' ?>"><?= $this->lang('hdr_nav_admin2') ?></a>
								</div>
								<?php } ?>			 			 		
							</div>		 							
							<div id="topsearch">
								<div id="topsearch2">
									<form name="search_form" method="post" action="<?= $C->SITE_URL ?>search">
										<input type="hidden" name="lookin" value="<?= $hdr_search ?>" />
										<div id="searchbtn"><input type="submit" value="<?= $this->lang('hdr_search_submit') ?>" /></div>
										<div class="searchselect">
											<a id="search_drop_lnk" href="javascript:;" onfocus="this.blur();" onclick="try{msgbox_close();}catch(e){}; dropdiv_open('search_drop_menu1');"><?= $this->lang('hdr_search_'.$hdr_search) ?></a>
											<div id="search_drop_menu1" class="searchselectmenu" style="display:none;">
												<a href="javascript:;" onclick="hdr_search_settype('posts',this.innerHTML);dropdiv_close('search_drop_menu1');" onfocus="this.blur();"><?= $this->lang('hdr_search_posts') ?></a>
												<a href="javascript:;" onclick="hdr_search_settype('users',this.innerHTML);dropdiv_close('search_drop_menu1');" onfocus="this.blur();"><?= $this->lang('hdr_search_users') ?></a>
												<a href="javascript:;" onclick="hdr_search_settype('groups',this.innerHTML);dropdiv_close('search_drop_menu1');" onfocus="this.blur();" style="border-bottom:0px;"><?= $this->lang('hdr_search_groups') ?></a>
											</div>
										</div>
										<div id="searchinput"><input type="text" name="lookfor" value="<?= isset($D->search_string)?htmlspecialchars($D->search_string):'' ?>" rel="autocomplete" autocompleteoffset="-6,4" /></div>
									</form>						
								</div>
							</div>		
						</div>		 	
					</div>
				</div>
				<div id="slim_msgbox" style="display:none;">
					<strong id="slim_msgbox_msg"></strong>
					<a href="javascript:;" onclick="msgbox_close('slim_msgbox'); this.blur();" onfocus="this.blur();"><b><?= $this->lang('pf_msg_okbutton') ?></b></a>
				</div>
				<?php if( $this->user->is_logged ) { ?>
				<div id="postform" style="display:none;">
					<form name="post_form" action="" method="post" onsubmit="return false;">
						<div id="pf_posting" style="display:none;">
							<img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/loading.gif" alt="" /><b><?= $this->lang('pf_msg_posting') ?></b>
						</div>
						<div id="pf_loading" style="display:none;">
							<img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/loading.gif" alt="" /><b><?= $this->lang('pf_msg_loading') ?></b>
						</div>
						<div id="pf_postedok" style="display:none;">
							<strong id="pf_postedok_msg"></strong>
							<a href="javascript:;" onclick="postform_topmsg_close();" onfocus="this.blur();"><b><?= $this->lang('pf_msg_okbutton') ?></b></a>
						</div>
						<div id="pf_postederror" style="display:none;">
							<strong id="pf_postederror_msg"></strong>
							<a href="javascript:;" onclick="postform_topmsg_close();" onfocus="this.blur();"><b><?= $this->lang('pf_msg_okbutton') ?></b></a>
						</div>
						<div id="pf_mainpart" style="display:none;">
							<script type="text/javascript">
								pf_msg_max_length	= <?= $C->POST_MAX_SYMBOLS ?>;
								pf_close_confirm	= "<?= $this->lang('pf_confrm_close') ?>";
								pf_rmatch_confirm	= "<?= $this->lang('pf_confrm_rmat') ?>";
							</script>
							<div id="pfhdr">
								<div id="pfhdrleft">
									<b id="pf_title_newpost"><?= $this->lang('pf_title_newmsg') ?></b>
									<b id="pf_title_edtpost" style="display:none;"><?= $this->lang('pf_title_edtmsg') ?></b>
									<div id="sharewith_user" class="pmuser" style="display:none;">
										<strong><?= $this->lang('pf_title_newmsg_usr') ?></strong> <input type="text" name="username" value="" rel="autocomplete" autocompleteoffset="0,3" autocompleteafter="d.post_form.message.focus(); postform_sharewith_user(d.post_form.username.value);" onblur="postform_bgcheck_username();" />
										<a href="javascript:;" onclick="dropdiv_open('updateoptions',-2);" onfocus="this.blur();"></a>
									</div>
									<div id="sharewith_group" class="pmuser" style="display:none;">
										<strong><?= $this->lang('pf_title_newmsg_grp') ?></strong> <input type="text" name="groupname" value="" rel="autocomplete" autocompleteoffset="0,3" autocompleteafter="d.post_form.message.focus(); postform_sharewith_group(d.post_form.groupname.value);" onblur="postform_bgcheck_groupname();" />
										<a href="javascript:;" onclick="dropdiv_open('updateoptions',-2);" onfocus="this.blur();"></a>
									</div>
									<div id="sharewith" onclick="dropdiv_open('updateoptions',-2);">
										<a href="javascript:;" id="selectedupdateoption" onfocus="this.blur();"><span defaultvalue="<?= $this->lang('os_pf_title_newmsg_all') ?>"></span><b></b></a>
										<div id="updateoptions" style="display:none;">
											<a href="javascript:;" onclick="postform_sharewith_all('<?= $this->lang('os_pf_title_newmsg_all') ?>');"><?= $this->lang('os_pf_title_newmsg_all') ?></a>
											<?php if( $this->request[0]=='user' && $this->params->user && $this->params->user!=$this->user->id && $tmp=$this->network->get_user_by_id($this->params->user) ) { ?>
											<a href="javascript:;" onclick="postform_sharewith_user('<?= htmlspecialchars($tmp->username) ?>');" onfocus="this.blur();" title="<?= htmlspecialchars($tmp->fullname) ?>"><?= htmlspecialchars(str_cut($tmp->username, 30)) ?></a>
											<?php } ?>
											<?php foreach($this->user->get_top_groups(10) as $g) { ?>
											<a href="javascript:;" onclick="postform_sharewith_group('<?= htmlspecialchars($g->title) ?>');" onfocus="this.blur();" title="<?= htmlspecialchars($g->title) ?>"><?= htmlspecialchars(str_cut($g->title, 30)) ?></a>
											<?php } ?>
											<!--
											<a href="javascript:;" onclick="postform_sharewith_findgroup();"><?= $this->lang('pf_title_newmsg_mngrp') ?></a>
											-->
											<a href="javascript:;" onclick="postform_sharewith_finduser();" style="border-bottom:0px;"><?= $this->lang('pf_title_newmsg_mnusr') ?></a>
										</div>
									</div>
								</div>
								<div id="pfhdrright">
									<a href="javascript:;" onclick="postform_close_withconfirm();" onfocus="this.blur();"></a>
									<small><?= $this->lang('pf_cnt_symbols_bfr') ?><span id="pf_chars_counter"><?= $C->POST_MAX_SYMBOLS ?></span><?= $this->lang('pf_cnt_symbols_aftr') ?></small>
								</div>
							</div>
							<textarea name="message" tabindex="1" rel="autocomplete" autocompleteoffset="0,3"></textarea>
							<div id="pfattach">
								<? if( $C->ATTACH_LINK_DISABLED==1 ) { echo '<div style="display:none;">'; }  ?>
								<a href="javascript:;" class="attachbtn" onclick="postform_attachbox_open('link', 96); this.blur();" id="attachbtn_link" tabindex="3"><b><?= $this->lang('pf_attachtab_link') ?></b></a>
								<? if( $C->ATTACH_LINK_DISABLED==1 ) { echo '</div>'; }  ?>
								<div id="attachok_link" class="attachok" style="display:none;"><span><b><?= $this->lang('pf_attached_link') ?></b> <em id="attachok_link_txt"></em> <a href="javascript:;" class="removeattachment" onclick="postform_attach_remove('link');" onfocus="this.blur();"></a></span></div>
								<? if( $C->ATTACH_IMAGE_DISABLED==1 ) { echo '<div style="display:none;">'; }  ?>
								<a href="javascript:;" class="attachbtn" onclick="postform_attachbox_open('image', 131); this.blur();" id="attachbtn_image" tabindex="3"><b><?= $this->lang('pf_attachtab_image') ?></b></a>
								<? if( $C->ATTACH_IMAGE_DISABLED==1 ) { echo '</div>'; }  ?>
								<div id="attachok_image" class="attachok" style="display:none;"><span><b><?= $this->lang('pf_attached_image') ?></b> <em id="attachok_image_txt"></em> <a href="javascript:;" class="removeattachment" onclick="postform_attach_remove('image');" onfocus="this.blur();"></a></span></div>
								<? if( $C->ATTACH_VIDEO_DISABLED==1 ) { echo '<div style="display:none;">'; }  ?>
								<a href="javascript:;" class="attachbtn" onclick="postform_attachbox_open('videoembed', 96); this.blur();" id="attachbtn_videoembed" tabindex="3"><b><?= $this->lang('pf_attachtab_videmb') ?></b></a>
								<? if( $C->ATTACH_VIDEO_DISABLED==1 ) { echo '</div>'; }  ?>
								<div id="attachok_videoembed" class="attachok" style="display:none;"><span><b><?= $this->lang('pf_attached_videmb') ?></b> <em id="attachok_videoembed_txt"></em> <a href="javascript:;" class="removeattachment" onclick="postform_attach_remove('videoembed');" onfocus="this.blur();"></a></span></div>
								<? if( $C->ATTACH_FILE_DISABLED==1 ) { echo '<div style="display:none;">'; }  ?>
								<a href="javascript:;" class="attachbtn" onclick="postform_attachbox_open('file', 96); this.blur();" id="attachbtn_file" tabindex="3"><b><?= $this->lang('pf_attachtab_file') ?></b></a>
								<? if( $C->ATTACH_FILE_DISABLED==1 ) { echo '</div>'; }  ?>
								<div id="attachok_file" class="attachok" style="display:none;"><span><b><?= $this->lang('pf_attached_file') ?></b> <em id="attachok_file_txt"></em> <a href="javascript:;" class="removeattachment" onclick="postform_attach_remove('file');" onfocus="this.blur();"></a></span></div>
								<a href="javascript:;" id="postbtn" onclick="postform_submit();" tabindex="2"><b id="postbtn_newpost"><?= $this->lang('pf_submit_newmsg') ?></b><b id="postbtn_edtpost" style="display:none;"><?= $this->lang('pf_submit_edtmsg') ?></b></a>
							</div>
						</div>
						<div id="attachbox" style="display:none;">
							<div id="attachboxhdr"></div>
							<div id="attachboxcontent">
								<div id="attachboxcontent_link" style="display:none;">
									<a href="javascript:;" class="closeattachbox" onclick="postform_attachbox_close();" onfocus="this.blur();"></a>
									<div class="attachform">
										<small id="attachboxtitle_link" defaultvalue="<?= $this->lang('pf_attachbx_ttl_link') ?>"></small>
										<input type="text" name="atch_link" value="" style="width:800px;" onpaste="postform_attach_pastelink(event,this,postform_attach_submit);" onkeyup="postform_attach_pastelink(event,this,postform_attach_submit);" />
									</div>
									<div id="attachboxcontent_link_ftr" class="submitattachment">
										<img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/loading.gif" alt="" style="margin-bottom:2px;" />
										<a href="javascript:;" class="submitattachmentbtn" onclick="postform_attach_submit();" onfocus="this.blur();"><b><?= $this->lang('pf_attachbtn_link') ?></b></a>
										<div class="orcancel"><?= $this->lang('pf_attachbtn_or') ?> <a href="javascript:;" onclick="postform_attachbox_close();" onfocus="this.blur();"><?= $this->lang('pf_attachbtn_orclose') ?></a></div>
									</div>
								</div>
								<div id="attachboxcontent_image" style="display:none;">
									<div class="litetabs">
										<a href="javascript:;" class="closeattachbox" onclick="postform_attachbox_close();" onfocus="this.blur();"></a>
										<a href="javascript:;" onclick="postform_attachimage_tab('upl');" id="attachform_img_upl_btn" class="onlitetab" onfocus="this.blur();"><b><?= $this->lang('pf_attachimg_tabupl') ?></b></a>
										<a href="javascript:;" onclick="postform_attachimage_tab('url');" id="attachform_img_url_btn" class="" onfocus="this.blur();"><b><?= $this->lang('pf_attachimg_taburl') ?></b></a>
									</div>
									<div class="attachform">
										<div id="attachform_img_upl_div">
											<small id="attachboxtitle_image_upl" defaultvalue="<?= $this->lang('pf_attachbx_ttl_imupl') ?>"></small>
											<input type="file" name="atch_image_upl" value="" size="50" />
										</div>
										<div id="attachform_img_url_div" style="display:none;">
											<small id="attachboxtitle_image_url" defaultvalue="<?= $this->lang('pf_attachbx_ttl_imurl') ?>"></small>
											<input type="text" name="atch_image_url" value="" style="width:800px;" onpaste="postform_attach_pastelink(event,this,postform_attach_submit);" onkeyup="postform_attach_pastelink(event,this,postform_attach_submit);" />
										</div>
									</div>
									<div id="attachboxcontent_image_ftr" class="submitattachment">
										<img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/loading.gif" alt="" style="margin-bottom:2px;" />
										<a href="javascript:;" class="submitattachmentbtn" onclick="postform_attach_submit();" onfocus="this.blur();"><b><?= $this->lang('pf_attachbtn_image') ?></b></a>
										<div class="orcancel"><?= $this->lang('pf_attachbtn_or') ?> <a href="javascript:;" onclick="postform_attachbox_close();" onfocus="this.blur();"><?= $this->lang('pf_attachbtn_orclose') ?></a></div>
									</div>
								</div>
								<div id="attachboxcontent_videoembed" style="display:none;">
									<a href="javascript:;" class="closeattachbox" onclick="postform_attachbox_close();" onfocus="this.blur();"></a>
									<div class="attachform">
										<small id="attachboxtitle_videoembed" defaultvalue="<?= $this->lang('pf_attachbx_ttl_videm') ?>"></small>
										<input type="text" name="atch_videoembed" value="" style="width:800px;" onpaste="postform_attach_pastelink(event,this,postform_attach_submit);" onkeyup="postform_attach_pastelink(event,this,postform_attach_submit);" />
									</div>
									<div id="attachboxcontent_videoembed_ftr" class="submitattachment">
										<img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/loading.gif" alt="" style="margin-bottom:2px;" />
										<a href="javascript:;" class="submitattachmentbtn" onclick="postform_attach_submit();" onfocus="this.blur();"><b><?= $this->lang('pf_attachbtn_videmb') ?></b></a>
										<div class="orcancel"><?= $this->lang('pf_attachbtn_or') ?> <a href="javascript:;" onclick="postform_attachbox_close();" onfocus="this.blur();"><?= $this->lang('pf_attachbtn_orclose') ?></a></div>
									</div>
								</div>
								<div id="attachboxcontent_file" style="display:none;">
									<a href="javascript:;" class="closeattachbox" onclick="postform_attachbox_close();" onfocus="this.blur();"></a>
									<div class="attachform">
										<small id="attachboxtitle_file" defaultvalue="<?= $this->lang('pf_attachbx_ttl_file') ?>"></small>
										<input type="file" name="atch_file" value="" size="50" />
									</div>
									<div id="attachboxcontent_file_ftr" class="submitattachment">
										<img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/loading.gif" alt="" style="margin-bottom:2px;" />
										<a href="javascript:;" class="submitattachmentbtn" onclick="postform_attach_submit();" onfocus="this.blur();"><b><?= $this->lang('pf_attachbtn_file') ?></b></a>
										<div class="orcancel"><?= $this->lang('pf_attachbtn_or') ?> <a href="javascript:;" onclick="postform_attachbox_close();" onfocus="this.blur();"><?= $this->lang('pf_attachbtn_orclose') ?></a></div>
									</div>
								</div>
							</div>
							<div id="attachboxftr"></div>
						</div>
					</form>
				</div>
				<?php } ?>
				<div id="pagebody">
					<?php if( isset($_GET['installed']) && $_GET['installed']=='ok' ) { ?>
						<?= okbox($this->lang('sharetronix_install_ok_ttl'), $this->lang('sharetronix_install_ok_txt',array('#VER#'=>$C->VERSION))) ?>
					<?php } ?>