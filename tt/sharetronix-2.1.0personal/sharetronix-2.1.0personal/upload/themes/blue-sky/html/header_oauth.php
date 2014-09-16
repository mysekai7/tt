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
		<link href="<?= $C->SITE_URL ?>themes/blue-sky/css/inside.css?v=<?= $C->VERSION ?>" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/inside.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/inside_autocomplete.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/inside_postform.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/inside_posts.js?v=<?= $C->VERSION ?>"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/blue-sky/js/swfobject.js"></script>
		<base href="<?= $C->SITE_URL ?>" />
		<script type="text/javascript"> var siteurl = "<?= $C->SITE_URL ?>"; </script>
		
		<?php if( isset($D->page_favicon) ) { ?>
		<link href="<?= $D->page_favicon ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 1 ) { ?>
		<link href="<?= $C->SITE_URL.'themes/blue-sky/imgs/favicon.ico' ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 2 ) { ?>
		<link href="<?= $C->IMG_URL.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_FAVICON ?>" type="image/x-icon" rel="shortcut icon" />
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
						<div id="userstuff">
							<div id="userlinks">
								<a href="<?= $C->SITE_URL ?>signin"><b><?= $this->lang('hdr_nav_signin') ?></b></a>
								<a href="<?= $C->SITE_URL ?>signup"><b><?= $this->lang('hdr_nav_signup') ?></b></a>	
							</div>
						</div>		 	
					</div>
				</div>
				<div id="pagebody">