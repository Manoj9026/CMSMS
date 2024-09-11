<?php
class stations extends model{
    private $_table = 'stations';
    public function __construct() {
        parent::__construct();
    }
    
    public function createStation( $fields) {
       return  parent::create($this->_table, $fields);  
    }
    
    public function updateStation( $fields, $where) {
        return parent::update($this->_table, $fields, $where);
    }
    
    public function getStation( $where, $order = null) {
        return  parent::get($this->_table, $where, $order);
    }


}
