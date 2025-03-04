<?php
/**
 * Description of config
 * All files property of tytern
 * @author tytern 
 */
class config{
    
    protected static $settings = array(
        'site_name'=>'Centralized Medical Stock Management System',
        'admin'=>array('name'=>'admin','email'=>'ajithparakrama@gmail.com'),
        'languages'=>array('en','si'),
        'routes'=>array('default'=>'','control'=>'super_'),
        'default_route'=>'index',
        'default_language'=>'en',
        'default_controller'=>'index',
        'default_action'=>'index',
       'mysql'=>array('host'=>'172.16.0.9','username'=>'user_cmsms','password'=>'P@pi$sa','db'=>'db_cmsms'),
      
        'remember'=>array('cookie_name'=>'my_co','cookie_expiry'=>604800,'supper_cookie'=>'myad'),
        'session'=>array('session_name'=>'myp_user','token_name'=>'token','supper_name'=>'supper_ad'),
        'pages'=>array(),
        'controllers'=>array('user','control')
    );

    public function __construct() {
    }
    /*
    public static function get($key){
        return isset(self::$settings[$key])?self::$settings[$key]:NULL;
    }
     * 
     */
    public static function get($path=null){
        if($path){
         $config = self::$settings;
         $path = explode('/', $path);
        // print_r($path);
        foreach ($path as $bit) {
         //   echo $bit;
            if(isset($config[$bit])){
                $config = $config[$bit];
               // echo 'set';
            }
        }
        return $config;         
        }
        return FALSE;
    }
    
    public static function set($key,$value){
        self::$settings[$key] = $value;
    }
}
