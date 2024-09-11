<?php
/**
 * Description of ajax
 * control all ajax request
 */
class ajaxController extends Controller{
    private $_item,$_section,$_supplier,$_measure,$_category,$_crv,$_bmdiv,$_station;
    public function __construct($data = array()) {
        parent::__construct($data);
    }
    
    public function item(){   
    /* get items with available quantity for issue and reciev*/ 
       $this->_item = new items(); 
      if(isset($this->getParams()[0])){ 
        $quary = 'SELECT item_master.item_code,
                        item_master.item_bin_no,
                          bmd_stock_master.qoh, 
                          bmd_stock_master.rol, 
                          item_master.item_name, 
                          master_unit.unit_name 
                FROM item_master 
                INNER JOIN bmd_stock_master ON item_master.item_code = bmd_stock_master.item_code
                INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id 
                WHERE item_master.item_cat_id=? AND item_master.active=?';

        $this->data['re'] = $this->_item->getFromQ($quary,array(clearInt(escape($this->getParams()[0])),'1'));


        $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $qu  ='SELECT bmd_iv_details.issue_qty 
                  FROM bmd_iv_details WHERE
                  bmd_iv_details.issue_status = ? AND
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.item_code = ? AND
                  bmd_iv_details.system_date > "2021-12-27" ';
      $this->data['issue'] = $this->_item->getFromQ($qu,array('0','1',$value->item_code));
        $issue = 0;
        foreach ($this->data['issue'] as $k => $v) {
         $issue+=$v->issue_qty;
        }
          $result[] = array('item_name'=>$value->item_name,'item_code'=>$value->item_code,'qoh'=>$value->qoh,'ablq'=>($value->qoh-$issue),'unit'=>$value->unit_name,'item_bin_no'=>$value->item_bin_no);
        }
        $this->data['result'] = $result;
      }
    }
 
     public function item_layout(){
        return 'api';
    } 


