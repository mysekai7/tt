<?php

	class qrcode
	{
		private $data;
		
		public function __construct(){
			$this->data = 'BEGIN:VCARD'."\n";
			$this->data .= 'VERSION:3.0'."\n";
		}
		
		public function fn($value){
			$this->data .= 'FN:'.$value."\n";	
		}
		public function nn($value){
			$this->data .= 'NICKNAME:'.$value."\n";	
		}
		
		public function work_phone($value){
			$this->data .= 'TEL;TYPE=WORK:'.$value."\n";	
		}
		
		public function home_phone($value){
			$this->data .= 'TEL;TYPE=HOME:'.$value."\n";	
		}
		
		public function email($value){
			$this->data .= 'EMAIL;TYPE=PREF,INTERNET:'.$value."\n";	
		}
		
		public function url($value){
			$this->data .= 'URL:'.$value."\n";	
		}
		
		public function finish(){
			$this->data .= 'END:VCARD';
		}
		
		public function get_link($size = 180, $EC_level = 'L', $margin = '0'){
			$this->data = urlencode($this->data); 
			return 'http://chart.apis.google.com/chart?chs='.$size.'x'.$size.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$this->data;
		}
	}
?>