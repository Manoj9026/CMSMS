<?php 

class reportController extends Controller{
	private $_item,$_category;
	public function __construct($data = array()) {
        parent::__construct($data);
        parent::checkAccess('report');
        $this->model = new reports();
    }

    public function singleitem(){
    	$this->_item = new items();
    	$this->data['items'] = $this->_item->getItem(array('active','=','1'));     
    }

    public function allstation_singleitem(){
      $this->_item = new items();

       if(input::get('item_code')){ 
        $quary = 'SELECT item_master.item_code, 
                          bmd_stock_master.qoh, 
                          bmd_stock_master.rol, 
                          item_master.item_name, 
                          master_unit.unit_name 
                FROM item_master 
                INNER JOIN bmd_stock_master ON item_master.item_code = bmd_stock_master.item_code
                INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id 
                WHERE item_master.item_code=? AND item_master.active=? ';

        $this->data['re'] = $this->_item->getFromQ($quary,array(clearInt(escape(input::get('item_code'))),'1'));

        $result = array();
        foreach ($this->data['re'] as $key => $value) {
          $qu  ='SELECT bmd_iv_details.issue_qty 
                  FROM bmd_iv_details WHERE
                  bmd_iv_details.issue_status = ? AND
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.item_code = ? AND bmd_iv_details.system_date > "2021-12-27" ';
      $this->data['issue'] = $this->_item->getFromQ($qu,array('0','1',$value->item_code));
        $issue = 0;
        foreach ($this->data['issue'] as $k => $v) {
         $issue+=$v->issue_qty;
        }
          $result[] = array('station'=>'BMD','item_name'=>$value->item_name,'item_code'=>$value->item_code,'qoh'=>number_format($value->qoh),'ablq'=>number_format(($value->qoh-$issue)),'issue'=>number_format($issue), 'unit'=>$value->unit_name,'rol'=>number_format($value->rol));
        }

         $quary = 'SELECT  item_master.item_name,
                        item_master.item_code,
                        master_unit.unit_name,
                        station_stock_master.qoh,
                        station_stock_master.rol,
                        stations.station_name
                        FROM
                        item_master
                        INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                        INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
                        INNER JOIN stations ON station_stock_master.station_id = stations.station_id
                        WHERE
                        item_master.item_code = ? AND
                        item_master.active = ?  ';

        $this->data['re'] = $this->_item->getFromQ($quary,array(clearInt(escape(input::get('item_code'))),'1'));
        foreach ($this->data['re'] as $key => $value) {
           $result[] =array('station'=>$value->station_name,'qoh'=>$value->qoh, 'rol'=>$value->rol,'unit'=>$value->unit_name,'issue'=>'-','ablq'=>$value->qoh);
        }


        $this->data['result'] = $result;
      } 
      $this->data['items'] = $this->_item->getItem(array('active','=','1')); 
    }