public function item_stock(){   
    /* get items with available quantity for  reports individual item stock*/ 
       $this->_item = new items(); 
      if(isset($this->getParams()[0])){ 
        $quary = 'SELECT item_master.item_code, 
                          bmd_stock_master.qoh, 
                          bmd_stock_master.rol, 
                          item_master.item_name, 
                          master_unit.unit_name 
                FROM item_master 
                INNER JOIN bmd_stock_master ON item_master.item_code = bmd_stock_master.item_code
                INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id 
                WHERE item_master.item_code=? AND item_master.active=?';

        $this->data['re'] = $this->_item->getFromQ($quary,array(clearInt(escape($this->getParams()[0])),'1'));

        $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $qu  ='SELECT bmd_iv_details.issue_qty 
                  FROM bmd_iv_details WHERE
                  bmd_iv_details.issue_status = ? AND
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.item_code = ? AND ';
      $this->data['issue'] = $this->_item->getFromQ($qu,array('0','1',$value->item_code));
        $issue = 0;
        foreach ($this->data['issue'] as $k => $v) {
         $issue+=$v->issue_qty;
        }
          $result['item'] = array('item_name'=>$value->item_name,'item_code'=>$value->item_code,'qoh'=>number_format($value->qoh),'ablq'=>number_format(($value->qoh-$issue)),'issue'=>number_format($issue), 'unit'=>$value->unit_name,'rol'=>number_format($value->rol));
        }
        $this->data['result'] = $result;
      }
    }
 
     public function item_stock_layout(){
        return 'api';
    } 

    public function supplier_search(){
      $this->_supplier = new suppliers();
      if(isset($this->getParams()[0])){ 
      $quary =  'SELECT supplier_master.*, user_master.name, user_master.service_no, user_master.user_rank FROM supplier_master 
          INNER JOIN user_master ON supplier_master.latest_user_tbl_id = user_master.user_id  
          WHERE supplier_master.supp_name LIKE ? AND supplier_master.active=? ORDER BY supplier_master.supp_name ASC LIMIT 20 ';

          $this->data['re'] = $this->_supplier->getFromQ($quary,array('%'.escape($this->getParams()[0]).'%','1'));
        }else{
        $quary =  'SELECT supplier_master.*, user_master.name, user_master.service_no, user_master.user_rank FROM supplier_master 
          INNER JOIN user_master ON supplier_master.latest_user_tbl_id = user_master.user_id  
          WHERE supplier_master.active="1"  ORDER BY supplier_master.supp_name ASC';

          $this->data['re'] = $this->_supplier->getFromQ($quary,array('1'));
        }


        $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $result[] = array('supp_id'=>$value->supp_id,'supp_name'=>$value->supp_name,'contact'=>$value->tele.' / '.$value->mobile,'addr'=>$value->addr,'email'=>$value->email,'supp_id'=>$value->supp_id,'service_no'=>$value->service_no,'user_rank'=>$value->user_rank,'name'=>$value->name,'latest_ip'=>$value->latest_ip,'system_dt'=>$value->system_dt);
        }

        $this->data['result'] = $result;

    }

    public function supplier_search_layout(){
        return 'api';
    } 
    
     public function item_name_search_layout(){
 return 'api';
    }

    public function item_name_search(){    
    $this->_item = new items();
      if(isset($this->getParams()[0])){ 
      $quary =  'SELECT * FROM item_master 
          WHERE item_master.item_name LIKE ? AND item_master.active=? 
          ORDER BY item_master.item_name ASC LIMIT 10 ';
          $se = escape($this->getParams()[0]);
          $this->data['re'] = $this->_item->getFromQ($quary,array('%'.$se.'%','1'));
        } 

        $this->data['result'] = $this->data['re'];        
    }
    
    public function item_search_layout(){
    return 'api';
    }

    public function item_search(){    
    $this->_item = new items();
      if(isset($this->getParams()[0])){ 
      $quary =  'SELECT item_master.*, item_category.cat_name, user_master.name, user_master.service_no, user_master.user_rank 
          FROM item_master 
          INNER JOIN user_master ON item_master.latest_user_tbl_id = user_master.user_id 
          INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
          WHERE item_master.item_name LIKE ? OR item_master.item_bin_no LIKE ? OR item_category.cat_name LIKE ? AND item_master.active=? OR  item_master.active=? 
          ORDER BY item_master.item_name ASC LIMIT 50 ';
          $se = escape($this->getParams()[0]);
          $this->data['re'] = $this->_item->getFromQ($quary,array('%'.$se.'%','%'.$se.'%','%'.$se.'%','1','0'));
        }else{
        $quary =  'SELECT item_master.*, item_category.cat_name, user_master.name, user_master.service_no, user_master.user_rank 
        FROM item_master 
        INNER JOIN user_master ON item_master.latest_user_tbl_id = user_master.user_id 
        INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
        WHERE item_master.active=?  OR item_master.active=? 
        ORDER BY item_master.item_name ASC LIMIT 50';

          $this->data['re'] = $this->_item->getFromQ($quary,array('1','0'));
        }

        $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $result[] = array('item_code'=>$value->item_code,'active'=>$value->active,'item_name'=>$value->item_name,'service_no'=>$value->service_no,'user_rank'=>$value->user_rank,'name'=>$value->name,'latest_ip'=>$value->latest_ip,'system_dt'=>$value->system_dt,'cat_name'=>$value->cat_name,'item_bin_no'=>$value->item_bin_no);
        }

        $this->data['result'] = $result;        
    }

public function measure_search(){
  $this->_measure = new measures();
  if(isset($this->getParams()[0])){
     $quary = 'SELECT * FROM master_unit  WHERE unit_name LIKE ? AND active=?  ORDER BY unit_name ASC';
   $this->data['re'] = $this->_measure->getFromQ($quary,array('%'.escape($this->getParams()[0]).'%','1')); 
  }else{
    $quary = 'SELECT * FROM master_unit  WHERE active=?  ORDER BY unit_name ASC';
   $this->data['re'] = $this->_measure->getFromQ($quary,array('1')); 
  }
   $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $result[] = array('measure_unit_id'=>$value->measure_unit_id,'unit_name'=>$value->unit_name);
        }
        $this->data['result'] = $result;  
}

