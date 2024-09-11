<?php

class ivController extends Controller{
	private $_category,$_station,$_crv;
	public function __construct($data=array()){
		 parent::__construct($data);
     parent::checkAccess('iv');
        $this->model = new ivs();
	}


	public function index(){
         if(input::get('del')){
          /*check user has permition to do this task*/
       /*   if(parent::checkPermition(2)){ 
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
           }  */         
       } 
       $query = 'SELECT station_iv_header.*, user_master.name, user_master.service_no, user_master.user_rank,  item_category.cat_name FROM station_iv_header 
        LEFT JOIN item_category ON station_iv_header.iv_type = item_category.item_cat_id
        LEFT JOIN user_master ON station_iv_header.latest_user_tbl_id = user_master.user_id 
        WHERE station_iv_header.active = ? AND station_iv_header.station_id=?  ORDER BY station_iv_header.iv_id DESC';

        $pagination = new paginator($query,array('1',$this->data['admin']->station_id) );

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();

     /*  foreach ($this->data['details']  as $key => $value) {
        $count =  $this->model->getFromQ('SELECT COUNT(*) as cou FROM
                              bmd_iv_details
                              WHERE
                              bmd_iv_details.active = ? AND
                              bmd_iv_details.issue_status = ? AND 
                              bmd_iv_details.iv_header_id=?
                              GROUP BY bmd_iv_details.iv_header_id',array('1','0',$value->iv_header_id));
        if($count){ $this->data['details'][$key]->cou = $count[0]->cou; }else{
          $this->data['details'][$key]->cou = '';
        }
          
       } */ 
	}

