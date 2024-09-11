<link rel="stylesheet" href="<?=HTTP_PATH?>public/plugins/daterangepicker/daterangepicker-bs3.css">
</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> RV and IV Details for a  item
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">RV and IV Details for a Item</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title"><!--RV and IV Details for a Items--></h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                     <form class="form-horizontal" method="post" enctype="multipart/form-data"> 
                    <div class="box-body  ">
                         <div class="form-group">
                                <label class="col-sm-3 text-right">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control" name="item_code" id="item" >
                                      <option></option>
                                      <?php foreach ($data['items'] as $key => $value) { ?>
                                      <option  <?=($value->item_code==input::get('item_code'))?'selected="true"':''; ?> value="<?=$value->item_code?>"><?=$value->item_name?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-sm-3 text-right">From / To <sup class="text-red">*</sup></label>
                                <div class="col-sm-6">
                                    <input type="text" id="date"  name="date" value="<?= input::get('date'); ?>" class="form-control">
                                </div> 
                            </div>
                    </div>
                    <div class="box-footer clearfix"> 
                       <div class="form-group">
                              <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                            <input type="submit" name="save" id="submit" value="Search" class="btn btn-sm btn-primary" />                                             
                                </div>
                            </div>
                       </div></form>
                </div> 
                 </div>
                 <?php if(isset($data['item'])){  ?>
                <div class="col-xs-12">
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title">
              	<label class="label label-primary uppercase">Name: <?=$data['item']->item_name?></label>
              	<label class=" label label-success">Current Stock: <?=$data['item']->qoh.' '.$data['item']->unit_name?></span></label>
              	<label class="label label-warning">Re-Order level: <?=($data['item']->rol!='')?$data['item']->rol:'0'?> <?=$data['item']->unit_name?></label></h3>
                    <div class="box-tools ">
                          <div class="col-lg-8">  
                        </div>
                    </div>
                    <div class="box-body">
                	<div class="row">
            			<div class="col-xs-6">
            				<div class="panel box box-success">
            				<div class="h4">RV</div>
                    <?php if($data['rv']){  ?>
            				<table class="table   table-hover ">
            					<tr>
            						<th>RV No</th>
            						<th>RV Date</th>
            						<th>Expire Date</th>
            						<th class="text-right">Qty</th>
            						<th></th>
            						</tr>
            					<?php $crvTotal =0; foreach ($data['rv'] as $key => $value) { $crvTotal+= $value->receive_qty;?>
            						 <tr>
            						 	<td><?=$value->rv_no?></td>
            						 	<td><?=$value->rv_date?></td>
            						 	<td><?=$value->expire_date?></td>
            						 	<td class="text-right"><?=$value->receive_qty.' '.$data['item']->unit_name?></td>
            						 	<td>
            						 		 <span class="label label-primary pull-right" data-html="true" data-toggle="tooltip"  title="<table  class='uppercase' width='100%' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span> 
            						 	</td>
               						 </tr>
            					<?php } ?>
                      <tr>
                        <th colspan="3" class="text-right">Total Quantity</th>
                        <th class="text-right"><?=$crvTotal.' '.$data['item']->unit_name?></th>
                      </tr>
            				</table>
                  <?php }else{ ?>
                    <div class="row text-center">No RV Details</div>
                  <?php } ?>
            			</div>
            		</div>

            			<div class="col-xs-6">
            				<div class="panel box box-danger">
            				<div class="h4">IV</div>
                    <?php if($data['iv']){ ?>
            				<table class="table  table-hover ">
            					<tr>
            						<th>IV No</th>
                        <th>Station</th>
                        <th class="text-right">Quantity</th>
            						<th>IV Date</th>
            						<th>Expire Date</th>
            						
            						<th></th>
            						</tr>
            					<?php $ivTotal = 0; foreach ($data['iv'] as $key => $value) { $ivTotal+=$value->issue_qty ?>
            						 <tr>
            						 	<td><?=$value->iv_no?></td>
                          <td><?=$value->iv_to?></td>
                          <td class="text-right"><?=$value->issue_qty.' '.$data['item']->unit_name?></td>
            						 	<td><?=$value->iv_date?></td>
            						 	<td><?=$value->expire_date?></td>
            						 	
            						 	<td class="tools">
            						 		 <span class="label label-primary pull-right" data-html="true" data-toggle="tooltip"  title="<table  class='uppercase' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span> 
            						 	</td>
               						 </tr>
            					<?php } ?>
                      <tr>
                        <th colspan="2" class="text-right">Total Quantity</th>
                        <th class="text-right"><?=$ivTotal.' '.$data['item']->unit_name?></th>
                      </tr>
            				</table>
                  <?php }else{ ?>
                    <div class="row text-center">No IV details</div>
                   <?php } ?>
            			</div>
            		</div>
            		</div>
                    </div>

                </div>
            </div>
        </div>
    <?php } ?>

           
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
      <script src="<?=HTTP_PATH?>public/plugins/jQueryUI/jquery-ui.js" type="text/javascript"></script>
      <script src="<?=HTTP_PATH?>public/plugins/daterangepicker/moment.min.js"></script>
<script src="<?=HTTP_PATH?>public/plugins/daterangepicker/daterangepicker.js"></script>
        <script>

           $('#date').daterangepicker({
            format: 'YYYY-MM-DD',changeYear:'true'        });  

        $('.select2').select2();

     /*   $('#submit').click(function(event){
          event.preventDefault();
           var select = $('#item option:selected').val();
           var date = $('#date').val();
           $.ajax({url:"<?=HTTP_PATH.'ajax/item_stock_details/'?>"+select+'/'+date,
            success:function(result){
            $('.result .box-title').html(result['item_name']+' (From '+result['from_date']+' To '+result['to_date']+' )');
              $('#crv').html(result['crv']+' '+result['unit_name']);
               $('#iv').html(result['iv']+' '+result['unit_name']);
              $('.result').removeClass('hide');
            }})
        }); */


       </script>