<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Hash{
    public static function make($strint, $salt=''){
        return hash('md5',$strint.$salt);
    }
    public static function salt($length){
        return random_bytes($length);
      //  return mcrypt_create_iv($length); php < 7
    }
    public static function unique(){
        return self::make(uniqid());
    }
    
}
