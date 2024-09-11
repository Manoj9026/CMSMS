<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class db{
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error=false,
            $_results,
            $_count = 0,
            $_insertId,
            $_errorMessage,
            $_value,
            $_where,
            $_order,
            $_operator = array('=','>','<','>=','<=','IN','!=');
    
    /*
     * construct function 
     */
    public function __construct() {
        try {
         //   print_r(config::get('mysql/password'));
            $this->_pdo = new PDO('mysql:host='.config::get('mysql/host').';dbname='.config::get('mysql/db'), config::get('mysql/username'), config::get('mysql/password'));
          } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
        }
        
        /*
         * get database connection instant
         */
        public static function getInstance(){
            if(!isset(self::$_instance)){                
                self::$_instance = new db();
            }
            return self::$_instance;
        }
        
       /*
        * query process function 
        * direct quary also process this function
        */ 
        public function query($sql,$params=array()){
           // echo $sql.'<br/>';
         //   print_r($params);
            $this->_error=FALSE;
            if($this->_query = $this->_pdo->prepare($sql)){
               $x=1;
                if(count($params)){
                   foreach ($params as $param) {
                       $this->_query->bindValue($x, $param);
                       $x++;
                   }
               }
               if($this->_query->execute()){
                   $this->_results =  $this->_query->fetchAll(PDO::FETCH_OBJ);
                   $this->_count = $this->_query->rowCount();
                //   echo $this->_query->rowCount();
                  }  else {
                      $this->_errorMessage = $this->_query->errorInfo();  
                      print_r($this->_errorMessage);
                      $this->_error = TRUE;    
                  }               
            }
            return $this;
        }
        
        /*
         * new action function 
         * select / delete /update all functions comes to hear
         * can directly access to this function 
         */
         private function action($action,$table,$where=array(),$order=array()){
             self::where($where);
             $this->_order ='';
            if($order){ self::order($order);}
             $sql = "{$action} FROM {$table} $this->_where $this->_order";
            // echo $sql.'<br/>';
            // print_r($this->_value);
                         if(!$this->query($sql,  $this->_value)->error()){
                            return $this;                          
                        }
         }
        
   
        
        /*
         * public get function select query create and process using action function
         */
        public function get($table,$where=NULL,$order=NULL){            
            return $this->action('SELECT * ', $table,$where,$order);
        }
        
        /*
         * public delete function delete function create and process using action function
         */
        public function delete($table,$where){
            return $this->action('DELETE ', $table,$where); 
        }     
        
        /*
         * public insert function insert query create and process using the action function
         */
        public function insert($table,$fields=array()){
          //  print_r($fields);
            if(count($fields)){
                $keys = array_keys($fields);
                $x=1;
                $values ="";
                foreach ($fields as $value) {
                   $values .="?, ";
                }
                $values = trim($values,", ");                
                $sql = "INSERT INTO {$table} (`".implode('`,`',$keys)."`) VALUES ({$values})";
              //  echo $sql;
                if(!$this->query($sql, $fields)->error()){
                    return TRUE;
                }
            }
            return FALSE;
        }
        
        /*
         * public update function update query create and process using action function
         */        
        public function update($table,$filds=array(),$where=  array()) {
            $set = "";
            $wh="";
            foreach ($filds as $name=> $value) {
                $set .= "{$name}=?, ";
            }
            $set = trim($set,", ");
            self::where($where);
          //  print_r($filds);
           // print_r($this->_value);
            $filds =  array_merge($filds, $this->_value);
           // print_r($filds);
            $sql = "UPDATE {$table} SET ".$set." $this->_where";    
           // echo $sql;
           // print_r($filds);
        if(!$this->query($sql,$filds)->error()){
            return TRUE; 
        }        
        return FALSE;
        }
        
        /*
         * private where function suport to complete where parth on the query
         */
        private function where($where=array()){
            $this->_value ='';
            $this->_where = '';
           // print_r($where);
            if(!is_array($where[0])){
                    if(count($where)===3){
                    $field    = $where[0];
                    $operator = $where[1];
                    $value    =   $where[2];
                    
                    if(in_array($operator, $this->_operator)){
                        if(is_array($value)&&($operator=='IN')){
                            $qumark = "";
                            foreach ($value as $v) {
                                $this->_value[] = $v;
                                $qumark .= "?,";
                                }
                                $qumark = trim($qumark, ',');
                            $this->_where = "  WHERE {$field} {$operator} ({$qumark})";   
                            return TRUE;
                        }
                        
                        $this->_where = "  WHERE {$field} {$operator} ?";
                        $this->_value[$field] = $value;
                       return TRUE;
                    }
                  }
            }elseif(is_array($where[0])){
                $sql = "  WHERE ";
                foreach ($where as $vl){
                    if(count($vl)===3){
                     $field     = $vl[0];
                    $operator   = $vl[1];
                    $value    =   $vl[2]; 
                    
                   if(in_array($operator,  $this->_operator)){ 
                       
                     if(is_array($value)&&($operator=='IN')){
                            $qumark = "";
                            foreach ($value as $v) {
                                $this->_value[] = $v;
                                $qumark .= "?,";
                                }
                                $qumark = trim($qumark, ',');
                            $sql .= "  {$field} {$operator} ({$qumark}) AND ";   
                           
                   }else{
                        $sql .=" {$field}{$operator}? AND ";
                        $this->_value[$field] = $value;
                    }
                   }
                    }
                }
               $sql = trim($sql, "AND "); 
               
               $this->_where = $sql;
             }            
        }
        
        /*
         * order by 
         */
        private function order($filds = array()){
            if(is_array($filds)){
                $or = " ORDER BY ";
                foreach ($filds as $key =>$value) {
                    $or .= $key." ".$value.", ";
                }
                $this->_order = trim($or, ", ");
            }
        }


        /*
         * return last insert id 
         */
        public function insertId(){
            return $this->_pdo->lastInsertId();
        }
        
        /*
         * if there is an error in the pdo return that error
         */
        public function error(){
            return $this->_error;
        }
        
        /*
         * if there is an error return error message
         */
        public function errorMessage(){
            return $this->_errorMessage;
        }
        
        /*
         * return number of query effected rows
         */
        public function count(){
            return $this->_count;
        }
        
        /*
         * return result of the sql query
         */
        public function result(){
            return $this->_results;
        }
        
        /*
         * return first element of the result 
         */
        public function first(){
            return (isset($this->result()[0]))?$this->result()[0]:array();
        }
        
        
    
    
}
