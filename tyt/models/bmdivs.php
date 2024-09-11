<?php
/**
 * Description of products
 *
 * @author User
 */
class bmdivs extends model{
    public $_table = 'bmd_iv_header';
     public $_bmd_iv_details = 'bmd_iv_details';
     public $_bmd_stock_master = 'bmd_stock_master';
    public function __construct() {
        parent::__construct();
    }
    
    public function createIv( $fields) {
        return  parent::create($this->_table, $fields);
    }

    public function updateIv($fields, $where) {
      //  print_r($where);
        return  parent::update($this->_table, $fields, $where);
    }
    
    public function getIv($where,$order=null){
        return parent::get($this->_table,$where,$order);
    }
 
    public function createBmdIvDetails($fields){
        return parent::create($this->_bmd_iv_details,$fields);
    }
    
    public function updateIvDetails($fields, $where) {
        return  parent::update($this->_bmd_iv_details, $fields, $where);
    }

    public function getIvDetails($where,$order=null){
        return parent::get($this->_bmd_iv_details,$where,$order);
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