    public function allitems(){
         /*get category details*/
            $this->_category =  new categorys();
            $this->data['category'] = $this->_category->getCategory(array('active','=','1'),array('cat_name'=>'ASC'));

            if(input::get('item_cat_id')!=''){

            //    if(token::check(input::get('token'))){
            $this->_item = new items(); 
            $quary = 'SELECT item_master.item_code, 
                              item_master.item_bin_no,
                              bmd_stock_master.qoh, 
                              bmd_stock_master.rol, 
                              item_master.item_name, 
                              master_unit.unit_name 
                    FROM item_master 
                    INNER JOIN bmd_stock_master ON item_master.item_code = bmd_stock_master.item_code
                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id ';
            if(input::get('item_cat_id')=='All'){
              $where = 'WHERE  item_master.active=?';
              $params = array('1');
            }else{ 
              $where = 'WHERE item_master.item_cat_id=? AND item_master.active=?';
                     $params = array(clearInt(input::get('item_cat_id')),'1');
                   }
              if(input::get('type')=='1'){
               $where .=' AND bmd_stock_master.qoh>?';
               array_push($params, '1');
             }
             
            $other = "item_cat_id=".clearInt(input::get('item_cat_id'));

           $where .= ' ORDER BY item_master.item_name ASC'; 
            $pagination = new paginator($quary.$where,$params,$other);
            $page = (input::get('page'))?input::get('page'):1;
            $limit = (input::get('limit'))?input::get('limit'):50;
            $links = (input::get('links'))?input::get('links'):3;
            
           $this->data['re'] =  $pagination->getData($limit, $page);
           $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
           $this->data['sum'] = $pagination->getSummery();
           $this->data['i'] = $pagination->getStart();

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
              $result[] = array('item_name'=>$value->item_name,'item_bin_no'=>$value->item_bin_no,'item_code'=>$value->item_code,'qoh'=>number_format($value->qoh),'ablq'=>number_format(($value->qoh-$issue)),'issue'=>number_format($issue), 'unit'=>$value->unit_name,'rol'=>number_format($value->rol));
              }
              $this->data['result'] = $result;          
            }
        }

       /* public function itemdetails(){
          $this->_item = new items();
          $this->data['items'] = $this->_item->getItem(array('active','=','1'));
        } */

        public function crviv(){
          $this->_item = new items();
          if(input::exists()){
            if(input::get('item_code')){
              $validate = new validate();
              $validate->check($_POST, array(
                    'item_code'=>array('name'=>'Item Name','required'=>TRUE,'min'=>'1'),
                    'date'=>array('name'=>'From / To Date','required'=>TRUE,'min'=>'1')
                    ));
              if($validate->passed()){ 
                $dates = escape(input::get('date'));
                $dates = explode(' - ', $dates);
                $item_code = escape(input::get('item_code'));
                /*Item basic details*/
                    $query = 'SELECT item_master.item_name, master_unit.unit_name,
                        bmd_stock_master.qoh,  bmd_stock_master.rol, item_category.cat_name FROM item_master 
                        INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                        INNER JOIN bmd_stock_master ON item_master.item_code = bmd_stock_master.item_code
                        INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
                        WHERE item_master.item_code = ? AND item_master.active=?';
             $this->data['item'] = $this->_item->getFromQ($query,array($item_code,'1'))[0];
                /*crv details */
                $qcrv ='SELECT user_master.name, 
                user_master.user_rank,       
                user_master.service_no,
                bmd_crv_details.latest_ip,   
                bmd_crv_details.system_dt,
                bmd_crv_details.expire_date, 
                bmd_crv_details.qty,
                bmd_crv_details.unit_price,  
                bmd_crv_header.crv_date,
                bmd_crv_header.crv_no,       
                bmd_crv_header.crv_id,
                bmd_crv_details.crv_header_id,
                bmd_crv_details.latest_user_tbl_id,
                bmd_crv_details.batch_no 
                FROM
                bmd_crv_details
                INNER JOIN user_master ON bmd_crv_details.latest_user_tbl_id = user_master.user_id
                INNER JOIN bmd_crv_header ON bmd_crv_details.crv_header_id = bmd_crv_header.crv_id
                WHERE bmd_crv_details.active = ? AND bmd_crv_details.item_code = ? AND bmd_crv_details.system_date BETWEEN ? AND ? ORDER BY bmd_crv_header.crv_id DESC';
               $params = array('1', $item_code,$dates[0],$dates[1]);
                $this->data['crv'] = $this->_item->getFromQ($qcrv,$params);
                /*IV details*/

                $qiv = 'SELECT
                bmd_iv_header.iv_no,
                bmd_iv_header.iv_type,
                bmd_iv_header.iv_date,
                bmd_iv_details.issue_qty,
                bmd_iv_details.expire_date,
                bmd_iv_details.batch_no,
                bmd_iv_details.latest_ip,
                user_master.name,
                user_master.user_rank,
                user_master.service_no,
                bmd_iv_details.system_dt,
                bmd_iv_details.issue_status,
                stations.station_name
                FROM
                bmd_iv_details
                INNER JOIN bmd_iv_header ON bmd_iv_details.iv_header_id = bmd_iv_header.iv_header_id
                INNER JOIN user_master ON bmd_iv_details.latest_user_tbl_id = user_master.user_id
                INNER JOIN stations ON bmd_iv_header.iv_to = stations.station_id
                WHERE bmd_iv_details.active = ? AND bmd_iv_details.item_code = ? AND bmd_iv_details.system_date BETWEEN ? AND ?';
                $this->data['iv'] = $this->_item->getFromQ($qiv,$params);
 
             
            }else{
              session::flash('error', $validate->errors());             
              }
            }
          }          
          $this->data['items'] = $this->_item->getItem(array('active','=','1'));
        }


        public function nill(){
          /*all nill drogs qty =0*/
          if(input::get('station_id')){
            $station_id = clearInt(escape(input::get('station_id')));
          }else{
             $station_id =($this->data['admin']->station_id=='1')?'2':$this->data['admin']->station_id;
          }
          $this->data['station_id'] = $station_id;

          if($station_id=='2'){
            $query= 'SELECT 
            bmd_stock_master.rol,
            item_master.item_name, 
            master_unit.unit_name,
            item_category.cat_name,
            item_master.item_bin_no
            FROM
            item_master
            INNER JOIN bmd_stock_master ON bmd_stock_master.item_code = item_master.item_code
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
            WHERE bmd_stock_master.active=? AND bmd_stock_master.qoh < ? 
            OR (SELECT COALESCE(Sum(bmd_iv_details.issue_qty)) FROM bmd_iv_details
                  WHERE
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.issue_status = ? AND 
                  item_master.item_code=bmd_iv_details.item_code ) >=bmd_stock_master.qoh ORDER BY item_master.item_name ASC';
                  $params = array('1','1','1','0');
                }else{
                 $query=   'SELECT station_stock_master.station_id,
                          station_stock_master.rol, 
                          item_master.item_name,
                          master_unit.unit_name,
                          item_category.cat_name,
                          item_master.item_bin_no
                           FROM
                            station_stock_master
                            INNER JOIN item_master ON station_stock_master.item_code = item_master.item_code
                            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                            INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
                          WHERE
                          station_stock_master.qoh < ? 
                          AND station_stock_master.station_id=?';
                          $params = array('1',$station_id);
                }


             $pagination = new paginator($query,$params);
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):50;
        $links = (input::get('links'))?input::get('links'):2;
        
       $this->data['item'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();

        
        $stationQ = 'SELECT station_name, station_id FROM stations WHERE station_id>? AND active=?';
        $this->data['station'] = $this->model->getFromQ($stationQ,array('1','1'));
        }

        public function crvprice(){
            
        $this->_item = new items();
    	$this->data['items'] = $this->_item->getItem(array('active','=','1'));
            
                  $query = 'SELECT item_master.item_code,
                  item_master.item_name,
                  item_master.item_bin_no,
                  bmd_crv_details.qty,
                  bmd_crv_details.unit_price,
                  bmd_crv_details.brand,
                  bmd_crv_header.crv_id,
                  bmd_crv_header.supplier_id,
                  bmd_crv_header.crv_no,
                  MAX(bmd_crv_header.crv_date) crv_date,
                  supplier_master.supp_name,
                  master_unit.unit_name
                  FROM
                  item_master
                  LEFT JOIN bmd_crv_details ON item_master.item_code = bmd_crv_details.item_code
                  LEFT JOIN bmd_crv_header ON bmd_crv_details.crv_header_id = bmd_crv_header.crv_id
                  LEFT JOIN supplier_master ON bmd_crv_header.supplier_id = supplier_master.supp_id
                  LEFT JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                  WHERE
                  item_master.active = ? AND
                  bmd_crv_details.active = ? AND
                  bmd_crv_header.active = ? ';
                  $param = array('1','1','1');
                  if(input::get('item_code') && input::get('item_code')!='all'){
                     $query .= 'AND item_master.item_code = ? '; 
                     array_push($param, input::get('item_code')); 
                  }
                  $query .= 'GROUP BY                      
                  item_master.item_code,
                  item_master.item_bin_no 
                  ORDER BY
                  bmd_crv_header.crv_date DESC';

        $pagination = new paginator($query,$param);
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):50;
        $links = (input::get('links'))?input::get('links'):2;
        
       $this->data['result'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
        
       
        }
        

        public function iv(){
         $this->_item = new items();

         if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST,array('date'=>array('name'=>'From / To','required'=>TRUE),
                    'item_code'=>array('name'=>'Item','required'=>TRUE,'min'=>1),
                    'iv_to'=>array('name'=>'Station Name','required'=>TRUE,'min'=>1)));
                if($validate->passed()){
                $this->iv = new bmdivs();
               $dates = escape(input::get('date'));
                $dates = explode(' - ', $dates);
                $item_code = escape(input::get('item_code'));
                $iv_to = escape(input::get('iv_to'));

                $query = 'SELECT bmd_iv_header.iv_to,
                          bmd_iv_header.iv_date,
                          bmd_iv_details.item_code,
                          Sum(bmd_iv_details.issue_qty) as total,
                          master_unit.unit_name,
                          stations.station_name 
                          FROM
                          bmd_iv_header
                          INNER JOIN bmd_iv_details ON bmd_iv_header.iv_header_id = bmd_iv_details.iv_header_id
                          INNER JOIN item_master ON bmd_iv_details.item_code = item_master.item_code
                          INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                          INNER JOIN stations ON bmd_iv_header.iv_to = stations.station_id
                          WHERE bmd_iv_details.item_code = ? AND bmd_iv_header.iv_to=? AND
                          bmd_iv_header.iv_date BETWEEN ? AND ?
                          GROUP BY 
                         bmd_iv_details.item_code,stations.station_name';
                         
                          $params = array($item_code,$iv_to,$dates[0],$dates[1]);
                          if($iv_to=='all'){ 
                           $query= str_replace(' AND bmd_iv_header.iv_to=?', '', $query);
                            array_splice($params,1,1);
                          }

                        //  $this->data['result'] = $dates[0].' '.$dates[1].' '.$item_code.' '.$iv_to;
                          $this->data['result'] = $this->iv->getFromQ($query,$params);

                }else{
                    session::flash('error', $validate->errors());
                }
                    
                }}
                        


          $this->data['items'] = $this->_item->getItem(array('active','=','1'));
          $this->data['stations'] = $this->model->getFromQ('SELECT *  FROM stations WHERE active = ? AND station_id>? ORDER BY station_name ASC',array('1','2'));

          
        }

 public function pendingiv(){
          $query = 'SELECT bmd_iv_header.iv_no,
                          bmd_iv_header.iv_date,
                          bmd_iv_header.iv_to,
                          stations.station_name,
                          bmd_iv_header.iv_header_id
                          FROM
                          bmd_iv_details
                          INNER JOIN bmd_iv_header ON bmd_iv_header.iv_header_id = bmd_iv_details.iv_header_id
                          INNER JOIN stations ON bmd_iv_header.iv_to = stations.station_id
                          WHERE
                          bmd_iv_details.active = ? AND
                          bmd_iv_details.issue_status = ?
                          GROUP BY
                          bmd_iv_details.iv_header_id ORDER BY bmd_iv_header.iv_date ASC';
          $pagination = new paginator($query,array('1','0'));
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):50;
        $links = (input::get('links'))?input::get('links'):2;
        
       $this->data['ivs'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
  }

  public function available(){
          /*all drugs available in stock*/
             if(input::get('station_id')){
            $station_id = clearInt(escape(input::get('station_id')));
          }else{
             $station_id =($this->data['admin']->station_id=='1')?'2':$this->data['admin']->station_id;
          }
          $this->data['station_id'] = $station_id;

          if($station_id=='2'){
            $query = 'SELECT
            bmd_stock_master.qoh,
            bmd_stock_master.rol,
            item_master.item_name,
            item_master.item_bin_no,
            item_master.measure_unit_id,
            master_unit.unit_name
            FROM
            item_master
            INNER JOIN bmd_stock_master ON bmd_stock_master.item_code = item_master.item_code
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            WHERE
            bmd_stock_master.active=? AND bmd_stock_master.qoh > ? AND 
            (SELECT COALESCE(Sum(bmd_iv_details.issue_qty),0) FROM bmd_iv_details
                  WHERE
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.issue_status = ? AND 
                  item_master.item_code=bmd_iv_details.item_code ) < bmd_stock_master.qoh ORDER BY item_master.item_name ASC';
                  $params = array('1','1','1','0');
                }else{
                  $query = 'SELECT   station_stock_master.item_code,
                            item_master.item_name,
                            item_master.item_bin_no,
                            master_unit.unit_name,
                            station_stock_master.qoh,
                            station_stock_master.rol,
                            station_stock_master.station_id
                            FROM
                            station_stock_master
                            INNER JOIN item_master ON station_stock_master.item_code = item_master.item_code
                            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                            WHERE
                            station_stock_master.qoh > ? AND
                            station_stock_master.active = ? AND
                            station_stock_master.station_id = ? ORDER BY item_master.item_name ASC';
                             $params = array('1','1',$station_id);
                }
                $other = 'station_id='.$station_id;
             $pagination = new paginator($query,$params,$other);
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):50;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['item'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
       $data['station_id'] = $station_id;
        $stationQ = 'SELECT station_name, station_id FROM stations WHERE station_id>? AND active=?';
         $this->data['station'] = $this->model->getFromQ($stationQ,array('1','1'));
  }

  public function rol(){
          /*all drugs less than reorder level*/
            if(input::get('station_id')){
            $station_id = clearInt(escape(input::get('station_id')));
          }else{
             $station_id =($this->data['admin']->station_id=='1')?'2':$this->data['admin']->station_id;
          }
          $this->data['station_id'] = $station_id;

          if($station_id=='2'){
          $query = 'SELECT
            bmd_stock_master.qoh,
            bmd_stock_master.rol,
            item_master.item_name,
            item_master.item_bin_no,
            item_master.measure_unit_id,
            master_unit.unit_name
            FROM
            item_master
            INNER JOIN bmd_stock_master ON bmd_stock_master.item_code = item_master.item_code
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            WHERE
            bmd_stock_master.rol > (bmd_stock_master.qoh-(SELECT COALESCE(Sum(bmd_iv_details.issue_qty),0) FROM bmd_iv_details
                  WHERE
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.issue_status = ? AND 
                  item_master.item_code=bmd_iv_details.item_code ))
            AND bmd_stock_master.qoh!=?
            AND bmd_stock_master.active=? ORDER BY bmd_stock_master.qoh ASC '; 
            $params = array('1','0','0','1');
          }else{
           $query= 'SELECT
            station_stock_master.item_code,
            item_master.item_name,
            item_master.item_bin_no,
            master_unit.unit_name,
            station_stock_master.qoh, 
            station_stock_master.rol
            FROM
            station_stock_master
            INNER JOIN item_master ON station_stock_master.item_code = item_master.item_code
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            WHERE
            station_stock_master.qoh < station_stock_master.rol AND
             station_stock_master.qoh !=? AND 
            station_stock_master.active = ? AND
            station_stock_master.station_id = ?';
            $params = array('0','1',$station_id);
          }
          $other = 'station_id='.$station_id;
             $pagination = new paginator($query,$params,$other);
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):50;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['item'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();

       $data['station_id'] = $station_id;
       $stationQ = 'SELECT station_name, station_id FROM stations WHERE station_id>? AND active=?';
       $this->data['station'] = $this->model->getFromQ($stationQ,array('1','1'));
  }

  public function expire(){
          /*nearly expire drugs*/
            if(input::get('station_id')){
            $station_id = clearInt(escape(input::get('station_id')));
          }else{
             $station_id =($this->data['admin']->station_id=='1')?'2':$this->data['admin']->station_id;
          }
          $this->data['station_id'] = $station_id;

          if(input::get('date')){
           $dates = escape(input::get('date'));
                $dates = explode(' - ', $dates);

          if($station_id=='2'){

         $query =  'SELECT
            bmd_crv_details.qty,
            bmd_crv_details.expire_date,
            bmd_crv_details.batch_no,
            item_master.item_name,
            item_master.item_bin_no,
            bmd_crv_header.crv_no,
            master_unit.unit_name, 
            bmd_stock_master.qoh
            FROM
            bmd_crv_details
            INNER JOIN item_master ON bmd_crv_details.item_code = item_master.item_code
            INNER JOIN bmd_crv_header ON bmd_crv_details.crv_header_id = bmd_crv_header.crv_id
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            INNER JOIN bmd_stock_master ON bmd_crv_details.item_code = bmd_stock_master.item_code
            WHERE bmd_crv_details.active=? AND bmd_stock_master.qoh>? AND bmd_crv_details.expire_date BETWEEN ? AND ? 
            ORDER BY bmd_crv_details.expire_date ASC';
            $params = array('1','0',$dates[0],$dates[1]);
         }else{
           $query =  'SELECT station_rv_header.rv_no,
                      station_rv_header.rv_date,
                      master_unit.unit_name,
                      item_master.item_code,
                      item_master.item_name,
                      item_master.item_bin_no,
                      station_stock_master.qoh,
                      station_rv_details.expire_date,
                      station_rv_details.batch_no,
                      station_rv_details.receive_qty
                      FROM
                      station_rv_details
                      INNER JOIN station_rv_header ON station_rv_details.rv_header_id = station_rv_header.rv_id
                      INNER JOIN item_master ON station_rv_details.item_code = item_master.item_code
                      INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                      INNER JOIN station_stock_master ON station_rv_details.item_code = station_stock_master.item_code
                      WHERE
                      station_rv_details.active = ? AND
                      station_stock_master.qoh>? AND
                      station_rv_details.expire_date BETWEEN ? AND ?  AND
                      station_rv_details.station_id = ? ORDER BY station_rv_details.expire_date ASC';
                      $params = array('1','0',$dates[0],$dates[1],$station_id);
         }

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):50;
        $links = (input::get('links'))?input::get('links'):3;
        $other = 'date='.escape(input::get('date')).'&&station_id='.$station_id;

        $pagination = new paginator($query,$params,$other);
       $this->data['item'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
          }

           $data['station_id'] = $station_id;
       $stationQ = 'SELECT station_name, station_id FROM stations WHERE station_id>? AND active=?';
       $this->data['station'] = $this->model->getFromQ($stationQ,array('1','1'));

  }


