<?php

/**
 * Description of folder
 *
 * @author oracle
 */
class folder {
    //put your code here
    public function create($path){        
        /* create folder on level up path*/
        $folders = explode('/', $path);
        $f ="";
        foreach ($folders as $folder){
            $f.= ($f!="")?'/'.$folder:$folder;            
            if(!is_dir($f)){
                mkdir($f,0777);
            }           
        }
    }
    
    public function clean($path){
      //  $path = '../'.$path;
        
        $files = glob($path."/*.{jpg,gif,png,pdf}", GLOB_BRACE);
        if(is_array($files)){
            foreach ($files as $key) {
               self::deleteFile($key);
             //   echo $key;
            }
        }
        return (self::fempty($path.'/'))?TRUE:FALSE;
    }
    
    public function delete($path){
     //  $path = '../'.$path;
        if(self::fempty($path)){
            return (rmdir($path))?TRUE:FALSE;
        }
    }
    
    public function fempty($path){
        if (!is_readable($path)) return NULL; 
        return (count(scandir($path)) == 2);
    }
    
    public function deleteFile($filename){
        if(is_file($filename)){
            return unlink($filename)?TRUE:FALSE;
        }
    }

    
}
