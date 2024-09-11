<?php 
class settingsController extends Controller{
    private $_rv;
    public function __construct($data = array()) {
         parent::__construct($data);
         parent::checkAccess('settings'); 
         $this->model = new crvs();
         // restricted access to station to change bmd stok and bmd to station stock
     }

     public function changestock(){
         $this->checkModuleAccess([4]);
     	$this->_item = new items();
    	$this->data['items'] = $this->_item->getItem(array('active','=','1'));

    	 if(input::exists()){
        if(token::check(input::get('token'))){ 
        	$validation = new validate();
        	$validation->check($_POST,array('item_code'=>array('name'=>'Item','required'=>TRUE, 'min'=>'1','number'=>TRUE),
        		'qoh'=>array('name'=>'Current stock','required'=>TRUE,'number'=>TRUE),
        		'rol'=>array('name'=>'Re-Order Level','required'=>TRUE,'number'=>TRUE)));
        	if($validation->passed()){
          //  $currentItem = $this->_item->getItem(array('active','=','1'),array('item_code','=',escape(input::get('item_code'))))[0];
           $currentItem=  $this->_item->getFromQ('SELECT
                                    master_unit.unit_name,
                                    item_master.item_name,
                                    bmd_stock_master.rol,
                                    bmd_stock_master.qoh
                                    FROM
                                    item_master
                                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                                    INNER JOIN bmd_stock_master ON bmd_stock_master.item_code = item_master.item_code WHERE bmd_stock_master.active=? AND bmd_stock_master.item_code=?',array('1',escape(input::get('item_code'))))[0];

            try{ 
              $this->model->updateStock(
                        array('qoh'=>clearInt(input::get('qoh')),
                          'rol'=>clearInt(input::get('rol')),
                          'last_txn_type'=>'up',
                          'latest_user_tbl_id'=> $this->data['admin']->user_id,
                          'latest_ip'=> $this->ip,
                          'system_date'=>date('Y-m-d'),
                          'system_dt'=>getDT()),array('item_code','=',escape(input::get('item_code')))); 

               $this->model->_log->write(LOG_PATH.'/settings/bmd/log.txt',$this->ip.' | '.getDT().' | change stock  | QOH :'.$currentItem->qoh.' '.$currentItem->unit_name.' to '.escape(input::get('qoh')).' '.$currentItem->unit_name.' | ROL : '.$currentItem->rol.' '.$currentItem->unit_name.' to '.escape(input::get('rol')).' '.$currentItem->unit_name.' | item : '.$currentItem->item_name.' | '.$this->data['admin']->service_no.' '.$this->data['admin']->user_rank.' '.$this->data['admin']->name);

              session::flash('success', 'Stock updated');
              router::redirect(HTTP_PATH.'settings/changestock');
              exit();

            } catch (Exception $ex) {
                      session::flash('error', $ex->getMessage());
                      } 
                  }else{
        		  session::flash('error', $validation->errors());
        	}
        	}
        }
      }


      public function changestock_station(){
          $this->checkModuleAccess([4]);
      $this->_item = new items();
      $this->data['items'] = $this->_item->getItem(array('active','=','1'));

     // $this->model->_log->write(LOG_PATH.'settings/station/log.txt');

       if(input::exists()){
        if(token::check(input::get('token'))){ 
          $validation = new validate();
          $validation->check($_POST,array('item_code'=>array('name'=>'Item','required'=>TRUE, 'min'=>'1','number'=>TRUE),
            'qoh'=>array('name'=>'Current stock','required'=>TRUE,'number'=>TRUE),
            'rol'=>array('name'=>'Re-Order Level','required'=>TRUE,'number'=>TRUE)));
          if($validation->passed()){
             $this->_rv = new rvs();
             $code = escape(input::get('item_code'));
            $curr = $this->_rv->getCurrentQuantity(array(array('item_code','=',$code),array('station_id','=',$this->data['admin']->station_id)));
         
            $currentItem = $this->_item->getFromQ('SELECT
                                    master_unit.unit_name,
                                    item_master.item_name 
                                    FROM
                                    item_master
                                    INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
                                      WHERE  item_master.item_code=?',array($code));
           
          
             try{ 
              if(count($curr)==0){
                $qoh=$rol=0;
                  $this->_rv->addToStationStock(array('station_id'=>$this->data['admin']->station_id,
                                             'item_code'=>$code,
                                             'qoh'=>escape(input::get('qoh')),
                                             'rol'=>escape(input::get('rol')),
                                             'last_txn_type'=>'in',
                                             'latest_user_tbl_id'=> $this->data['admin']->user_id,
                                              'latest_ip'=> $this->ip,
                                              'system_date'=>date('Y-m-d'),
                                              'system_dt'=>getDT(),
                                             'active'=>'1')); 
            }else{
              $qoh = $curr[0]->qoh;
              $rol = $curr[0]->rol; 
             $this->_rv->updateStock(array('qoh'=>escape(input::get('qoh')),
              'rol'=>escape(input::get('rol')),
          'last_txn_type'=>'in',
          'latest_user_tbl_id'=> $this->data['admin']->user_id,
          'latest_ip'=> $this->ip,
          'system_date'=>date('Y-m-d'),
          'system_dt'=>getDT()),array(array('item_code','=',$code),array('station_id','=',$this->data['admin']->station_id)));
           }
            
           
            $log = LOG_PATH.'/settings/'.$this->data['admin']->station_id.'/';
            $this->model->_folder->create($log);
            
            $this->model->_log->write($log.'log.txt',$this->ip.' | '.getDT().' | change stock  | QOH :'.$qoh.' '.$currentItem[0]->unit_name.' to '.escape(input::get('qoh')).' '.$currentItem[0]->unit_name.' | ROL : '.$rol.' '.$currentItem[0]->unit_name.' to '.escape(input::get('rol')).' '.$currentItem[0]->unit_name.' | item : '.$currentItem[0]->item_name.' | '.$this->data['admin']->service_no.' '.$this->data['admin']->user_rank.' '.$this->data['admin']->name);
          
               session::flash('success', 'Stock updated');
             router::redirect(HTTP_PATH.'settings/changestock_station');
           exit();

            } catch (Exception $ex) {
                      session::flash('error', $ex->getMessage());
                      } 
                  }
          }else{
              session::flash('error', $validation->errors());
          }
        }
      }
     

   

    }