public function stock(){
  $this->_category = new categorys();

      if(input::get('item_cat_id')){  
            $station_id = clearInt(input::get('station_id'));  
            $item_cat_ids = input::get('item_cat_id');

             $other = "station_id=".clearInt(input::get('station_id'))."&&type=".clearInt(input::get('type')).'&&';
            foreach ($item_cat_ids as $k=>$v ) {
                $other.="item_cat_id[]=".$v."&&";
              }
            
            if($station_id=='2'){
              /*bmd is selected*/
             $quary = 'SELECT item_master.item_code, 
                              item_master.item_bin_no,
                              bmd_stock_master.qoh, 
                              bmd_stock_master.rol, 
                              item_master.item_name, 
                              master_unit.unit_name 
                    FROM item_master 
                    INNER JOIN bmd_stock_master ON item_master.item_code = bmd_stock_master.item_code
                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id ';

               $where = 'WHERE item_master.active=? AND (';
               $params = array('1');
              foreach ($item_cat_ids as $k=>$v ) {
                $where.=' item_master.item_cat_id=? OR ';
                array_push($params, $v);
              }
               $where = trim($where,'OR ').')';
              if(input::get('type')=='1'){
               $where .=' AND bmd_stock_master.qoh>?';
               array_push($params, '1');
             } 
           $where .= ' ORDER BY item_master.item_name ASC';

       
            }
             if($station_id>2){
             /* other stations selected */
            
             $quary = 'SELECT item_master.item_code, 
                                 item_master.item_bin_no,
                              station_stock_master.qoh, 
                              station_stock_master.rol, 
                              item_master.item_name, 
                              master_unit.unit_name 
                    FROM item_master 
                    INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id  ';

              $where = 'WHERE item_master.active=? AND station_stock_master.active=? AND station_stock_master.station_id=? AND (';
               $params = array('1','1',$station_id);
              foreach ($item_cat_ids as $k=>$v ) {
                $where.=' item_master.item_cat_id=? OR ';
                array_push($params, $v);
              }
               $where = trim($where,'OR ').')';

            if(input::get('type')=='1'){
               $where .=' AND station_stock_master.qoh>?';
               array_push($params, '1'); 
             }
           
            }

             $pagination = new paginator($quary.$where,$params,$other);
            $page = (input::get('page'))?input::get('page'):1;
            $limit = (input::get('limit'))?input::get('limit'):50;
            $links = (input::get('links'))?input::get('links'):3;
            
           $this->data['result'] =  $pagination->getData($limit, $page);
           $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
           $this->data['sum'] = $pagination->getSummery();
           $this->data['i'] = $pagination->getStart();
           $this->data['other']=$other;

      }

   $query =  'SELECT cat_name, item_cat_id FROM item_category WHERE 
          active = ? ORDER BY cat_name ASC';  
        $this->data['category'] = $this->_category->getFromQ($query,array('1'));
         $stationQ = 'SELECT station_name, station_id FROM stations WHERE station_id>? AND active=?';
         $this->data['station'] = $this->_category->getFromQ($stationQ,array('1','1'));
}



