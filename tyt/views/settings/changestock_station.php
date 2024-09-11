</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Curent stock of a item
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Curent stock of a item</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-orange">
                    <div class="box-header">        
              <h3 class="box-title"><!--Curent stock  Report --> </h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                    <div class="box-body "> 
                       <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                         <div class="form-group">
                                <label class="col-sm-3">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9 ">
                                    <select class="select2 form-control" name="item_code" id="item" >
                                      <option></option>
                                      <?php foreach ($data['items'] as $key => $value) { ?>
                                      <option value="<?=$value->item_code?>"><?=$value->item_name?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group">
                              <label class="col-sm-3">Current stock<sup class="text-red">*</sup></label>
                              <div class="col-sm-5  "> 
                                <input type="text"  name="qoh" id="qoh_input"   onkeyup="validInt(this)" class="form-control">  
                              </div>
                            </div> 

                            <div class="form-group">
                              <label class="col-sm-3">Re-Order level<sup class="text-red">*</sup></label>
                              <div class="col-sm-5  "> 
                                <input type="text"  name="rol" id="rol_input" onkeyup="validInt(this)" class="form-control"> 
                              </div>
                            </div> 

                            <div class="form-group">
                              <span class="col-sm-3"></span>
                              <div class="col-sm-5">
                                <input type="submit" name="save" value="update" class="btn btm-sm btn-primary" />
                               </div>
                                </div> 
                              </form>
                    </div>
                    <div class="box-footer clearfix"> 
                       
                       </div>
                </div>  

                <div class="box result hide box-orange">
                  <div class="box-header">
                    <div class="box-title"></div>
                  </div>

                  <div class="box-body no-padding ">
                   
                   <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">Items In stock <span id="qoh" class="badge badge-primary badge-pill uppercase"></span></li>
                     
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
           $('.result .box-title').html('');
              $('#qoh').html('');
               $('#rol').html('');
               $('#qoh_input').val('');
                 $('#rol_input').val('');

           var select = this.value;
           $.ajax({url:"<?=HTTP_PATH.'ajax/item_stockstation/'?>"+select,
            success:function(result){
              console.log(result['item']['item_name']);

              $('.result .box-title').html(result['item']['item_name']);
              $('#qoh').html(result['item']['qoh']+' '+result['item']['unit']);
               $('#rol').html(result['item']['rol']+' '+result['item']['unit']);
                

                 var qoh = result['item']['qoh'].replace(/[^0-9.]/ig,'');
                 var rol = result['item']['rol'].replace(/[^0-9.]/ig,'');
                 $('#qoh_input').val(qoh);
                 $('#rol_input').val(rol);

                 $('.result').removeClass('hide'); 
            }})
        });


       </script>