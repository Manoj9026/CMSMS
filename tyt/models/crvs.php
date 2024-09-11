<?php
/**
 * Description of products
 *
 * @author User
 */
class crvs extends model{
    public $_table = 'bmd_crv_header';
     public $_table_crv_type = 'crv_types';
     public $_bmd_crv_details = 'bmd_crv_details';
     public $_bmd_stock_master = 'bmd_stock_master';

    public function __construct() {
        parent::__construct(); 
    }
    
    public function createCrv( $fields) {
        return  parent::create($this->_table, $fields);
    }

    public function updateCrv($fields, $where) {
      //  print_r($where);
        return  parent::update($this->_table, $fields, $where);
    }
    
    public function getCrv($where,$order=null){
        return parent::get($this->_table,$where,$order);
    }

    public function getCrvTypes($where,$order=null){
        return parent::get($this->_table_crv_type,$where,$order);
    }

    public function createCrvDetails($fields){
        return parent::create($this->_bmd_crv_details,$fields);
    }
    
    public function updateCrvDetails($fields, $where) {
        return  parent::update($this->_bmd_crv_details, $fields, $where);
    }

    public function getCrvDetails($where,$order=null){
        return parent::get($this->_bmd_crv_details,$where,$order);
    }

    public function updateStock($fields,$where){
        return parent::update($this->_bmd_stock_master,$fields,$where);
    }

    public function getCurrentQuantity($where,$order=null){
        return parent::get($this->_bmd_stock_master,$where,$order);
    }

    public function getItemsWithQuantity($quary,$params=null){
        return parent::getFromQ($quary,$params);
       }
    
    
}

