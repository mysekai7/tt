<?php
	
	$this->load_template('header.php');
	
?>
					<div id="home_left">
						<?php if( $D->tab == 'group' ) { ?>
						<script type="text/javascript">
							pf_hotkeyopen_loadgroup	= "<?= $D->onlygroup->title ?>";
						</script>
						<a href="javascript:;" class="npbtn" id="postform_open_button" onclick="<?= ($C->SPAM_CONTROL)? 'spam_control':'postform_open' ?>(({groupname:'<?= $D->onlygroup->title ?>'}));" onfocus="this.blur();"><b><?= $this->lang('dbrd_left_newpost') ?></b></a>
						<?php } else { ?>
						<a href="javascript:;" class="npbtn" id="postform_open_button" onclick="<?= ($C->SPAM_CONTROL)? 'spam_control':'postform_open' ?>();" onfocus="this.blur();"><b><?= $this->lang('dbrd_left_newpost') ?></b></a>
						<?php } ?>
						
						<div id="homefltr">
							<a href="<?= $C->SITE_URL ?>dashboard" class="item mystr<?= $D->tab=='all'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_all') ?></strong><span><small id="dbrd_tab_all" style="<?= $D->tabs_state['all']==0||$D->tab=='all'?'display:none;':'' ?>"><?= $D->tabs_state['all'] ?></small></span></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:@me" class="item atme<?= $D->tab=='@me'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_@me', array('#USERNAME#'=>$this->user->info->username)) ?></strong><span><small id="dbrd_tab_mention" style="<?= $D->tabs_state['@me']==0||$D->tab=='@me'?'display:none;':'' ?>"><?= $D->tabs_state['@me'] ?></small></span></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:private" class="item prvt<?= $D->tab=='private'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_private') ?></strong><span><small id="dbrd_tab_private" style="<?= $D->tabs_state['private']==0||$D->tab=='private'?'display:none;':'' ?>"><?= $D->tabs_state['private'] ?></small></span></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:commented" class="item cmnt<?= $D->tab=='commented'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_commented') ?></strong><span><small id="dbrd_tab_commented" style="<?= $D->tabs_state['commented']==0||$D->tab=='commented'?'display:none;':'' ?>"><?= $D->tabs_state['commented'] ?></small></span></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:feeds" class="item xfed<?= $D->tab=='feeds'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_feeds') ?></strong><span><small id="dbrd_tab_feeds" style="<?= $D->tabs_state['feeds']==0||$D->tab=='feeds'?'display:none;':'' ?>"><?= $D->tabs_state['feeds'] ?></small></span></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:tweets" class="item xtwit<?= $D->tab=='tweets'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_tweets') ?></strong><span><small id="dbrd_tab_feeds" style="<?= $D->tabs_state['tweets']==0||$D->tab=='tweets'?'display:none;':'' ?>"><?= $D->tabs_state['tweets'] ?></small></span></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:bookmarks" class="item fvrt<?= $D->tab=='bookmarks'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_bookmarks') ?></strong></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:reshares" class="item resh<?= $D->tab=='reshares'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_reshares') ?></strong></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:everybody" class="item allp<?= $D->tab=='everybody'?' onitem':'' ?>"><b></b><strong><?= $this->lang('dbrd_leftmenu_everybody', array('#COMPANY#'=>$C->COMPANY)) ?></strong></a>
							<?php if( count($D->menu_groups) > 0 ) { ?>
								<a href="javascript:;" id="dbrd_menu_groupsbtn" class="dropio<?= $D->groupsmenu_active?' dropped':'' ?>" onclick="dbrd_groupmenu_toggle();" onfocus="this.blur();"><?= $this->lang('dbrd_leftmenu_groups') ?></a>
								<div id="dbrd_menu_groups" style="<?= $D->groupsmenu_active?'':'display:none;' ?>">
									<?php foreach($D->menu_groups as $g) { ?>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:group/g:<?= $g->groupname ?>" class="item<?= $D->tab=='group'&&$D->onlygroup->id==$g->id?' onitem':'' ?>" title="<?= htmlspecialchars($g->title) ?>"><b style="background-image:url('<?= $C->IMG_URL.'avatars/thumbs2/'.$g->avatar ?>');"></b><strong><?= htmlspecialchars(str_cut($g->title,14)) ?></strong></a>
									<?php } ?>
								</div>
								<script type="text/javascript">
									dbrd_grpmenu_showst	= <?= $D->groupsmenu_active ? 1 : 0 ?>;
								</script>
							<?php } ?>
						</div>
					</div>
					<div id="home_content">
						<?php if( $D->tab == 'group' ) { ?>
						<div class="ttl" style="margin-bottom:8px;">
							<div class="ttl2">
								<h3><a href="<?= $C->SITE_URL ?><?= $D->onlygroup->groupname ?>" title="<?= htmlspecialchars($D->onlygroup->title) ?>"><?= htmlspecialchars(str_cut($D->onlygroup->title, 50)) ?></a></h3>
								<div id="postfilter">
									<a href="javascript:;" onclick="dropdiv_open('postfilteroptions');" id="postfilterselected" onfocus="this.blur();"><span><?= $this->lang('posts_filter_'.$D->filter) ?></span></a>
									<div id="postfilteroptions" style="display:none;">
										<a href="<?= $C->SITE_URL ?>dashboard/tab:group/g:<?= $D->onlygroup->groupname ?>/filter:all"><?= $this->lang('posts_filter_all') ?></a>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:group/g:<?= $D->onlygroup->groupname ?>/filter:links"><?= $this->lang('posts_filter_links') ?></a>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:group/g:<?= $D->onlygroup->groupname ?>/filter:images"><?= $this->lang('posts_filter_images') ?></a>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:group/g:<?= $D->onlygroup->groupname ?>/filter:videos"><?= $this->lang('posts_filter_videos') ?></a>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:group/g:<?= $D->onlygroup->groupname ?>/filter:files" style="border-bottom:0px;"><?= $this->lang('posts_filter_files') ?></a>
									</div>
									<span><?= $this->lang('posts_filter_ttl') ?></span>
								</div>		
							</div>
						</div>
						<?php }elseif( $D->tab == 'reshares' ) { ?>
						<div class="htabs" style="margin-bottom:6px; margin-top:0px;">
							<strong><?= $this->lang('dbrd_reshtab_title') ?></strong>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:reshares/reshtab:byme" class="<?= $D->reshtab=='byme'?'onhtab':'' ?>"><b><?= $this->lang('dbrd_reshtab_subtab_byme') ?></b></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:reshares/reshtab:byother" class="<?= $D->reshtab=='byother'?'onhtab':'' ?>"><b><?= $this->lang('dbrd_reshtab_subtab_byother') ?></b></a>
						</div>
						<?php } elseif( $D->tab != 'private' ) { ?>
						<div class="ttl" style="margin-bottom:8px;">
							<div class="ttl2">
								<h3><?= $this->lang('dbrd_poststitle_'.$D->tab, array('#USERNAME#'=>$this->user->info->username, '#COMPANY#'=>htmlspecialchars($C->COMPANY))) ?></h3>
								<div id="postfilter">
									<a href="javascript:;" onclick="dropdiv_open('postfilteroptions');" id="postfilterselected" onfocus="this.blur();"><span><?= $this->lang('posts_filter_'.$D->filter) ?></span></a>
									<div id="postfilteroptions" style="display:none;">
										<a href="<?= $C->SITE_URL ?>dashboard/tab:<?= $D->tab ?>/filter:all"><?= $this->lang('posts_filter_all') ?></a>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:<?= $D->tab ?>/filter:links"><?= $this->lang('posts_filter_links') ?></a>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:<?= $D->tab ?>/filter:images"><?= $this->lang('posts_filter_images') ?></a>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:<?= $D->tab ?>/filter:videos"><?= $this->lang('posts_filter_videos') ?></a>
										<a href="<?= $C->SITE_URL ?>dashboard/tab:<?= $D->tab ?>/filter:files" style="border-bottom:0px;"><?= $this->lang('posts_filter_files') ?></a>
									</div>
									<span><?= $this->lang('posts_filter_ttl') ?></span>
								</div>		
							</div>
						</div>
						<?php } else { ?>
						<div class="htabs" style="margin-bottom:6px; margin-top:0px;">
							<strong><?= $this->lang('dbrd_privtab_title') ?></strong>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:private/filter:all/privtab:all" class="<?= $D->privtab=='all'?'onhtab':'' ?>"><b><?= $this->lang('dbrd_privtab_subtab_all') ?></b></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:private/filter:all/privtab:inbox" class="<?= $D->privtab=='inbox'?'onhtab':'' ?>"><b><?= $this->lang('dbrd_privtab_subtab_inbox') ?></b></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:private/filter:all/privtab:sent" class="<?= $D->privtab=='sent'?'onhtab':'' ?>"><b><?= $this->lang('dbrd_privtab_subtab_sent') ?></b></a>
							<a href="<?= $C->SITE_URL ?>dashboard/tab:private/filter:all/privtab:usr" class="<?= $D->privtab=='usr'?'onhtab':'' ?>"><b><?= $this->lang('dbrd_privtab_subtab_usr') ?></b></a>
						</div>
						<div id="pmfilter" style="display:<?= $D->privtab=='usr'&&!$D->privusr?'block':'none' ?>;">
							<form name="privform" method="POST" action="javascript:;" onsubmit="privmsg_usrfilter_setusr(this.privusr_inp.value,true); return false;">
							<?= $this->lang('dbrd_privtab_usrtb_txt') ?>: <input type="text" name="privusr_inp" value="" rel="autocomplete" autocompletecallback="privmsg_usrfilter_setusr(word);" style="width:178px;" />
							</form>
						</div>
						<div id="pmfilterok" style="display:<?= $D->privtab=='usr'&&$D->privusr?'block':'none' ?>;">
							<strong><?= $this->lang('dbrd_privtab_usrtb_txt') ?>&nbsp;</strong> <b><?= $D->privusr?$D->privusr->username:'' ?></b>
							<a href="javascript:;" onclick="privmsg_usrfilter_reset();" onfocus="this.blur();"><small><?= $this->lang('dbrd_privtab_usrtb_txt_x') ?></small></a>
						</div>
						<script type="text/javascript">
							var tmpfnc	= function() { try { document.privform.privusr_inp.focus(); } catch(e) {} };
							if( d.addEventListener ) {
								d.addEventListener("load", tmpfnc, false);
								w.addEventListener("load", tmpfnc, false);
							}
							else if( d.attachEvent ) {
								d.attachEvent("onload", tmpfnc);
								w.attachEvent("onload", tmpfnc);
							}
						</script>
						<?php } ?>
						<?php if($this->param('msg')=='deletedpost') { ?>
						<?= okbox($this->lang('msg_post_deleted_ttl'), $this->lang('msg_post_deleted_txt'), TRUE, 'margin-bottom:5px;') ?>
						<?php } ?>
						<div id="posts_html">
							<?= $D->posts_html ?>
						</div>
					</div>
					<div id="home_right">
						
						<?php if( $C->MOBI_DISABLED==0 ) { ?>
						<div id="mobiad">
							<strong><?= $this->lang('dbrd_right_mobi_title', array('#SITE_TITLE#' => $C->OUTSIDE_SITE_TITLE) ) ?></strong>
							<?= $this->lang('dbrd_right_mobi_text2') ?> <b title='<?= $C->SITE_URL.'m'; ?>'><a href="<?= $C->SITE_URL.'m'; ?>"><?= $D->mobi_site_url; ?></a></b>
						</div>
						<?php } ?>
						
						<div style="background: url('<?= $C->IMG_URL ?>custom/sharetronix-download_l.png') no-repeat; display: none; width: 195px; height: 56px; margin: 0 0 10px 0;">
							<p style="margin: 20px 0 0 50px; padding: 0; font-weight: bold;">
								<a href="http://sharetronix.com/sharetronix/download" target="_blank" style="font-size: 10px;">
									Download Sharetronix
								</a>	
							</p>
						</div>
						
						<div style="background: url('<?= $C->IMG_URL ?>custom/sharetronix-addons_l.png') no-repeat; display: none; width: 195px; height: 56px; margin: 0 0 10px 0;">
							<p style="margin: 20px 0 0 50px; padding: 0; font-weight: bold;">
								<a href="http://sharetronix.com/sharetronix/addons" target="_blank" style="font-size: 10px;">
									Sharetronix Add-ons
								</a>	
							</p>
						</div>
						
						<?php if($D->whattodo_active) { ?>
						<a href="javascript:;" id="closedgtd" style="display:<?= $D->whattodo_minimized?'block':'none' ?>;" onclick="dbrd_whattodo_show();" onfocus="this.blur();"><b><?= $this->lang('dbrd_whattodo_title_mnm') ?></b></a>
						<div id="greentodo" style="display:<?= $D->whattodo_minimized?'none':'block' ?>;">
							<div id="greentodo2">
								<div id="gtdttl">
									<b><?= $this->lang('dbrd_whattodo_title') ?></b>
									<a href="javascript:;" title="<?= $this->lang('dbrd_whattodo_closebtn') ?>" onclick="dbrd_whattodo_hide();" onfocus="this.blur();"></a>
								</div>
								<div id="gtdlist">
									<?php foreach($D->whattodo_lines as $k=>$line) { ?>
									<a href="<?= $line[0] ?>" class="<?= $k==0 ? 'frst' : ($k==count($D->whattodo_lines)-1 ? 'last' : '') ?>"><?= $this->lang($line[1]) ?></a>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php } ?>
						
						<?php if( count($D->last_online) > 0 ) { ?>
						<div class="ttl" style="margin-top:0px; margin-bottom:8px;"><div class="ttl2"><h3><?= $this->lang('dbrd_right_lastonline') ?></h3></div></div>
						<div class="slimusergroup" style="margin-right:-10px; margin-bottom:5px;">
							<?php foreach($D->last_online as $u) { ?>
							<a href="<?= userlink($u['username']) ?>" class="slimuser" title="<?= htmlspecialchars($u['username']) ?>"><img src="<?= $C->IMG_URL ?>avatars/thumbs1/<?= $u['avatar'] ?>" alt="" style="padding:3px;" /></a>
							<?php } ?>
						</div>
						<?php } ?>
						
						<?php if( count($D->saved_searches) > 0 ) { ?>
						<div class="ttl" style="margin-top:0px; margin-bottom:8px;"><div class="ttl2"><h3><?= $this->lang('dbrd_right_savedsearches') ?></h3></div></div>
						<div class="taglist" style="margin-bottom:5px;">
							<?php foreach($D->saved_searches as $id=>$tmp) { ?>
							<a href="<?= $C->SITE_URL ?>search/saved:<?= $tmp->search_key ?>" title="<?= htmlspecialchars($tmp->search_string) ?>"><?= preg_replace('/^\#/', '<small>#</small>', htmlspecialchars(str_cut($tmp->search_string,25))) ?></a>
							<?php } ?>
						</div>
						<?php } ?>
						
						<?php if( count($D->post_tags) > 0 ) { ?>
						<div class="ttl" style="margin-top:0px; margin-bottom:8px;"><div class="ttl2"><h3><?= $this->lang('dbrd_right_posttags') ?></h3></div></div>
						<div class="taglist" style="margin-bottom:5px;">
							<?php foreach($D->post_tags as $tmp) { ?>
							<a href="<?= $C->SITE_URL ?>search/posttag:%23<?= $tmp ?>" title="#<?= htmlspecialchars($tmp) ?>"><small>#</small><?= htmlspecialchars(str_cut($tmp,25)) ?></a>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
					<div class="klear"></div>
<?php
	
	$this->load_template('footer.php');
	
?>