public function pdf_allitems(){
           if(input::get('item_cat_id')!=''){
            $this->_item = new items();  
            $quary = 'SELECT item_master.item_code, 
                                item_master.item_bin_no,
                              bmd_stock_master.qoh, 
                              bmd_stock_master.rol, 
                              item_master.item_name, 
                              master_unit.unit_name 
                    FROM item_master 
                    INNER JOIN bmd_stock_master ON item_master.item_code = bmd_stock_master.item_code
                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id ';
            if(input::get('item_cat_id')=='QWxs'){
              $where = 'WHERE  item_master.active=?';
              $params = array('1');
            }else{ 
              $where = 'WHERE item_master.item_cat_id=? AND item_master.active=?';
                     $params = array(clearInt(base64_decode(input::get('item_cat_id'))),'1');
            }

              if(input::get('type')=='1'){
               $where .=' AND bmd_stock_master.qoh > ?';
               array_push($params, '1');
             }

           //  print_r($params);
           $this->data['re'] =  $this->_item->getFromQ($quary.$where,$params);
           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1" width="100%" ><thead><tr>
           <th width="10mm" style="text-align:center" ><b>No</b></th>
           <th width="80mm"><b>Item Name</b></th> 
           <th width="12mm"><b>Bin Card</b></th>
           <th width="25mm" style="text-align:right"><b>IN Stock</b></th>
           <th width="27mm" style="text-align:right"><b>IV reserved Qty</b></th>
           <th width="25mm" style="text-align:right"><b>Available Qty</b></th></tr></thead>
           <tbody>';
           $result = array();
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++;
              $qu  ='SELECT bmd_iv_details.issue_qty 
                      FROM bmd_iv_details WHERE
                      bmd_iv_details.issue_status = ? AND
                      bmd_iv_details.active = ? AND
                      bmd_iv_details.item_code = ?';
          $this->data['issue'] = $this->_item->getFromQ($qu,array('0','1',$value->item_code)); $issue = 0;
            foreach ($this->data['issue'] as $k => $v) {  $issue+=$v->issue_qty;  }
            $this->data['html'] .= '<tr>
                   <td width="10mm" style="text-align:center">'.$i.'</td>
                   <td width="80mm">'.htmlentities($value->item_name).'</td> 
                   <td width="12mm">'.htmlentities($value->item_bin_no).'</td>
                   <td width="25mm" style="text-align:right">'.number_format($value->qoh).' '.$value->unit_name.'</td>
                   <td width="27mm" style="text-align:right">'.number_format($issue).' '.$value->unit_name.'</td>
                   <td width="25mm" style="text-align:right" >'.number_format($value->qoh-$issue).' '.$value->unit_name.'</td>
               </tr>';
             //  break;
              }
              $this->data['html'] .= '</tbody></table>'; 
              $this->pdf('Curent stock Report');                    
            }
   }

