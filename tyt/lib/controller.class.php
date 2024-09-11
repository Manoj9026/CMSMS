<?php
/**
 * Description of controller
 * All files property of tytern
 * @author tytern 
 */
class Controller extends model{
    protected $data,$model,$params,$user,$ip;
    //put your code here
    public function __construct($data=array()) {
        $this->data = $data;
        $this->param = App::getRouter()->getParam();  
               $this->user = new cuser();
                if($this->user->isLoggedIn()){
                $this->data['admin'] = $this->user->getData();
                $this->data['admin']->station = $this->user->getFromQ('SELECT stations.station_name FROM stations WHERE stations.station_id = ?',array($this->data['admin']->station_id))[0]->station_name;

                $this->ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
         }    
    }
    public  function getData(){
         return $this->data;
    }
    public  function getModel(){
        return $this->model;
    }
    public  function getParams(){
        return $this->param;
    }
    public function checkPermition($privilage){       
        if($this->data['admin']->privilage_id < ($privilage+0.2)){
            session::flash('error', "You don't have permition to access.");
            router::redirect(HTTP_PATH);
            exit();
        }
        return true;
    }
    
    function checkModuleAccess($array){
        if(!in_array($this->data['admin']->section, $array)){
            session::flash('info', 'You Don\'t have permision');
            router::redirect(HTTP_PATH); 
            exit();
        }
    }
    

    public function checkAccess($section){
        /*
        *area and station id 
        *area is crv
        *station id in 2
        */

        $system = array(
            'crv'=>array(2),
            'bmdiv'=>array(2),
            'report'=>array(1,2,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100),
            'cetegory'=>array(2),
            'item'=>array(2),
            'measure'=>array(2),
            'station'=>array(2),
            'supplier'=>array(2),
            'rv'=>array(5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100),
            'iv'=>array(5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100),
            'settings'=>array(2,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100),
            'issuing'=>array(2)
        );

        if(!in_array($this->data['admin']->station_id,$system[$section])){
          session::flash('error', "You don't have permition to access.");
            router::redirect(HTTP_PATH);
            exit();
        }else{
            return true;
        }

    }


}
