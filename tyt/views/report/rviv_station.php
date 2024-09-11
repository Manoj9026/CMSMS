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
          <h1> RV and IV summery for a  item
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">RV and IV summery for a Item</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title"><!--RV and IV summery for a Items--></h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                    <div class="box-body  ">
                      <form class="form-horizontal"> 
                         <div class="form-group">
                                <label class="col-sm-3 text-right">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control" id="item" >
                                      <option></option>
                                      <?php foreach ($data['items'] as $key => $value) { ?>
                                      <option value="<?=$value->item_code?>"><?=$value->item_name?></option>
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

                            <div class="form-group">
                              <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                              <button type="submit" id="submit" class="btn bnt-sm bnt-info">Search </button>
                                </div>
                            </div>

                            </form>
                            <br/><br/><br/>
                    </div>
                    <div class="box-footer clearfix"> 
                       
                       </div>
                </div>  

                <div class="box result hide box-lime">
                  <div class="box-header">
                    <div class="box-title"></div>
                  </div>

                  <div class="box-body no-padding ">
                   
                   <ul class="list-group">
                     
                    <li class="list-group-item d-flex justify-content-between align-items-center">Total RV<span id="crv" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center"> Total IV<span id="iv" class="badge badge-primary badge-pill"></span></li>
                     
                  </ul>
  
                    
                  </div>

                   <div class="box-footer clearfix"> 
                       
                       </div>
                </div>  
            </div>
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

        $('#submit').click(function(event){
          event.preventDefault();
           var select = $('#item option:selected').val();
           var date = $('#date').val();
           $.ajax({url:"<?=HTTP_PATH.'ajax/item_stock_details_station/'?>"+select+'/'+date,
            success:function(result){
            //  console.log(result['item']['item_name']);
              $('.result .box-title').html(result['item_name']+' (From '+result['from_date']+' To '+result['to_date']+' )');
              $('#crv').html(result['rv']+' '+result['unit_name']);
               $('#iv').html(result['iv']+' '+result['unit_name']);
             //   $('#issue').html('( '+result['item']['issue']+' '+result['item']['unit']+' )');
               //  $('#ablq').html(result['item']['ablq']+' '+result['item']['unit']);
                 $('.result').removeClass('hide');
            }})
        });


       </script>