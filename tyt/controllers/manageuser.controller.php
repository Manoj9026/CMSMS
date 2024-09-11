<?php
class manageuserController extends Controller{
	private $_station;
	 public function __construct($data=array()){
		 parent::__construct($data);
		 $this->model = new cuser();
	}

	public function index(){
    /*check user has permitionn to access this page*/
            $this->checkModuleAccess([4]);
      parent::checkPermition('1');

       if(input::get('del')){
         parent::checkPermition('2');
           try {
               $this->model->change(array('active'=>'0'),array('user_id','=', clearInt(input::get('del'))));
               session::flash('success', 'User account deactivated');
               router::redirect(HTTP_PATH.'manageuser');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'User account not deactivated!');
               router::redirect(HTTP_PATH.'manageuser');
               exit();
           }
           
       }

       if(input::get('reset')){
         parent::checkPermition('2');

         $station = $this->model->getFromQ('SELECT stations.station_name
                 FROM
                 user_master
                 INNER JOIN stations ON user_master.station_id = stations.station_id
                 WHERE
                 user_master.user_id=?',array(clearInt(input::get('reset'))))[0];
         $pass = remove_space($station->station_name).'@123';
           try {
             $salt = Hash::salt(32);

               $this->model->change(array(
                                'salt'=>$salt,
                                'pwd'=>  Hash::make($pass, $salt))
               ,array('user_id','=', clearInt(input::get('reset'))));
               session::flash('success', 'Password reset as '.$pass);
               router::redirect(HTTP_PATH.'manageuser');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Password not reset!');
               router::redirect(HTTP_PATH.'manageuser');
               exit();
           } 
           
       }



          $query = 'SELECT a.*,
                  b.name as b_name,
                  b.service_no as b_service_no,
                  b.user_rank as b_user_rank,
                  stations.station_name
                  FROM
                  user_master a
                  INNER JOIN user_master b ON a.latest_user_tbl_id = b.user_id
                  INNER JOIN stations ON a.station_id = stations.station_id
                  WHERE a.active=? ';
                  $params = array('1');
                  /*check admin is system admin or not*/
                 if($this->data['admin']->privilage_id!='4'){
                    if($this->data['admin']->privilage_id>='2'){
                        $query .= ' AND a.station_id=? AND a.privilage_id <=? ';
                        array_push($params,$this->data['admin']->station_id );
                         array_push($params,$this->data['admin']->privilage_id );
                    }
                  }
        $pagination = new paginator($query,$params);

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
	}



public function inactive(){
          /*check user has permitionn to access this page*/
    $this->checkModuleAccess([4]);
      parent::checkPermition('3');

       if(input::get('rep')){
         parent::checkPermition('3');
           try {
               $this->model->change(array('active'=>'1'),array('user_id','=', clearInt(input::get('rep'))));
               session::flash('success', 'User account activated');
               router::redirect(HTTP_PATH.'manageuser/inactive');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'User account not activated!');
               router::redirect(HTTP_PATH.'manageuser/inactive');
               exit();
           }
           
       }

       if(input::get('reset')){
         parent::checkPermition('2');
           try {
             $salt = Hash::salt(32);
               $this->model->change(array(
                                'salt'=>$salt,
                                'pwd'=>  Hash::make('bmd@123', $salt))
               ,array('user_id','=', clearInt(input::get('reset'))));
               session::flash('success', 'Password reset as bmd@123');
               router::redirect(HTTP_PATH.'manageuser/inactive');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Password not reset!');
               router::redirect(HTTP_PATH.'manageuser/inactive');
               exit();
           }
           
       }
          $query = 'SELECT a.*,
                  b.name as b_name,
                  b.service_no as b_service_no,
                  b.user_rank as b_user_rank,
                  stations.station_name
                  FROM
                  user_master a
                  INNER JOIN user_master b ON a.latest_user_tbl_id = b.user_id
                  INNER JOIN stations ON a.station_id = stations.station_id
                  WHERE a.active=? ';
                  $params = array('0');
                  /*check admin is system admin or not*/
                 if($this->data['admin']->privilage_id!='4'){
                    if($this->data['admin']->privilage_id>='2'){
                        $query .= ' AND a.station_id=? AND a.privilage_id <=? ';
                        array_push($params,$this->data['admin']->station_id );
                         array_push($params,$this->data['admin']->privilage_id );
                    }
                  }
        $pagination = new paginator($query,$params);

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
}


