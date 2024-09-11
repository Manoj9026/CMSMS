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
            CRV Header
            <small><?=$data['title']?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>crv">All CRV</a></li>
            <li class="active"><?=$data['title']?> CRV</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-10">
                    <div class="box box-green">
                    <div class="box-header">
                        <h3 class="box-title"><?=$data['title']?> CRV Header</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <?=(input::get('crv_id'))?'<input type="hidden" value="'.input::get('crv_id').'" name="crv_id" />':'';?>
                            <div class="form-group">
                                <label class="col-sm-3">Receive Type<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                   <select name="crv_type_id" class="select2 form-control">
                                       <option></option>
                                       <?php foreach ($data['crv_types'] as $key => $value) { ?>
                                       <option value="<?=$value->crv_type_id?>" <?= (input::get('crv_type_id')==$value->crv_type_id)?'selected="selected"':''; ?> ><?=$value->crv_type_txt?></option>
                                       <?php } ?>
                                   </select>
                                  </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Supplier<sup class="text-red">*</sup></label>
                                <div class="col-sm-7">
                                    <select class="select2 form-control" name="supplier_id">
                                        <option></option>
                                        <?php foreach($data['supplier'] as $key=>$value){ ?>
                                        <option value="<?=$value->supp_id?>" <?= (input::get('supplier_id')==$value->supp_id)?'selected="selected"':''; ?> ><?=$value->supp_name?></option>
                                    <?php    } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                  <a target="new" href="<?=HTTP_PATH?>supplier/newsupplier"  class="btn bnt-sm btn-success"><i class="fa fa-plus"></i> New Sullpier</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">CRV No<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" name="crv_no" maxlength="100" value="<?= input::get('crv_no'); ?>" class=" form-control ">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Order No<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" name="order_no" maxlength="100" value="<?= input::get('order_no'); ?>" class=" form-control ">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">CRV Date<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" id="crv_date" class="form-control" name="crv_date" value="<?=  input::get('crv_date')?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Officer<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text"   class="form-control" name="officer" value="<?=  input::get('officer')?>" />
                                </div>
                            </div>
                            
                     
                            <div class="form-group">
                                <label class="col-sm-3">Remarks</label>
                                <div class="col-sm-9">
                                    <textarea name="remarks" class="form-control" maxlength="200" rows="3"><?=input::get('remarks');?></textarea>
                                </div>
                            </div>

                             
                            <div class="form-group">
                                <div class="col-sm-offset-3">
                                    <a href="<?=HTTP_PATH.'crv/newcrv'?>" class="btn btn-sm btn-default" >Clear</a>
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
       <script src="<?=HTTP_PATH?>public/plugins/jQueryUI/jquery-ui.js" type="text/javascript"></script>
        <script>
          $('.select2').select2({
            placeholder:'Select'});
           var nowTemp = new Date();
          var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

          $('#crv_date').datepicker({
            dateFormat:'yy-mm-dd',
            maxDate: now,
            changeYear:true,
            changeMonth:true
          });

      //    var newPrice = price.replace(/[^0-9\.]/g, '');

        </script>



