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
            RV Header
            <small><?=$data['title']?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>crv">All RV</a></li>
            <li class="active"><?=$data['title']?> RV</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-10">
                    <div class="box box-green">
                    <div class="box-header">
                        <h3 class="box-title"><?=$data['title']?> RV Header</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <?=(input::get('rv_id'))?'<input type="hidden" value="'.input::get('rv_id').'" name="rv_id" />':'';?>
                            <div class="form-group">
                                <label class="col-sm-3">Receive Category<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                   <select name="rv_type_id" class="select2 form-control">
                                       <option></option>
                                       <?php foreach ($data['category'] as $key => $value) { ?>
                                       <option value="<?=$value->item_cat_id?>" <?= (input::get('rv_type_id')==$value->item_cat_id)?'selected="selected"':''; ?> ><?=$value->cat_name?></option>
                                       <?php } ?>
                                   </select>
                                  </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3">RV No<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" name="rv_no" maxlength="100" value="<?= input::get('rv_no'); ?>" class=" form-control ">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">RV Date<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" id="rv_date" class="form-control" name="rv_date" value="<?=  input::get('rv_date')?>" />
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-3">IV No<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" name="iv_no" maxlength="100" value="<?= input::get('iv_no'); ?>" class=" form-control ">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">IV Date<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" id="iv_date" class="form-control" name="iv_date" value="<?=  input::get('iv_date')?>" />
                                </div>
                            </div>
                            
                     
                            <div class="form-group">
                                <label class="col-sm-3">IV From</label>
                                <div class="col-sm-9">
                                  <input type="text" name="iv_from" value="<?=input::get('iv_from')?>" class="form-control">
                                   
                                </div>
                            </div>

                             
                            <div class="form-group">
                                <div class="col-sm-offset-3">
                                    <a href="<?=HTTP_PATH.'rv/newrv'?>" class="btn btn-sm btn-default" >Clear</a>
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

          $('#rv_date').datepicker({
            dateFormat:'yy-mm-dd',
            maxDate: now,
            changeYear:true,
            changeMonth:true
          });

          $('#iv_date').datepicker({
            dateFormat:'yy-mm-dd',
            maxDate: now,
            changeYear:true,
            changeMonth:true
          });

      //    var newPrice = price.replace(/[^0-9\.]/g, '');

        </script>



