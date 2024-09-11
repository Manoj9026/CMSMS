<?php
/**
 * Description of app
 * All files property of tytern
 * @author tytern 
 */
class apiController extends Controller {
    private $_section,$_location;
    public function __construct($data = array()) {
        parent::__construct($data);
        $this->_section = new section();
        $this->_location = new location();
    }
    
    public function index(){
        
    }
    
    public function category(){     
        foreach ($this->_section->get(array('act','=','1'),array('place'=>'ASC','sid'=>'DESC')) as $key =>$value){
          $value->sub = $this->_section->getSub(array(array('act','=','1'),array('sno','=',$value->sid)),array('place'=>'ASC','cid'=>'DESC'));
           $this->data['jsone'][]= $value;
        }
    }
    
    public function location(){     
        foreach ($this->_location->getDistricts() as $key =>$value){
          $value->city = $this->_location->getCity($value->did);
           $this->data['jsone'][]= $value;
        }
    }
    
    public function adds(){
        
    }

    

    public function category_layout(){
        return 'api';
    }
    public function location_layout(){
        return 'api';
    }
    public function location_adds(){
        return 'api';
    }
    
}
