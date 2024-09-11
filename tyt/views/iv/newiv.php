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
            IV
            <small><?=$data['title']?> </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>bmdiv">All IV</a></li>
            <li class="active"><?=$data['title']?> IV</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-9">
                    <div class="box box-yellow">
                    <div class="box-header">
                        <h3 class="box-title"><?=$data['title']?> IV</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <?=(input::get('iv_id'))?'<input type="hidden" value="'.input::get('iv_id').'" name="iv_id" />':'';?>

                            <div class="form-group">
                              <label class="col-sm-3">Default Item Category<sup class="text-red">*</sup></label>
                              <div class="col-sm-9"> 
                                <select class="select2 form-control" name="iv_type">
                                  <option></option>
                                  <?php foreach ($data['category'] as $key => $value) {  ?>
                                  <option <?= (input::get('iv_type')==$value->item_cat_id)?'selected="true"':'';?> value="<?=$value->item_cat_id?>" ><?=$value->cat_name?></option>
                                <?php } ?>
                                </select>
                              </div>
                            </div> 
                            
                            <div class="form-group">
                              <label class="col-sm-3">IV No<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <input type="text" maxlength="30" class=" form-control" name="iv_no" value="<?=input::get('iv_no')?>">
                              </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3">Date<sup class="text-red">*</sup></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="iv_date" name="iv_date" value="<?= input::get('iv_date'); ?>" />
                                </div>
                            </div>
                           
                           <div class="form-group">
                              <label class="col-sm-3">IV to<sup class="text-red">*</sup></label>
                              <div class="col-sm-9"> 
                                <input type="text" class="form-control" maxlength="150" value="<?=input::get('iv_to');?>" name="iv_to">
                              </div>

                            </div>

                            <div class="form-group">
                              <label class="col-sm-3">Remarks</label>
                              <div class="col-sm-9">
                                <textarea class="form-control" maxlength="100" name="remarks" rows="4"><?=input::get('remarks');?></textarea>
                              </div>
                            </div>

                            
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3">
                                    <a href="<?=HTTP_PATH.'iv/newiv'?>" class="btn btn-sm btn-default" >Clear</a>
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
          $('.select2').select2();
           var nowTemp = new Date();
          var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

          $('#iv_date').datepicker({
            dateFormat:'yy-mm-dd',
          //  maxDate: now,
            changeYear:true,
            changeMonth:true
          });
      </script>