/*create new user
*above level 2 users can create user less than his pivilage
*/
	public function newuser(){
            $this->checkModuleAccess([4]);
    parent::checkPermition('1');
		if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $vali = array(
                    'station_id'=>array('name'=>'Station','required'=>TRUE,'min'=>'1'),
                    'user_name'=>array('name'=>'User Name','required'=>TRUE,'min'=>'2','username'=>TRUE),
                    'pwd'=>array('name'=>'Password','required'=>TRUE,'min'=>'6','max'=>'20'),
                    'c_pwd'=>array('name'=>'Confirm password','required'=>TRUE,'min'=>'6','max'=>'20','match'=>'pwd','matchname'=>'Password'),
                    'service_no'=>array('name'=>'Service Number','required'=>TRUE,'min'=>'1'),
                    'user_rank'=>array('name'=>'Rank','required'=>TRUE,'min'=>'2'),
                    'name'=>array('name'=>'Name with Initials','required'=>TRUE,'min'=>'2'),
                    'regiment'=>array('name'=>'Regiment','required'=>TRUE,'min'=>'2'),
                    'unit'=>array('name'=>'Unit','required'=>TRUE,'min'=>'2'),
                    'nic'=>array('name'=>'NIC','required'=>TRUE,'min'=>'2'),
                    'addr'=>array('name'=>'Address','required'=>TRUE,'min'=>'2'),
                    'mobile'=>array('name'=>'Mobile','required'=>TRUE,'min'=>'2'),
                    'section'=>array('name'=>'Section','required'=>TRUE,'min'=>'1'),
                    'privilage_id'=>array('name'=>'Privilage Level','required'=>TRUE));

                if(input::get('user_id')){
                  unset($vali['pwd']);
                  unset($vali['c_pwd']);
                  unset($vali['user_name']['username']);
                }

                $validate->check($_POST, $vali);
                if($validate->passed()){
                    if(input::get('user_id')){
                        //update Item
                        try {
                            $this->model->change(array( 'user_name'=>escape(input::get('user_name')),
                              'station_id'=>escape(input::get('station_id')),
                              'service_no'=>escape(input::get('service_no')),
                              'user_rank'=>escape(input::get('user_rank')),
                              'name'=>escape(input::get('name')),
                              'regiment'=>escape(input::get('regiment')),
                              'unit'=>escape(input::get('unit')),
                              'nic'=>escape(input::get('nic')),
                              'addr'=>escape(input::get('addr')),
                              'mobile'=>escape(input::get('mobile')),
                              'privilage_id'=>escape(input::get('privilage_id')),
                               'section'=> escape(input::get('section')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_dt'=>getDT(),
                              'active'=>'1'),
                            array('user_id','=', escape(input::get('user_id'))));    
                            session::flash('success', 'User Details updated');
                            router::redirect(HTTP_PATH.'manageuser');
                            exit();
                        } catch (Exception $ex) {
                            session::flash('error', $ex->getMessage());
                            }
                    }else{
                        //create new user
                        try {
                           $salt = Hash::salt(32);
                            $this->model->createUser(array( 'user_name'=>escape(input::get('user_name')),
                              'station_id'=>escape(input::get('station_id')),
                              'service_no'=>escape(input::get('service_no')),
                              'user_rank'=>escape(input::get('user_rank')),
                              'name'=>escape(input::get('name')),
                              'regiment'=>escape(input::get('regiment')),
                              'unit'=>escape(input::get('unit')),
                              'nic'=>escape(input::get('nic')),
                              'addr'=>escape(input::get('addr')),
                              'mobile'=>escape(input::get('mobile')),
                              'privilage_id'=>escape(input::get('privilage_id')),
                                'section'=> escape(input::get('section')),
                              'salt'=>$salt,
                              'pwd'=>  Hash::make(escape(input::get('pwd')), $salt),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_dt'=>getDT(),
                              'active'=>'1'));
                            session::flash('success', 'New User account created');
                            router::redirect(HTTP_PATH.'manageuser/');
                           exit();
                        } catch (Exception $ex) {
                            session::flash('error',$ex->getMessage());
                        }
                    }
                }else{
                    session::flash('error', $validate->errors());
                }
            } 
        }

		/*
		 *get stations name
		 */
		$this->station = new stations();
		$this->data['station'] = $this->station->getStation(array('active','=','1'));


		/*
         * edit exissting user
         */
        $this->data['title'] = 'New';
        if(isset($this->getParams()[0])){
          $this->model->find(escape($this->getParams()[0]));
          $this->data['edit'] =  $this->model->getData();
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           }  
        }

	}
}