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
		<link href="<?= $C->SITE_URL ?>themes/simplicity/css/inside.css?v=<?= $C->VERSION ?>" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/simplicity/js/inside.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/simplicity/js/inside_autocomplete.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/simplicity/js/inside_postform.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/simplicity/js/inside_posts.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/simplicity/js/swfobject.js"></script>
		<base href="<?= $C->SITE_URL ?>" />
		<script type="text/javascript"> var siteurl = "<?= $C->SITE_URL ?>"; </script>
		
		<?php if( isset($D->page_favicon) ) { ?>
		<link href="<?= $D->page_favicon ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 1 ) { ?>
		<link href="<?= $C->SITE_URL.'themes/simplicity/imgs/favicon.ico' ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 2 ) { ?>
		<link href="<?= $C->IMG_URL.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_FAVICON ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } ?>
		<?php if( $this->user->is_logged && $this->user->info->js_animations == "0" ) { ?>
		<script type="text/javascript"> disable_animations = true; </script>
		<?php } ?>
		<?php if( $this->lang('global_html_direction') == 'rtl' ) { ?>
		<style type="text/css"> body { direction:rtl; } </style>
		<?php } ?>
	</head>
	<body>
		<div id="header">
			<div id="header2">	
				<div id="theheader">
					<?php if( $C->HDR_SHOW_LOGO==2&&!empty($C->HDR_CUSTOM_LOGO) ) { 
						$logo_width = @getimagesize($C->IMG_DIR.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_LOGO); $logo_width = @intval($logo_width[0]);
						?>
						<a href="<?= $C->SITE_URL ?>" style="width:<?= $logo_width ?>px; background-image:url('<?= $C->IMG_URL.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_LOGO ?>');" id="logolink" title="<?= htmlspecialchars($C->SITE_TITLE) ?>"><b><?= htmlspecialchars($C->SITE_TITLE) ?></b></a>
					<?php } else { ?>
						<a href="<?= $C->SITE_URL ?>" id="logolink" title="<?= htmlspecialchars($C->SITE_TITLE) ?>"><b><?= htmlspecialchars($C->SITE_TITLE) ?></b></a>		
					<?php } ?>
					<div id="userlinks">			
						<?php if( $this->user->is_logged ) { ?>				
						<a href="<?= $C->SITE_URL.$this->user->info->username ?>"  class="username"><?= $this->user->info->username ?></a> &middot; 				
						<a href="<?= $C->SITE_URL ?>settings"><?= $this->lang('hdr_nav_settings') ?></a> &middot; 				
						<a href="<?= $C->SITE_URL ?>signout"><?= $this->lang('hdr_nav_signout') ?></a>			
						<?php } else { ?>		 				
						<a href="<?= $C->SITE_URL ?>signin"><?= $this->lang('hdr_nav_signin') ?></a> &middot; 				
						<a href="<?= $C->SITE_URL ?>signup"><?= $this->lang('hdr_nav_signup') ?></a>			 			
						<?php } ?> 		
					</div>		
					<div id="nav">							
						<a href="<?= $C->SITE_URL ?>dashboard" class="<?= $this->request[0]=='dashboard'||$this->request[0]=='home'?'onnav ':'' ?>homelink"><b><?= $this->lang('hdr_nav_home') ?></b></a>							
						<a href="<?= $C->SITE_URL ?>members" class="<?= $this->request[0]=='members'?'onnav':'' ?>"><b><?= $this->lang('hdr_nav_users') ?></b></a>							
						<a href="<?= $C->SITE_URL ?>groups" class="<?= $this->request[0]=='groups'?'onnav':'' ?>"><b><?= $this->lang('hdr_nav_groups') ?></b></a>							
						<?php if( $this->user->is_logged && $this->user->info->is_network_admin == 1 ) { ?>							
						<a href="<?= $C->SITE_URL ?>admin" class="<?= $this->request[0]=='admin'?'onnav':'' ?>"><b><?= $this->lang('hdr_nav_admin2') ?></b></a>							
						<?php } ?>		
					</div>							
					<div class="klear"></div>	
				</div>
			</div>
		</div>
		<div class="klear"></div>
		<div id="whiterow">
			<div id="whiterow2">
				<div id="pagebody">