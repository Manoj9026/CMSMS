<link rel="stylesheet" href="<?=HTTP_PATH?>public/plugins/jQueryUI/jquery-ui.css">
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
            Add IV Details 
            <small class=""><?=$data['ivheader'][0]->iv_no?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>bmdiv">IV</a></li>
            <li class="active">Add items to <span class=""><?=$data['ivheader'][0]->iv_no?></span></li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-yellow">
                   
                    <div class="box-body">
                      
                         <form class="form-horizontal" method="post" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-6">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <input type="hidden" name="iv_header_id" value="<?= $data['ivheader'][0]->iv_id ?>">

                            <div class="form-group">
                                <label class="col-sm-3">Item Category <sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                   <select name="item_cat_id" id="item_cat_id" class="form-control select2">
                                       <option></option>
                                       <?php foreach ($data['category'] as $key => $value) { ?>
                                       <option value="<?=$value->item_cat_id?>" <?= ($data['ivheader'][0]->iv_type==$value->item_cat_id)?'selected="true"':'';?> <?= (input::get('item_cat_id')==$value->item_cat_id)?'selected="true"':''; ?> ><?=$value->cat_name?></option>
                                       <?php } ?>
                                   </select>
                                  </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3">Item Name <sup class="text-red">*</sup></label>
                                <div class="col-sm-9" id="item_select">
                                    <select data-tags="true" data-placeholder="Select an option" data-allow-clear="true" class="select2 form-control" id="item_code" name="item_code">
                                        <option></option>                                       
                                    </select>
                                </div>
                            </div>                         
                          
                             <div class="form-group">
                                <label class="col-sm-3">Batch No<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <select  class="select2 form-control" name="batch_no" id="batch_no" >
                                      <option></option>
                                    </select>
                                </div>
                            </div>  

                             </div><div class="col-md-6">

                            <div class="form-group">
                                <label class="col-sm-3">Issue Quantity<sup class="text-red">*</sup> </label>
                                <div class="col-sm-9">
                                    <input type="text" min="0" name="issue_qty" id="issue_qty" maxlength="10"  onkeyup="validInt(this)" value="<?= input::get('issue_qty'); ?>" class="form-control">
                                    <span class="small" id="ablq"></span>
                                </div>
                            </div>
                                            
                     
                            <div class="form-group">
                                <label class="col-sm-4">Expire Date<sup class="text-red">*</sup></label>
                                <div class="col-sm-6">
                                <!--  <input type="text" class="form-control" name="expire_date" id="expire_date" value="<?=input::get('expire_date')?>"> -->
                                    <select class="select2 form-control" id="expire_date"  name="expire_date"  >
                                    <option></option>
                                  </select> 
                                  </div>
                                </div>
                            </div>

                           <br/>
                             
                            <div class="form-group">
                                <div class="col-sm-offset-6">
                                   <!-- <a href="<?=HTTP_PATH.'crv/crvdetails/'.input::get('crv_header_id')?>" class="btn btn-sm btn-default" >Clear</a> -->
                                    <input type="submit" name="save" value="save" class="btn btn-sm btn-primary" />    
                                </div>
                            </div>
                         </div>
                    </div>
                    </form>
                  </div>

                   <div class="box box-yellow">
                    <div class="box-header">
                        <h3 class="box-title">
                          <label class="label label-primary "> IV No : <?=$data['ivheader'][0]->iv_no?></label>
                          <label class="label label-success "></label>
                        </h3> 
                        <span class="pull-right"> <h3 class="box-title"> <label class="label label-info"> Date : <?=$data['ivheader'][0]->iv_date?></label></h3></span>
                    </div> 
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                        <tr>
                        <th>No</th>
                        <th >Item</th>
                        <th class="text-right">Quantity</th>                      
                        <th>Batch NO</th>
                        <th>EXP Date</th> 
                        
                        <th width='20%' class="text-right">Action</th>
                        </tr> 
                        <?php   
                        $total= $i=0; foreach ($data['details'] as $key=>$value ) { $i++;
                         ?>
                          <tr>
                            <td><?=$i?></td>
                            <td><?=$value->item_name?></td>
                            <td class="text-right" ><?=$value->issue_qty.' '.$value->unit_name?></td>
                            <td><?=$value->batch_no?></td>
                            <td><?=$value->expire_date?></td>
                            
                            <td  class="text-right">
                                  <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table  class='' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span>   

                                 
                                   
                                     
                                   <a href="<?=HTTP_PATH.'iv/ivdetails/'.$value->iv_header_id.'/?del='.$value->tbl_id?>" class="btn btn-sm btn-danger"  onclick="return confirmDelete();" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i></a>   
                                 
                              

                            </td>
                          </tr>
                        <?php  } ?> 
                      </table>
                    </div> 


                </div>    
            </div>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->   
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
      <script src="<?=HTTP_PATH?>public/plugins/jQueryUI/jquery-ui.js" type="text/javascript"></script>
        <script>

           function loadItem(cat,select=null){
               $.ajax({url: "<?=HTTP_PATH.'ajax/item_station/'?>"+cat, 
                success: function(result){ 
                 $("#item_code").select2('destroy');              
                $('#item_code').children('option:not(:first)').remove();  
              var se = '';      
              $.each( result, function( key, val ) {
                  if(select==val['item_code']){ se = 'selected="true"';}else{se = '';}
                  $('#item_code').append('<option data-color="available" '+se+' data-qty="'+val['qoh']+'" data-unit="'+val['unit']+'" value="'+val['item_code']+'">'+val['item_name']+' - '+val['qoh']+' '+val['unit']+'</option>');
              });
              
              $("#item_code").select2({tags: "true"});
              }});
            }

            $('.select2').select2();

          var selected_option = $('#item_cat_id option:selected').val();
          if(selected_option!=''){
            loadItem(selected_option,<?=input::get('item_code')?>);
          }

       
            $('#item_cat_id').change(function(){
               $("#item_code").select2("val", "");
                loadItem(this.value);
                $('#issue_qty').prop('value','');
                $('#ablq').html('');
             });
          
          function loadBadgeExp(value_select){
             $("#batch_no").select2('destroy');              
                $('#batch_no').children('option:not(:first)').remove();
                $.ajax({url:"<?=HTTP_PATH.'ajax/getbadge_station/'?>"+value_select,
                success: function(result){
                  $.each( result, function( key, val ) {
                     $('#batch_no').append('<option value="'+val['batch_no']+'" >'+val['batch_no']+'</option>');
                   });
                  $('#batch_no').select2();
                }
                }); 

                 $("#expire_date").select2('destroy');              
                $('#expire_date').children('option:not(:first)').remove();            

                 $.ajax({url:"<?=HTTP_PATH.'ajax/getexp_station/'?>"+value_select,
                success: function(result){
                  $.each( result, function( key, val ) {
            $('#expire_date').append('<option value="'+val['expire_date']+'" >'+val['expire_date']+'</option>');
                   });
                  $('#expire_date').select2();
                }
                }); 
          }

         

          $('#item_code').change(function(){
            var selected = $(this).find('option:selected');
            var value_select = this.value;
            if(value_select!=''){
              var unit = selected.data('unit'); 
              var qty = selected.data('qty'); 
              $('#ablq').html('('+qty+' '+unit+' available)');
              $('#issue_qty').val('');
            $('#issue_qty').prop('max',qty);
            if(qty<1){
              alert('Not items available in stock');
            }

            loadBadgeExp(value_select);
          }});
        
          $('#issue_qty').on('keyup', function(){ 
            if ($(this).val() > $(this).attr('max')*1) { 
              $(this).val($(this).attr('max'));
              alert($(this).attr('max')+' only available');
               } 
            });


        </script>