public function measure_search_layout(){
  return 'api';
}
    
 public function category_search(){
    $this->_category = new categorys();
  if(isset($this->getParams()[0])){
     $quary = 'SELECT * FROM item_category  WHERE cat_name LIKE ?   ORDER BY cat_name ASC';
   $this->data['re'] = $this->_category->getFromQ($quary,array('%'.escape($this->getParams()[0]).'%')); 
  }else{
    $quary = 'SELECT * FROM item_category   ORDER BY cat_name ASC';
   $this->data['re'] = $this->_category->getFromQ($quary); 
  }
   $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $result[] = array('item_cat_id'=>$value->item_cat_id,'cat_name'=>$value->cat_name,'active'=>$value->active);
        }
        $this->data['result'] = $result; 
 }

 public function category_search_layout(){
  return 'api';
 }   


 public function view_crv(){
  $this->_crv = new crvs();
  if(isset($this->getParams()[0])){ 
          $this->data['header'] =  $this->_crv->getCrv(array('crv_id','=', escape($this->getParams()[0])));
          if(count($this->data['header'])>0){
            $result = array();

            $this->_supplier = new suppliers();
            $result['supplier'] = $this->_supplier->getSupplier(array('supp_id','=',$this->data['header'][0]->supplier_id))[0]; 

            $crv_type = $this->_crv->getCrvTypes(array('crv_type_id','=',$this->data['header'][0]->crv_type_id))[0]->crv_type_txt;

            $result['header'] = array_merge((array)$this->data['header'][0],array('crv_type'=> $crv_type));

         $query =   'SELECT
        bmd_crv_details.*,
        item_master.item_name,
        item_master.item_bin_no,
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
        bmd_crv_details.crv_header_id= ?  AND  bmd_crv_details.active = ?
        ORDER BY bmd_crv_details.crv_detail_id ASC';
        $this->data['re'] = $this->_crv->getFromQ($query,array($this->data['header'][0]->crv_id,'1'));
       
        if($this->data['re']){
          foreach ($this->data['re'] as $key => $value) {
            $result['recode'][] = $value;
          }}

            $this->data['result'] = $result;
              }
        }
 }

 public function view_crv_layout(){
  return 'api';
 } 

 public function getbadge(){
   $this->_item = new items(); 
      if(isset($this->getParams()[0])){ 
            $query = 'SELECT 
          DISTINCT bmd_crv_details.batch_no 
          FROM bmd_crv_details WHERE 
          bmd_crv_details.active=? AND 
          bmd_crv_details.item_code=?  ORDER BY bmd_crv_details.crv_detail_id DESC LIMIT 200';

          $this->data['re'] = $this->_item->getFromQ($query,array('1',clearInt(escape($this->getParams()[0]))));
          $this->data['result'] = $this->data['re'];
        }
 }

 public function getbadge_layout(){
  return 'api';
 }

 public function getexp(){
   $this->_item = new items(); 
      if(isset($this->getParams()[0])){ 
            $query = 'SELECT 
          DISTINCT bmd_crv_details.expire_date 
          FROM bmd_crv_details WHERE 
          bmd_crv_details.active=? AND bmd_crv_details.expire_date >CURDATE() AND 
          bmd_crv_details.item_code=?  ORDER BY bmd_crv_details.crv_detail_id DESC LIMIT 200';
          $this->data['re'] = $this->_item->getFromQ($query,array('1',clearInt(escape($this->getParams()[0]))));
          $this->data['result'] = $this->data['re'];
        }
 }

 public function getexp_layout(){
  return 'api';
 }


 public function view_bmd_iv(){
  $this->_bmdiv = new bmdivs();
  if(isset($this->getParams()[0])){
          $this->data['header'] =  $this->_bmdiv->getIv(array('iv_header_id','=', escape($this->getParams()[0])));
          if(count($this->data['header'])>0){
            $result = array();

            $this->_station = new stations();
            $result['station'] = $this->_station->getStation(array('station_id','=',$this->data['header'][0]->iv_to))[0]; 
 
            $result['header'] = $this->data['header'][0];

         $query =   'SELECT bmd_iv_details.*,
                            item_master.item_name,
                            item_master.item_bin_no,
                            item_master.measure_unit_id,
                            master_unit.unit_name,
                            item_master.item_code,
                            user_master.name,
                            user_master.service_no, 
                            user_master.user_rank
                            FROM
                            bmd_iv_details
                            INNER JOIN item_master ON bmd_iv_details.item_code = item_master.item_code
                            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                            INNER JOIN user_master ON bmd_iv_details.latest_user_tbl_id = user_master.user_id
                            WHERE
                            bmd_iv_details.iv_header_id= ?  AND  bmd_iv_details.active = ?
                            ORDER BY bmd_iv_details.bmd_iv_detail_id ASC';
        $this->data['re'] = $this->_bmdiv->getFromQ($query,array($this->data['header'][0]->iv_header_id,'1'));
       
        if($this->data['re']){
          foreach ($this->data['re'] as $key => $value) {
            $result['recode'][] = $value;
          }}

            $this->data['result'] = $result;
              }
        }
 }

 public function view_bmd_iv_layout(){
  return 'api';
 } 

 public function crv_search(){
       $this->_crv = new crvs();
  if(isset($this->getParams()[0])){

   $quary =  'SELECT bmd_crv_header.*, crv_types.crv_type_txt, supplier_master.supp_name,user_master.name, user_master.service_no, user_master.user_rank FROM bmd_crv_header 
        INNER JOIN crv_types ON bmd_crv_header.crv_type_id = crv_types.crv_type_id
        INNER JOIN supplier_master ON bmd_crv_header.supplier_id = supplier_master.supp_id
         INNER JOIN user_master ON bmd_crv_header.latest_user_tbl_id = user_master.user_id 
        WHERE bmd_crv_header.active = ? AND bmd_crv_header.crv_no LIKE ?  ORDER BY bmd_crv_header.crv_id DESC LIMIT 20';

   $this->data['re'] = $this->_crv->getFromQ($quary,array('1','%'.escape($this->getParams()[0]).'%')); 
  }else{
    $quary = 'SELECT bmd_crv_header.*, crv_types.crv_type_txt, supplier_master.supp_name,user_master.name, user_master.service_no, user_master.user_rank FROM bmd_crv_header 
        INNER JOIN crv_types ON bmd_crv_header.crv_type_id = crv_types.crv_type_id
        INNER JOIN supplier_master ON bmd_crv_header.supplier_id = supplier_master.supp_id
         INNER JOIN user_master ON bmd_crv_header.latest_user_tbl_id = user_master.user_id 
        WHERE bmd_crv_header.active = ?  ORDER BY bmd_crv_header.crv_id DESC LIMIT 20';
   $this->data['re'] = $this->_crv->getFromQ($quary,array('1')); 
  }
   $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $result[] = array('crv_no'=>$value->crv_no,'crv_date'=>$value->crv_date,'supp_name'=>$value->supp_name,'crv_type_txt'=>$value->crv_type_txt,'remarks'=>$value->remarks,'service_no'=>$value->service_no,'user_rank'=>$value->user_rank,'name'=>$value->name,'latest_ip'=>$value->latest_ip,'system_dt'=>$value->system_dt,'crv_id'=>$value->crv_id,'order_no'=>$value->order_no);
        }
        $this->data['result'] = $result; 
 }

 public function crv_search_layout(){
   return 'api';
 }

