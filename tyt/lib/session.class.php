<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of session
 * All files property of tytern
 * @author tytern 
 */
class session {
    
   /* protected static $flash_message;
    
    public static function sefFlash($message){
        self::$flash_message = $message;
    }
    
    public static function hasFlash(){
        return !is_null(self::$flash_message);
    }
   
    public static function flash(){
        echo self::$flash_message;
        self::$flash_message=NULL;
    }
    
    public static function set($key,$value){
        return $_SESSION[$key] = $value;
    }
    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return NULL;
    }
    
    public static function delete($key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }
    
    public static function destroy(){
        session_destroy();
    }
    * 
    */
    
        public static function exists($name){
        return (isset($_SESSION[$name]))?TRUE:FALSE;
    }

    public static function put($name,$value){
        return $_SESSION[$name] = $value;
    }
    
    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return NULL;
    }

    public static function delete($name){
        if(self::exists($name)){
            unset($_SESSION[$name]);
        }
    }
    
    public static function flash($name,$string=NULL){
        if(self::exists($name)){
            $session = self::get($name);
            self::delete($name);
            return $session;
            }  elseif($string!='') {
                $st = '';
                if(is_array($string)){
                    foreach ($string as $value) {
                       $st.= $value.'<br/>'; 
                    }
                     self::put($name, $st);  
                }else{
                self::put($name, $string);   
                }
            }
            
    }
    
        public static function arrPut($name,$value){
      $msg = '<ul>';
        foreach ($value as $v) {
          $msg .='<li>'.$v.'</li>';  
        }
        $msg .= '</ul>';
        self::put($name, $msg);
    }

}
