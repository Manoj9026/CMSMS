<?php
class categorys extends model{
    private $_table = 'item_category';
    public function __construct() {
        parent::__construct();
    }
    
    public function createCategory( $fields) {
        return  parent::create($this->_table, $fields);
    }
    
    public function updateCategory( $fields, $where) {
        return parent::update($this->_table, $fields, $where);
    }
    
    public function getCategory( $where, $order = null) {
        return  parent::get($this->_table, $where, $order);
    }
}
