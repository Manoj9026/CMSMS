<?php /*compatible with php 7> 2021-07-08*/
 class measureController extends Controller{
     public function __construct($data = array()) {
         parent::__construct($data);
         parent::checkAccess('measure');
         $this->model = new Measures();
     }
     
     public function index(){  
         $this->checkModuleAccess([1,4]);
      if(input::get('del') || input::get('rep')){
         if(parent::checkPermition(2)){ 
          if(input::get('del')){
            $active ='0';$message = 'Delete';$id = escape(input::get('del'));
          }else{
            $active='1';$message = 'Reactivate';$id = escape(input::get('rep'));
          }
           try {
               $this->model->updateMeasure(array('active'=>$active),array('measure_unit_id','=', $id));
               session::flash('success', 'Measure unit  '.$message);
               router::redirect(HTTP_PATH.'measure');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Measure unit not '.$message);
               router::redirect(HTTP_PATH.'measure');
               exit();
           }  
           }         
       }
 
       $query = 'SELECT * FROM master_unit  WHERE active=? AND measure_unit_id>? ORDER BY unit_name ASC';
       $param = array(1,1);
       if($this->data['admin']->privilage_id>3){
         $query= str_replace('active=? AND', '', $query);
         unset($param[0]);
       }
        $pagination = new paginator($query,$param);
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['measure'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();        
     }
     
     public function newmeasure(){
         $this->checkModuleAccess([1,4]);
        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
                    'unit_name'=>array('name'=>'Measure name','required'=>'true','min'=>'1')
                    ));
                if($validate->passed()){
                    if(input::get('measure_unit_id')){
                        //update Measure
                        try {
                            $this->model->updateMeasure(array('unit_name'=> escape(input::get('unit_name'))),array('measure_unit_id','=', escape(input::get('measure_unit_id'))));    
                            session::flash('success', 'Measure updated');
                            router::redirect(HTTP_PATH.'measure');
                            exit();
                        } catch (Exception $ex) {
                            session::flash('error', $ex->getMessage());
                            }
                    }else{
                        //create new Measure
                        try {
                            $this->model->createMeasure(array('unit_name'=> escape(input::get('unit_name')),'active'=>'1'));
                           session::flash('success', 'New Measure created');
                           router::redirect(HTTP_PATH.'measure/');
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
         * edit exissting Measure
         */
        $this->data['title'] = 'New';
        if(isset($this->getParams()[0])){
           if(parent::checkPermition(1)){ 
          $this->data['edit'] =  $this->model->getMeasure(array('measure_unit_id','=', escape($this->getParams()[0])))[0];
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           }  
        }
        }
     }
 }

