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
            Add CRV Details 
            <small class="uppercase"><?=input::get('crv_no');?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>crv">CRV</a></li>
            <li class="active">Add items to <span class="uppercase"><?=input::get('crv_no');?></span></li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-green">
                    
                    <div class="box-body">
                      
                         <form class="form-horizontal" method="post" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-6">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <input type="hidden" name="crv_header_id" value="<?=input::get('crv_header_id')?>">

                            <div class="form-group">
                                <label class="col-sm-3">Item Category <sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                   <select name="item_cat_id" id="item_cat_id" class="form-control select2">
                                       <option></option>
                                       <?php foreach ($data['category'] as $key => $value) {
                                        $se = ((input::get('item_cat_id'))!='')?input::get('item_cat_id'):'1';
                                        ?>
                                       <option value="<?=$value->item_cat_id?>" <?= ($se==$value->item_cat_id)?'selected="true"':''; ?> ><?=$value->cat_name?></option>
                                       <?php } ?>
                                   </select>
                                  </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9" id="item_select">
                                    <select class="select2 form-control" id="item_code" name="item_code">
                                        <option></option>                                       
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3">Received Quantity<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" name="qty" autocomplete="off" min="1" maxlength="10" onkeyup="validInt(this)" value="<?= input::get('qty'); ?>" class="form-control">
                                </div>
                            </div>

                          
                             <div class="form-group">
                                <label class="col-sm-3">Batch No </label>
                                <div class="col-sm-9">
                                    <input type="text" name="batch_no" maxlength="100" minlength="4" value="<?= input::get('batch_no'); ?>" class="form-control">
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-sm-3">Remarks</label>
                                <div class="col-sm-9">
                                    <textarea name="remarks_details" maxlength="150" class="form-control"><?=input::get('remarks_details');?></textarea>
                                </div>
                            </div>
                              </div><div class="col-md-6">
                                                    
                     
                            <div class="form-group">
                                <label class="col-sm-3">Unit price (LKR)<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" name="unit_price" maxlength="15" onkeyup="validIntDesimal(this)" value="<?= input::get('unit_price'); ?>" class="form-control curency text-right">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3">Brand Name<sup class="text-red"></sup></label>
                                <div class="col-sm-9">
                                    <input type="text" minlength="4" maxlength="150" name="brand" value="<?= input::get('brand'); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">Manufacturer  Date </label>
                                <div class="col-sm-6">
                                    <input type="text" id="manufacture_date"  name="manufacture_date" value="<?= input::get('manufacture_date'); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Expire Date </label>
                                <div class="col-sm-6">
                                    <input type="text" id="expire_date" focusout="checkFormDate(this)" class="form-control" name="expire_date" value="<?=  input::get('expire_date')?>" />
                                </div>
                            </div>
                           

                           <br/>
                             
                            <div class="form-group">
                                <div class="col-sm-offset-6">
                                    <a href="<?=HTTP_PATH.'crv/crvdetails/'.input::get('crv_header_id')?>" class="btn btn-sm btn-default" >Clear</a>
                                    <input type="submit" name="save" value="save" class="btn btn-sm btn-primary" />    
                                </div>
                            </div>
                         </div>
                    </div>
                    </form>
                  </div>

                   <div class="box box-green">
                    <div class="box-header">
                        <h3 class="box-title">
                          <label class="label label-primary uppercase"> CRV No : <?=input::get('crv_no');?></label>
                          <label class="label label-success uppercase"><?= $data['suplier']; ?></label>
                        </h3> 
                        <span class="pull-right"> <h3 class="box-title"> <label class="label label-info"> Date : <?=input::get('crv_date');?></label></h3></span>
                    </div>
                  </div>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                        <tr>
                        <th>No</th>
                        <th >Item</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Price (LKR)</th>   
                       <th class="text-right">Amount <br/>(LKR)</th>                      
                        <th>Batch NO</th>
                        <th>Brand</th>
                        <th>MFG Date</th>
                        <th>EXP Date</th> 
                        
                        <th width='20%' class="text-right">Action</th>
                        </tr> 
                        <?php  $total= $i=0; foreach ($data['details'] as $key=>$value ) { $i++;
                           $amount = ($value->unit_price*$value->qty);
                          $total +=$amount;
                         ?>
                          <tr>
                            <td><?=$i?></td>
                            <td><?=$value->item_name.' ('.$value->item_bin_no.')'?></td>
                            <td class="text-right"><?= ($value->qty+0).' '.$value->unit_name?></td>
                            <td class="text-right"><?= $value->unit_price?></td>
                            <td class="text-right"><?= $amount?> </td>
                            <td><?=$value->batch_no?></td>
                            <td><?=$value->brand?></td>
                            <td><?=$value->manufacture_date?></td>
                            <td><?=$value->expire_date?></td>
                            
                            <td  class="text-right">
                                  <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table  class='uppercase' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span>                              
                                  <?php if($data['admin']->privilage_id>1){  ?>
                                 <a href="<?=HTTP_PATH.'crv/crvdetails/'.$value->crv_header_id.'/?del='.$value->crv_detail_id?>" class="btn btn-sm btn-danger"  onclick="return confirmDelete();" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i></a>
                               <?php }?>
                            </td>
                          </tr>
                        <?php } ?>
                        <tr>
                          <th colspan="4" class="text-right">Total Amount </th>
                          <th class="text-right"><?=number_format($total,2);?></th>
                          <th colspan="5" class="text-left"> LKR</th>
                        </tr>
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

          
          var nowTemp = new Date();
          var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

          $('#manufacture_date').datepicker({
            dateFormat:'yy-mm-dd',
            maxDate: now,
            changeYear:true,
            changeMonth:true
          });
          $('#expire_date').datepicker({
            dateFormat:'yy-mm-dd',
         //   minDate: now,
            changeYear:true,
            changeMonth:true
          });


           function loadItem(cat,select=null){
               $.ajax({url: "<?=HTTP_PATH.'ajax/item/'?>"+cat, 
                success: function(result){ 
                 $("#item_code").select2('destroy');              
                $('#item_code').children('option:not(:first)').remove();  
              var se = '';      
              $.each( result, function( key, val ) {
            if(select==val['item_code']){ se = 'selected="true"';}
               $('#item_code').append('<option  '+se+' value="'+val['item_code']+'"><span style="background-color: #dd1c1c;">'+val['item_name']+' ('+val['item_bin_no']+') '+' - '+val['qoh']+' '+val['unit']+'</span></option>');
              });
              $("#item_code").select2();
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
             });
          

        </script>



