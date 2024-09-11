<?php

class bmdivController extends Controller{
	private $_category,$_station,$_crv;
	public function __construct($data=array()){ 
		 parent::__construct($data);
            parent::checkAccess('bmdiv');
        $this->model = new bmdivs();
	}


	public function index(){
            $this->checkModuleAccess([2,4]);
            if($this->data['admin']->section){
         if(input::get('del')){
          /*check user has permition to do this task*/
          if(parent::checkPermition(2)){ 
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
        
	public function newiv(){
            $this->checkModuleAccess([2,4]);
     if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                 if(input::get('iv_header_id')){$bmdIv = array('name'=>'IV Number','required'=>TRUE,'min'=>'3'); }else{ $bmdIv =array('name'=>'IV Number','required'=>TRUE,'min'=>'3','unique'=>'bmd_iv_header');}
                $validate->check($_POST, array(
                    'iv_type'=>array('name'=>'Default Item Type','required'=>TRUE,'min'=>'1'),
                    'iv_no'=>$bmdID,
                    'iv_date'=>array('name'=>'IV Date','required'=>TRUE,'min'=>'3','date'=>'Y-m-d'),
                    'iv_to'=>array('name'=>'IV To','required'=>TRUE,'min'=>'1'),
                    ));
                
                
                
                if($validate->passed()){
                    if(input::get('iv_header_id')){
                        //update bmdiv
                        try {
                            $this->model->updateIv(array('iv_type'=> escape(input::get('iv_type')),
                            	'iv_no'=> escape(input::get('iv_no')),
                            	'iv_date'=> escape(input::get('iv_date')),
                            	'iv_to'=> escape(input::get('iv_to')),
                            	'authority'=>escape(input::get('authority')),
                            	'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              	'latest_ip'=> $this->ip,
                              	'system_date'=>date('Y-m-d'),
                              	'system_dt'=>getDT(),
                              	'active'=>'1'),array('iv_header_id','=', escape(input::get('iv_header_id'))));    
                            session::flash('success', 'Category updated');
                            router::redirect(HTTP_PATH.'bmdiv');
                            exit();
                        } catch (Exception $ex) {
                            session::flash('error', $ex->getMessage());
                            }
                    }else{
                        //create new bmdIV
                        try {
                            $this->model->createIv(array('iv_type'=> escape(input::get('iv_type')),
                            	'iv_no'=> escape(input::get('iv_no')),
                            	'iv_date'=> escape(input::get('iv_date')),
                            	'iv_to'=> escape(input::get('iv_to')),
                            	'authority'=>escape(input::get('authority')),
                            	'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              	'latest_ip'=> $this->ip,
                              	'system_date'=>date('Y-m-d'),
                              	'system_dt'=>getDT(),
                              	'active'=>'1'));
                            session::flash('success', 'New category created');
                            router::redirect(HTTP_PATH.'bmdiv/');
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

        /*get Station details*/
        $this->_station =  new stations();
        $this->data['stations'] = $this->_station->getStation(array('active','=','1'),array('station_name'=>'ASC'));

        /*
         * edit exissting ID
         */

        $this->data['title'] = 'New';
        if(isset($this->getParams()[0])){ 
               if(parent::checkAccess('bmdiv')){ 
                   
          $this->data['edit'] =  $this->model->getIv(array('iv_header_id','=', escape($this->getParams()[0])))[0];
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           }
           }  
        }
	}


  public function ivdetails(){
    var_dump('ivdetails');
      $this->checkModuleAccess([2,4]);
     if(input::get('del')){
       if(parent::checkPermition(2)){ 
           try {
                /*get the current quantity*/
               $deleteDetails = $this->model->getIvDetails(array('bmd_iv_detail_id','=', clearInt(input::get('del'))))[0];
               if($deleteDetails->issue_status=='1'){ 
              $this->upStock($deleteDetails->item_code,$deleteDetails->issue_qty,'IN');
                }
              $this->model->updateIvDetails(array('active'=>'0'),array('bmd_iv_detail_id','=', clearInt(input::get('del'))));
               session::flash('success', 'Item details deleted');
               router::redirect(HTTP_PATH.'bmdiv/ivdetails/'.$this->getParams()[0]);
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Item details not deleted!');
               router::redirect(HTTP_PATH.'bmdiv/ivdetails/'.$this->getParams()[0]);
               exit();
           }
          } 
       }

       if(input::get('issue')){
        try{
          $deleteDetails = $this->model->getIvDetails(array('bmd_iv_detail_id','=', clearInt(input::get('issue'))))[0];
          $this->upStock($deleteDetails->item_code,$deleteDetails->issue_qty,'OUT');
          $this->model->updateIvDetails(array('issue_status'=>'1','system_dt'=>getDT()),array('bmd_iv_detail_id','=', clearInt(input::get('issue'))));
               session::flash('success', 'Item Issued.');
               router::redirect(HTTP_PATH.'bmdiv/ivdetails/'.$this->getParams()[0]);
               exit();
        }catch (Exception $ex) {
               session::flash('error', 'Item not issued!');
               router::redirect(HTTP_PATH.'bmdiv/ivdetails/'.$this->getParams()[0]);
               exit();
           }
       }

       if(input::get('undo')){
        /*check user has permition to do this task*/
        if(parent::checkPermition(2)){ 
        try{
          $deleteDetails = $this->model->getIvDetails(array('bmd_iv_detail_id','=', clearInt(input::get('undo'))))[0];
          $this->upStock($deleteDetails->item_code,$deleteDetails->issue_qty,'IN');
          $this->model->updateIvDetails(array('issue_status'=>'0','system_dt'=>getDt()),array('bmd_iv_detail_id','=', clearInt(input::get('undo'))));
               session::flash('success', 'Undo Issued Item and stock updated');
               router::redirect(HTTP_PATH.'bmdiv/ivdetails/'.$this->getParams()[0]);
               exit();
        }catch (Exception $ex) {
               session::flash('error', 'Not undo issued!');
               router::redirect(HTTP_PATH.'bmdiv/ivdetails/'.$this->getParams()[0]);
               exit();
           }
         }
       }


      if(isset($this->getParams()[0])){
          $this->data['ivheader'] =  $this->model->getIv(array('iv_header_id','=', escape($this->getParams()[0]))); 
          if(count($this->data['ivheader'])==0){
            router::redirect(HTTP_PATH.'bmdiv');
            exit();
          }

          $supplier = new stations();
                $this->data['station'] = $supplier->getStation(array('station_id','=',$this->data['ivheader'][0]->iv_to)); 
           } else{
          router::redirect(HTTP_PATH.'bmdic');
          exit();
        }


        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
             'item_code'=>array('name'=>'Item name','required'=>TRUE,'min'=>'1'),                     
             'batch_no'=>array('name'=>'Batch Number','required'=>FALSE,'min'=>'4','max'=>'25'),
             'issue_qty'=>array('name'=>'Issue Quantity','required'=>TRUE,'number'=>true,'min'=>'1','max'=>'8'),
             'expire_date'=>array('name'=>'Expire Date','required'=>false,'date'=>'Y-m-d'),
             'iv_header_id'=>array('name'=>'IV header id','min'=>'1')
                  ));         
                  $this->_crv = new crvs();       
                if($validate->passed()){
                    try {
                          $this->model->createBmdIvDetails(array(
                              'iv_header_id'=>clearInt(input::get('iv_header_id')),
                              'item_code'=> clearInt(input::get('item_code')),
                              'issue_qty'=> clearSql(input::get('issue_qty')),
                              'batch_no'=> escape(input::get('batch_no')),
                              'expire_date'=> escape(input::get('expire_date')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'));

                       
                     //     session::flash('success', 'Iitem added to IV');
                      //    router::redirect(HTTP_PATH.'bmdiv/ivdetails/'.clearInt(input::get('iv_header_id')));
                      //    exit();
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
        $this->checkModuleAccess([2,4]);
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


     public function printiv(){
         $this->checkModuleAccess([2,4]);
  $this->_bmdiv = new bmdivs();
  if(isset($this->getParams()[0])){
          $this->data['header'] =  $this->_bmdiv->getIv(array('iv_header_id','=', escape($this->getParams()[0])));
          
          $this->_station = new stations();
          $this->data['station'] = $this->_station->getStation(array('station_id','=',$this->data['header'][0]->iv_to)); 
          
          $this->_category = new categorys();
          $this->data['type'] = $this->_category->getCategory(array('item_cat_id','=',  $this->data['header'][0]->iv_type));
            
          if(count($this->data['header'])>0){
      
            
            
            
            $result['header'] = $this->data['header'];

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
       $i=0;
       $this->data['tr'] ='';
        if($this->data['re']){
          foreach ($this->data['re'] as $key => $value) {
            $i++;
                $this->data['tr'] .='<tr>
                                    <td width="15mm" height="18" ></td>    
                                    <td width="65mm" ><p style="font-size:9px;">'.$value->item_name.'</p></td>                                       
                                    <td width="15mm" style="text-align:right" >'.$value->issue_qty.'</td>
                                    <td width="15mm" style="text-align:right" >'.$value->unit_name.'</td>
                                    <td width="5mm" > </td> 
                                    <td width="30mm" >'.$value->item_bin_no.'</td>
                                    </tr>';            
          }}
           $this->data['html'] = '<table border="0" width="100%"  ><tbody >
                                <tr><td height="5mm" width="20mm" ></td>
                                  <td  width="130mm" >'.$this->data['header'][0]->iv_no.' </td> 
                                 </tr>  
                                 <tr><td width="150mm"></td></tr>
                                 <tr><td height="5mm" width="20mm" ></td>
                                  <td  width="130mm" >'.$this->data['header'][0]->iv_date.'</td>   
                                 </tr> 

                                <tr>
                                  <td height="5mm" width="10mm" ></td>
                                  <td width="50mm" >BMD DAMPS</td>
                                  <td width="10mm"></td>
                                  <td width="80mm">'.$this->data['station'][0]->station_name.'</td>
                                 </tr>
                                 <tr><td width="150mm"></td></tr>
                                  <tr>
                                  <td width="50mm" height="20"></td>
                                  <td width="100mm">'.$this->data['header'][0]->authority.'</td>
                                  
                                  </tr>
                                  <tr><td width="150mm" height="7mm"></td></tr>
                                  <tr>
                                  <td width="40mm" height="5mm" ></td>
                                  <td width="40mm">'.$this->data['type'][0]->cat_name.'</td>
                                  <td width="30mm">'.$i.' Items</td> 
                                  </tr>
                                  <tr><td  height="7mm" width="150mm"></td></tr>
                                  </tbody>
                                  </table><br/><table border="0" width="100%" ><tbody>'.$this->data['tr'];
           
          $this->data['html'] .='<tr>
                                    <td width="20mm" height="18" ></td>    
                                    <td width="55mm" >X X X</td>                                       
                                    <td width="20mm" style="text-align:right" >X X X</td>
                                    <td width="15mm" style="text-align:right" >X X X</td>
                                    <td width="5mm" > </td> 
                                    <td width="30mm" >X X X</td>
                                    </tr>';   
          $this->data['html'] .='</tbody></table>';
              }
        }
     
              $this->pdf('IV');    
    }



/**********************************
 create pdf files
***********************************/

public function pdf($reports){
 require_once (ROOT.DS.'tyt'.DS.'lib'.DS.'TCPDF-master'.DS.'tcpdf.php');
      // create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Dte of AMPS ');
$pdf->SetTitle($reports.date('Y-m-d h:i:s'));
$pdf->SetSubject($reports.date('Y-m-d h:i:s'));
$pdf->SetKeywords($reports.date('Y-m-d h:i:s'));

// set default header data
//$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $reports, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(15, 25, 15); 
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


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
$pdf->SetFont('dejavusans', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage('P', array(179,250));
//$pdf->Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');

// Set some content to print
// set bacground image

//$img_file = HTTP_PATH.'public/images/crv.jpg';
//$pdf->Image($img_file, 0, 0, 466, 246, '', '', 'TRL', false, 300, '', false, false, 0);

$html = $this->data['html']; 
$pdf->writeHTML($html, true, false, false, false, '');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($reports.'('.date('Y-m-d his').').pdf', 'I');
 //   exit(); 
        }

    public function upablStock($code,$units){

    }
  


}