public function bmdiv_search(){
       $this->_crv = new bmdivs();
  if(isset($this->getParams()[0])){

   $quary =  'SELECT bmd_iv_header.*, user_master.name, user_master.service_no, user_master.user_rank, stations.station_name, item_category.cat_name FROM bmd_iv_header 
        INNER JOIN stations ON bmd_iv_header.iv_to = stations.station_id
        INNER JOIN item_category ON bmd_iv_header.iv_type = item_category.item_cat_id
         INNER JOIN user_master ON bmd_iv_header.latest_user_tbl_id = user_master.user_id 
        WHERE bmd_iv_header.active = ? AND bmd_iv_header.iv_no LIKE ?  ORDER BY bmd_iv_header.iv_header_id DESC LIMIT 20';

   $this->data['re'] = $this->_crv->getFromQ($quary,array('1','%'.escape($this->getParams()[0]).'%')); 
  }else{
    $quary = 'SELECT bmd_iv_header.*, user_master.name, user_master.service_no, user_master.user_rank, stations.station_name, item_category.cat_name FROM bmd_iv_header 
        INNER JOIN stations ON bmd_iv_header.iv_to = stations.station_id
        INNER JOIN item_category ON bmd_iv_header.iv_type = item_category.item_cat_id
         INNER JOIN user_master ON bmd_iv_header.latest_user_tbl_id = user_master.user_id 
        WHERE bmd_iv_header.active = ?  ORDER BY bmd_iv_header.iv_header_id DESC LIMIT 20';
   $this->data['re'] = $this->_crv->getFromQ($quary,array('1')); 
  }
   $result = array();
        foreach ($this->data['re'] as $key => $value) {

          $count =  $this->_crv->getFromQ('SELECT COUNT(*) as cou FROM
                              bmd_iv_details
                              WHERE
                              bmd_iv_details.active = ? AND
                              bmd_iv_details.issue_status = ? AND 
                              bmd_iv_details.iv_header_id=?
                              GROUP BY bmd_iv_details.iv_header_id',array('1','0',$value->iv_header_id));
        if($count){ $cou = $count[0]->cou; }else{
          $cou = '';
        }


          $result[] = array('iv_no'=>$value->iv_no,'iv_date'=>$value->iv_date,'station_name'=>$value->station_name,'cat_name'=>$value->cat_name,'authority'=>$value->authority,'service_no'=>$value->service_no,'user_rank'=>$value->user_rank,'name'=>$value->name,'latest_ip'=>$value->latest_ip,'system_dt'=>$value->system_dt,'iv_header_id'=>$value->iv_header_id,'cou'=>$cou);
        }
        $this->data['result'] = $result; 
 }

 public function bmdiv_search_layout(){
   return 'api';
 }
 /*
 public function item_stock_details(){
     $this->_crv = new crvs();
  if(isset($this->getParams()[0])){
    $dates = escape($this->getParams()[1]);
   $dates = explode(' - ', $dates);
    $item_code = escape($this->getParams()[0]);
    $qiv = 'SELECT sum(bmd_iv_details.issue_qty) as iv FROM bmd_iv_details WHERE bmd_iv_details.active = ? AND bmd_iv_details.item_code = ? AND bmd_iv_details.system_date BETWEEN ? AND ?';
    $params = array('1', $item_code,$dates[0],$dates[1]);

    $qcrv = 'SELECT Sum(bmd_crv_details.qty) as crv FROM bmd_crv_details WHERE bmd_crv_details.active = ? AND bmd_crv_details.item_code = ? AND bmd_crv_details.system_date BETWEEN ? AND ?';

    $qname = 'SELECT item_master.item_name,master_unit.unit_name FROM item_master INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id WHERE  item_master.active = ? AND item_master.item_code = ?';
    $re = $this->_crv->getFromQ($qiv,$params);
    $result['iv']=($re[0]->iv!='')?$re[0]->iv:'0';
     $re = $this->_crv->getFromQ($qcrv,$params);
     $result['crv']= ($re[0]->crv!='')?$re[0]->crv:'0';
    $re = $this->_crv->getFromQ($qname,array('1', $item_code));
       $result['item_name']=$re[0]->item_name;
       $result['unit_name']=$re[0]->unit_name;
       $result['from_date']=$dates[0];
       $result['to_date']=$dates[1];
    $this->data['result'] = $result;
       }
 } */
/*
 public function item_stock_details_layout(){
  return 'api';
 } */
/*
 public function iv(){
  /*Iv details for report withing range

  $this->iv = new bmdivs();
     $dates = escape(input::get('date'));
                $dates = explode(' - ', $dates);
                $item_code = escape(input::get('item_code'));
                $iv_to = escape(input::get('iv_to'));

                $query = 'SELECT bmd_iv_header.iv_to,
                          bmd_iv_header.iv_date,
                          bmd_iv_details.item_code,
                          Sum(bmd_iv_details.issue_qty) as total,
                          master_unit.unit_name 
                          FROM
                          bmd_iv_header
                          INNER JOIN bmd_iv_details ON bmd_iv_header.iv_header_id = bmd_iv_details.iv_header_id
                          INNER JOIN item_master ON bmd_iv_details.item_code = item_master.item_code
                          INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                          WHERE bmd_iv_details.item_code = ? AND
                          bmd_iv_header.iv_to=? AND
                          bmd_iv_header.iv_date BETWEEN ? AND ?
                          GROUP BY 
                          bmd_iv_details.item_code ';
                        //  $this->data['result'] = $dates[0].' '.$dates[1].' '.$item_code.' '.$iv_to;
                          $this->data['result'] = $this->iv->getFromQ($query,array($item_code,$iv_to,$dates[0],$dates[1]));
 }

 public function iv_layout(){
  return 'api';
 } */

 /* station IV */

  public function item_station(){   
    /* get items with available quantity for issue and reciev*/ 
       $this->_item = new items(); 
      if(isset($this->getParams()[0])){ 
        $quary = 'SELECT
                  item_master.item_code,
                  item_master.item_name,
                  master_unit.unit_name 
                  FROM
                  item_master
                  INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                  WHERE item_master.item_cat_id=? AND item_master.active=? ';


        $this->data['re'] = $this->_item->getFromQ($quary,array(clearInt(escape($this->getParams()[0])),'1'));
        $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $query = 'SELECT   station_stock_master.qoh,
                    station_stock_master.last_txn_type,
                    station_stock_master.rol
                    FROM
                    station_stock_master WHERE station_stock_master.item_code=? AND station_stock_master.active=? AND station_stock_master.station_id=? ';
                 $temp =   $this->_item->getFromQ($query,array($value->item_code,'1',$this->data['admin']->station_id));
                
              if(count($temp)==1){  $result[]=array('item_code'=>$value->item_code,'item_name'=>$value->item_name,'unit'=>$value->unit_name,'qoh'=>$temp[0]->qoh,'rol'=>$temp[0]->rol);}else{
                 $result[]=array('item_code'=>$value->item_code,'item_name'=>$value->item_name,'unit'=>$value->unit_name,'qoh'=>'0','rol'=>'');
              }

        }


        $this->data['result'] = $result;
      }
    }
 
     public function item_station_layout(){
        return 'api';
    } 

 public function view_rv(){
  $this->_rv = new rvs();
  if(isset($this->getParams()[0])){
    $rv_id = clearInt($this->getParams()[0]);
    $quary = 'SELECT * FROM station_rv_header WHERE rv_id=? and active=? AND station_id=?';
    $this->data['header'] = $this->_rv->getFromQ($quary,array($rv_id,'1',$this->data['admin']->station_id));

    if(count($this->data['header'])>0){
            $result = array();
           $result['header'] = $this->data['header'][0];

           $query = 'SELECT  station_rv_details.*,
                             master_unit.unit_name,
                              item_master.item_name,
                              item_master.item_code
                              FROM
                              station_rv_details
                              INNER JOIN item_master ON station_rv_details.item_code = item_master.item_code
                              INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id WHERE station_rv_details.rv_header_id =? AND station_rv_details.active=?';

           $this->data['re'] = $this->_rv->getFromQ($query,array($this->data['header'][0]->rv_id,'1'));
       
        if($this->data['re']){
          foreach ($this->data['re'] as $key => $value) {
            $result['recode'][] = $value;
          }}

          }

    }
    $this->data['result'] = $result;
 }

 public function view_rv_layout(){
  return 'api';
 } 

 public function rv_search(){
          $this->_rv = new rvs();
          if(count($this->getParams())==1){ 
          $query = 'SELECT station_rv_header.*,  user_master.name, user_master.service_no,user_master.user_rank 
          FROM station_rv_header         
          INNER JOIN user_master ON station_rv_header.latest_user_tbl_id = user_master.user_id
          WHERE station_rv_header.active = ? AND station_rv_header.station_id=? AND station_rv_header.rv_no LIKE ?
          ORDER BY station_rv_header.rv_id DESC LIMIT 20';
          $params = array('1',$this->data['admin']->station_id, '%'.escape($this->getParams()[0]).'%');
        }else{
           $query = 'SELECT station_rv_header.*,  user_master.name, user_master.service_no,user_master.user_rank 
          FROM station_rv_header         
          INNER JOIN user_master ON station_rv_header.latest_user_tbl_id = user_master.user_id
          WHERE station_rv_header.active = ? AND station_rv_header.station_id=? 
          ORDER BY station_rv_header.rv_id DESC LIMIT 20';
          $params = array('1',$this->data['admin']->station_id);
        }
           $this->data['result'] = $this->_rv->getFromQ($query,$params); 
   }
    
 public function rv_search_layout(){
  return 'api';
 }  


