</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            View CRV Details 
            <small class="uppercase"><?=input::get('crv_no');?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>crv">CRV</a></li>
            <li class="active uppercase">View details <?=input::get('crv_no');?></li> 
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-12">
                    
                   

                   <div class="box box-green">
                    
                    <div class="box-header">
                      <div class="toolbar pull-right">
                        <a href="<?=HTTP_PATH?>crv" class="btn btn-sm btn-default"> <i class="fa  fa-arrow-circle-left " > </i> Back </a>
                      </div>
                    </div>
 
                    <div class="box-body no-padding">
                      <div class="row">
                        <div class="col-sm-6 box-body">
                          <table class="table no-border">
                            <tr>
                              <td></td>
                              <th>CRV Number</th>
                              <td><span class="uppercase"><?=$data['header']->crv_no?></span></td>
                            </tr>

                            <tr>
                              <td></td>
                              <th>CRV Date</th>
                              <td><span class="uppercase"><?=$data['header']->crv_date?></span></td>
                            </tr>

                            <tr>
                              <td></td>
                              <th>CRV Type</th>
                              <td><span class="uppercase"><?=$data['header']->crv_type?></span></td>
                            </tr>
                          </table>
                        </div>

                        <div class="col-sm-6 box-body">
                          <table class="table no-border">
                            <tr>
                              <td></td>
                              <th>Supplier</th>
                              <td><span ><?=$data['supplier']->supp_name?></span></td>
                            </tr>

                            <tr>
                              <td></td>
                              <th>Address</th>
                              <td><span ><?=$data['supplier']->addr?></span></td>
                            </tr>

                            <tr>
                              <td></td>
                              <th>Contact</th>
                              <td><span ><?=$data['supplier']->tele.' / '.$data['supplier']->mobile?></span></td>
                            </tr>
                            <tr>
                              <td></td>
                              <th>Email</th>
                              <td><span ><?=$data['supplier']->email?></span></td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <hr class="success" /> 
                        <table class="table table-condensed">
                        <tr>
                        <th>No</th>
                        <th >Item</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Unit Price <br/>(LKR)</th>
                        <th class="text-right">Amount <br/>(LKR)</th>                        
                        <th>Batch NO</th>
                        <th>Brand</th>
                        <th>MFG Date</th>
                        <th>EXP Date</th> 
                        
                        <th class="text-center">Infomation</th>
                        </tr> 
                        <?php $total = 0;  if(count($data['details'])>0){  $i=0; foreach ($data['details'] as $key=>$value ) { $i++;
                          $amount = ($value->unit_price*$value->qty);
                          $total +=$amount;
                         ?>
                          <tr>
                            <td><?=$i?></td>
                            <td><?=$value->item_name?></td>
                            <td class="text-right"><?=$value->qty.' '.$value->unit_name?></td>
                            <td class="text-right"><?= number_format($value->unit_price,2)?></td>
                            <td class="text-right"><?= number_format($amount,2)?> </td>
                            <td><?=$value->batch_no?></td>
                            <td><?=$value->brand?></td>
                            <td><?=$value->manufacture_date?></td>
                            <td><?=$value->expire_date?></td>
                            
                            <td class="text-center">
                                 <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table  class='uppercase' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span>                                

                                
                            </td>
                          </tr>
                        <?php }?>
                        <tr>
                          <th colspan="4" class="text-right">Total Amount </th>
                          <th class="text-right"><?=number_format($total,2);?></th>
                          <th colspan="5" class="text-left"> LKR</th>
                        </tr>

                      <?php }else{  ?>
                          <tr><td colspan="9" class="text-center"><a class="btn btn-lg btn-warning" href="<?=HTTP_PATH?>crv/crvdetails/<?=$data['header']->crv_id?>"> Add Items to CRV</a></td></tr>
                         <?php } ?>
                      </table>
                    </div> 


                </div>    
            </div>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->   
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
       



