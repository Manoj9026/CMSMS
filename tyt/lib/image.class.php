<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image
 *
 * @author oracle
 */
class image {
	public $image;
	public $image_type;
        public $image_extention;
        public $fold;
        public $_newWidth;
        public $_newHeight;
        public $waded = '0';
       //   public $watermark=__DIR__.'/watermark/opacity-30.png';  


        public function __construct($filename = null){
		if (!empty($filename)) {
			$this->load($filename);
		}
                $this->fold= new folder();
	}
	public function load($filename) {
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
              //  $mime = elplode('\'',$image_info['mime']);
             //   $this->image_extention = end($mime);
		if ($this->image_type == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif ($this->image_type == IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif($filename);
		} elseif ($this->image_type == IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng($filename);
		} else {
			throw new Exception("The file you're trying to open is not supported");
		}
	}
        
	public function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {		
            if ($this->image_type == IMAGETYPE_JPEG) {
                	imagejpeg($this->image,$filename,$compression);                        
		} elseif ($image_type == IMAGETYPE_GIF) {
                      imagegif($this->image,$filename);         
		} elseif ($this->image_type == IMAGETYPE_PNG) {
                      imagepng($this->image,$filename);
		}
		if ($permissions != null) {
			chmod($filename,$permissions);
		}                
	}
        
        public function output($image_type=IMAGETYPE_JPEG, $quality = 80) {
		if ($image_type == IMAGETYPE_JPEG) {
			header("Content-type: image/jpeg");
			imagejpeg($this->image, null, $quality);
		} elseif ($image_type == IMAGETYPE_GIF) {
			header("Content-type: image/gif");
			imagegif($this->image);         
		} elseif ($image_type == IMAGETYPE_PNG) {
			header("Content-type: image/png");
			imagepng($this->image);
		}
	}
	public function getWidth() {
		return imagesx($this->image);
	}
	public function getHeight() {
		return imagesy($this->image);
	}
        
	public function resizeToHeight($height) {            
		$ratio = $height / $this->getHeight();
		$width = round($this->getWidth() * $ratio);
		$this->resize($width,$height);                
	}
        
	public function resizeToWidth($width,$water=FALSE) {
            	$ratio = $width / $this->getWidth();
		$height = round($this->getHeight() * $ratio);
		$this->resize($width,$height,$water);
            }
            
	public function square($size) {
		$new_image = imagecreatetruecolor($size, $size);
		if ($this->getWidth() > $this->getHeight()) {
			$this->resizeToHeight($size);
			
			imagecolortransparent($new_image, imagecolorallocate($new_image, 0, 0, 0));
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
			imagecopy($new_image, $this->image, 0, 0, ($this->getWidth() - $size) / 2, 0, $size, $size);
		} else {
			$this->resizeToWidth($size);
			
			imagecolortransparent($new_image, imagecolorallocate($new_image, 0, 0, 0));
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
			imagecopy($new_image, $this->image, 0, 0, 0, ($this->getHeight() - $size) / 2, $size, $size);
		}
		$this->image = $new_image;
	}
   
	public function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getHeight() * $scale/100; 
		$this->resize($width,$height);
	}
   
