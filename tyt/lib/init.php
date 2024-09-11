<?php

//require_once (ROOT.DS.'config'.DS.'config.php');

function tyt__autoload($class_name){
    $lib_path = ROOT.DS.'tyt'.DS.'lib'.DS.strtolower($class_name).'.class.php';
    $controller_path = ROOT.DS.'tyt'.DS.'controllers'.DS.  str_replace('controller','',  strtolower($class_name)).'.controller.php';
    $model_path = ROOT.DS.'tyt'.DS.'models'.DS.strtolower($class_name).'.php';

   // echo '--------------------'.$controller_path.'<br/>';
    
    if(file_exists($lib_path)){
        require_once ($lib_path);
     //   echo $lib_path.'<br/>';
    }elseif(file_exists($controller_path)){
        require_once ($controller_path);
      //  echo $controller_path.'<br/>';
    }
    elseif(file_exists($model_path)){
        require_once ($model_path);
        //echo $model_path.'<br/>';
    }
    else{
        //require_once '';
       //   throw new Exception('Faill to include class '.$class_name);
      //  include ROOT.DS.'tyt'.DS.'controllers'.DS.'error.controller.php';  
     // include ROOT.DS.'tyt'.DS.'controllers'.DS.'error.controller.php';           
    }
}


spl_autoload_register("tyt__autoload");

function __($key,$default_value){
    return lang::get($key, $default_value);
}
 