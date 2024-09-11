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
          <h1> Nearly Expire items
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Nearly Expire items</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-9">
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title"></h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                     <form class="form-horizontal" method="post" enctype="multipart/form-data"> 
                    <div class="box-body  ">
                           <div class="form-group">
                                <label class="col-sm-3 text-right">Time period <sup class="text-red">*</sup></label>
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
                    <div class="box">
                    <div class="box-header">        
              <h3 class="box-title">Expire items withing <?=input::get('date');?> <small class="pagi"><?=$data['sum']?></small></h3>
                    <div class="box-tools ">
                          <div class="col-lg-8">
                          <a href="<?=HTTP_PATH.'report/pdf_expire_station/?date='.input::get('date')?>" target="_new" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Download PDF as PDF"><i class="fa fa-file-pdf-o"></i></a>   
                        </div>
                    </div>
                  </div>
                    <div class="box-body">   
            				<div class="panel ">
            				<table class="table  table-hover ">
            					<tr>
            						<th>Item Name</th>
                        <th>Expire On</th>
            						<th>CRV No</th>
            						<th>Batch</th>
            						<th class="text-right">CRV Quantity</th>
            						<th class="text-right">Current Stock</th>
            						
            						</tr>
            						<?php foreach ($data['item'] as $key => $value) { ?>
            						<tr>
            							<td><?=$value->item_name?></td>
                          <td><?=$value->expire_date?></td>
            							<td><?=$value->rv_no?></td>
            							<td><?=$value->batch_no?></td>
            							<td class="text-right"><?=$value->receive_qty.' '.$value->unit_name?></td>
            							<td class="text-right"><?=$value->qoh.' '.$value->unit_name?></td>
            							
            						</tr>	
            					<?php	} ?> 
            					</table>
            				</table> 
            		</div> 
            		<?=$data['pagination']?>
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

         /*  $('#date').daterangepicker({
            format: 'YYYY-MM-DD',changeYear:'true'        });  
           */
       $('#date').daterangepicker(
      {	format: 'YYYY-MM-DD',changeYear:'true',
        ranges   : {
          'Today'       : [moment(), moment()],
          'Tomorow'		: [moment(),moment().add(1,'days')],
          'Next Week'	: [moment(),moment().add(1,'week')],
          'Within Next 2 Weeks'	: [moment(),moment().add(2,'week')], 
         /* 'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')], */
          'Next Month'	: [moment(),moment().add(1,'month')],
          'withing Next 2 Month'	: [moment(),moment().add(2,'month')],
          'withing Next 3 Month'	: [moment(),moment().add(3,'month')]  
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
    /*  function (start, end) {
      //  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      } */
    )

       </script>