<?php

/**
 * Description of app
 * All files property of tytern
 * @author tytern 
 */
class App {
    protected static $router;
    public static $db;
    public function __construct() {
        
    }
    
    public static function getRouter(){
        return self::$router;
    }
    
    public static function run($uri){
        self::$router = new router($uri);
        self::$db = new db(config::get('db.host'), config::get('db.user'), config::get('db.password'), config::get('db.db_name'));
        lang::load(self::$router->getLanguage());
         
        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_methord = strtolower(self::$router->getMethordPrefix().self::$router->getAction());        
        $layout = self::$router->getRoute();
      //  echo $layout.' '.$controller_class.' - '.$controller_methord.'  '.session::get('role').' '.session::get('role').session::get(config::get('session/session_name')).'<br/>';
   //    die();
        if($layout=='index' && $controller_class == 'IndexController' ){  
          //   router::redirect(HTTP_PATH.'control/cusers/login/'); 
        }
 
     //   echo $controller_class.' '.$controller_methord;
        if(session::get('role')<='0' || !session::get(config::get('session/supper_name'))){
           if($controller_methord!='login'){
                 router::redirect(HTTP_PATH.'cusers/login/');
            } 
        }

        
        
         
        // create controllers methord        
        if(class_exists($controller_class)){

          $controller_object = new $controller_class();  
        if(method_exists($controller_object, $controller_methord)){  
            
            $view_path = $controller_object->$controller_methord();
           //if has diferent layout get that template
            $con_lay = $controller_methord.'_layout';
          
            if(method_exists($controller_object,$con_lay)){
                 $layout = $controller_object->$con_lay();
            }
            $view_object = new view($controller_object->getData(), $view_path);              
            $content = $view_object->render();            
        }else{    
            
            
        //throw new Exception("Methord ".$controller_methord." Does not exits");
           $controller_object = new errorController();
            $view_path = $controller_object->error();
            $view_object = new view($controller_object->getData(), VIEWS_PATH.DS.'error'.DS.'404.php');
          //  $view_object = new view($controller_object->getData(),$view_path);
        }}
        
        $layout_path = VIEWS_PATH.DS.$layout.'.php';      
        $layout_view_object = new view(compact('content'),$layout_path);
        echo $layout_view_object->render();
        
    }

}
