<?php
class suppliers extends model{
    private $_table = 'supplier_master';
    public function __construct() {
        parent::__construct();
    }
    
    public function createSupplier( $fields) {
       return  parent::create($this->_table, $fields);
    }
    
    public function updateSupplier( $fields, $where) {
        return parent::update($this->_table, $fields, $where);
    }
    
    public function getSupplier( $where, $order = null) {
        return  parent::get($this->_table, $where, $order);
    }

    
}
