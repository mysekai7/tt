<?php
	
	$this->user->write_pageview();
	
	$hdr_search	= ($this->request[0]=='members' ? 'users' : ($this->request[0]=='groups' ? 'groups' : ($this->request[0]=='search' ? $D->tab : 'posts') ) );
	
	$this->load_langfile('inside/header.php');
	
	if(isset($_GET['signout']))
	{
		if( $this->user->is_logged ) $this->user->logout();
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title><?= htmlspecialchars($D->page_title) ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="<?= $C->SITE_URL ?>themes/default/css/inside.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/default/js/inside.js"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/default/js/inside_autocomplete.js"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/default/js/inside_postform.js"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/default/js/inside_posts.js"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/default/js/swfobject.js"></script>
		<base href="<?= $C->SITE_URL ?>" />
		<script type="text/javascript"> var siteurl = "<?= $C->SITE_URL ?>"; </script>
		
		<?php if( isset($D->page_favicon) ) { ?>
		<link href="<?= $D->page_favicon ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 1 ) { ?>
		<link href="<?= $C->SITE_URL.'themes/default/imgs/favicon.ico' ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 2 ) { ?>
		<link href="<?= $C->IMG_URL.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_FAVICON ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } ?>
		<?php if( $this->user->is_logged && $this->user->info->js_animations == "0" ) { ?>
		<script type="text/javascript"> disable_animations = true; </script>
		<?php } ?>
		<?php if( $this->lang('global_html_direction') == 'rtl' ) { ?>
		<style type="text/css"> #site { direction:rtl; } </style>
		<?php } ?>
	</head>
	<body>
		<div id="site">
			<div id="wholesite">
				<div id="toprow" class="<?= $this->request[0]=='dashboard'||$this->request[0]=='home'? 'specialhomelink' : '' ?>">
					<div id="toprow2">
						<?php if( $C->HDR_SHOW_LOGO == 1 ) { ?>
						<a href="<?= $C->SITE_URL ?>dashboard" id="logolink" title="<?= htmlspecialchars($C->SITE_TITLE) ?>"><strong><?= htmlspecialchars($C->SITE_TITLE) ?></strong></a>
						<?php } elseif( $C->HDR_SHOW_LOGO==2 && !empty($C->HDR_CUSTOM_LOGO) ) { ?>
						<a href="<?= $C->SITE_URL ?>dashboard" id="logolink_custom" title="<?= htmlspecialchars($C->SITE_TITLE) ?>"><img src="<?= $C->IMG_URL.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_LOGO ?>" alt="<?= htmlspecialchars($C->SITE_TITLE) ?>" /></a>	
						<?php } ?>
						<div id="userstuff">
							<?php if( $this->user->is_logged ) { ?>
							<div id="avatar"><img src="<?= $C->IMG_URL ?>avatars/thumbs2/<?= $this->user->info->avatar ?>" alt="" /></div>
							<a href="<?= $C->SITE_URL ?><?= $this->user->info->username ?>" id="username"><span><?= $this->user->info->username ?></span></a>
							<div id="userlinks">
								<a href="<?= $C->SITE_URL.'oauth/authorize?oauth_token='.$_GET['oauth_token'].'&signout=1' ?>">
									<b><?= $this->lang('hdr_nav_signas') ?></b></a>
							</div>
							<?php } else { ?>
							<div id="userlinks">
								<a href="<?= $C->SITE_URL ?>signin"><b><?= $this->lang('hdr_nav_signin') ?></b></a>
								<a href="<?= $C->SITE_URL ?>signup"><b><?= $this->lang('hdr_nav_signup') ?></b></a>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div id="pagebody">