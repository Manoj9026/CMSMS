<?php
/**
 * Description of cuser
 * All files property of tytern
 * @author tytern 
 */
class cuser extends model{
    private $_sName,$_isLoggedIn,$_cookieName,$_table;
    public  $profile = array('images/user/user_id/profile/laer'=>'','images/user/user_id/profile/face'=>array('160','160'),'images/user/user_id/profile/small'=>array('45','45'),'images/user/user_id/profile/thumb'=>array('128','128')) /*banner image sizes*/;

    //put your code here
    public function __construct($user=NULL) {
        parent::__construct();
        $this->_table = 'user_master';
        $this->_sName = config::get('session/supper_name');
        $this->_cookieName = config::get('remember/supper_cookie');
        if(!$user){
            if(session::exists($this->_sName)){
                $user = session::get($this->_sName);
                if($this->find($user)){
                        $this->_isLoggedIn = TRUE;
                }else{
                    $this->_isLoggedIn = FALSE;
                }
            }
        }
    }
    
    public function login($username=NULL,$password=NULL,$remember=NULL){
        $user = $this->find($username);
        if(!$username && !$password && $this->exists()){
            session::put($this->_sName, $this->_data->user_id);
            return TRUE;
        }else{
            if($user){
                 if($this->_data->pwd === Hash::make($password,  $this->_data->salt) && $this->_data->active=='1'){
                    session::put($this->_sName, $this->_data->user_id);
                    session::put('role', $this->_data->privilage_id);

                    $this->change(array('last_login_date'=>date('Y-m-d'),'last_login_date_time'=>date('Y-m-d h:i:s'),'latest_ip'=>$_SERVER['REMOTE_ADDR']),array('user_id','=',$this->_data->user_id));

                    if($remember){
                        $hash = Hash::unique();
                        $hashcheck = $this->db->get('cusession',array('cuno','=',$this->_data->user_id));
                        if(!$hashcheck->count()){
                            $this->db->insert('cusession',array('hash'=>$hash,'cuno'=>  $this->_data->user_id));
                        }else{
                            $hash = $hashcheck->first()->hash;
                        }
                        cookie::put($this->_cookieName, $hash, config::get('remember/cookie_expiry'));
                        die('loging');
                    }
                     
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function createUser($fields){
          return  parent::create($this->_table, $fields);
    }
    
    public function cookilogin($hash){
        $hashCheck = $this->db->get('cusession',array('hash','=',$hash));
        if($hashCheck->count()){
            return $hashCheck->first()->user_id;
        }
    }

    public function find($user=NULL){
        if($user){
            $field = (!is_numeric($user))?'user_name':'user_id';
            $data = $this->db->get($this->_table,array($field,'=',$user));
         //   print_r($data);
            if($data->count()){
                $this->_data = $data->first();
                return TRUE;
            }
            return FALSE;
        }
    }
    
        public function exists(){
        return (!empty($this->_data))?TRUE:FALSE;
    }
    
    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }
    
    public function logout(){
        $this->db->delete('cusession',array('cuno','=',  $this->_data->user_id));
        session::delete($this->_sName);
        session::delete('role');
        cookie::delete($this->_cookieName);
        session_destroy();
    }
    public function change($fields,$where){
        $this->update($this->_table, $fields, $where);
    }


    /*
     * get user details
     */
    public function getData(){
        return $this->_data;
    }
    
    /*
     * update user profile image
     */
    public function addProfile($image){
        $this->_data->user_id = $image['user_id'];
        if($image['image']['error']=='0'){
            if($this->addImage($image['image'],'profile')){
               $this->update($this->_table,array('path'=>  $this->_path),array('user_id','=',  $this->_data->user_id));
            return TRUE; 
            }
        }
    }
    
    private function addImage($image,$type='profile'){
        $img = new image($image['tmp_name']);
        $name  =  'profile-'.$this->_data->user_id.'.'.$img->getExtention($image['name']);
        $paths= $this->$type;
        foreach ($paths as $key => $value) {
            $path = str_replace('user_id', $this->_data->user_id, $key);
            $this->_folder->create(PUBLIC_PATH.DS.$path);
           if(!empty($value)){
               if(is_array($value)){
                  $img->resize($value[0],$value[1]); 
               }else{
                $image->resizeToWidth($value);   
               }
        }else{
            $this->_path = $path.'/'.$name;
        }        
        $img->save(PUBLIC_PATH.DS.$path.'/'.$name);
        }
        return TRUE;
    }
    
}