public function pdf_crvprice(){
            $query = 'SELECT item_master.item_code,
                  item_master.item_name,
                  item_master.item_bin_no,
                  bmd_crv_details.qty,
                  bmd_crv_details.unit_price,
                  bmd_crv_details.brand,
                  bmd_crv_header.crv_id,
                  bmd_crv_header.supplier_id,
                  bmd_crv_header.crv_no,
                  MAX(bmd_crv_header.crv_date) crv_date,
                  supplier_master.supp_name,
                  master_unit.unit_name
                  FROM
                  item_master
                  INNER JOIN bmd_crv_details ON item_master.item_code = bmd_crv_details.item_code
                  INNER JOIN bmd_crv_header ON bmd_crv_details.crv_header_id = bmd_crv_header.crv_id
                  INNER JOIN supplier_master ON bmd_crv_header.supplier_id = supplier_master.supp_id
                  INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                  WHERE
                  item_master.active = ? AND
                  bmd_crv_details.active = ? AND
                  bmd_crv_header.active = ?';
                  
            $param = array('1','1','1');
                  if(input::get('item_code') && input::get('item_code')!='all'){
                     $query .= 'AND item_master.item_code = ? '; 
                     array_push($param, input::get('item_code')); 
                  }
                  
                  $query .=' GROUP BY
                  item_master.item_code
                  ORDER BY
                  item_master.item_name ASC';
          //  $this->_item = new items();
           $this->data['re'] =  $this->model->getFromQ($query,$param);
           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1"><thead><tr>
           <th width="10mm"><b>No</b></th>
           <th width="125mm" ><b>Item Name</b></th> 
           <th width="10mm" ><b>Bin Code</b></th> 
           <th width="20mm" ><b>Date</b></th> 
           <th width="25mm" style="text-align:right" ><b>Purchase Qty</b></th>
           <th width="25mm" style="text-align:right" ><b>Price</b></th>
           <th width="50mm" ><b>Supplier Name</b></th>
           </tr></thead>
           <tbody>'; 
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++;             
            $this->data['html'] .= '<tr>
                   <td width="10mm">'.$i.'</td>
                   <td width="125mm">'.htmlentities($value->item_name).'</td>
                   <td width="10mm">'.htmlentities($value->item_bin_no).'</td>
                   <td width="20mm">'.$value->crv_date.'</td>
                   <td width="25mm" style="text-align:right">'.$value->qty.' '.$value->unit_name.'</td>
                   <td width="25mm" style="text-align:right">'.$value->unit_price.'</td>
                   <td width="50mm">'.$value->supp_name.'</td> 
               </tr>'; 
              // break;
              }
              $this->data['html'] .= '</tbody></table><p style="color:red"><sup>*</sup>Details sort by items name</p>'; 
              $this->pdf('CRV latest purches price Report',"L"); 
}
   public function pdf_nill(){
           
           if(input::get('station_id')){
            $station_id = clearInt(escape(input::get('station_id')));
          }else{
             $station_id =($this->data['admin']->station_id=='1')?'2':$this->data['admin']->station_id;
          }
          $this->data['station_id'] = $station_id;

          if($station_id=='2'){
            $query= 'SELECT 
            bmd_stock_master.rol,
            item_master.item_name, 
            item_master.item_bin_no, 
            master_unit.unit_name,
            item_category.cat_name
            FROM
            item_master
            INNER JOIN bmd_stock_master ON bmd_stock_master.item_code = item_master.item_code
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
            WHERE bmd_stock_master.active=? AND bmd_stock_master.qoh < ? 
            OR (SELECT COALESCE(Sum(bmd_iv_details.issue_qty)) FROM bmd_iv_details
                  WHERE
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.issue_status = ? AND 
                  item_master.item_code=bmd_iv_details.item_code ) >=bmd_stock_master.qoh ORDER BY item_master.item_name ASC';
                  $params = array('1','1','1','0');
                }else{
                 $query=   'SELECT station_stock_master.station_id,
                          station_stock_master.rol, 
                          item_master.item_name,
                          item_master.item_bin_no, 
                          master_unit.unit_name,
                          item_category.cat_name
                           FROM
                            station_stock_master
                            INNER JOIN item_master ON station_stock_master.item_code = item_master.item_code
                            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                            INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
                          WHERE
                          station_stock_master.qoh < ? 
                          AND station_stock_master.station_id=?';
                          $params = array('1',$station_id);
                }




            $this->_item = new items();
           $this->data['re'] =  $this->_item->getFromQ($query,$params);
            $this->data['station'] = $this->_item->getFromQ('SELECT stations.station_name FROM stations WHERE stations.station_id = ?',array($station_id))[0];

           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1"><thead><tr>
           <th width="10mm"><b>No</b></th>
           <th width="130mm"><b>Item Name</b></th> 
           <th width="15mm"><b>Bin card</b></th> 
           <th width="25mm"><b>Category</b></th> 
           </tr></thead>
           <tbody>'; 
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++;             
            $this->data['html'] .= '<tr>
                   <td width="10mm">'.$i.'</td>
                   <td width="130mm">'.htmlentities($value->item_name).'</td> 
                   <td width="15mm">'.htmlentities($value->item_bin_no).'</td>
                   <td width="25mm">'.htmlentities($value->cat_name).'</td>
               </tr>'; 
              // break;
              }
              $this->data['html'] .= '</tbody></table>'; 
              $this->pdf('Nill Items Report - '.$this->data['station']->station_name ); 
}

public function pdf_available(){
   if(input::get('station_id')){
            $station_id = clearInt(escape(input::get('station_id')));
          }else{
             $station_id =($this->data['admin']->station_id=='1')?'2':$this->data['admin']->station_id;
          }
          $this->data['station_id'] = $station_id;

          if($station_id=='2'){
   $query = 'SELECT
            bmd_stock_master.qoh,
            bmd_stock_master.rol,
            item_master.item_name,
            item_master.item_bin_no,
            item_master.measure_unit_id,
            master_unit.unit_name
            FROM
            item_master
            INNER JOIN bmd_stock_master ON bmd_stock_master.item_code = item_master.item_code
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            WHERE
            bmd_stock_master.active=? AND bmd_stock_master.qoh > ? AND 
            (SELECT COALESCE(Sum(bmd_iv_details.issue_qty),0) FROM bmd_iv_details
                  WHERE
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.issue_status = ? AND 
                  item_master.item_code=bmd_iv_details.item_code ) < bmd_stock_master.qoh ORDER BY item_master.item_name ASC';
                  $params = array('1','1','1','0');
                }else{
                   $query = 'SELECT   station_stock_master.item_code,
                            item_master.item_name,
                            item_master.item_bin_no,
                            master_unit.unit_name,
                            station_stock_master.qoh,
                            station_stock_master.rol,
                            station_stock_master.station_id
                            FROM
                            station_stock_master
                            INNER JOIN item_master ON station_stock_master.item_code = item_master.item_code
                            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                            WHERE
                            station_stock_master.qoh > ? AND
                            station_stock_master.active = ? AND
                            station_stock_master.station_id = ? ORDER BY item_master.item_name ASC';
                             $params = array('1','1',$station_id);
                }

            $this->_item = new items();
           $this->data['re'] =  $this->_item->getFromQ($query,$params);
            $this->data['station'] = $this->_item->getFromQ('SELECT stations.station_name FROM stations WHERE stations.station_id = ?',array($station_id))[0];

           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1"><thead><tr>
           <th width="10mm" style="text-align:center"><b>No</b></th>
           <th width="100mm"><b>Item Name</b></th> 
           <th width="12mm"><b>Bin Card</b></th> 
           <th width="30mm" style="text-align:right"><b>Available Quantity</b></th>
           <th width="25mm" style="text-align:right"><b>Re-order Level</b></th>
           </tr></thead>
           <tbody>'; 
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++;             
            $this->data['html'] .= '<tr>
                   <td width="10mm" style="text-align:center">'.$i.'</td>
                   <td width="100mm">'.htmlentities($value->item_name).'</td>
                   <td width="12mm">'.htmlentities($value->item_bin_no).'</td>
                   <td width="30mm" style="text-align:right">'.number_format($value->qoh).' '.$value->unit_name.'</td> 
                   <td width="25mm" style="text-align:right">'.number_format($value->rol).' '.$value->unit_name.'</td> 
               </tr>'; 
              }
              $this->data['html'] .= '</tbody></table>'; 
              $this->pdf('Available Items Report - '.$this->data['station']->station_name); 
}

