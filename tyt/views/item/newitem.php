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
            Item
            <small><?=$data['title']?> </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>item">All Items</a></li>
            <li class="active"><?=$data['title']?> Item</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-8">
                    <div class="box box-aqua">
                    <div class="box-header">
                        <h3 class="box-title"><?=$data['title']?> Item</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <?=(input::get('item_code'))?'<input type="hidden" value="'.input::get('item_code').'" name="item_code" />':'';?>
                            
                            <div class="form-group">
                              <label class="col-sm-3">Item Category<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <select class="select2 form-control" name="item_cat_id">
                                  <option></option>
                                  <?php foreach ($data['category'] as $key => $value) { ?>
                                    <option  value="<?= $value->item_cat_id ?>" <?= (input::get('item_cat_id')==$value->item_cat_id)?'selected="selected"':'';?> ><?= $value->cat_name; ?></option>
                                  <?php  } ?>                                  
                                </select>
                              </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" id="item_name" class="form-control" maxlength="250" name="item_name" value="<?=  input::get('item_name')?>" />
                                    <span class="ui-autocomplete" id="item_sudgesion"></span>
                                </div>
                            </div>
                           
                           <div class="form-group">
                              <label class="col-sm-3">Item Unit<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <select class="select2 form-control" name="measure_unit_id" >
                                  <option></option>
                                  <?php foreach ($data['measures'] as $key => $value) { ?>
                                    <option value="<?= $value->measure_unit_id?>" <?= (input::get('measure_unit_id')==$value->measure_unit_id)?'selected="selected"':'';?> ><?= $value->unit_name; ?></option>
                                  <?php  } ?> 
                                </select>


                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-3">Bin card No </label>
                              <div class="col-sm-9"><input type="text" class="form-control" maxlength="50" name="item_bin_no" value="<?= input::get('item_bin_no') ?>"></div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3">
                                    <a href="<?=HTTP_PATH.'item/newitem'?>" class="btn btn-sm btn-default" >Clear</a>
                                    <input type="submit" name="save" value="save" class="btn btn-sm btn-primary" />    
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>    
            </div>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
      <script>
          $('.select2').select2();
          
          $('#item_name').keyup(function(){
              var newitem = this.value;
              if((newitem).length>2){
              $.ajax({url:"<?=HTTP_PATH.'ajax/item_name_search/'?>"+newitem,
            success:function(result){
                var  text = '';
                if(result.length>0){ $i=0;text += '<ul>';
                $.each( result, function( key, val ) {     
                     $i++;  
                     if($i<5){
                       text +=  '<li>'+val['item_name']+'</li>'; 
                     }
                });
                text += '</ul>';
            }else{
               text = ''; 
            }
          
            $('#item_sudgesion').html(text);
          $('#item_sudgesion').show();   
             
          }});
            }else{
                $('#item_sudgesion').html('');
          $('#item_sudgesion').hide();  
            }
         
          });
      </script>



