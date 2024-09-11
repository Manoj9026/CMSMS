<?php

class bmdissuController extends Controller{
	private $_category,$_station,$_crv;
	public function __construct($data=array()){ 
		 parent::__construct($data);
            parent::checkAccess('bmdiv');
        $this->model = new bmdivs();
	}


	public function index(){
            $this->checkModuleAccess([3,4]);
            if($this->data['admin']->section){
         if(input::get('del')){
          /*check user has permition to do this task*/
          if(parent::checkPermition(1)){ 
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
       }
       $query = 'SELECT bmd_iv_header.*, user_master.name, user_master.service_no, user_master.user_rank, stations.station_name, item_category.cat_name FROM bmd_iv_header 
        INNER JOIN stations ON bmd_iv_header.iv_to = stations.station_id
        INNER JOIN item_category ON bmd_iv_header.iv_type = item_category.item_cat_id
        INNER JOIN user_master ON bmd_iv_header.latest_user_tbl_id = user_master.user_id 
        WHERE bmd_iv_header.active = ?  ORDER BY bmd_iv_header.iv_header_id DESC';

        $pagination = new paginator($query,array('1') );

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();

       foreach ($this->data['details']  as $key => $value) {
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
                }
            }
        }
        
       


  public function view(){ 
      $this->checkModuleAccess([3,4]); 
       if(input::get('issue')){ 
        try{
          $deleteDetails = $this->model->getIvDetails(array('bmd_iv_detail_id','=', clearInt(input::get('issue'))))[0];
          $this->upStock($deleteDetails->item_code,$deleteDetails->issue_qty,'OUT');
          $this->model->updateIvDetails(array('issue_status'=>'1','issue_time'=>getDT(),'issue_by'=>$this->data['admin']->user_id , 'system_dt'=>getDT()),array('bmd_iv_detail_id','=', clearInt(input::get('issue'))));
               session::flash('success', 'Item Issued.');
               router::redirect(HTTP_PATH.'bmdissu/view/'.$this->getParams()[0]);
               exit();
        }catch (Exception $ex) {
               session::flash('error', 'Item not issued!');
               router::redirect(HTTP_PATH.'bmdissu/view/'.$this->getParams()[0]);
               exit();
           }
       }
       
           /*get category details*/
      $this->_category =  new categorys();
      $this->data['category'] = $this->_category->getCategory(array('active','=','1'),array('cat_name'=>'ASC')); 
       $this->data['ivheader'] =  $this->model->getIv(array('iv_header_id','=', escape($this->getParams()[0])));
      $query = 'SELECT bmd_iv_details.*,
                item_master.item_code,
                item_master.item_bin_no,
                item_master.item_name,
                master_unit.unit_name,
                user_master.name,
                user_master.service_no, 
                user_master.user_rank,
                concat(U.service_no," ", U.user_rank," ",U.name,"  <br/> ",bmd_iv_details.issue_time) issu_by
                FROM
                bmd_iv_details
                INNER JOIN item_master ON bmd_iv_details.item_code = item_master.item_code
                INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                INNER JOIN user_master ON bmd_iv_details.latest_user_tbl_id = user_master.user_id
                Left JOIN user_master U ON bmd_iv_details.issue_by = U.user_id
                WHERE bmd_iv_details.active=? AND 
                bmd_iv_details.iv_header_id=? ORDER BY
                bmd_iv_details.bmd_iv_detail_id DESC';
    $this->data['details'] =  $this->model->getFromQ($query,array('1',clearInt($this->getParams()[0])));
  }


    public function upStock($code,$units,$type){
        $this->checkModuleAccess([3,4]);
      /*get the current quantity*/
     $current = $this->model->getCurrentQuantity(array('item_code','=',$code))[0]->qoh;
       switch ($type) {
       case 'IN':
          $newStoc = $current+$units; 
         break;       
       default:
         $newStoc = $current-$units; 
         break;
     }
      return $this->model->updateStock(
        array('qoh'=>$newStoc,
          'last_txn_type'=>$type,
          'latest_user_tbl_id'=> $this->data['admin']->user_id,
          'latest_ip'=> $this->ip,
          'system_date'=>date('Y-m-d'),
          'system_dt'=>getDT()),array('item_code','=',$code)); 
    }


       

}