public function pdf_rol(){
   if(input::get('station_id')){
            $station_id = clearInt(escape(input::get('station_id')));
          }else{
             $station_id =($this->data['admin']->station_id=='1')?'2':$this->data['admin']->station_id;
          }
          $this->data['station_id'] = $station_id;

          if($station_id=='2'){
            $query = 'SELECT
            bmd_stock_master.qoh,
            bmd_stock_master.rol,
            item_master.item_name,
            item_master.item_bin_no,
            item_master.measure_unit_id,
            master_unit.unit_name
            FROM
            item_master
            INNER JOIN bmd_stock_master ON bmd_stock_master.item_code = item_master.item_code
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            WHERE
            bmd_stock_master.rol > (bmd_stock_master.qoh-(SELECT COALESCE(Sum(bmd_iv_details.issue_qty),0) FROM bmd_iv_details
                  WHERE
                  bmd_iv_details.active = ? AND
                  bmd_iv_details.issue_status = ? AND 
                  item_master.item_code=bmd_iv_details.item_code ))
            AND bmd_stock_master.qoh!=?
            AND bmd_stock_master.active=? ORDER BY bmd_stock_master.qoh ASC '; 
            $params = array('1','0','0','1');
          }else{
             $query= 'SELECT
            station_stock_master.item_code,
            item_master.item_name,
            item_master.item_bin_no,
            master_unit.unit_name,
            station_stock_master.qoh, 
            station_stock_master.rol
            FROM
            station_stock_master
            INNER JOIN item_master ON station_stock_master.item_code = item_master.item_code
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            WHERE
            station_stock_master.qoh < station_stock_master.rol AND
            station_stock_master.qoh !=? AND 
            station_stock_master.active = ? AND
            station_stock_master.station_id = ?';
            $params = array('0','1',$station_id);
          }
 
            $this->_item = new items();
           $this->data['re'] =  $this->_item->getFromQ($query,$params);
           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1"><thead><tr>
           <th width="10mm"><b>No</b></th>
           <th width="100mm"><b>Item Name</b></th> 
           <th width="15mm"><b>Bin Code</b></th> 
           <th width="28mm" style="text-align:right"><b>Physical Quantity</b></th>
           <th width="28mm" style="text-align:right"><b>Re-order Level</b></th>
           </tr></thead>
           <tbody>'; 
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++;             
            $this->data['html'] .= '<tr>
                   <td width="10mm">'.$i.'</td>
                   <td width="100mm">'.htmlentities($value->item_name).'</td>
                   <td width="15mm">'.$value->item_bin_no.'</td>    
                   <td width="28mm" style="text-align:right">'.number_format($value->qoh).' '.$value->unit_name.'</td> 
                   <td width="28mm" style="text-align:right">'.number_format($value->rol).' '.$value->unit_name.'</td> 
               </tr>'; 
              //   if($i==80){break;}
              }
              $this->data['html'] .= '</tbody></table> <p style="color:red"><sup>*</sup>Above items Not included nill items</p>'; 
              $this->pdf('Items below Re-Order Level Report'); 
}

public function pdf_expire(){
            /*nearly expire drugs*/
             if(input::get('station_id')){
            $station_id = clearInt(escape(input::get('station_id')));
          }else{
             $station_id =($this->data['admin']->station_id=='1')?'2':$this->data['admin']->station_id;
          }
          $this->data['station_id'] = $station_id;

          if(input::get('date')){
           $dates = escape(input::get('date'));
                $dates = explode(' - ', $dates);

          if($station_id=='2'){ 

         $query =  'SELECT
            bmd_crv_details.qty,
            bmd_crv_details.expire_date,
            bmd_crv_details.batch_no,
            item_master.item_name,
            item_master.item_bin_no,
            bmd_crv_header.crv_no,
            master_unit.unit_name, 
            bmd_stock_master.qoh
            FROM
            bmd_crv_details
            INNER JOIN item_master ON bmd_crv_details.item_code = item_master.item_code
            INNER JOIN bmd_crv_header ON bmd_crv_details.crv_header_id = bmd_crv_header.crv_id
            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
            INNER JOIN bmd_stock_master ON bmd_crv_details.item_code = bmd_stock_master.item_code
            WHERE bmd_crv_details.active=? AND bmd_stock_master.qoh>? AND bmd_crv_details.expire_date BETWEEN ? AND ? 
            ORDER BY bmd_crv_details.expire_date ASC';
            $params = array('1','0',$dates[0],$dates[1]);
         }else{
           $query =  'SELECT station_rv_header.rv_no,
                      station_rv_header.rv_date,
                      master_unit.unit_name,
                      item_master.item_code,
                      item_master.item_name,
                      item_master.item_bin_no,
                      station_stock_master.qoh,
                      station_rv_details.expire_date,
                      station_rv_details.batch_no,
                      station_rv_details.receive_qty
                      FROM
                      station_rv_details
                      INNER JOIN station_rv_header ON station_rv_details.rv_header_id = station_rv_header.rv_id
                      INNER JOIN item_master ON station_rv_details.item_code = item_master.item_code
                      INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                      INNER JOIN station_stock_master ON station_rv_details.item_code = station_stock_master.item_code
                      WHERE
                      station_rv_details.active = ? AND
                      station_stock_master.qoh>? AND
                      station_rv_details.expire_date BETWEEN ? AND ?  AND
                      station_rv_details.station_id = ? ORDER BY station_rv_details.expire_date ASC';
                      $params = array('1','0',$dates[0],$dates[1],$station_id);
                    }

              $this->_item = new items();
           $this->data['re'] =  $this->_item->getFromQ($query,$params);
           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1"><thead><tr>
           <th width="10mm"><b>No</b></th>
           <th width="120mm" colspan="2"><b>Item Name</b></th> 
           <th width="30mm"><b>Expire On</b></th>
           <th width="60mm"><b>CRV No</b></th>
           <th width="20mm"><b>Batch</b></th>
           <th width="25mm" style="text-align:right"><b>Current Stock   </b></th>
          
           </tr></thead>
           <tbody>'; 
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++; 
            $crv = ($station_id=='2')?$value->crv_no:$value->rv_no;
            $qoh = ($station_id=='2')?$value->qoh:$value->receive_qty;

            $this->data['html'] .= '<tr>
                   <td width="10mm">'.$i.'</td>
                   <td width="105mm">'.htmlentities($value->item_name).'</td><td width="15mm">'.$value->item_bin_no.'</td>
                   <td width="30mm" style="text-align:right">'.$value->expire_date.'</td> 
                   <td width="60mm"  >'.$crv.'</td> 
                   <td width="20mm" >'.$value->batch_no.'</td> 
                   <td width="25mm" style="text-align:right">'.$qoh.' '.$value->unit_name.'</td> 
                   
               </tr>'; 
               if($i==80){break;}
              }
              $this->data['html'] .= '</tbody></table><p style="color:red"><sup>*</sup>Above items has sort based on expire Date</p>'; 
              $this->pdf('Expire items withing '.$dates[0].' - '.$dates['1'],'L'); 
          }

}


function pdf_stock(){
$this->_item = new items();
 if(input::get('item_cat_id')){  
            $station_id = clearInt(input::get('station_id'));  
            $item_cat_ids = input::get('item_cat_id');
            if($station_id=='2'){
              /*bmd is selected*/
             $quary = 'SELECT item_master.item_code,
                              item_master.item_bin_no,
                              bmd_stock_master.qoh, 
                              bmd_stock_master.rol, 
                              item_master.item_name, 
                              master_unit.unit_name 
                    FROM item_master 
                    INNER JOIN bmd_stock_master ON item_master.item_code = bmd_stock_master.item_code
                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id ';

               $where = 'WHERE item_master.active=? AND (';
               $params = array('1');
              foreach ($item_cat_ids as $k=>$v ) {
                $where.=' item_master.item_cat_id=? OR ';
                array_push($params, $v);
              }
               $where = trim($where,'OR ').')';
              if(input::get('type')=='1'){
               $where .=' AND bmd_stock_master.qoh>?';
               array_push($params, '1');
             } 
           $where .= ' ORDER BY item_master.item_name ASC';       
            }
             if($station_id>2){
             /* other stations selected */
            
             $quary = 'SELECT item_master.item_code,
                              item_master.item_bin_no,
                              station_stock_master.qoh, 
                              station_stock_master.rol, 
                              item_master.item_name, 
                              master_unit.unit_name 
                    FROM item_master 
                    INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id  ';

              $where = 'WHERE item_master.active=? AND station_stock_master.active=? AND station_stock_master.station_id=? AND (';
               $params = array('1','1',$station_id);
              foreach ($item_cat_ids as $k=>$v ) {
                $where.=' item_master.item_cat_id=? OR ';
                array_push($params, $v);
              }
               $where = trim($where,'OR ').')';

            if(input::get('type')=='1'){
               $where .=' AND station_stock_master.qoh>?';
               array_push($params, '1'); 
             }           
            }
             $this->data['re'] =  $this->_item->getFromQ($quary.$where,$params);
            $this->data['station'] = $this->_item->getFromQ('SELECT stations.station_name FROM stations WHERE stations.station_id = ?',array($station_id))[0];
 
            
           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1" width="100%" ><thead><tr>
           <th width="5%" style="text-align:center" ><b>No</b></th>
           <th width="72%"><b>Item Name</b></th> 
           <th width="6%"><b>Bin No</b></th>
           <th width="17%" style="text-align:right"><b> Quantity</b></th></tr></thead>
           <tbody>';
           $result = array();
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++;
             
            $this->data['html'] .= '<tr>
                   <td width="5%" style="text-align:center">'.$i.'</td>
                   <td width="72%">'.htmlentities($value->item_name).'</td>
                   <td width="6%">'.$value->item_bin_no.'</td>
                   <td width="17%" style="text-align:right">'.number_format($value->qoh).' '.$value->unit_name.'</td> 
               </tr>';
             //  break;
              }
              $this->data['html'] .= '</tbody></table>'; 
              $this->pdf('Curent stock Report - '.$this->data['station']->station_name);   

          }
}

