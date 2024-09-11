<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of crv
 *
 * @author User
 */
class crvController extends Controller {
    public $_supplier,$_category;
    public function __construct($data = array()) {
        parent::__construct($data);
        parent::checkAccess('crv');
        $this->model = new crvs();
    }
    
    public function index(){   
      //  if(!in_array($this->data['admin']->section, [2,4])){  }
       
      $this->checkModuleAccess([1,4]);
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

        $pagination = new paginator('SELECT bmd_crv_header.*, crv_types.crv_type_txt, supplier_master.supp_name,user_master.name, user_master.service_no, user_master.user_rank FROM bmd_crv_header 
        INNER JOIN crv_types ON bmd_crv_header.crv_type_id = crv_types.crv_type_id
        INNER JOIN supplier_master ON bmd_crv_header.supplier_id = supplier_master.supp_id
         INNER JOIN user_master ON bmd_crv_header.latest_user_tbl_id = user_master.user_id 
        WHERE bmd_crv_header.active = ?  ORDER BY bmd_crv_header.crv_date DESC',array(1) );

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):20;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);
       $this->data['pagination'] = $pagination->createLinks($links, 'pagination pagination-sm no-margin ');
       $this->data['sum'] = $pagination->getSummery();
       $this->data['i'] = $pagination->getStart();
        
    }
    
    public function newcrv(){
         $this->checkModuleAccess([1,4]);
        if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                if(input::get('crv_id')){$crv = array('name'=>'CRV No','required'=>TRUE,'min'=>'1'); }else{ $crv =array('name'=>'CRV No','required'=>TRUE,'min'=>'1','unique'=>'bmd_crv_header');}
                $validate->check($_POST, array(
                  'order_no'=>array('name'=>'Order No','required'=>TRUE,'min'=>'1'),
                 'crv_type_id'=>array('name'=>'Recieve Type','required'=>TRUE,'min'=>'1'),  
                    'supplier_id'=>array('name'=>'Supplier','required'=>TRUE,'min'=>'1'), 
                    'crv_no'=>$crv,
                    'officer'=>array('name'=>'Offcier','required'=>true,'min'=>'5'),
                    'crv_date'=>array('name'=>'CRV Date','required'=>TRUE,'date'=>'Y-m-d'),
                    'remarks'=>array('name'=>'Remarks','max'=>200),
                ));                
                if($validate->passed()){
                  if(input::get('crv_id')){
                      //update crv
                      try { $this->model->updateCrv(array(
                              'crv_type_id'=> clearInt(input::get('crv_type_id')),
                              'supplier_id'=> clearInt(input::get('supplier_id')),
                              'crv_no'=> escape(input::get('crv_no')),
                              'order_no'=>escape(input::get('order_no')),
                              'crv_date'=> escape(input::get('crv_date')),
                              'remarks'=> escape(input::get('remarks')),
                              'officer'=>escape(input::get('officer')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'),
                              array('crv_id','=', escape(input::get('crv_id'))));
                          session::flash('success', 'CRV header details Updated');
                          router::redirect(HTTP_PATH.'crv');
                          exit();
                           } catch (Exception $ex) {
                          session::flash('error', $ex->getMessage());
                      } 
                  }else{
                      //crate crv
                      try {
                          $this->model->createCrv(array(
                              'crv_type_id'=> clearInt(input::get('crv_type_id')),
                              'supplier_id'=> clearInt(input::get('supplier_id')),
                              'crv_no'=> escape(input::get('crv_no')),
                              'order_no'=>escape(input::get('order_no')),
                              'crv_date'=> escape(input::get('crv_date')),
                              'remarks'=> escape(input::get('remarks')),
                              'officer'=>escape(input::get('officer')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'));
                          session::flash('success', 'New CRV created');
                          router::redirect(HTTP_PATH.'crv');
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

        // get crv type details
         $this->data['crv_types'] = $this->model->getCrvTypes(array('active','=','1'), array('crv_type_txt'=>'ASC'));
        
        //get supliers details
        $this->_supplier = new suppliers();
         $this->data['supplier'] = $this->_supplier->getSupplier(array('active','=','1')); 
         
        /*
         * edit exissting Item
         */
        $this->data['title'] = 'New';
        if(isset($this->getParams()[0])){       
           if(parent::checkAccess('crv')){ 
            
          $this->data['edit'] =  $this->model->getCrv(array('crv_id','=', escape($this->getParams()[0])))[0];
           $this->data['title'] = 'Edit';
            foreach ($this->data['edit'] as $key => $value) {
               input::put($key, $value);
           }  
        }
      }
    }

    public function crvdetails(){
         $this->checkModuleAccess([1,4]);
      if(input::get('del')){
         if(parent::checkPermition(2)){ 
           try {
                /*get the current quantity*/
               $deleteDetails = $this->model->getCrvDetails(array('crv_detail_id','=', clearInt(input::get('del'))))[0];
              $this->upStock($deleteDetails->item_code,$deleteDetails->qty,'OUT');
              $this->model->updateCrvDetails(array('active'=>'0'),array('crv_detail_id','=', clearInt(input::get('del'))));
               session::flash('success', 'Item details deleted and stock update');
                router::redirect(HTTP_PATH.'crv/crvdetails/'.$this->getParams()[0]);
               exit();
           } catch (Exception $ex) {
               session::flash('error', 'Item details not deleted!');
                router::redirect(HTTP_PATH.'crv/crvdetails/'.$this->getParams()[0]);
               exit();
           }
           }
       }


      if(input::exists()){
            if(token::check(input::get('token'))){
                $validate = new validate();
                $validate->check($_POST, array(
                  'item_cat_id'=>array('name'=>'Item Category','required'=>TRUE,'min'=>'1'), 
                 'item_code'=>array('name'=>'Item name','required'=>TRUE,'min'=>'1'),  
                  'qty'=>array('name'=>'Recived Quantity','required'=>TRUE,'number'=>true,'min'=>'1','max'=>'8'), 
                  'batch_no'=>array('name'=>'Batch Number','required'=>TRUE,'min'=>'1','max'=>'25'),
                  'unit_price'=>array('name'=>'Unit Price','required'=>FALSE,'number'=>true, 'min'=>'1','max'=>'15'),
                  'brand'=>array('name'=>'Brand Name','required'=>FALSE,'min'=>'4','max'=>'150'),
                  'manufacture_date'=>array('name'=>'Manufacture Date','required'=>FALSE,'date'=>'Y-m-d'),
                  'expire_date'=>array('name'=>'Expire Date','date'=>'Y-m-d','required'=>FALSE,),
                  'remarks_details'=>array('name'=>'Remarks','max'=>'150')
                  ));                
                if($validate->passed()){
                    try {
                          $this->model->createCrvDetails(array(
                            'item_cat_id'=>clearInt(input::get('item_cat_id')),
                              'crv_header_id'=>clearInt(input::get('crv_header_id')),
                              'item_code'=> clearInt(input::get('item_code')),
                              'qty'=> clearSql(input::get('qty')),
                              'abl_stock'=> clearSql(input::get('qty')),
                              'batch_no'=> escape(input::get('batch_no')),
                              'unit_price'=> escape(input::get('unit_price')),
                              'brand'=> escape(input::get('brand')),
                              'manufacture_date'=> escape(input::get('manufacture_date')),
                              'expire_date'=> escape(input::get('expire_date')),
                              'remarks_details'=>escape(input::get('remarks_details')),
                              'latest_user_tbl_id'=> $this->data['admin']->user_id,
                              'latest_ip'=> $this->ip,
                              'system_date'=>date('Y-m-d'),
                              'system_dt'=>getDT(),
                              'active'=>'1'));

                          $this->upStock(clearInt(input::get('item_code')),
                            clearInt(input::get('qty')),'IN');

                          session::flash('success', 'New CRV item added and stock update');
                           router::redirect(HTTP_PATH.'crv/crvdetails/'.clearInt(input::get('crv_header_id')));
                         exit();
                      } catch (Exception $exc) {
                         session::flash('error', $ex->getMessage());
                      }                                        
                  
                }else{
                    session::flash('error',$validate->errors());
                }                
            }
        }


      /*crv haeder details*/
      if(isset($this->getParams()[0])){
          $this->data['header'] =  $this->model->getCrv(array('crv_id','=', escape($this->getParams()[0]))); 
          if(count($this->data['header'])==0){
            router::redirect(HTTP_PATH.'crv');
            exit();
          }else{
                foreach ($this->data['header'][0] as $key => $value) {
                if($key=='crv_id'){ $crv_header_id =$value; input::put('crv_header_id',$value); continue;}
                input::put($key, $value);
                }
                $supplier = new suppliers();
                $this->data['suplier'] = $supplier->getSupplier(array('supp_id','=',input::get('supplier_id')))[0]->supp_name; 
              }
        }else{
          router::redirect(HTTP_PATH.'crv');
          exit();
        }

        /*get category details*/
        $this->_category =  new categorys();
        $this->data['category'] = $this->_category->getCategory(array('active','=','1'),array('cat_name'=>'ASC'));

        /*get current added crv items details*/

       $sql = 'SELECT
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
        bmd_crv_details.crv_header_id=?  AND  bmd_crv_details.active = ?
        ORDER BY bmd_crv_details.crv_detail_id DESC';

         $pagination = new paginator($sql,array($crv_header_id,1));

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):1000;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);
        
      
    }

    public function upStock($code,$units,$type){
      /*get the current quantity*/
         $this->checkModuleAccess([1,4]);
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

    public function viewcrv(){
         $this->checkModuleAccess([1,4]);
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
        bmd_crv_details.crv_header_id=?  AND  bmd_crv_details.active = ?
        ORDER BY bmd_crv_details.crv_detail_id DESC';

         $pagination = new paginator($sql,array($crv_header_id,1));

        $page = (input::get('page'))?input::get('page'):1;
        $limit = (input::get('limit'))?input::get('limit'):200;
        $links = (input::get('links'))?input::get('links'):3;
        
       $this->data['details'] =  $pagination->getData($limit, $page);

    }


    public function printcrv(){
         $this->checkModuleAccess([1,4]);
       $this->_crv = new crvs();

       //crv table start
         

  if(isset($this->getParams()[0])){
          $this->data['header'] =  $this->_crv->getCrv(array('crv_id','=', escape($this->getParams()[0])));
          if(count($this->data['header'])>0){
            $result = array();

            $this->_supplier = new suppliers();
            $result['supplier'] = $this->_supplier->getSupplier(array('supp_id','=',$this->data['header'][0]->supplier_id)); 

            $this->data['header']['crv_type'] = $this->_crv->getCrvTypes(array('crv_type_id','=',$this->data['header'][0]->crv_type_id))[0]->crv_type_txt;

            $result['header'] = $this->data['header'];

         $query =   'SELECT
        bmd_crv_details.*,
        item_master.item_name,
        item_master.measure_unit_id,
        item_master.item_bin_no,
        master_unit.unit_name,
        item_master.item_code,
        user_master.name,
        user_master.service_no, 
        user_master.user_rank,
        item_category.cat_name
        FROM
        bmd_crv_details
        INNER JOIN item_master ON bmd_crv_details.item_code = item_master.item_code
        INNER JOIN master_unit ON item_master.measure_unit_id = master_unit.measure_unit_id
        INNER JOIN user_master ON bmd_crv_details.latest_user_tbl_id = user_master.user_id
        INNER JOIN item_category ON item_master.item_cat_id = item_category.item_cat_id
        WHERE
        bmd_crv_details.crv_header_id= ?  AND  bmd_crv_details.active = ?
        ORDER BY bmd_crv_details.crv_detail_id ASC';
        $this->data['re'] = $this->_crv->getFromQ($query,array($this->data['header'][0]->crv_id,'1'));
       
         $this->data['html'] = '<table border="0" width="100%" ><tbody>
                                <tr>
                                  <td width="35mm"></td>
                                  <td height="53" width="260" ></td>
                                  <td  width="287" >'.$result['supplier'][0]->supp_name.'</td>
                                  <td  width="90" ></td>
                                  <td  width="150" >BASE MEDICAL DEPORT<br/>'.$result['header'][0]->crv_no.'<br/>'.$result['header'][0]->crv_date.'</td>
                                 </tr>
                                  <tr>
                                  <td width="35mm"></td>
                                  <td height="120" width="150"></td>
                                  <td colspan="2"  >'.strtoupper($result['header'][0]->order_no).'<br/>'.strtoupper(nl2br($result['header'][0]->remarks)).'</td>
                                  <td></td>
                                  </tr>
                                  </tbody>
                                  </table>';


        $this->data['html'] .= '<table cellspacing="0" cellpadding="2" border="0" width="100%" ><thead><tr>
           
           <th width="30mm" height="20"  > </th>
           <th width="20mm"  > </th>
           <th width="115mm" > </th> 
           <th width="32mm" style="text-align:right"> </th>
           <th width="22mm" style="text-align:right"> </th>
           <th width="37mm" style="text-align:right; vertical-align:bottom;"><b>Reciving &nbsp;&nbsp;</b></th>
           <th width="27mm" style="text-align:right; vertical-align:bottom;"><b>Date of Exp</b></th>
           <th width="34mm" style="text-align:right; vertical-align: bottom;"><b>Unit Price</b></th>
           </tr></thead>
            ';

        if($this->data['re']){
          $i=0;
          foreach ($this->data['re'] as $key => $value) {
            $i++;
            $brand = ($value->brand!='')?'('.$value->brand.')':'';
             $this->data['html'] .='<tr>
                                    <td width="30mm" height="20" > </td>
                                    <td width="20mm" height="20" >'.$value->item_bin_no.'</td>
                                    <td width="115mm" >'.$value->item_name.' - '.$brand.'</td>
                                    <td width="32mm" style="text-align:left" >'.$value->cat_name.'</td>
                                    <td width="22mm" style="text-align:left" >'.$value->unit_name.'</td>
                                    <td width="37mm" style="text-align:right" >'.($value->qty+0).' &nbsp;&nbsp;</td>
                                    <td width="27mm" style="text-align:right" >'.$value->expire_date.'</td>
                                    <td width="34mm" style="text-align:right">'.$value->unit_price.'</td>
                                    </tr>';            
              }
              $y=TRUE;
              while($i<12){
                $i++;
                $x =($y==TRUE)?'<p style="font-size:8px">XXXXX</p>':'';
                $y=FALSE;
                $this->data['html'] .='<tr height="20" ><td></td><td>'.$x.'</td><td>'.$x.'</td><td>'.$x.'</td><td style="text-align:right" >'.$x.'</td><td style="text-align:right" >'.$x.'</td><td style="text-align:right" >'.$x.'</td><td style="text-align:right">'.$x.'</td></tr>';
                  }
                }          
              }
        }
        $this->data['html'] .= '</tbody></table>'; 
          
              $this->data['html'] .= '<table border="0" width="100%" ><tbody>
                                   <tr>
                                  <td height="20" colspan="3" > </td> 
                                 </tr>
                                <tr>
                                    <td height="20" width="50mm"></td>
                                  <td style="text-align:left; "  > DIRECTOR, DAMPS</td>
                                  <td style="text-align:left; ">'.$result['header'][0]->officer.'</td>
                                  <td></td> 
                                 </tr>
                                   
                                  </table><br/>';


              $this->pdf('CRV');    
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
$pdf->SetMargins(10, 15, 30,10); 
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
$pdf->AddPage('L', array(216,356));
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

    
    
}
