<?php
 class itemController extends Controller{
  private $_category, $_measure;
     public function __construct($data = array()) {
         parent::__construct($data);
         parent::checkAccess('item');
         $this->model = new Items(); 
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
               $this->model->updateItem(array('active'=>$active),array('item_code','=', $id));
               session::flash('success', 'Item  '.$message);
               router::redirect(HTTP_PATH.'item');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Item not '.$message);
               router::redirect(HTTP_PATH.'item');
               exit();
           }  
           }         
       }
     

        $pagination = new paginator('SELECT item_master.*, item_category.cat_name, user_master.name, user_master.service_no, user_master.user_rank FROM item_master 
        INNER JOIN user_master ON item_master.latest_user_tbl_id = user_master.user_id 
        INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
         WHERE item_master.active=? || item_master.active=?  ORDER BY item_master.item_name ASC',array(1,0) );
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):50;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['item'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
          
     }
     
     public function newItem(){
         $this->checkModuleAccess([1,4]);
        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $vali = array(
                    'item_cat_id'=>array('name'=>'Category','required'=>TRUE,'min'=>'1'),
                    'item_name'=>array('name'=>'Item name','required'=>TRUE,'min'=>'2'),
                    'measure_unit_id'=>array('name'=>'Item Unit','required'=>TRUE,'min'=>'1'),
                    'item_bin_no'=>array('name'=>'Bin cad No','required'=>FALSE,'min'=>'2','unique'=>'item_master')
                    );
                if(input::get('item_code')){
                     unset($vali['item_bin_no']['unique']);
                }
                $validate->check($_POST,$vali);
                
                
                if($validate->passed()){
                    if(input::get('item_code')){
                        //update Item
                        try {
                            $this->model->updateItem(array(                              
                              'item_name'=> escape(input::get('item_name')),
                              'item_cat_id'=> escape(input::get('item_cat_id')),
                              'measure_unit_id'=> escape(input::get('measure_unit_id')),
                              'item_bin_no'=> escape(input::get('item_bin_no')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'),
                            array('item_code','=', escape(input::get('item_code'))));    
                            session::flash('success', 'Item updated');
                            router::redirect(HTTP_PATH.'item');
                            exit();
                        } catch (Exception $ex) {
                            session::flash('error', $ex->getMessage());
                            }
                    }else{
                        //create new Item
                        try {
                           
                            $this->model->createItem(array(
                              'item_name'=> escape(input::get('item_name')),
                              'item_cat_id'=> escape(input::get('item_cat_id')),
                              'measure_unit_id'=> escape(input::get('measure_unit_id')),
                              'item_bin_no'=> escape(input::get('item_bin_no')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'));
                            session::flash('success', 'New Item created');
                            router::redirect(HTTP_PATH.'item/');
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
          $this->data['edit'] =  $this->model->getItem(array('item_code','=', escape($this->getParams()[0])))[0];
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           } 
           } 
        }


        /*
        *get category list
        */
        $this->_category = new categorys();
        $this->data['category'] = $this->_category->getCategory(array('active','=','1'),array('cat_name'=>'ASC'));

        /*
        *Get unit list
        */

        $this->_measure = new measures();
        $this->data['measures'] = $this->_measure->getMeasure(array('active','=','1'),array('unit_name'=>'ASC'));
     }
 }