/**************************************************************/
/*********************station reports**************************/
/**************************************************************/

public function single_station(){
    /*station single item report*/
    $this->_item = new items();

    $query =  'SELECT item_master.item_name,item_master.item_code
              FROM
              item_master
              INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
              WHERE
              item_master.active = ? AND
              station_stock_master.active = ? AND
              station_stock_master.station_id = ?';
      $this->data['items'] = $this->_item->getFromQ($query,array('1','1',$this->data['admin']->station_id));
}

public function rviv_station(){
    /*rv iv summary station*/
    $this->_item = new items();
     $query =  'SELECT item_master.item_name,item_master.item_code
              FROM
              item_master
              INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
              WHERE
              item_master.active = ? AND
              station_stock_master.active = ? AND
              station_stock_master.station_id = ?';
      $this->data['items'] = $this->_item->getFromQ($query,array('1','1',$this->data['admin']->station_id));
}

public function rviv(){
  /*rv iv full details station*/
            $this->_item = new items();
          if(input::exists()){

            if(input::get('item_code')){
              $validate = new validate();
              $validate->check($_POST, array(
                    'item_code'=>array('name'=>'Item Name','required'=>TRUE,'min'=>'1'),
                    'date'=>array('name'=>'From / To Date','required'=>TRUE,'min'=>'1')
                    ));
              if($validate->passed()){ 
                $dates = escape(input::get('date'));
                $dates = explode(' - ', $dates);
                $item_code = escape(input::get('item_code'));
                /*Item basic details*/
                    $query = 'SELECT item_master.item_name, master_unit.unit_name, item_category.cat_name, station_stock_master.qoh, station_stock_master.rol
                            FROM item_master
                            INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                            INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
                            INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
                            WHERE
                            item_master.item_code = ? AND
                            item_master.active = ? AND
                            station_stock_master.station_id = ?';
             $this->data['item'] = $this->_item->getFromQ($query,array($item_code,'1',$this->data['admin']->station_id))[0];
                /*rv details */
              $qcrv = 'SELECT station_rv_header.rv_no,
                              station_rv_header.rv_date,
                              station_rv_details.expire_date,
                              station_rv_details.receive_qty,
                              station_rv_details.latest_ip,
                              station_rv_details.system_dt,
                              user_master.`name`,
                              user_master.user_rank,
                              user_master.service_no
                              FROM
                              station_rv_details
                              INNER JOIN station_rv_header ON station_rv_details.rv_header_id = station_rv_header.rv_id
                              INNER JOIN user_master ON station_rv_details.latest_user_tbl_id = user_master.user_id
                              WHERE
                              station_rv_details.active = ? AND
                              station_rv_details.item_code = ? AND
                              station_rv_header.rv_date BETWEEN ? AND ? AND
                              station_rv_details.station_id = ?';

               $params = array('1', $item_code,$dates[0],$dates[1],$this->data['admin']->station_id);
                $this->data['rv'] = $this->_item->getFromQ($qcrv,$params);
                /*IV details*/

                $qiv =  'SELECT user_master.`name`,
                        user_master.user_rank,
                        user_master.service_no,
                        station_iv_header.iv_no,
                        station_iv_header.iv_date,
                        station_iv_details.expire_date,
                        station_iv_details.issue_qty,
                        station_iv_details.latest_ip,
                        station_iv_details.system_dt,
                        station_iv_header.iv_to
                        FROM
                        user_master
                        INNER JOIN station_iv_details ON station_iv_details.latest_user_tbl_id = user_master.user_id
                        INNER JOIN station_iv_header ON station_iv_details.iv_header_id = station_iv_header.iv_id
                        WHERE
                        station_iv_details.active = ? AND
                        station_iv_details.item_code = ? AND
                        station_iv_header.iv_date BETWEEN ? AND ? AND
                        station_iv_details.station_id = ?';
                $this->data['iv'] = $this->_item->getFromQ($qiv,$params);
        }else{
              session::flash('error', $validate->errors());             
              }
            }
          }          
           $query =  'SELECT item_master.item_name,item_master.item_code
              FROM
              item_master
              INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
              WHERE
              item_master.active = ? AND
              station_stock_master.active = ? AND
              station_stock_master.station_id = ?';
      $this->data['items'] = $this->_item->getFromQ($query,array('1','1',$this->data['admin']->station_id));
}



public function expire_station(){
  /*expire items station*/
   if(input::get('date')){
           $dates = escape(input::get('date'));
                $dates = explode(' - ', $dates);

         $query =  'SELECT station_rv_header.rv_no,
                      station_rv_header.rv_date,
                      master_unit.unit_name,
                      item_master.item_code,
                      item_master.item_name,
                      station_stock_master.qoh,
                      station_rv_details.expire_date,
                      station_rv_details.batch_no,
                      station_rv_details.receive_qty
                      FROM
                      station_rv_details
                      INNER JOIN station_rv_header ON station_rv_details.rv_header_id = station_rv_header.rv_id
                      INNER JOIN item_master ON station_rv_details.item_code = item_master.item_code
                      INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                      INNER JOIN station_stock_master ON station_rv_details.item_code = station_stock_master.item_code
                      WHERE
                      station_rv_details.active = ? AND
                      station_rv_details.expire_date BETWEEN ? AND ?  AND
                      station_rv_details.station_id = ? ORDER BY station_rv_details.expire_date ASC';

      //   echo $query;
        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):50;
        $links = (input::get('links'))?input::get('links'):3;
        $other = 'date='.escape(input::get('date'));
        $params = array('1',$dates[0],$dates[1],$this->data['admin']->station_id);
        $pagination = new paginator($query,$params);
     
       $this->data['item'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
       }

}

