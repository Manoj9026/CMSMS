<?php

/*
 * this is the cuserController file that control all task of super admin
 */
class cusersController extends Controller{

    //put your code here
    public function __construct($data= array()) {
        parent::__construct($data);
        $this->model = new cuser();
    }
      
    public function login(){
         /*
         * if user alrady logged in redirect to home page
         */
        if($this->model->isLoggedIn()){
            router::redirect(HTTP_PATH);
        }
        
        /*
         * check admin cookies awailable or not
         */
        if(cookie::exists(config::get('remember/supper_cookie')) && !session::exists(config::get('session/supper_name'))){
            $hash = cookie::get(config::get('remember/supper_cookie'));
            if($user = $this->model->cookilogin($hash)){
                router::redirect(HTTP_PATH);
            }
        }
        
        /*
         * if submit button click by user for login
         */
        if(input::exists()){
           if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
                    'username'=>array('name'=>'Username','required'=>TRUE,'min'=>2),
                    'password'=>array('name'=>'Password','required'=>TRUE,'min'=>6,'max'=>20)
                ));
                if($validate->passed()){
                    $remember = (input::get('remember'))?TRUE:FALSE;                    
                    if(!$this->model->login(escape(input::get('username')),  input::get('password'),$remember)){
                        $this->data['error'][] = 'Username or password incorrect';
                    }else{
                        session::flash('success', 'Welcome ');
                    router::redirect(HTTP_PATH);
                    exit();
                    }
                }  else {
                $this->data['error'] = $validate->errors();    
                }
            }
        }
        
        /*
         * token use for the page
         */
         $this->data['token'] = token::genarate();
      //   echo '<pre>';
      //   print_r($this->model);
      //   print_r($_SESSION);
    }
    
    public function tes(){
         $salt = Hash::salt(32);
        $this->model->change(array('pass'=>  Hash::make(input::get('new'), $salt),'salt'=>$salt),array('cuid','>','0'));       
   }
    
    /*
     * supper user functions
     */
    public function login_layout(){
        return 'login';
    }
    
    public function logout(){
    if($this->model->isLoggedIn()){
    $this->model->logout();
    }
    router::redirect(HTTP_PATH);
    }
    
     
    
   
    
        public function profile(){
          //  $this->data['admin'] = $this->model->getData();
            $validate = new validate();
            /*
             * change password
             */
            if(input::get('change')){
                if(token::check(input::get('token'))){
                    
                    $validate->check($_POST, array(
                        'password'=>array('name'=>'Current Password','required'=>TRUE),
                        'newpassword'=>array('name'=>'New Password','required'=>TRUE,'min'=>6,'max'=>16),
                        'cpassword'=>array('name'=>'confirm Password','required'=>TRUE,'min'=>6,'match'=>'newpassword','matchname'=>'New Password')
                    ));
                    if($validate->passed()){
                        if(Hash::make(input::get('password'), $this->data['admin']->salt)!==$this->data['admin']->pwd){
                            session::flash('error', 'Current password incorrect');
                           }else{
                         $salt = Hash::salt(32);
                            try {
                                 $this->model->change(array('pwd'=>  Hash::make(input::get('newpassword'), $salt),'salt'=>$salt),array('user_id','=',$this->data['admin']->user_id));
                                 session::flash('success', 'password change successful');
                               //   router::redirect(HTTP_PATH.'wp/users/logout');
                                 }catch (Exception $ex) {
                                 session::flash('error', $ex->getMessage());
                            }
                        }
                    }else{
                        session::flash('error', $validate->errors());
                    }
                }
            }
            
            if(input::get('saveprofile')){
                if(token::check(input::get('token'))){
                     $validate->check($_FILES, array(
                        'profile'=>array('name'=>'profile image','required'=>TRUE,'file'=>array('jpg','JPEG','png','PNG'))));
                    if($validate->passed()){
                       try{
                           $this->model->addProfile(array('cuid'=>  $this->data['admin']->cuid,'image'=>  input::get('profile')));
                           session::flash('success','Profile image updated');
                       } catch (Exception $ex) {
                           session::flash('error',$ex->getMessage());
                       }
                        
                        
                    }else{
                        session::flash('error', $validate->errors()); 
                    }
                }
            }
            
       
            
          /*  if(is_file(PUBLIC_PATH.DS.str_replace('laer', 'face', $this->data['admin']->path))){
                $this->data['profile'] = HTTP_PATH.'public/'.str_replace('laer', 'face', $this->data['admin']->path);                  
            }*/

            $this->data['token'] = token::genarate();
        }

    
}
