<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file
 *
 * @author oracle
 */
class file {
    //put your code here
    private $_ext,$_folder;
    public $_fileName;

    public function __construct() {
        $this->_folder = new folder();
    }
    
    
    /*
     * return extention from file name
     */
    public function getExtention($name=null){
       // echo $name;
       if($name){
        $ext = explode('.', $name);
       $this->_ext = end($ext);
       }
       return $this->_ext;
    }
    
    /*
     * remove spacess and special characters 
     */
    public function creteName($name){
        $find = array(" ", "/", "\\", "'", "\"", "%");
        $result = strtolower(str_replace($find, "-", $name));
        return $result;
    }

    
    /*
     * save file in a directory
     */
    private function save($file,$destination){
        if($file && $destination){
            return (move_uploaded_file($file['tmp_name'], '../'.$destination))?TRUE:FALSE;
        }
    }
    
    public function move($file,$destination){
        if(is_file($file) && $destination){
            return (rename($file, $destination))?TRUE:FALSE;
        }
    }


    /*
     * rename file when change the name 
     */
    public function rename($old,$new){
        
    }
    
    /*
     * upload file to server
     */
    
    public function addFile($file=array(),$details=array()){
      //  print_r($file);
        $this->_folder->create($details['path']);
        $this->_fileName = self::creteName($details['name']).'.'.  self::getExtention($file['name']);        
      
       /*   if(!self::move($file['tmp_name'], '../'.$details['path'].'/'.$this->_fileName)){
           throw new Exception ("There was a problem to upload file");
       }else{ 
           return TRUE;
           } 
          */
           if(!self::save($file, $details['path'].'/'.$this->_fileName)){
           throw new Exception ("There was a problem to upload file");
       }else{ 
           return TRUE;
           } 
    }
    
    public function fileName(){
        return $this->_fileName;
    }
    
    
    
    
    
}
