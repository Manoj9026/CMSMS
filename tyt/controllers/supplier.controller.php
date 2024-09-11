<?php
 class supplierController extends Controller{
  private $_category, $_measure;
     public function __construct($data = array()) {
         parent::__construct($data);
         parent::checkAccess('supplier');
         $this->model = new suppliers(); 
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
               $this->model->updateSupplier(array('active'=>$active),array('supp_id','=', $id));
               session::flash('success', 'Supplier  '.$message);
               router::redirect(HTTP_PATH.'supplier');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Supplier not '.$message);
               router::redirect(HTTP_PATH.'supplier');
               exit();
           }  
           }         
       }


         $query ='SELECT supplier_master.*, user_master.name, user_master.service_no, user_master.user_rank 
          FROM supplier_master 
          INNER JOIN user_master ON supplier_master.latest_user_tbl_id = user_master.user_id  
          WHERE supplier_master.active=? AND supplier_master.supp_id>? ORDER BY supplier_master.supp_name ASC';
          $param = array(1,1);
       if($this->data['admin']->privilage_id>3){
       $query= str_replace('supplier_master.active=? AND', '', $query);
       unset($param[0]);
       }

      

        $pagination = new paginator($query,$param);

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['item'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();         
     }
     
     public function newsupplier(){
         $this->checkModuleAccess([1,4]);
        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
                    'supp_name'=>array('name'=>'Supplier Name','required'=>TRUE,'min'=>'3'),
                    'addr'=>array('name'=>'Address','required'=>FALSE,'min'=>'5'),
                    'email'=>array('name'=>'Email','required'=>FALSE,'email'=>'false'),
                    'tele'=>array('name'=>'Telephone','required'=>FALSE,'number'=>'false','count'=>'10'),
                    'mobile'=>array('name'=>'Mobile','required'=>FALSE,'number'=>'false','count'=>'10'),
                    ));
                if($validate->passed()){
                    if(input::get('supp_id')){
                        //update Item
                        try {
                            $this->model->updateSupplier(array(                              
                              'supp_name'=> escape(input::get('supp_name')),
                              'addr'=> escape(input::get('addr')),
                              'tele'=> clearInt(input::get('tele')),
                              'mobile'=> clearInt(input::get('mobile')),
                              'email'=> escape(input::get('email')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'),
                            array('supp_id','=', escape(input::get('supp_id'))));    
                            session::flash('success', 'Suplier details updated');
                            router::redirect(HTTP_PATH.'supplier');
                            exit();
                        } catch (Exception $ex) {
                            session::flash('error', $ex->getMessage());
                            }
                    }else{
                        //create new Item
                        try {
                            $this->model->createSupplier(array(
                              'supp_name'=> escape(input::get('supp_name')),
                              'addr'=> escape(input::get('addr')),
                              'tele'=> clearInt(input::get('tele')),
                              'mobile'=> clearInt(input::get('mobile')),
                              'email'=> escape(input::get('email')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'));
                            session::flash('success', 'New Supplier created');
                            router::redirect(HTTP_PATH.'supplier/');
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
         * edit exissting Item
         */
        $this->data['title'] = 'New';
        if(isset($this->getParams()[0])){
           if(parent::checkPermition(1)){ 
          $this->data['edit'] =  $this->model->getSupplier(array('supp_id','=', escape($this->getParams()[0])))[0];
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           }  
        }
      }

     }

 }

