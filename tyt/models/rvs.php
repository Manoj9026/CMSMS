<?php
/**
 * Description of products
 *
 * @author User
 */
class rvs extends model{
    public $_table = 'station_rv_header'; 
     public $_station_rv_details = 'station_rv_details';
     public $_station_stock_master = 'station_stock_master';
    public function __construct() {
        parent::__construct();
    }
    
    public function createRv( $fields) {
        return  parent::create($this->_table, $fields);
    }

    public function updateRv($fields, $where) {
      //  print_r($where);
        return  parent::update($this->_table, $fields, $where);
    }
    
    public function getRv($where,$order=null){
        return parent::get($this->_table,$where,$order);
    }
 
    public function createRvDetails($fields){
        return parent::create($this->_station_rv_details,$fields);
    }
    
    public function updateRvDetails($fields, $where) {
        return  parent::update($this->_station_rv_details, $fields, $where);
    }

    public function getRvDetails($where,$order=null){
        return parent::get($this->_station_rv_details,$where,$order);
    }

    public function updateStock($fields,$where){
        return parent::update($this->_station_stock_master,$fields,$where);
    }

    public function getCurrentQuantity($where,$order=null){
        return parent::get($this->_station_stock_master,$where,$order);
    }

    public function addToStationStock($fields){
        return parent::create($this->_station_stock_master,$fields);
    }

    public function getItemsWithQuantity($quary,$params=null){
        return parent::getFromQ($quary,$params);
       }
    
    
}

