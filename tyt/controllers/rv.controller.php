<?php

/**
 * Description of rv
 *
 * @author User
 */
class rvController extends Controller {
    public $_supplier,$_category;
    public function __construct($data = array()) {
        parent::__construct($data);
        parent::checkAccess('rv');
        $this->model = new rvs();
    }
    
    public function index(){        
        if($this->data['admin']->station_id!=2){

        } 

         if(input::get('del')){
           try {
               $this->model->updateCrv(array('active'=>'0'),array('crv_id','=', clearInt(input::get('del'))));
               session::flash('success', 'Item details deleted');
               router::redirect(HTTP_PATH.'crv');
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Item details not deleted!');
               router::redirect(HTTP_PATH.'crv');
               exit();
           }
           
       }

        $pagination = new paginator('SELECT station_rv_header.*,  user_master.name, user_master.service_no,user_master.user_rank 
          FROM station_rv_header         
          INNER JOIN user_master ON station_rv_header.latest_user_tbl_id = user_master.user_id
          WHERE station_rv_header.active = ? AND station_rv_header.station_id=?
          ORDER BY station_rv_header.rv_id DESC',array('1',$this->data['admin']->station_id) );

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();

    }
    
    public function newrv(){
        
        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                if(input::get('rv_id')){$rv = array('name'=>'RV No','required'=>TRUE,'min'=>'1'); }else{ $rv =array('name'=>'RV No','required'=>TRUE,'min'=>'1','unique'=>'station_rv_header');}
                $validate->check($_POST, array(
                 'rv_type_id'=>array('name'=>'Recieve Category','required'=>TRUE,'min'=>'1'), 
                    'rv_no'=>$rv, 
                    'rv_date'=>array('name'=>'RV Date','required'=>TRUE,'date'=>'Y-m-d'),                     
                    'iv_no'=>array('name'=>'RV No','required'=>TRUE,'min'=>'1'),
                    'iv_date'=>array('name'=>'IV Date','required'=>TRUE,'date'=>'Y-m-d'),
                    'iv_from'=>array('name'=>'IV From','max'=>120),
                ));                
                if($validate->passed()){
                  if(input::get('rv_id')){
                      //update rv - ok
                      try { $this->model->updateRv(array(
                              'rv_type_id'=> clearInt(input::get('rv_type_id')),
                              'rv_no'=> (input::get('rv_no')),
                              'rv_date'=> escape(input::get('rv_date')),
                              'iv_no'=> (input::get('iv_no')),
                              'iv_date'=> escape(input::get('iv_date')),
                              'iv_from'=> (escape(input::get('iv_from'))),
                              'station_id'=>$this->data['admin']->station_id,
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'),
                              array('rv_id','=', escape(input::get('rv_id'))));
                          session::flash('success', 'RV header Updated');
                          router::redirect(HTTP_PATH.'rv');
                          exit();
                           } catch (Exception $ex) {
                          session::flash('error', $ex->getMessage());
                      } 
                  }else{
                      //crate Rv - ok
                      try {
                          $this->model->createRv(array(
                              'rv_type_id'=> clearInt(input::get('rv_type_id')),
                              'rv_no'=> (escape(input::get('rv_no'))),
                              'rv_date'=> escape(input::get('rv_date')),
                              'iv_no'=> (escape(input::get('iv_no'))),
                              'iv_date'=> escape(input::get('iv_date')),
                              'iv_from'=> (escape(input::get('iv_from'))),
                              'station_id'=>$this->data['admin']->station_id,
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'));
                          session::flash('success', 'New RV header created');
                          router::redirect(HTTP_PATH.'rv');
                          exit();
                      } catch (Exception $exc) {
                         session::flash('error', $ex->getMessage());
                      }                                        
                  } 
                }else{
                    session::flash('error',$validate->errors());
                }                
            }
        }

         /*get category details*/
        $this->_category =  new categorys();
        $this->data['category'] = $this->_category->getCategory(array('active','=','1'),array('cat_name'=>'ASC'));
        
        /*
         * edit exissting Item
         */
        $this->data['title'] = 'New';
        if(isset($this->getParams()[0])){
           if(parent::checkPermition(2)){ 
          $this->data['edit'] =  $this->model->getRv(array(array('rv_id','=',escape($this->getParams()[0])),array('station_id','=',$this->data['admin']->station_id)));
          /*redirect if user try to edit other station RV*/
          if(count($this->data['edit'])!=1){ router::redirect(HTTP_PATH.'rv');}          
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'][0] as $key => $value) {
               input::put($key, $value);
           }  
        }
      }else{ 
        $station = new stations(); 
       $rv_type_id = $station->getStation(array('station_id','=',$this->data['admin']->station_id))[0]->default_item_cat_id;
       input::put('rv_type_id',$rv_type_id);
     }
    }

    public function rvdetails(){

      if(input::get('del')){
         if(parent::checkPermition(2)){ 
           try {
                /*get the current quantity*/
               $deleteDetails = $this->model->getRvDetails(array('tbl_id','=', clearInt(input::get('del'))))[0];
              $this->upStock($deleteDetails->item_code,$deleteDetails->receive_qty,'OUT');
              $this->model->updateRvDetails(array('active'=>'0'),array(array('rv_header_id','=',clearInt($this->getParams()[0])),array('tbl_id','=', clearInt(input::get('del')))));
               session::flash('success', 'Item details deleted and stock update');
               router::redirect(HTTP_PATH.'rv/rvdetails/'.$this->getParams()[0]);
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Item details not deleted!');
               router::redirect(HTTP_PATH.'rv/rvdetails/'.$this->getParams()[0]);
               exit();
           }
           }
       } 

      if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array( 
                 'item_code'=>array('name'=>'Item name','required'=>TRUE,'min'=>'1'),  
                  'receive_qty'=>array('name'=>'Recived Quantity','required'=>TRUE,'number'=>true,'min'=>'1','max'=>'8'), 
                  'batch_no'=>array('name'=>'Batch Number','required'=>TRUE,'min'=>'1','max'=>'25'),
                  'expire_date'=>array('name'=>'Expire Date','required'=>TRUE,'date'=>'Y-m-d','future'=>TRUE)
                  ));                
                if($validate->passed()){
                    try {
                          $this->model->createRvDetails(array(
                              'rv_header_id'=>clearInt(input::get('rv_header_id')),
                              'item_code'=> clearInt(input::get('item_code')),
                              'receive_qty'=> clearInt(input::get('receive_qty')),
                              'batch_no'=> escape(input::get('batch_no')),
                              'expire_date'=> escape(input::get('expire_date')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'station_id'=>$this->data['admin']->station_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'));

                          $this->upStock(clearInt(input::get('item_code')),clearInt(input::get('receive_qty')),'IN');
                          session::flash('success', 'New RV item added and stock update');
                          router::redirect(HTTP_PATH.'rv/rvdetails/'.clearInt(input::get('rv_header_id')));
                          exit(); 
                      } catch (Exception $exc) {
                         session::flash('error', $ex->getMessage());
                      }                     
                }else{
                    session::flash('error',$validate->errors());
                }                
            }
        }

      /*rv heder details*/
      if(isset($this->getParams()[0])){
          $this->data['header'] =  $this->model->getRv(array(array('rv_id','=', escape($this->getParams()[0])),array('station_id','=',$this->data['admin']->station_id)));

          if(count($this->data['header'])==0){
            router::redirect(HTTP_PATH.'rv');
            exit();
          }else{
                foreach ($this->data['header'][0] as $key => $value) {
                 if($key=='rv_id'){ $rv_header_id =$value; input::put('rv_header_id',$value); continue;}
                input::put($key, $value);
                }               
              }
        }else{
          router::redirect(HTTP_PATH.'rv');
          exit();
        }

        /*get category details*/
        $this->_category =  new categorys();
        $this->data['category'] = $this->_category->getCategory(array('active','=','1'),array('cat_name'=>'ASC'));

        /*get current added crv items details*/
                      $sql =  'SELECT station_rv_details.tbl_id,
                                station_rv_details.rv_header_id,
                                station_rv_details.item_code,
                                station_rv_details.receive_qty,
                                station_rv_details.batch_no,
                                station_rv_details.expire_date,
                                station_rv_details.station_id,
                                station_rv_details.latest_user_tbl_id,
                                station_rv_details.system_date,
                                station_rv_details.system_dt,
                                station_rv_details.latest_ip,
                                station_rv_details.active,
                                item_master.item_name,
                                master_unit.unit_name,
                                user_master.name,
                                user_master.user_rank,
                                user_master.service_no
                                FROM
                                station_rv_details
                                INNER JOIN item_master ON station_rv_details.item_code = item_master.item_code
                                INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                                INNER JOIN user_master ON station_rv_details.latest_user_tbl_id = user_master.user_id
                                WHERE station_rv_details.station_id=? AND 
                                      station_rv_details.active=? AND 
                                      station_rv_details.rv_header_id=?';
 
          $this->data['details'] =  $this->model->getFromQ($sql,array($this->data['admin']->station_id,'1',escape($this->getParams()[0])));  
    }

    public function upStock($code,$units,$type){
      /*get the current quantity*/
     $curr = $this->model->getCurrentQuantity(array(array('item_code','=',$code),array('station_id','=',$this->data['admin']->station_id)));
    
    $current = (count($curr)>0)?$curr[0]->qoh:0;
     switch ($type) {
       case 'IN':
          $newStoc = $current+$units; 
         break;       
       default:
         $newStoc = $current-$units; 
         break;
     }

    if(count($curr)==0){
      /*add new item to the table if this station not available this item */
    return  $this->model->addToStationStock(array('station_id'=>$this->data['admin']->station_id,
                                             'item_code'=>$code,
                                             'qoh'=>$newStoc,
                                             'last_txn_type'=>$type,
                                             'latest_user_tbl_id'=> $this->data['admin']->user_id,
                                              'latest_ip'=> $this->ip,
                                              'system_date'=>date('Y-m-d'),
                                              'system_dt'=>getDT(),
                                             'active'=>'1'));
    }else{
      /*update the qurant quantity with new araivals*/
       return $this->model->updateStock(
        array('qoh'=>$newStoc,
          'last_txn_type'=>$type,
          'latest_user_tbl_id'=> $this->data['admin']->user_id,
          'latest_ip'=> $this->ip,
          'system_date'=>date('Y-m-d'),
          'system_dt'=>getDT()),array(array('item_code','=',$code),array('station_id','=',$this->data['admin']->station_id))); 
        }
    }

    public function viewcrv(){
        /*crv haeder details*/
      if(isset($this->getParams()[0])){
          $this->data['header'] =  $this->model->getCrv(array('crv_id','=', escape($this->getParams()[0])))[0];
          if(count($this->data['header'])==0){
            router::redirect(HTTP_PATH.'crv');
            exit();
          }else{
                foreach ($this->data['header'] as $key => $value) {
                if($key=='crv_id'){ $crv_header_id =$value; input::put('crv_header_id',$value); continue;}
                input::put($key, $value);
                }
                $supplier = new suppliers();
                $this->data['supplier'] = $supplier->getSupplier(array('supp_id','=',$this->data['header']->supplier_id))[0]; 

               $this->data['header']->crv_type = $this->model->getCrvTypes(array('crv_type_id','=',$this->data['header']->crv_type_id))[0]->crv_type_txt;

              }
        }else{
          router::redirect(HTTP_PATH.'crv');
          exit();
        }

         $sql = 'SELECT
        bmd_crv_details.*,
        item_master.item_name,
        item_master.measure_unit_id,
        master_unit.unit_name,
        item_master.item_code,
        user_master.name,
        user_master.service_no, 
        user_master.user_rank
        FROM
        bmd_crv_details
        INNER JOIN item_master ON bmd_crv_details.item_code = item_master.item_code
        INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
        INNER JOIN user_master ON bmd_crv_details.latest_user_tbl_id = user_master.user_id
        WHERE
        bmd_crv_details.crv_header_id= "'.$crv_header_id.'"  AND  bmd_crv_details.active = 1
        ORDER BY bmd_crv_details.crv_detail_id DESC';

         $pagination = new paginator($sql);

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):200;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);

    }
    
    
}