public function allitems_station(){
        if(input::get('item_cat_id')!=''){

            //    if(token::check(input::get('token'))){
            $this->_item = new items(); 
            $quary = 'SELECT item_master.item_code, 
                              station_stock_master.qoh, 
                              station_stock_master.rol, 
                              item_master.item_name, 
                              master_unit.unit_name 
                    FROM item_master 
                    INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id  ';

            if(input::get('item_cat_id')=='All'){
              $where = 'WHERE station_stock_master.station_id=? AND  item_master.active=? ';
              $params = array($this->data['admin']->station_id,'1');
            }else{ 
              $where = 'WHERE station_stock_master.station_id=?AND item_master.item_cat_id=? AND item_master.active=?';
                     $params = array($this->data['admin']->station_id,clearInt(input::get('item_cat_id')),'1');
                   }
              if(input::get('type')=='1'){
               $where .=' AND station_stock_master.qoh>?';
               array_push($params, '1');
             }
            $other = "item_cat_id=".clearInt(input::get('item_cat_id'));

            $pagination = new paginator($quary.$where,$params,$other);
            $page = (input::get('page'))?input::get('page'):1;
            $limit = (input::get('limit'))?input::get('limit'):50;
            $links = (input::get('links'))?input::get('links'):3;
            
           $this->data['re'] =  $pagination->getData($limit, $page);
           $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
           $this->data['sum'] = $pagination->getSummery();
           $this->data['i'] = $pagination->getStart();

           $result = array();
            foreach ($this->data['re'] as $key => $value) {
          
              $result[] = array('item_name'=>$value->item_name,'item_code'=>$value->item_code,'qoh'=>number_format($value->qoh), 'unit'=>$value->unit_name,'rol'=>number_format($value->rol));
              }
              $this->data['result'] = $result;          
            }

          $query =  'SELECT station_stock_master.station_id,
          item_category.cat_name,
          item_category.item_cat_id
          FROM
          item_category
          INNER JOIN item_master ON item_master.item_cat_id = item_category.item_cat_id
          INNER JOIN station_stock_master ON station_stock_master.item_code = item_master.item_code
          WHERE
          station_stock_master.station_id = ? AND
          station_stock_master.active = ?'; 

        $this->_category =  new categorys();
        $this->data['category'] = $this->_category->getFromQ($query,array($this->data['admin']->station_id,'1'));
}

public function pdf_allitems_station(){
             if(input::get('item_cat_id')!=''){
            $this->_item = new items();  
            $quary = 'SELECT item_master.item_code, 
                              station_stock_master.qoh, 
                              station_stock_master.rol, 
                              item_master.item_name, 
                              master_unit.unit_name 
                    FROM item_master 
                    INNER JOIN station_stock_master ON item_master.item_code = station_stock_master.item_code
                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id  ';
            if(input::get('item_cat_id')=='QWxs'){
              $where = 'WHERE station_stock_master.station_id=? AND  item_master.active=?';
              $params = array($this->data['admin']->station_id,'1');
            }else{ 
              $where = 'WHERE station_stock_master.station_id=? AND item_master.item_cat_id=? AND item_master.active=?';
                     $params = array($this->data['admin']->station_id,clearInt(base64_decode(input::get('item_cat_id'))),'1');
                   }
              if(input::get('type')=='1'){
               $where .=' AND bmd_stock_master.qoh>?';
               array_push($params, '1');
             }
              $this->_station = new stations();
              $station_name = $this->_station->getStation(array('station_id','=', $this->data['admin']->station_id))[0]->station_name;
           $this->data['re'] =  $this->_item->getFromQ($quary.$where,$params);
         //  print_r($this->data['re']);
           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1" width="100%" ><thead><tr>
           <th width="5%" style="text-align:center" ><b>No</b></th>
           <th width="75%"><b>Item Name</b></th> 
           <th width="20%" style="text-align:right"><b>IN Stock</b></th></tr>
           <tbody>';
           $result = array();
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++;
           
            $this->data['html'] .= '<tr>
                   <td width="5%" style="text-align:center">'.$i.'</td>
                   <td width="75%">'.htmlentities($value->item_name).'</td> 
                   <td width="20%" style="text-align:right">'.number_format($value->qoh).' '.$value->unit_name.'</td> 
               </tr>';
             //  break;
              }
              $this->data['html'] .= '</tbody></table>'; 
              $this->pdf($station_name.' - Curent stock Report');                    
            }
}


public function pdf_expire_station(){
  /*expire items station*/
   if(input::get('date')){
           $dates = escape(input::get('date'));
                $dates = explode(' - ', $dates);

         $query =  'SELECT station_rv_header.rv_no,
                      station_rv_header.rv_date,
                      master_unit.unit_name,
                      item_master.item_code,
                      item_master.item_name,
                      item_master.item_bin_no
                      station_stock_master.qoh,
                      station_rv_details.expire_date,
                      station_rv_details.batch_no,
                      station_rv_details.receive_qty
                      FROM
                      station_rv_details
                      INNER JOIN station_rv_header ON station_rv_details.rv_header_id = station_rv_header.rv_id
                      INNER JOIN item_master ON station_rv_details.item_code = item_master.item_code
                      INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                      INNER JOIN station_stock_master ON station_rv_details.item_code = station_stock_master.item_code
                      WHERE
                      station_rv_details.active = ? AND
                      station_rv_details.expire_date BETWEEN ? AND ?  AND
                      station_rv_details.station_id = ? ORDER BY station_rv_details.expire_date ASC';

        $params = array('1',$dates[0],$dates[1],$this->data['admin']->station_id);

         $this->_item = new stations();
         $station_name = $this->_item->getStation(array('station_id','=', $this->data['admin']->station_id))[0]->station_name;
           $this->data['re'] =  $this->_item->getFromQ($query,$params);
           $this->data['html'] = '<table cellspacing="0" cellpadding="1" border="1"><thead><tr>
           <th width="10mm"><b>No</b></th>
           <th width="40mm"><b>Item Name </b></th> 
           <th width="10mm"><b>Expire On</b></th>
           <th width="20mm"><b>CRV No</b></th>
           <th width="10mm"><b>Batch</b></th>
           <th width="15mm" style="text-align:right"><b>Current Stock   </b></th>
          
           </tr></thead>
           <tbody>'; 
           $i = 0;
            foreach ($this->data['re'] as $key => $value) {  $i++;             
            $this->data['html'] .= '<tr>
                   <td width="10mm">'.$i.'</td>
                   <td width="40mm">'.htmlentities($value->item_name).' - '.$value->item_bin_no.'</td>
                   <td width="10mm" style="text-align:right">'.$value->expire_date.'</td> 
                   <td width="20mm"  >'.$value->rv_no.'</td> 
                   <td width="10mm" >'.$value->batch_no.'</td> 
                   <td width="15mm" style="text-align:right">'.$value->receive_qty.' '.$value->unit_name.'</td>                   
               </tr>'; 
               if($i==80){break;}
              }
              $this->data['html'] .= '</tbody></table><p style="color:red"><sup>*</sup>Above items has sort based on expire Date</p>'; 
              $this->pdf($station_name.' - Expire items withing '.$dates[0].' - '.$dates['1'],"L"); 
       }
}



/**********************************
 create pdf files
***********************************/

public function pdf($reports,$ori="P"){
   //        require_once (ROOT.DS.'tyt'.DS.'lib'.DS.'TCPDF-master'.DS.'tcpdf.php');
      // create new PDF document
$pdf = new pdf($ori, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
define('FOOTER_TEXT',strtoupper($this->data['admin']->service_no.' '.$this->data['admin']->user_rank.' '.$this->data['admin']->name)); 
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Dte of AMPS ');
$pdf->SetTitle($reports.date('Y-m-d h:i:s'));
$pdf->SetSubject($reports.date('Y-m-d h:i:s'));
$pdf->SetKeywords($reports.date('Y-m-d h:i:s'));

// set default header data
$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $reports, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage(); 

// Set some content to print
$html = $this->data['html']; 
$pdf->writeHTML($html, true, false, false, false, '');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($reports.'('.date('Y-m-d his').').pdf', 'I');
    exit(); 
        }


}