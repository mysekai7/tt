<?php
	
	class pubsubhubbub
	{
		private $db1;
		private $hub_url;
		
		public function __construct($hub_url=FALSE)
		{
			$this->db1		= & $GLOBALS['db1'];
			$this->hub_url	= $hub_url;
		}
		
		public function set_hub($hub_url)
		{
			$this->hub_url	= $hub_url;
		}
		
		public function subscribe($topic_url, $how=TRUE)
		{
			$topic_url	= trim($topic_url);
			$is_subscribed	= FALSE;
			$r	= $this->db1->query('SELECT id, status FROM pubsubhubbub_subscriptions WHERE feed_url="'.$this->db1->e($topic_url).'" LIMIT 1', FALSE);
			if( $obj = $this->db1->fetch_object($r) ) {
				$is_subscribed	= $obj->status == 'subscribe';
			}
			if( $is_subscribed && $how ) {
				return TRUE;
			}
			if( !$is_subscribed && !$how ) {
				return TRUE;
			}
			$res	= $this->_httppost( array (
				'hub.callback'	=> $GLOBALS['C']->OUTSIDE_SITE_URL.'pubsubhubbub-callback',
				'hub.mode'		=> $how ? 'subscribe' : 'unsubscribe',
				'hub.topic'		=> $topic_url,
				'hub.verify'	=> 'async',
			) );
			if( ! $res ) {
				return FALSE;
			}
			if( $obj && $obj->id ) {
				$this->db1->query('UPDATE pubsubhubbub_subscriptions SET status="'.($how?'subscribe':'unsubscribe').'", last_status_date="'.time().'" WHERE id="'.$obj->id.'" LIMIT 1', FALSE);
			}
			else {
				$this->db1->query('INSERT INTO pubsubhubbub_subscriptions SET feed_url="'.$this->db1->e($topic_url).'", status="'.($how?'subscribe':'unsubscribe').'", last_status_date="'.time().'" ', FALSE);
			}
			return TRUE;
		}
		
		public function receive_notif($posted)
		{
			if( ! isset($posted['mode'], $posted['topic'], $posted['challenge']) ) {
				return FALSE;
			}
			$topic_url	= trim($posted['topic']);
			$r	= $this->db1->query('SELECT id, status FROM pubsubhubbub_subscriptions WHERE feed_url="'.$this->db1->e($topic_url).'" LIMIT 1', FALSE);
			if( ! $obj = $this->db1->fetch_object($r) ) {
				return FALSE;
			}
			if( $posted['mode'] != $obj->status ) {
				return FALSE;
			}
			$this->db1->query('UPDATE pubsubhubbub_subscriptions SET last_status_date="'.time().'", parse_needed=1 WHERE id="'.$obj->id.'" LIMIT 1', FALSE);
			return $posted['challenge'];
		}
		
		public function publish($topic_url)
		{
			$topic_url	= trim($topic_url);
			if( empty($topic_url) ) {
				return FALSE;
			}
			return	$this->_httppost( array (
				'hub.mode'	=> 'publish',
				'hub.url'	=> $topic_url,
			) );
		}
		
		private function _httppost($params=array())
		{
			if( ! $this->hub_url ) {
				return FALSE;
			}
			if( ! function_exists('curl_init') ) {
				return FALSE;
			}
			$ch	= curl_init();
			if( ! $ch ) {
				return FALSE;
			}
			$tmp	= array();
			foreach($params as $k=>$v) {
				$tmp[]	= $k.'='.urlencode($v);
			}
			$tmp	= implode('&', $tmp);
			curl_setopt_array($ch, array(
				CURLOPT_FOLLOWLOCATION	=> TRUE,
				CURLOPT_RETURNTRANSFER	=> TRUE,
				CURLOPT_HEADER	=> FALSE,
				CURLOPT_NOBODY	=> FALSE,
				CURLOPT_CONNECTTIMEOUT	=> 10,
				CURLOPT_TIMEOUT	=> 10,
				CURLOPT_MAXREDIRS	=> 5,
				CURLOPT_URL		=> $this->hub_url,
				CURLOPT_POST	=> TRUE,
				CURLOPT_POSTFIELDS	=> $tmp,
			) );
			$txt	= curl_exec($ch);
			$status	= FALSE;
			if( ! curl_errno($ch) ) {
				$code	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if( $code==204 || $code==202 ) {
					$status	= TRUE;
				}
			}
			curl_close($ch);
			return $status;
		}
	}
	
?>