<?php
class items extends model{
    private $_table = 'item_master',$_bmd_stock_master = 'bmd_stock_master',$_item_code;
    public function __construct() {
        parent::__construct();
    }
    
    public function createItem( $fields) {
        $this->db->insert($this->_table,$fields); 
        $this->_item_code = $this->db->insertId();  
        return parent::create($this->_bmd_stock_master,array('item_code'=>$this->_item_code));    
    }
    
    public function updateItem( $fields, $where) {
        return parent::update($this->_table, $fields, $where);
    }
    
    public function getItem( $where, $order = null) {
        return  parent::get($this->_table, $where, $order);
    }


}
