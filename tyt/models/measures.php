<?php
class measures extends model{
    private $_table = 'master_unit';
    public function __construct() {
        parent::__construct();
    }
    
    public function createMeasure( $fields) {
        return  parent::create($this->_table, $fields);
    }
    
    public function updateMeasure( $fields, $where) {
        return parent::update($this->_table, $fields, $where);
    }
    
    public function getMeasure( $where, $order = null) {
        return  parent::get($this->_table, $where, $order);
    }
}
