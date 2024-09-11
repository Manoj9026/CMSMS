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
          <h1> Individual items issued for a station
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Individual items issued for a station</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title"><!--CRV and IV summery for a Items--></h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                    <div class="box-body  ">
                      <form class="form-horizontal"  method="post" enctype="multipart/form-data"> 
                        <input type="hidden" name="token" value="<?=token::genarate()?>"> 
                         <div class="form-group">
                                <label class="col-sm-3 text-right">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control" name="item_code" id="item_code" >
                                      <option></option> 
                                       
                                      <?php foreach ($data['items'] as $key => $value) { ?>
                                      <option <?=(input::get('item_code')==$value->item_code)?'selected="selected"':''?> value="<?=$value->item_code?>"><?=$value->item_name?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 text-right">Station Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control" id="iv_to" name="iv_to" >
                                      <option></option> 
                                      <option <?=(input::get('iv_to')=='all')?'selected="selected"':''?>  value="all">All</option> 
                                      <?php foreach ($data['stations'] as $key => $value) { ?>
                                <option <?=(input::get('iv_to')==$value->station_id)?'selected="selected"':''?> value="<?=$value->station_id?>"><?=$value->station_name?></option>
                           <?php   } ?> 
                                    </select>
                                </div>
                            </div>

                            

                             <div class="form-group">
                                <label class="col-sm-3 text-right">From / To <sup class="text-red">*</sup></label>
                                <div class="col-sm-6">
                                    <input type="text" id="date"  name="date" value="<?= input::get('date'); ?>" class="form-control">
                                </div> 
                                 
                            </div>

                            <div class="form-group">
                              <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                              <button type="submit" id="submit" class="btn bnt-sm bnt-info">Search </button>
                                </div>
                            </div>

                            </form> 
                    </div>
                    <div class="box-footer clearfix"> 
                       
                       </div>
                </div>                  
            </div>
        <?php if(isset($data['result'])){  ?>
            <div class="col-xs-6 result ">
              <div class="box box-lime">
                <div class="box-header">

                </div>

                <div class="box-body">  
                  <table class="table">
                    <tr>
                      <th>Location</th>
                      <th>Quantity</th>
                    </tr>
                    <?php foreach ($data['result'] as $key => $value) { ?>
                    <tr>
                    <td><?=$value->station_name?></td>
                    <td><?=$value->total.' '.$value->unit_name?></td> 
                  </tr>
                <?php } ?>
                  </table>

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
            format: 'YYYY-MM-DD',changeYear:'true'        
          });  

        $('.select2').select2();


       /*    $('#submit').click(function(event){
          event.preventDefault(); 
           $('.result').removeClass('hide');
            var date = $('#date').val();
            var item_code = $('#item_code option:selected').val();
            var iv_to = $('#iv_to option:selected').val();
            $('#quantity').html('0');
            $('#duration').text(date);
               $('#station').text($('#iv_to option:selected').text());
               $('#item_name').text($('#item_code option:selected').text());

           $.ajax({url:"<?=HTTP_PATH?>"+"ajax/iv/?date="+date+"&&item_code="+item_code+"&&iv_to="+iv_to,
            success:function(result){
              $('#quantity').html(result[0]['total']+' '+result[0]['unit_name']);              
               

             
            }});
         }); */

       

       </script>