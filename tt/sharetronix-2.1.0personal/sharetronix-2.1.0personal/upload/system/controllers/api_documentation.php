<?php
	function bb_apply_tags($text)
	{
		$text	= preg_replace( '#\[b\](.+?)\[/b\]#is', '<b>\\1</b>', $text );
		$text	= preg_replace( '#\[i\](.+?)\[/i\]#is', '<i>\\1</i>', $text );
		$text	= preg_replace( '#\[u\](.+?)\[/u\]#is', '<u>\\1</u>', $text );
		
		$text	= preg_replace( '#(^|\s)((http|https|news|ftp)://\w+[^\s\[\]]+)#ie', "bb_build_url('\\2', '\\2')", $text );
		$text = preg_replace( '#\[url\](\S+?)\[/url\]#ie', "bb_build_url('\\1', '\\1')", $text );
		$text = preg_replace( '#\[url\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/url\]#ie', "bb_build_url('\\1', '\\2')", $text );
		$text = preg_replace( '#\[url\s*=\s*(\S+?)\s*\](.*?)\[\/url\]#ie', "bb_build_url('\\1', '\\2')", $text );
		
		return $text;
	}
	
	function bb_build_url( $link, $txt )
	{
		$url	= array();
		$url['html']	= $link;
		$url['show']	= $txt;
		$url['st']	= '';
		$url['end']	= '';
		$skip_it = 0;
		
		if ( preg_match( "/([\.,\?]|&#33;)$/", $url['html'], $match) ) {
			$url['end'] .= $match[1];
			$url['html'] = preg_replace( "/([\.,\?]|&#33;)$/", "", $url['html'] );
			$url['show'] = preg_replace( "/([\.,\?]|&#33;)$/", "", $url['show'] );
		}
		
		$url['html'] = preg_replace( "/&amp;/" , "&" , $url['html'] );
		
		$url['html'] = preg_replace( "/javascript:/i", "java script&#58;", $url['html'] );
		
		if ( ! preg_match("#^(http|news|https|ftp|aim)://#", $url['html'] ) ) {
			$url['html'] = 'http://'.$url['html'];
		}
		
		if (preg_match( "/^img src/i", $url['show'] )) $skip_it = 1;
		
		$url['show'] = preg_replace( "/&amp;/" , "&" , $url['show'] );
		$url['show'] = preg_replace( "/javascript:/i", "javascript&#58;", $url['show'] );
		
		if ( (strlen($url['show']) -58 ) < 3 )  $skip_it = 1;
		
		if (!preg_match( "/^(http|ftp|https|news):\/\//i", $url['show'] )) $skip_it = 1;
		
		$show     = $url['show'];
		
		if ($skip_it != 1) {
			$stripped = preg_replace( "#^(http|ftp|https|news)://(\S+)$#i", "\\2", $url['show'] );
			$uri_type = preg_replace( "#^(http|ftp|https|news)://(\S+)$#i", "\\1", $url['show'] );
			$show = $uri_type.'://'.substr( $stripped , 0, 35 ).'...'.substr( $stripped , -15   );
		}
		return $url['st'] . '<a href="'.$url['html'].'" target="_blank">'.$show.'</a>' . $url['end'];
	}
	
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('home');
	}
	
	$this->load_langfile('inside/api_documentation.php');
	$D->page_title	= $this->lang('api_doc_page_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));
	if($this->param('show')) $D->choosen_param = intval($this->param('show'));
		else $D->choosen_param = 1;
	
	if($D->choosen_param < 1 || $D->choosen_param > intval($this->lang('api_doc_cat_num'))) $D->choosen_param = 1;

	$D->cat = array();
	
	for($i = 1; $i <= intval($this->lang('api_doc_cat_'.$D->choosen_param.'_post_num')); $i++ )
	{
		$D->cat[$i]['title'] = htmlspecialchars($this->lang('api_doc_cat_'.$D->choosen_param.'_post_'.$i.'_title', 
											array('#SITE_TITLE#'=>$C->SITE_TITLE)));
		$D->cat[$i]['text'] = $this->lang('api_doc_cat_'.$D->choosen_param.'_post_'.$i.'_text', 
								array('#SITE_TITLE#'=>$C->SITE_TITLE, '#SITE_URL#'=>$C->SITE_URL));
		$D->cat[$i]['text'] = nl2br(bb_apply_tags($D->cat[$i]['text']));
	}
	
	$this->load_template('api_documentation.php');
	
?>