public function iv_search(){
          $this->_rv = new rvs();
          if(count($this->getParams())==1){ 
           $query = 'SELECT station_iv_header.*, user_master.name, user_master.service_no, user_master.user_rank,  item_category.cat_name FROM station_iv_header 
        INNER JOIN item_category ON station_iv_header.iv_type = item_category.item_cat_id
        INNER JOIN user_master ON station_iv_header.latest_user_tbl_id = user_master.user_id 
        WHERE station_iv_header.active = ? AND station_iv_header.station_id=? AND station_iv_header.iv_no LIKE ?  ORDER BY station_iv_header.iv_id DESC LIMIT 20 '; 

          $params = array('1',$this->data['admin']->station_id, '%'.escape($this->getParams()[0]).'%');
        }else{
           $query = 'SELECT station_iv_header.*, user_master.name, user_master.service_no, user_master.user_rank,  item_category.cat_name FROM station_iv_header 
        INNER JOIN item_category ON station_iv_header.iv_type = item_category.item_cat_id
        INNER JOIN user_master ON station_iv_header.latest_user_tbl_id = user_master.user_id 
        WHERE station_iv_header.active = ? AND station_iv_header.station_id=? ORDER BY station_iv_header.iv_id DESC LIMIT 20 ';
          $params = array('1',$this->data['admin']->station_id);
        }
           $this->data['result'] = $this->_rv->getFromQ($query,$params); 
   }
    
 public function iv_search_layout(){
  return 'api';
 } 
 public function getbadge_station(){
  /*get batch in station*/
   $this->_item = new items(); 
      if(isset($this->getParams()[0])){ 
            $query = 'SELECT 
          DISTINCT station_rv_details.batch_no 
          FROM station_rv_details WHERE 
          station_rv_details.active=? AND 
          station_rv_details.item_code=?  ORDER BY station_rv_details.tbl_id DESC LIMIT 200';

          $this->data['re'] = $this->_item->getFromQ($query,array('1',clearInt(escape($this->getParams()[0]))));
          $this->data['result'] = $this->data['re'];
        }
 }

 public function getbadge_station_layout(){
  return 'api';
 }

