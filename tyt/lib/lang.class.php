<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lang
 * All files property of tytern
 * @author tytern 
 */
class lang {

    protected static $data;
    
    public static function load($lang_code){
        $lang_file_path = ROOT.DS.'tyt'.DS.'lang'.DS.strtolower($lang_code).'.php';
        if(file_exists($lang_file_path)){
            self::$data=  include ($lang_file_path);
        }else{
            throw new Exception ('Lang file not found :'.$lang_file_path);
        }
    }
    
    public static function get($key,$default_value=''){
        return isset(self::$data[strtolower($key)])?self::$data[strtolower($key)]:$default_value;
    }
    

}
