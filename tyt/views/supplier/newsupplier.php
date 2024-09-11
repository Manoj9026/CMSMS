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
            Supplier
            <small><?=$data['title']?> </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>supplier">Suppliers</a></li>
            <li class="active"><?=$data['title']?> Supplier</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-8">
                    <div class="box box-teal">
                    <div class="box-header">
                        <h3 class="box-title"><?=$data['title']?> Supplier</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <?=(input::get('supp_id'))?'<input type="hidden" value="'.input::get('supp_id').'" name="supp_id" />':'';?>
                            
                            <div class="form-group">
                              <label class="col-sm-3">Supplier Name<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="supp_name" maxlength="50" value="<?=input::get('supp_name');?>">
                              </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3">Address<sup class="text-red"></sup></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="150" name="addr" value="<?=  input::get('addr')?>" />
                                </div>
                            </div>
                           
                           <div class="form-group">
                              <label class="col-sm-3">Email</label>
                              <div class="col-sm-9">
                                <input type="email" name="email" maxlength="100" value="<?= input::get('email')?>" class="form-control">
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-3">Telephone </label>
                              <div class="col-sm-9"><input type="text" class="form-control" onkeyup="validInt(this)" maxlength="10" minlength="10" name="tele" value="<?= input::get('tele') ?>"></div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-3">Mobile </label>
                              <div class="col-sm-9"><input type="text" onkeyup="validInt(this)" class="form-control" maxlength="10" minlength="10" k name="mobile" value="<?= input::get('mobile') ?>"></div>
                            </div>

                           

                            
                            <div class="form-group">
                                <div class="col-sm-offset-3">
                                    <a href="<?=HTTP_PATH.'supplier/newsupplier'?>" class="btn btn-sm btn-default" >Clear</a>
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
      </script>