	public function resize($width,$height,$water=FALSE) { 
            $wt =__DIR__."/watermark/opacity-50-sm.png";
        if($this->getWidth()>$width && $this->getHeight()>$height){ $wt =__DIR__."/watermark/opacity-50.png";  }
            if( $this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG) {
                $w = $this->getWidth();
                $h = $this->getHeight();
                $new = imagecreatetruecolor($w, $h );
                imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
                imagealphablending($new, true);
                imagesavealpha($new, true);
                if($water==TRUE && $this->waded=='0'){
               $watermark = imagecreatefrompng($wt);  
                imagecopy($this->image, $watermark, imagesx($new) - imagesx($watermark) - ($width/2), imagesy($new) - imagesy($watermark) - ($height/2), 0, 0, imagesx($watermark), imagesy($watermark));
                $this->waded = '1';
                }
                imagecopyresampled($new, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
                $this->image = $new;
                $this->cut(0, 0, $width, $height);
                }else{ //JPEG //original
                $new_image = imagecreatetruecolor($width, $height);		
		imagecolortransparent($new_image, imagecolorallocate($new_image, 0, 0, 0));
		imagealphablending($new_image, TRUE);
		imagesavealpha($new_image, true);	
                if($water==TRUE && $this->waded=='0'){
                $watermark = imagecreatefrompng($wt);              
               // imagecopy($this->image, $watermark, imagesx($new_image) - imagesx($watermark), imagesy($new_image) - imagesy($watermark), 0, 0, imagesx($watermark), imagesy($watermark));
                imagecopy($this->image, $watermark, (imagesx($this->image) - imagesx($watermark)) /2, (imagesy($this->image) - imagesy($watermark))/2, 0, 0, imagesx($watermark), imagesy($watermark));
               $this->waded = '1';
                }
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
                $this->cut(0, 0, $width, $height);
                }
        //    }
	}
        
    public function cut($x, $y, $width, $height) {
    	$new_image = imagecreatetruecolor($width, $height);	
		imagecolortransparent($new_image, imagecolorallocate($new_image, 0, 0, 0));
		imagealphablending($new_image, false);
		imagesavealpha($new_image, true);
		imagecopy($new_image, $this->image, 0, 0, $x, $y, $width, $height);
		$this->image = $new_image;
	}
        
	public function maxarea($width, $height = null)	{
		$height = $height ? $height : $width;
		
		if ($this->getWidth() > $width) {
			$this->resizeToWidth($width);
		}
		if ($this->getHeight() > $height) {
			$this->resizeToheight($height);
		}
	}
	
	public function minarea($width, $height = null)	{
		$height = $height ? $height : $width;
		
		if ($this->getWidth() < $width) {
			$this->resizeToWidth($width);
		}
		if ($this->getHeight() < $height) {
			$this->resizeToheight($height);
		}
	}
	public function cutFromCenter($width, $height) {
		
		if ($width < $this->getWidth() && $width > $height) {
			$this->resizeToWidth($width);
		}
		if ($height < $this->getHeight() && $width < $height) {
			$this->resizeToHeight($height);
		}
		
		$x = ($this->getWidth() / 2) - ($width / 2);
		$y = ($this->getHeight() / 2) - ($height / 2);
		
		return $this->cut($x, $y, $width, $height);
	}
	public function maxareafill($width, $height, $red = 0, $green = 0, $blue = 0) {
	    $this->maxarea($width, $height);
	    $new_image = imagecreatetruecolor($width, $height); 
	    $color_fill = imagecolorallocate($new_image, $red, $green, $blue);
	    imagefill($new_image, 0, 0, $color_fill);        
	    imagecopyresampled(	$new_image, 
	    					$this->image, 
	    					floor(($width - $this->getWidth())/2), 
	    					floor(($height-$this->getHeight())/2), 
	    					0, 0, 
	    					$this->getWidth(), 
	    					$this->getHeight(), 
	    					$this->getWidth(), 
	    					$this->getHeight()
	    				); 
	    $this->image = $new_image;
	}
        
        public static function getExtention($string){
           $ext = explode('.', $string);
           return end($ext);
        }
        
        
        /*
     * remove spacess and special characters 
     */
    public function creteName($name){
        $find = array(" ", "/", "\\", "'", "\"", "%");
        $result = strtolower(str_replace($find, "-", $name));
        return $result;
    }
    
    public function rotate($angle){
        // rotate the rezized image
    $this->image= imagerotate($this->image, -$angle, 0);
    }
    
      /*  public function upload($name,$foldes=array()){
            if(is_array($foldes)){
                foreach ($foldes as $folder=>$size) {
                  //  echo $folder.' '.$size;
                  $this->fold->create($folder);
                  self::resizeToWidth($size);
                  self::save($folder.'/'.$name);
                }
            }
        }
       * 
       */

    
    
    
    
}