	public function newiv(){
    
     if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
                    'iv_type'=>array('name'=>'Default Item Type','required'=>TRUE,'min'=>'1'),
                    'iv_no'=>array('name'=>'IV Number','required'=>TRUE,'min'=>'3'),
                    'iv_date'=>array('name'=>'IV Date','required'=>TRUE,'min'=>'3','date'=>'Y-m-d'),
                    'iv_to'=>array('name'=>'IV To','required'=>TRUE,'min'=>'1'),
                    ));
                if($validate->passed()){
                    if(input::get('iv_id')){
                        //update bmdiv
                        try {
                            $this->model->updateIv(array('iv_type'=> escape(input::get('iv_type')),
                              'iv_no'=> strtoupper(input::get('iv_no')),
                              'iv_date'=> escape(input::get('iv_date')),
                              'iv_to'=> strtoupper(escape(input::get('iv_to'))),
                              'remarks'=>strtoupper(escape(input::get('remarks'))),
                              'station_id'=>$this->data['admin']->station_id,
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                                'latest_ip'=> $this->ip,
                                'system_date'=>date('Y-m-d'),
                                'system_dt'=>getDT(),
                                'active'=>'1'),array('iv_id','=', escape(input::get('iv_id'))));    
                            session::flash('success', 'Iv updated');
                            router::redirect(HTTP_PATH.'iv');
                            exit();
                        } catch (Exception $ex) {
                            session::flash('error', $ex->getMessage());
                            } 
                    }else{
                        //create new bmdIV
                        try {
                            $this->model->createIv(array('iv_type'=> escape(input::get('iv_type')),
                            	'iv_no'=> strtoupper(input::get('iv_no')),
                            	'iv_date'=> escape(input::get('iv_date')),
                            	'iv_to'=> strtoupper(escape(input::get('iv_to'))),
                            	'remarks'=>strtoupper(escape(input::get('remarks'))),
                              'station_id'=>$this->data['admin']->station_id,
                            	'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              	'latest_ip'=> $this->ip,
                              	'system_date'=>date('Y-m-d'),
                              	'system_dt'=>getDT(),
                              	'active'=>'1'));
                            session::flash('success', 'New Iv header created');
                            router::redirect(HTTP_PATH.'iv/');
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

       $this->data['title'] = 'New';
        /*
         * edit exissting IV
         */
          if(isset($this->getParams()[0])){
           if(parent::checkPermition(1)){ 
          $this->data['edit'] =  $this->model->getIv(array('iv_id','=', escape($this->getParams()[0])))[0];
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           }
           }  
        }else{ 
        $station = new stations(); 
       $iv_type = $station->getStation(array('station_id','=',$this->data['admin']->station_id))[0]->default_item_cat_id;
       input::put('iv_type',$iv_type);
     }
	}


  public function ivdetails(){
     if(input::get('del')){
       if(parent::checkPermition(2)){ 
           try {
                /*get the current quantity*/
               $deleteDetails = $this->model->getIvDetails(array('tbl_id','=', clearInt(input::get('del'))))[0];
             
              $this->upStock($deleteDetails->item_code,$deleteDetails->issue_qty,'IN');
         
              $this->model->updateIvDetails(array('active'=>'0'),array('tbl_id','=', clearInt(input::get('del'))));
               session::flash('success', 'Item details deleted');
               router::redirect(HTTP_PATH.'iv/ivdetails/'.$this->getParams()[0]);
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Item details not deleted!');
               router::redirect(HTTP_PATH.'iv/ivdetails/'.$this->getParams()[0]);
               exit();
           }
          } 
       } 

      
      if(isset($this->getParams()[0])){
          $this->data['ivheader'] =  $this->model->getIv(array(array('iv_id','=', escape($this->getParams()[0])) , array('station_id','=',$this->data['admin']->station_id))); 
          if(count($this->data['ivheader'])==0){
            router::redirect(HTTP_PATH.'iv');
            exit();
            } 
           } else{
          router::redirect(HTTP_PATH.'iv');
          exit();
        }

        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
             'item_code'=>array('name'=>'Item name','required'=>TRUE,'min'=>'1'),                     
             'batch_no'=>array('name'=>'Batch Number','required'=>FALSE,'min'=>'4','max'=>'25'),
             'issue_qty'=>array('name'=>'Issue Quantity','required'=>TRUE,'number'=>true,'min'=>'1','max'=>'8'),
             'expire_date'=>array('name'=>'Expire Date','required'=>FALSE,'date'=>'Y-m-d'),
             'iv_header_id'=>array('name'=>'IV header id','min'=>'1')
                  ));         
                  $this->_crv = new crvs();       
                if($validate->passed()){
                    try {
                          $this->model->createIvDetails(array(
                              'iv_header_id'=>clearInt(input::get('iv_header_id')),
                              'item_code'=> clearInt(input::get('item_code')),
                              'issue_qty'=> clearInt(input::get('issue_qty')),
                              'batch_no'=> escape(input::get('batch_no')),
                              'expire_date'=> escape(input::get('expire_date')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'station_id'=>$this->data['admin']->station_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'));

                             $this->upStock(clearInt(input::get('item_code')),clearInt(input::get('issue_qty')),'OUT');

                        session::flash('success', 'Iitem added to IV');
                         router::redirect(HTTP_PATH.'iv/ivdetails/'.clearInt(input::get('iv_header_id')));
                       exit();
                      } catch (Exception $exc) {
                         session::flash('error', $ex->getMessage());
                      }                                        
                  
                }else{
                    session::flash('error',$validate->errors());
                }                
            }
        }
           /*get category details*/
      $this->_category =  new categorys();
      $this->data['category'] = $this->_category->getCategory(array('active','=','1'),array('cat_name'=>'ASC')); 
      
      
      $query = 'SELECT station_iv_details.*,
                item_master.item_code,
                item_master.item_name,
                master_unit.unit_name,
                user_master.name,
                user_master.service_no, 
                user_master.user_rank
                FROM
                station_iv_details
                LEFT JOIN item_master ON station_iv_details.item_code = item_master.item_code
                LEFT JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                LEFT JOIN user_master ON station_iv_details.latest_user_tbl_id = user_master.user_id
                WHERE station_iv_details.active=? AND 
                station_iv_details.iv_header_id=? ORDER BY
                station_iv_details.tbl_id DESC';
    //  echo $query;
    $this->data['details'] =  $this->model->getFromQ($query,array('1',clearInt($this->getParams()[0])));
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
  


}