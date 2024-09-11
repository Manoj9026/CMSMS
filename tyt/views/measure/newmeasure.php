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
            Measure
            <small><?=$data['title']?> </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>measure">All Measure units</a></li>
            <li class="active"><?=$data['title']?> Measure Unit</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-8">
                    <div class="box box-blue">
                    <div class="box-header">
                        <h3 class="box-title"><?=$data['title']?> Measure</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <?=(input::get('measure_unit_id'))?'<input type="hidden" value="'.input::get('measure_unit_id').'" name="measure_unit_id" />':'';?>
                            <div class="form-group">
                                <label class="col-sm-3">Measure Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="50" minlength="1" name="unit_name" value="<?=  input::get('unit_name')?>" />
                                </div>
                            </div>
                           
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3">
                                    <a href="<?=HTTP_PATH.'measure/newmeasure'?>" class="btn btn-sm btn-default" >Clear</a>
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



