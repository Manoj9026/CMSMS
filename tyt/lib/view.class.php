<?php
/**
 * Description of view
 * All files property of tytern
 * @author tytern 
 */
class view {
    protected $data,$path;
    //put your code here
    
    public static function getDefaultViewPath(){
    $router = App::getRouter();
    if(!$router){
        return FALSE;
    } 
    $controller_dir = $router->getController();    
    $template_name = $router->getMethordPrefix().$router->getAction().'.php';
    return VIEWS_PATH.DS.$controller_dir.DS.$template_name;
    }
    
    public function __construct($data=array(),$path=NULL) {
        if(!$path){
            $path = self::getDefaultViewPath();
        }
        if(!file_exists($path)){
            throw new Exception('Template file is not found in the path '.$path);
        }
        $this->path = $path;
        $this->data = $data;        
    }
    
    
    public function render(){
        $data = $this->data;
        ob_start();        
        include ($this->path);
        $content = ob_get_clean();        
        return $content;        
    }
    
    

}
