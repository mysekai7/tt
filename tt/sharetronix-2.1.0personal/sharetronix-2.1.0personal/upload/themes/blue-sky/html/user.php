<?php
	
	$this->load_template('header.php');
	
?>
		<div id="profile">
			<div id="profile2">
				<div id="profile_left">
					<?php if($D->is_my_profile) { ?>
					<div id="profileavatar"><a href="<?= $C->SITE_URL ?>settings/avatar"><img src="<?= $C->IMG_URL.'avatars/'.$D->usr->avatar ?>" alt="" border="0" /></a></div>
					<?php } else { ?>
					<div id="profileavatar"><img src="<?= $C->IMG_URL.'avatars/'.$D->usr->avatar ?>" /></div>
					<?php } ?>
					
					<?php if($this->user->is_logged && $this->user->id != $D->usr->id && $this->user->info->is_network_admin){ ?>
						<div class="user-settings">
							<a href="javascript: void(0);" style="padding: 5px 0 0 5px;" onClick="show_hide_div_by_id('usr-el-menu');">User Management </a>
						</div>
						<div class="user-elements" id="usr-el-menu">
							<a href="<?= $C->SITE_URL ?>admin/editusers/user:<?= trim(urlencode($D->usr->username)) ?>"><span>Edit Profile</span> </a>
							<a href="<?= $C->SITE_URL ?>admin/suspendusers?usrtosusp=<?= trim(urlencode($D->usr->username)) ?>"><span>Suspend User</span> </a>
							<a href="<?= $C->SITE_URL ?>admin/deleteuser?usrtodel=<?= trim(urlencode($D->usr->username)) ?>"><span>Delete User</span> </a>
						</div>
					<?php } ?>
					
					<?php if($D->profile_protected){?>
						<div class="prof_prot_message"> <span><?= $this->lang('post_profile_protected') ?></span></div>
					<?php } ?>
					
					<?php if(!$D->profile_protected){?>
					
						<div class="ttl" style="margin-bottom:3px;">
							<div class="ttl2">
								<h3><?= $this->lang('usr_left_cnt_ttl') ?></h3>
								<?php if($D->is_my_profile) { ?>
								<a href="<?= $C->SITE_URL ?>settings/contacts" class="ttlink"><?= $this->lang('usr_left_editlink') ?></a>
								<?php } elseif($D->tab != 'info') { ?>
								<a href="<?= userlink($D->usr->username) ?>/tab:info" class="ttlink"><?= $this->lang('usr_left_cnt_more') ?></a>
								<?php } ?>
							</div>
						</div>
						
						<table cellpadding="0" cellspacing="3">
							<tr>
								<td class="contactparam"><img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/cicons_username.gif" alt="" title="<?= htmlspecialchars($D->usr->fullname) ?>" /></td>
								<td class="contactvalue"><?= $D->usr->username ?></td>
							</tr>
							<?php if( !empty($D->usr->details['website']) ) { ?>
							<tr>
								<td class="contactparam"><img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/cicons_site.gif" alt="" title="<?= $this->lang('usr_left_cnt_site') ?>" /></td>
								<td class="contactvalue"><a href="<?= htmlspecialchars($D->usr->details['website']) ?>" title="<?= htmlspecialchars($D->usr->details['website']) ?>" target="_blank"><?= htmlspecialchars(str_cut(preg_replace('/^(http(s)?|ftp)\:\/\/(www\.)?/','',$D->usr->details['website']),25)) ?></a></td>
							</tr>
							<?php } ?>
							<?php if( !empty($D->usr->details['personal_email']) && $D->he_follows_me) { ?>
							<tr>
								<td class="contactparam"><img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/cicons_mail.gif" alt="" title="<?= $this->lang('usr_left_cnt_pemail') ?>" /></td>
								<td class="contactvalue"><a href="mailto:<?= htmlspecialchars($D->usr->details['personal_email']) ?>" target="_blank"><?= htmlspecialchars(str_cut($D->usr->details['personal_email'],25)) ?></a></td>
							</tr>
							<?php } ?>
							<?php if( !empty($D->usr->details['work_phone']) ) { ?>
							<tr>
								<td class="contactparam"><img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/cicons_phone.gif" alt="" title="<?= $this->lang('usr_left_cnt_wphone') ?>" /></td>
								<td class="contactvalue"><?= htmlspecialchars(str_cut($D->usr->details['work_phone'],25)) ?></td>
							</tr>
							<?php } ?>
							<?php if( !empty($D->usr->details['personal_phone']) ) { ?>
							<tr>
								<td class="contactparam"><img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/cicons_phone.gif" alt="" title="<?= $this->lang('usr_left_cnt_pphone') ?>" /></td>
								<td class="contactvalue"><?= htmlspecialchars(str_cut($D->usr->details['personal_phone'],25)) ?></td>
							</tr>
							<?php } ?>
						</table>
						<?php if( count($D->personal_tags)>0 ) { ?>
						<div class="ttl" style="margin-top:5px; margin-bottom:5px;">
							<div class="ttl2">
								<h3><?= $this->lang('usr_left_tgsubx_ttl') ?></h3>
								<?php if($D->is_my_profile) { ?>
								<a href="<?= $C->SITE_URL ?>settings/profile" class="ttlink"><?= $this->lang('usr_left_editlink') ?></a>
								<?php } ?>
							</div>
						</div>
						<div class="taglist">
							<?php foreach($D->personal_tags as $t) { ?>
							<a href="<?= $C->SITE_URL ?>search/usertag:<?= urlencode($t) ?>" title="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars(str_cut($t, 20)) ?></a>
							<? } ?>
						</div>
						<?php } ?>
						
						<?php if( count($D->post_tags) > 0 ) { ?>
						<div class="ttl" style="margin-top:5px; margin-bottom:5px;"><div class="ttl2"><h3><?= $this->lang('usr_left_posttags') ?></h3></div></div>
						<div class="taglist">
							<?php foreach($D->post_tags as $tmp) { ?>
							<a href="<?= $C->SITE_URL ?>search/posttag:%23<?= $tmp ?>" title="#<?= htmlspecialchars($tmp) ?>"><small>#</small><?= htmlspecialchars(str_cut($tmp,25)) ?></a>
							<?php } ?>
						</div>
						<?php } ?>
						
						<?php if( count($D->some_following) > 0 ) { ?>
						<div class="ttl" style="margin-bottom:8px; margin-top:4px;">
							<div class="ttl2">
								<h3><?= $this->lang('usr_left_following') ?></h3>
								<?php if( count($D->some_following) > 6 ) { ?>
								<a href="<?= $C->SITE_URL ?><?= $D->usr->username ?>/tab:coleagues/filter:ifollow" class="ttlink"><?= $this->lang('usr_left_flw_more') ?></a>
								<? } ?>
							</div>
						</div>
						<div class="slimusergroup">
							<?php $i=0; foreach($D->some_following as $u) { ?>
							<a href="<?= $C->SITE_URL ?><?= $u->username ?>" class="slimuser" title="<?= htmlspecialchars($u->username) ?>"><img src="<?= $C->IMG_URL ?>avatars/thumbs1/<?= $u->avatar ?>" alt="" /></a>
							<?php if(++$i==6) { break; } } ?>
						</div>
						<?php } ?>
						<div class="ttl" style="margin-top:5px; margin-bottom:5px;">
							<div class="ttl2">
								<h3><?= $this->lang('usr_left_qr_code') ?></h3>
							</div>
						</div>
						<div id="qr_link" style="text-align: center; ">
							<img src="<?= $D->qr->get_link(180) ?>" border='0' />
						</div>
					
					<?php } ?>
				</div>
				<div id="profile_right">
					<div id="profilehdr">
						<?php if( $this->user->is_logged ) { ?>
						<div id="usermenu">
							<?php if( $D->is_my_profile ) { ?>
							<a href="javascript:;" onclick="<?= ($C->SPAM_CONTROL)? 'spam_control':'postform_open' ?>();" class="um_ptg" onmouseover="userpage_top_tooltip(this.firstChild.innerHTML);" onmouseout="userpage_top_tooltip('');"><b><?= $this->lang('usr_toplnks_newpost') ?></b></a>
							<a href="<?= $C->SITE_URL ?>settings" class="um_edit" onfocus="this.blur();" onmouseover="userpage_top_tooltip(this.firstChild.innerHTML);" onmouseout="userpage_top_tooltip('');"><b><?= $this->lang('usr_toplnks_settings') ?></b></a>
							<?php } else { ?>
							<a href="javascript:;" onclick="<?= ($C->SPAM_CONTROL)? 'spam_control':'postform_open' ?>(({username:'<?= $D->usr->username ?>'}));" class="um_pm" onmouseover="userpage_top_tooltip(this.firstChild.innerHTML);" onmouseout="userpage_top_tooltip('');"><b><?= $this->lang('usr_toplnks_private',array('#USERNAME#'=>$D->usr->username)) ?></b></a>
							<a href="javascript:;" onclick="postform_mention('<?= $D->usr->username ?>',true);" class="um_atuser" onfocus="this.blur();" onmouseover="userpage_top_tooltip(this.firstChild.innerHTML);" onmouseout="userpage_top_tooltip('');"><b><?= $this->lang('usr_toplnks_mention',array('#USERNAME#'=>$D->usr->username)) ?></b></a>
							<a href="javascript:;" id="usrpg_btn_follow" style="<?= $D->i_follow_him?'display:none':'' ?>" onclick="user_follow('<?= $D->usr->username ?>',this,'usrpg_btn_unfollow','<?= addslashes($this->lang('msg_follow_user_on',array('#USERNAME#'=>$D->usr->username))) ?>');" class="um_follow" onfocus="this.blur();" onmouseover="userpage_top_tooltip(this.firstChild.innerHTML);" onmouseout="userpage_top_tooltip('');"><b><?= $this->lang('usr_toplnks_follow',array('#USERNAME#'=>$D->usr->username)) ?></b></a>
							<a href="javascript:;" id="usrpg_btn_unfollow" style="<?= $D->i_follow_him?'':'display:none' ?>" onclick="user_unfollow('<?= $D->usr->username ?>',this,'usrpg_btn_follow','<?= addslashes($this->lang('user_unfollow_confirm',array('#USERNAME#'=>$D->usr->username))) ?>','<?= addslashes($this->lang('msg_follow_user_off',array('#USERNAME#'=>$D->usr->username))) ?>');" class="um_unfollow" onfocus="this.blur();" onmouseover="userpage_top_tooltip(this.firstChild.innerHTML);" onmouseout="userpage_top_tooltip('');"><b><?= $this->lang('usr_toplnks_unfollow',array('#USERNAME#'=>$D->usr->username)) ?></b></a>
							<?php } ?>
							<div id="usrpg_top_tooltip" class="umtt" style="display:none;"><div></div></div>
						</div>
						<?php } ?>
						<h2><?= empty($D->usr->fullname) ? htmlspecialchars($D->usr->username) : htmlspecialchars($D->usr->fullname) ?><?= (!$D->usr->active)? '<span style="color: red; display: inline;">'.$this->lang('usr_is_suspended').'</span>':'' ?></h2>
						<span><?= htmlspecialchars($D->usr->position) ?></span>
						<div id="profilenav">
							<a href="<?= userlink($D->usr->username) ?>" class="<?= $D->tab=='updates'?'onptab':'' ?>"><b><?= $this->lang('usr_tab_updates') ?></b></a>
							<?php if(!$D->profile_protected){?>
								<a href="<?= userlink($D->usr->username) ?>/tab:info" class="<?= $D->tab=='info'?'onptab':'' ?>"><b><?= $this->lang('usr_tab_info') ?></b></a>
								<a href="<?= userlink($D->usr->username) ?>/tab:coleagues" class="<?= $D->tab=='coleagues'?'onptab':'' ?>"><b><?= $this->lang('usr_tab_coleagues') ?></b></a>
								<a href="<?= userlink($D->usr->username) ?>/tab:groups" class="<?= $D->tab=='groups'?'onptab':'' ?>"><b><?= $this->lang('usr_tab_groups') ?></b></a>
							<?php } ?>
							<?php if($D->tab == 'updates') { ?>
							<a href="<?= $C->SITE_URL ?>rss/username:<?= $D->usr->username ?>" id="rssicon" title="<?= $this->lang('usr_updates_rss_dsc',array('#USERNAME#'=>$D->usr->username)) ?>" target="_blank"><?= $this->lang('usr_updates_rss') ?></a>
							<?php } ?>
						</div>
					</div>
					
				<?php if( $D->tab == 'updates' ) { ?>
					<div class="htabs" style="margin-bottom:6px;">
						<a href="<?= userlink($D->usr->username) ?>/tab:updates/filter:posts" class="<?= $D->filter=='posts'?'onhtab':'' ?>"><b><?= $D->filter2_title ?><?= ($D->filter=='posts')? '('.$D->num_results.')':'' ?></b></a>
						<a href="<?= userlink($D->usr->username) ?>/tab:updates/filter:reshares" class="<?= $D->filter=='reshares'?'onhtab':'' ?>"><b><?= $D->filter5_title ?><?= ($D->filter=='reshares')? '('.$D->num_results.')':'' ?></b></a>
						<a href="<?= userlink($D->usr->username) ?>/tab:updates/filter:tweets" class="<?= $D->filter=='tweets'?'onhtab':'' ?>"><b><?= $D->filter3_title ?><?= ($D->filter=='tweets')? '('.$D->num_results.')':'' ?></b></a>
						<a href="<?= userlink($D->usr->username) ?>/tab:updates/filter:rss" class="<?= $D->filter=='rss'?'onhtab':'' ?>"><b><?= $D->filter4_title ?><?= ($D->filter=='rss')? '('.$D->num_results.')':'' ?></b></a>
						<a href="<?= userlink($D->usr->username) ?>/tab:updates/filter:all" class="<?= $D->filter=='all'?'onhtab':'' ?>"> <b><?= $D->filter1_title ?><?= ($D->filter=='all')? '('.$D->num_results.')':'' ?></b></a>
					</div>
					<?php if($this->param('msg')=='deletedpost') { ?>
					<?= okbox($this->lang('msg_post_deleted_ttl'), $this->lang('msg_post_deleted_txt'), TRUE, 'margin-bottom:6px;') ?>
					<?php } ?>
					<div id="userposts">
						<div id="posts_html">
							<?= $D->posts_html ?>
						</div>
					</div>
				<?php } elseif( $D->tab == 'coleagues' ) { ?>
					<div class="htabs" style="margin-bottom:6px;">
						<a href="<?= userlink($D->usr->username) ?>/tab:coleagues/filter:ifollow" class="<?= $D->filter=='ifollow'?'onhtab':'' ?>"><b><?= $D->filter1_title ?> <small>(<?= $D->fnums['ifollow'] ?>)</small></b></a>
						<a href="<?= userlink($D->usr->username) ?>/tab:coleagues/filter:followers" class="<?= $D->filter=='followers'?'onhtab':'' ?>"><b><?= $D->filter2_title ?> <small>(<?= $D->fnums['followers'] ?>)</small></b></a>
						<?php if($D->show_tab_incommon){ ?>
						<a href="<?= userlink($D->usr->username) ?>/tab:coleagues/filter:incommon" class="<?= $D->filter=='incommon'?'onhtab':'' ?>"><b><?= $D->filter3_title ?> <small>(<?= $D->fnums['incommon'] ?>)</small></b></a>
						<?php } ?>
					</div>
					<div id="grouplist">
						<?= $D->users_html ?>
					</div>
				<?php } elseif( $D->tab == 'groups' ) { ?>
					<div id="grouplist">
						<div class="ttl" style="margin-top:8px; margin-bottom:6px;">
							<div class="ttl2">
								<h3><?= $D->groups_title ?></h3>
								<?php if( $D->num_results > 1 ) { ?>
								<div id="postfilter">
									<a href="javascript:;" onclick="dropdiv_open('postfilteroptions');" id="postfilterselected" onfocus="this.blur();"><span><?= $this->lang('groups_orderby_'.$D->orderby) ?></span></a>
									<div id="postfilteroptions" style="display:none;">
										<a href="<?= userlink($D->usr->username) ?>/tab:groups/orderby:name" style="float:none;"><?= $this->lang('groups_orderby_name') ?></a>
										<a href="<?= userlink($D->usr->username) ?>/tab:groups/orderby:date" style="float:none;"><?= $this->lang('groups_orderby_date') ?></a>
										<a href="<?= userlink($D->usr->username) ?>/tab:groups/orderby:users" style="float:none;"><?= $this->lang('groups_orderby_users') ?></a>
										<a href="<?= userlink($D->usr->username) ?>/tab:groups/orderby:posts" style="float:none; border-bottom:0px;"><?= $this->lang('groups_orderby_posts') ?></a>
									</div>
									<span><?= $this->lang('groups_orderby_ttl') ?></span>
								</div>
								<?php } ?>
							</div>
						</div>
						<?= $D->groups_html ?>
					</div>
				<?php } elseif( $D->tab == 'info' ) { ?>
					<div style="padding-top:8px;">
						<?php if( !empty($D->usr->about_me) ) { ?>
						<div class="ttl" style="margin-top:4px;"><div class="ttl2">
							<h3><?= $this->lang('usr_info_section_aboutme') ?></h3>
							<?php if( $D->is_my_profile ) { ?>
							<a class="ttlink" href="<?= $C->SITE_URL ?>settings/profile"><?= $this->lang('usr_info_edit') ?></a>
							<?php } ?>
						</div></div>
						<div style="margin-left:4px;">
							<table cellspacing="4">
								<tr>
								<td><?= htmlspecialchars($D->usr->about_me) ?></td>
								</tr>
							</table>
						</div>
						<?php } ?>
						<div class="ttl"><div class="ttl2">
							<h3><?= $this->lang('usr_info_section_details') ?></h3>
							<?php if( $D->is_my_profile ) { ?>
							<a class="ttlink" href="<?= $C->SITE_URL ?>settings/profile"><?= $this->lang('usr_info_edit') ?></a>
							<?php } ?>
						</div></div>
						<div style="margin-left:4px;">
							<table cellspacing="4">
								<?php if( !empty($D->usr->location) ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_location') ?></td>
									<td class="detailsvalue"><?= htmlspecialchars($D->usr->location) ?></td>
								</tr>
								<?php } ?>
								<?php if( !empty($D->usr->gender) ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_gender') ?></td>
									<td class="detailsvalue"><?= $this->lang('usr_info_aboutme_gender_'.$D->usr->gender) ?></td>
								</tr>
								<?php } ?>
								<?php if( !empty($D->birthdate) ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_birthdate') ?></td>
									<td class="detailsvalue"><?= $D->birthdate ?></td>
								</tr>
								<?php } ?>
								<?php if( !empty($D->i->website) ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_website') ?></td>
									<td class="detailsvalue"><a href="<?= htmlspecialchars($D->i->website) ?>" target="_blank"><?= htmlspecialchars($D->i->website) ?></a></td>
								</tr>
								<?php } ?>
								<?php if( !empty($D->i->personal_phone) ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_pphone') ?></td>
									<td class="detailsvalue"><?= htmlspecialchars($D->i->personal_phone) ?></td>
								</tr>
								<?php } ?>
								<?php if( !empty($D->i->work_phone) ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_wphone') ?></td>
									<td class="detailsvalue"><?= htmlspecialchars($D->i->work_phone) ?></td>
								</tr>
								<?php } ?>
								<?php if( !empty($D->i->personal_email) && $D->he_follows_me) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_pemail') ?></td>
									<td class="detailsvalue"><a href="mailto:<?= htmlspecialchars($D->i->personal_email) ?>" target="_blank"><?= htmlspecialchars($D->i->personal_email) ?></a></td>
								</tr>
								<?php } ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_datereg') ?></td>
									<td class="detailsvalue"><?= $D->date_register ?></td>
								</tr>
								<?php if( !empty($D->date_lastlogin) ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_info_aboutme_datelgn') ?></td>
									<td class="detailsvalue"><?= $D->date_lastlogin ?></td>
								</tr>
								<?php } ?>
								<?php if( $this->user->is_logged && $this->user->info->is_network_admin>0 ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_profile_lastloginip') ?></td>
									<td class="detailsvalue"><?= long2ip($D->usr->lastlogin_ip) ?></td>
								</tr>
								<?php } ?>
								<?php if( $this->user->is_logged && $this->user->info->is_network_admin>0 ) { ?>
								<tr>
									<td class="detailsparam"><?= $this->lang('usr_profile_regip') ?></td>
									<td class="detailsvalue"><?= long2ip($D->usr->reg_ip) ?></td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<?php if( count($D->i->prs) > 0 ) { ?>
						<div class="ttl" style="margin-top:4px;"><div class="ttl2">
							<h3><?= $this->lang('usr_info_section_xtprofiles') ?></h3>
							<?php if( $D->is_my_profile ) { ?>
							<a class="ttlink" href="<?= $C->SITE_URL ?>settings/contacts"><?= $this->lang('usr_info_edit') ?></a>
							<?php } ?>
						</div></div>
						<div style="margin-left:4px;">
							<table cellspacing="4">
								<tr>
								<?php $i=0; foreach($D->i->prs as $k=>$v) { $i++; ?>
									<td><img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/ext_<?= $k ?>.gif" alt="<?= $this->lang('usr_info_'.$k) ?>" title="<?= $this->lang('usr_info_'.$k) ?>"></td>
									<td width="150"><a href="<?= htmlspecialchars($v[0]) ?>" target="_blank"><?= htmlspecialchars($v[1]) ?></a></td>
								<?php if($i%4==0 && count($D->i->prs)>$i) { ?>
								</tr>
								<tr>
								<?php } } ?>
								</tr>
							</table>
						</div>
						<?php } ?>
						<?php if( count($D->i->ims) > 0 ) { ?>
						<div class="ttl" style="margin-top:4px;"><div class="ttl2">
							<h3><?= $this->lang('usr_info_section_messengers') ?></h3>
							<?php if( $D->is_my_profile ) { ?>
							<a class="ttlink" href="<?= $C->SITE_URL ?>settings/contacts"><?= $this->lang('usr_info_edit') ?></a>
							<?php } ?>
						</div></div>
						<div style="margin-left:4px;">
							<table cellspacing="4">
								<tr>
								<?php $i=0; foreach($D->i->ims as $k=>$v) { $i++; ?>
									<td><img src="<?= $C->SITE_URL.'themes/'.$C->THEME ?>/imgs/<?= $k ?>.gif" alt="<?= $this->lang('usr_info_'.$k) ?>" title="<?= $this->lang('usr_info_'.$k) ?>" /></td>
									<td width="170"><?= htmlspecialchars($v) ?></td>
								<?php if($i%4==0 && count($D->i->ims)>$i) { ?>
								</tr>
								<tr>
								<?php } } ?>
								</tr>
							</table>
						</div>
						<?php } ?>
					</div>
				<?php } ?>
				</div>
				<div class="klear"></div>
			</div>
		</div>
<?php
	
	$this->load_template('footer.php');
	
?>