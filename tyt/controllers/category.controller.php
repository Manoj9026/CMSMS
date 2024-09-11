<?php /*compatible with php 7> 2021-07-08*/
 class categoryController extends Controller{
     public function __construct($data = array()) {
         parent::__construct($data);
         parent::checkAccess('cetegory');
         $this->model = new categorys();
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
               $this->model->updateCategory(array('active'=>$active),array('item_cat_id','=', $id));
               session::flash('success', 'Category '.$message);
               router::redirect(HTTP_PATH.'category');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Category not '.$message);
               router::redirect(HTTP_PATH.'category');
               exit();
           } 
           }          
       }
        $query = 'SELECT * FROM item_category  WHERE active=? AND item_cat_id > ?  ORDER BY cat_name ASC';
       $param = array(1,1);
       if($this->data['admin']->privilage_id>3){
         $query= str_replace('active=? AND ', '', $query);
         unset($param[0]);
       }
        $pagination = new paginator($query,$param); 
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['category'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();

      // $this->data['category'] = $this->model->getCategory(array('active','=','1'),array('cat_name'=>'ASC'));         
     }
     
     public function newcategory(){
         $this->checkModuleAccess([1,4]);
        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
                    'cat_name'=>array('name'=>'Category name','required'=>'true','min'=>'3')
                    ));
                if($validate->passed()){
                    if(input::get('item_cat_id')){
                        //update category
                        try {
                            $this->model->updateCategory(array('cat_name'=> escape(input::get('cat_name'))),array('item_cat_id','=', escape(input::get('item_cat_id'))));    
                            session::flash('success', 'Category updated');
                            router::redirect(HTTP_PATH.'category');
                            exit();
                        } catch (Exception $ex) {
                            session::flash('error', $ex->getMessage());
                            }
                    }else{
                        //create new category
                        try {
                            $this->model->createCategory(array('cat_name'=> escape(input::get('cat_name')),'active'=>'1'));
                            session::flash('success', 'New category created');
                            router::redirect(HTTP_PATH.'category/');
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
         * edit exissting category
         */
        $this->data['title'] = 'New';
        if(isset($this->getParams()[0])){
           if(parent::checkPermition(1)){ 
          $this->data['edit'] =  $this->model->getCategory(array('item_cat_id','=', escape($this->getParams()[0])))[0];
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           }  
        }
        }
     }
 }

