</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Current  stock of a item
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Current  stock of a item</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title"><!--Current  stock  Report --> </h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                    <div class="box-body no-padding">
                         <div class="form-group">
                                <label class="col-sm-3">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control" id="item" >
                                      <option></option>
                                      <?php foreach ($data['items'] as $key => $value) { ?>
                                      <option value="<?=$value->item_code?>"><?=$value->item_name?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
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
                    <li class="list-group-item d-flex justify-content-between align-items-center">Items In stock <span id="qoh" class="badge badge-primary badge-pill uppercase"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">IV Pending items<span id="issue" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Items available for issue<span id="ablq" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Re Order Level<span id="rol" class="badge badge-primary badge-pill"></span></li>
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
 
      <script type="text/javascript">
        $('.select2').select2();

        $('#item').change(function(){
           var select = this.value;
           $.ajax({url:"<?=HTTP_PATH.'ajax/item_stock/'?>"+select,
            success:function(result){
              console.log(result['item']['item_name']);
              $('.result .box-title').html(result['item']['item_name']);
              $('#qoh').html(result['item']['qoh']+' '+result['item']['unit']);
               $('#rol').html(result['item']['rol']+' '+result['item']['unit']);
                $('#issue').html('( '+result['item']['issue']+' '+result['item']['unit']+' )');
                 $('#ablq').html(result['item']['ablq']+' '+result['item']['unit']);
                 $('.result').removeClass('hide');
            }})
        });


       </script>