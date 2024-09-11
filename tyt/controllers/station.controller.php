<?php
class stationController extends Controller{
	private $_category; 
     public function __construct($data = array()) {
         parent::__construct($data);
         parent::checkAccess('station');
         $this->model = new stations();
     }
     
     public function index(){  
         $this->checkModuleAccess([2,4]);
      if(input::get('del') || input::get('rep')){
         if(parent::checkPermition(2)){ 
          if(input::get('del')){
            $active ='0';$message = 'Delete';$id = escape(input::get('del'));
          }else{
            $active='1';$message = 'Reactivate';$id = escape(input::get('rep'));
          }
           try {
               $this->model->updateStation(array('active'=>$active),array('station_id','=', $id));
               session::flash('success', 'Station  '.$message);
               router::redirect(HTTP_PATH.'station');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Station not '.$message);
               router::redirect(HTTP_PATH.'station');
               exit();
           }  
           }         
       }

     
        $query ='SELECT stations.*, user_master.name, user_master.service_no, user_master.user_rank FROM stations 
         INNER JOIN user_master ON stations.latest_user_tbl_id = user_master.user_id 
        WHERE stations.active = ? AND stations.station_id>?    ORDER BY stations.station_name ASC';
          $param = array(1,1);
       if($this->data['admin']->privilage_id>3){
       $query= str_replace('stations.active = ? AND ', '', $query);
       unset($param[0]);
       }

        $pagination = new paginator($query,$param); 

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart(); 

     	}

    public function newstation(){
        $this->checkModuleAccess([2,4]);
        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
                    'station_name'=>array('name'=>'Station name','required'=>TRUE,'min'=>'4','max'=>'150'),
                    'addr'=>array('name'=>'Station Address','required'=>FALSE,'min'=>'4','max'=>'200'),
                    'tele'=>array('name'=>'Phone','required'=>FALSE,'count'=>'10','number'=>'true'),
                    'mobile'=>array('name'=>'mobile','required'=>FALSE,'count'=>'10','number'=>'true'),
                    'email'=>array('name'=>'Email','required'=>FALSE,'email'=>'true'),
                    'incharge_detail'=>array('name'=>'Incharge Rank and Name','required'=>TRUE,'min'=>'5'),
                    'default_item_cat_id'=>array('name'=>'Default Item Category','required'=>TRUE,'min'=>'1')
                    ));
                if($validate->passed()){
                    if(input::get('station_id')){
                        //update Station
                        try {
                            $this->model->updateStation(
                            	array(
                            	'station_name'=> strtoupper(escape(input::get('station_name'))),
                            	'addr'=>escape(input::get('addr')),
                            	'tele'=>clearInt(input::get('tele')),
                            	'mobile'=>clearInt(input::get('mobile')),
                            	'email'=>escape(input::get('email')),
                            	'incharge_detail'=>escape(input::get('incharge_detail')),
                            	'default_item_cat_id'=>clearInt(input::get('default_item_cat_id')),
                            	'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              	'latest_ip'=> $this->ip,
                              	'system_date'=>date('Y-m-d'),
                              	'system_dt'=>getDT(),
                              	'active'=>'1'),array('station_id','=', clearInt(input::get('station_id'))));    
                            session::flash('success', 'Station details updated');
                            router::redirect(HTTP_PATH.'station');
                            exit();
                        } catch (Exception $ex) {
                            session::flash('error', $ex->getMessage());
                            }
                    }else{
                        //create new station
                        try {
                            $this->model->createStation(array(
                            	'station_name'=> strtoupper(escape(input::get('station_name'))),
                            	'addr'=>escape(input::get('addr')),
                            	'tele'=>clearInt(input::get('tele')),
                            	'mobile'=>clearInt(input::get('mobile')),
                            	'email'=>escape(input::get('email')),
                            	'incharge_detail'=>escape(input::get('incharge_detail')),
                            	'default_item_cat_id'=>clearInt(input::get('default_item_cat_id')),
                            	'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              	'latest_ip'=> $this->ip,
                              	'system_date'=>date('Y-m-d'),
                              	'system_dt'=>getDT(),
                              	'active'=>'1'));
                            session::flash('success', 'New Station created');
                            router::redirect(HTTP_PATH.'station/');
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

        /*get category details*/
        $this->_category =  new categorys();
        $this->data['category'] = $this->_category->getCategory(array('active','=','1'),array('cat_name'=>'ASC'));

        /*
         * edit exissting Measure
         */
        $this->data['title'] = 'New';
        if(isset($this->getParams()[0])){
           if(parent::checkPermition(1)){ 
          $this->data['edit'] =  $this->model->getStation(array('station_id','=', escape($this->getParams()[0])))[0];
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           }  
        }
       } 
     }

     }
