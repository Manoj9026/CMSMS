<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class validate{
    private $_passed = false;
    private $_errors = array();
    private $_db = null;
    
    public function __construct() {
        $this->_db = db::getInstance();
    }
    
    public function check($source,$items=  array()){
    //  print_r($items);
    //   print_r($source);
        foreach ($items as $item=>$rules) {
            if(isset($source[$item])){
                   $value = $source[$item]; 
                    
            foreach ($rules as $rule => $rule_value) {
                if($rule==='required' && $rule_value==TRUE && empty($value)){
                    $this->addError("{$rules['name']}  is required.");
                }elseif (!empty ($value)) {
                    switch ($rule){
                        case 'min':
                            if(strlen($value)<$rule_value){
                                $this->addError("{$rules['name']} must be a minimum of {$rule_value} characters.");
                            }
                            break;
                        case 'max':
                            if(strlen($value)>$rule_value){
                                $this->addError("{$rules['name']} must be a less than {$rule_value} characters.");
                            }
                            break;
                            case 'count':
                            if(strlen($value)!=$rule_value){
                                $this->addError("{$rules['name']} must be a {$rule_value} numbers.");
                            }
                            break;
                        case 'date':
                            if(!self::validateDate($value,$rule_value)){
                                $this->addError("{$rules['name']}  invalid format.");
                            }
                            break;
                        case 'time':    
                            if(!self::validateDate($value,$rule_value)){
                                $this->addError("{$rules['name']}  invalid format.");
                            }
                            break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) { 
                                $this->addError("{$rules['name']} Not valid.");
                                }
                                
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value,array($item,'=',$value));
                            if($check->count()){
                                $this->addError("{$rules['name']} already exists.");
                            }
                            break;
                        case 'match':
                            if($value!=$source[$rule_value]){
                                $this->addError("{$rules['name']} must match with {$rules['matchname']}");
                            }
                            break;
                        case 'available':
                            $check = $this->_db->get($rule_value[0],array($rule_value[1],'=',$value));
                            if(!$check->count()){
                                $this->addError("{$rules['name']} Not available.");
                            }
                            break;
                        case 'url':
                            if(filter_var($value,FILTER_VALIDATE_URL)===FALSE){
                                $this->addError("{$rules['name']} Not a valid URL.");
                            }
                            break; 
                        case 'json';
                            /*if json value*/
                            if(count($value)>$rule_value['req']){ $this->addError("{$rules['name']} atlest ".$rule_value['req']." required"); }
                            $i=0;
                            foreach($value as $jvalue){ $i++;
                                if(strlen($jvalue)<$rule_value['min']){ $this->addError("{$rules['name']} {$i} must be a minimum of {$rule_value['min']} characters."); }
                                if(strlen($jvalue)>$rule_value['max']){ $this->addError("{$rules['name']} {$i} must be a less than {$rule_value['max']} characters."); }
                            }
                            
                        break; 
                        case 'number';
                            if(!is_numeric($value)){
                                $this->addError("{$rules['name']} Not a valid format.");
                            }
                        break;
                        case 'file':                            
                           if(!is_uploaded_file($source[$item]['tmp_name'])){
                               $this->addError("{$rules['name']} not uploaded. Try again.");    
                            }else{
                           $ext = file::getExtention($source[$item]['name']);
                            if(!in_array($ext, $rule_value)){
                                $this->addError("{$ext} type not allowed.");  
                            }
                            } 
                            case 'future';
                            $date = date('Y-m-d'); 
                            if( $date > $value){
                                 $this->addError("{$rules['name']} shuld be future date.");
                            }
                            break;
                            case 'past';
                             $date = date('Y-m-d');
                            if($date < $value){
                                 $this->addError("{$rules['name']} shuld be past date.");
                            }    

                            break;
                            case 'username';
                              $check = $this->_db->query('SELECT stations.station_name, 
                                                                    user_master.user_name
                                                                    FROM
                                                                    user_master
                                                                    INNER JOIN stations ON user_master.station_id = stations.station_id
                                                                     WHERE
                                                                     user_master.user_name =?',array($value))->result();
                            if(count($check)>0){
                                $this->addError("{$rules['name']} already exists in ".$check[0]->station_name.' contact system administrator for the account transfer');
                            }                 
                            
                    }
                }
                }
            }
                else{
                    $this->addError("You must select or check {$rules['name']}.");
                }
            
            
        }
        if(empty($this->_errors)){
            $this->_passed=TRUE; 
        }
    }
    
    private function addError($error){
        $this->_errors[] = $error;
    }
    
    public function errors(){
        return $this->_errors;
    }
    
    public function passed(){
        return $this->_passed;
    }
    
    public function validateDate($date, $format = 'Y-m-d H:i:s'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

}