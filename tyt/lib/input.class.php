<?php

/**
 * Description of input
 * All files property of tytern
 * @author tytern 
 */
class input{
    public static function exists($type ='post'){
        switch ($type){
            case 'post':
                return (!empty($_POST))?TRUE:FALSE;
                break;
            case 'get':
                return (!empty($_GET))?TRUE:FALSE;
                break;
            default :
                return FALSE;
                break;
        }
    }
    
    public static function get($item){
        
        if(isset($_POST[$item])){
            return $_POST[$item];
            
        }else if(isset ($_GET[$item])){
            return $_GET[$item];
        }else if(isset ($_FILES[$item])){
            return $_FILES[$item];
        }else{
        return '';
        }
    }
    
    public static function put($item,$value){
        $_POST[$item] = $value;
    }
    
    public static function clear(){
        unset($_POST);
    }
    
    public static function all($type){
        $result = array();
        switch ($type) {
            case 'post':
                foreach ($_POST as $key => $value) {
                $result[$key]=$value;
                }
                break;
            case 'get':
                foreach ($_GET as $key => $value) {
                $result[$key]=$value;
            }
                break;
            case 'file':
                foreach ($_FILES as $key => $value) {
                $result[$key]=$value;
            }
                break;
        } 
        return $result;
    }
    
}