public function getexp_station(){
   $this->_item = new items(); 
      if(isset($this->getParams()[0])){ 
            $query = 'SELECT 
          DISTINCT station_rv_details.expire_date 
          FROM station_rv_details WHERE 
          station_rv_details.active=? AND station_rv_details.expire_date >CURDATE() AND 
          station_rv_details.item_code=?  ORDER BY station_rv_details.tbl_id DESC LIMIT 200';

          $this->data['re'] = $this->_item->getFromQ($query,array('1',clearInt(escape($this->getParams()[0]))));
          $this->data['result'] = $this->data['re'];
        }
 }
 public function getexp_station_layout(){
  return 'api';
 }

 public function view_iv(){
      $this->_iv = new ivs();
       $this->data['ivheader'] =  $this->_iv->getIv(array(array('iv_id','=', clearInt($this->getParams()[0])) , array('station_id','=',$this->data['admin']->station_id)))[0]; 
       $result = array();
        $query = 'SELECT station_iv_details.*,
                item_master.item_code,
                item_master.item_name,
                item_master.item_bin_no,
                master_unit.unit_name,
                user_master.name,
                user_master.service_no, 
                user_master.user_rank
                FROM
                station_iv_details
                INNER JOIN item_master ON station_iv_details.item_code = item_master.item_code
                INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                INNER JOIN user_master ON station_iv_details.latest_user_tbl_id = user_master.user_id
                WHERE station_iv_details.active=? AND 
                station_iv_details.iv_header_id=? ORDER BY
                station_iv_details.tbl_id DESC';
    $this->data['details'] =  $this->_iv->getFromQ($query,array('1',clearInt($this->getParams()[0])));
    $result['header'] = $this->data['ivheader'];
    if(count($this->data['details'])>0){
      $result['recode'] = $this->data['details'];
    }
    $this->data['result'] = $result;
 }

 public function view_iv_layout(){
  return 'api';
 }

 public function item_stockstation(){
  /* get items with available quantity for  reports individual item stock*/ 
  /* Usage
  * 1.
  * 2. change master station stock 
  */
       $this->_item = new items(); 
      if(isset($this->getParams()[0])){ 
      $quary = 'SELECT  item_master.item_name,
                        item_master.item_code,
                        master_unit.unit_name,
                        station_stock_master.qoh,
                        station_stock_master.rol
                        FROM
                        item_master
                        INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                        INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
                        WHERE
                        item_master.item_code = ? AND
                        item_master.active = ? AND
                        station_stock_master.station_id = ?';

        $this->data['re'] = $this->_item->getFromQ($quary,array(clearInt(escape($this->getParams()[0])),'1',$this->data['admin']->station_id));

        $result = array();
        foreach ($this->data['re'] as $key => $value) {
         
          $result['item'] = array('item_name'=>$value->item_name,'item_code'=>$value->item_code,'qoh'=>number_format($value->qoh), 'unit'=>$value->unit_name,'rol'=>number_format($value->rol));
        }
        $this->data['result'] = $result;
      }
 }

 public function item_stockstation_layout(){
  return 'api';
 }
 
  public function item_stock_details_station(){
     $this->_rv = new rvs();
  if(isset($this->getParams()[0])){
    $dates = escape($this->getParams()[1]);
   $dates = explode(' - ', $dates);
    $item_code = escape($this->getParams()[0]);
   // $qiv = 'SELECT sum(bmd_iv_details.issue_qty) as iv FROM bmd_iv_details WHERE bmd_iv_details.active = ? AND bmd_iv_details.item_code = ? AND bmd_iv_details.system_date BETWEEN ? AND ?';

    $qiv = 'SELECT Sum(station_iv_details.issue_qty) as iv FROM station_iv_details INNER JOIN station_iv_header ON station_iv_details.iv_header_id = station_iv_header.iv_id WHERE
        station_iv_details.active = ? AND
        station_iv_header.iv_date BETWEEN ? AND ? AND
        station_iv_details.item_code = ? AND
        station_iv_details.station_id = ? ';

    $params = array('1',$dates[0],$dates[1], $item_code,$this->data['admin']->station_id);

    //$qrv = 'SELECT Sum(bmd_crv_details.qty) as crv FROM bmd_crv_details WHERE bmd_crv_details.active = ? AND bmd_crv_details.item_code = ? AND bmd_crv_details.system_date BETWEEN ? AND ?';
    $qrv = 'SELECT Sum(station_rv_details.receive_qty) as rv FROM
            station_rv_details
            INNER JOIN station_rv_header ON station_rv_details.rv_header_id = station_rv_header.rv_id
            WHERE
            station_rv_details.active = ? AND
            station_rv_header.rv_date BETWEEN ? AND ? AND
            station_rv_details.item_code = ? AND
            station_rv_details.station_id = ?';


    $qname = 'SELECT item_master.item_name,master_unit.unit_name FROM item_master INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id WHERE  item_master.active = ? AND item_master.item_code = ?';
    $re = $this->_rv->getFromQ($qiv,$params);
    $result['iv']=($re[0]->iv!='')?$re[0]->iv:'0';
     $re = $this->_rv->getFromQ($qrv,$params);
     $result['rv']= ($re[0]->rv!='')?$re[0]->rv:'0';
    $re = $this->_rv->getFromQ($qname,array('1', $item_code));
       $result['item_name']=$re[0]->item_name;
       $result['unit_name']=$re[0]->unit_name;
       $result['from_date']=$dates[0];
       $result['to_date']=$dates[1];
    $this->data['result'] = $result;
       }
 }

 public function item_stock_details_station_layout(){
  return 'api';
 }


}