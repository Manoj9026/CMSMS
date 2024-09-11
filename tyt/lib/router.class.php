<?php
/**
 * Description of router
 * All files property of tytern
 * @author tytern 
 */
class router{
protected $uri,$controller,$action,$param,$route,$methord_prefix,$language;
    
    public function __construct($uri){
        $this->uri = urldecode(trim($uri,'/'));
        
        //get defaults
        $routes = config::get('routes');
        $this->route = config::get('default_route');
        $this->methord_prefix = isset($routes[$this->route])?$routes[$this->route]:'';
        $this->language = config::get('default_language');
        $this->controller = config::get('default_controller');
        $this->action = config::get('default_action');
        
        $uri_parts = explode('?', $this->uri);
        
        $path = $uri_parts[0];
        
        $path_parts = explode('/', $path);
        //   print_r($path_parts);
        if(count($path_parts)){
         //get rout or languadge set at first eliment
            if(in_array(strtolower(current($path_parts)), array_keys($routes))){
                $this->route = strtolower(current($path_parts));
                $this->methord_prefix = isset($routes[$this->route])?$routes[$this->route]:'';
               //  echo current($path_parts);
               array_shift($path_parts);
            }elseif (in_array(strtolower(current($path_parts)), config::get('languages'))) {
             //   echo current($path_parts);
                $this->language = strtolower(current($path_parts));
                array_shift($path_parts);
            }elseif(in_array(strtolower(current($path_parts)), config::get('pages'))){
              //   echo current($path_parts);
                array_unshift($path_parts,'index','site');
             }
            //get controller
            if(current($path_parts)){
               // echo current($path_parts);
               $this->controller = strtolower(current($path_parts));
                array_shift($path_parts);
            }
            //get action
            if(current($path_parts)){ 
                $this->action = strtolower(str_replace('-','',current($path_parts)));
                array_shift($path_parts);
            }
            $this->param = $path_parts;
            
        }
      //  die();
    }
    
    
    public static function redirect($location){
        header('Location: '.$location);
    }

        public function getUri(){
    return $this->uri;
    }
public function getController(){ 
    return $this->controller;
    }
public function getAction(){
    return $this->action;    
}
public function getParam(){ 
    return $this->param;    
} 
public function getMethordPrefix(){
    return $this->methord_prefix;    
}
public function getRoute(){ 
    return $this->route;    
}
public function getLanguage(){
    return $this->language;    
}

   

}
