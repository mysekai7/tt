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
		<link href="<?= $C->SITE_URL ?>themes/facebookish/css/inside.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/facebookish/js/inside.js"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/facebookish/js/inside_autocomplete.js"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/facebookish/js/inside_postform.js"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/facebookish/js/inside_posts.js"></script>
		<script type="text/javascript" src="<?= $C->SITE_URL ?>themes/facebookish/js/swfobject.js"></script>
		<base href="<?= $C->SITE_URL ?>" />
		<script type="text/javascript"> var siteurl = "<?= $C->SITE_URL ?>"; </script>
		
		<?php if( isset($D->page_favicon) ) { ?>
		<link href="<?= $D->page_favicon ?>" type="image/x-icon" rel="shortcut icon" />
		<?php } elseif( $C->HDR_SHOW_FAVICON == 1 ) { ?>
		<link href="<?= $C->SITE_URL.'themes/facebookish/imgs/favicon.ico' ?>" type="image/x-icon" rel="shortcut icon" />
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
		<div id="fbhdr">
			<div id="fbhdr2">	
				<?php if( $C->HDR_SHOW_LOGO==2&&!empty($C->HDR_CUSTOM_LOGO) ) { 
					$logo_width = @getimagesize($C->IMG_DIR.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_LOGO); $logo_width = @intval($logo_width[0]);
					?>
					<a href="<?= $C->SITE_URL ?>" id="logolink" style="background-image:url('<?= $C->IMG_URL.'attachments/'.$this->network->id.'/'.$C->HDR_CUSTOM_LOGO ?>');width:<?= $logo_width ?>px;" title="<?= htmlspecialchars($C->SITE_TITLE) ?>"></a>
				<?php } else { ?>
					<a href="<?= $C->SITE_URL ?>" id="logolink" title="<?= htmlspecialchars($C->SITE_TITLE) ?>"></a>
				<?php } ?>			
				<?php if( $this->user->is_logged ) { ?>	
				<div id="fbhdrsearch" style='margin-left: 174px;'>	
					<a  style='color: white;' href="<?= $C->SITE_URL.'oauth/authorize?oauth_token='.$_GET['oauth_token'].'&signout=1' ?>">
									<b><?= $this->lang('hdr_nav_signas') ?></b></a>	
				</div>			
				<?php } ?>	
			</div>
		</div>
		<div id="whiterow">
			<div id="whiterow2">
				<div id="pagebody">