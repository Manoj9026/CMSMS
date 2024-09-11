<?php
/**
 * Description of model
 * All files property of tytern
 * @author tytern 
 */
class model {
    protected $db,$_data,$_folder,$_file,$_image;
    public function __construct() {
        $this->db = db::getInstance();
        $this->_folder = new folder();
         $this->_log = new log();
      //  $this->_image = new image();
    }
    
    public function create($table,$fields){
        try {
            $this->db->insert($table,$fields);  
           
        } catch (Exception $ex) {
           throw new Exception ($ex->getMessage()[2]);  
        }
         return TRUE;
    }
    
    public function update($table,$fields,$where){
        try {
            $this->db->update($table,$fields,$where);
         } catch (Exception $ex) {
             throw new Exception ($ex->getMessage()[2]);   
        }
        return TRUE;
    }
    
    public function delete($table,$where){
       try {
            $this->db->delete($table,$where);
         } catch (Exception $ex) {
             throw new Exception ($ex->getMessage());   
        }
        return TRUE; 
    }
    
    public function get($table,$where,$order=null){
      try {
          return  $this->db->get($table,$where,$order)->result();
         } catch (Exception $ex) {
             throw new Exception ($ex->getMessage());   
        }
    }

    public function getFromQ($quary,$params=null){
       try{ 
       return $this->db->query($quary,$params)->result();
   }catch(Exception $ex){
            throw  new Exception($ex->getMessage());
            
        }
    } 
    
}

