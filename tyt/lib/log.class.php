<?php 

class log{
	 
		public function write($file,$log){
		$f = fopen($file,"a+");
				if ($f == true){
				
				fwrite($f, $log); 
				fwrite($f, "\r\n"); 
				fclose($f);	
			}
		}
		





}