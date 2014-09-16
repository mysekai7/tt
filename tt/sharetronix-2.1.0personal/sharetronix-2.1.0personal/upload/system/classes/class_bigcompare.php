<?php
	class bigcompare
	{
		private $biggest_number;
		
		public function __construct()
		{
			$biggest_number = FALSE;
		}
		
		public function if_valid_string($str)
		{
			$len = ( mb_strlen($str) - 1 ); 

			while($len > -1){
				if( !is_numeric($str[$len]) ) return FALSE;
				$len--;
			}	
			
			return TRUE;
		}
		
		public function try_new_candidate($num)
		{
			$num = (string) $num;

			if( !$this->if_valid_string($num) ){
				return FALSE;
			}
			
			if($this->biggest_number == FALSE){ 
				$this->biggest_number = (string) $num;
				return TRUE;
			}
			
			if( $num == $this->biggest_number){
				return TRUE; 
			}
			
			$candidate_len = mb_strlen($num);
			$biggy_len = mb_strlen($this->biggest_number);
			
			if( $candidate_len > $biggy_len ){
				$this->biggest_number = $num;
				return TRUE; 
			}elseif( $candidate_len < $biggy_len ){
				return TRUE;	
			}
			
			if( $candidate_len != $biggy_len){
				return FALSE;
			}
			
			$cnt = 0;
			$succ = FALSE;
			
			while($cnt < $biggy_len){
				if( $this->biggest_number[$cnt] == $num[$cnt] ){
					$cnt++;
					continue;
				}elseif($this->biggest_number[$cnt] < $num[$cnt]){
					$this->biggest_number = $num;
					$succ = TRUE;
				}else{
					$succ = FALSE;
					break;
				}
				
				$cnt++;	
			}
			
			return $succ;
		}
		
		public function try_new_candidate_by_array($arr)
		{
			$maximum = ( $this->biggest_number )? $this->biggest_number : '0';
			
			foreach($arr as $candidate){ 
				$this->try_new_candidate($candidate);
			}
		}
		
		public function get_biggest()
		{
			return ($this->biggest_number)? $this->biggest_number : 0;
		}
	}
?>