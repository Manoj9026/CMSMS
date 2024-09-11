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
            Station
            <small><?=$data['title']?> </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>station">All Station</a></li>
            <li class="active"><?=$data['title']?> Item</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-8">
                    <div class="box box-olive">
                    <div class="box-header">
                        <h3 class="box-title"><?=$data['title']?> Station</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <?=(input::get('station_id'))?'<input type="hidden" value="'.input::get('station_id').'" name="station_id" />':'';?>
                            
                            <div class="form-group">
                              <label class="col-sm-3">Station Name<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <input type="text" maxlength="150" class=" form-control" name="station_name" value="<?=input::get('station_name')?>">
                              </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3">Address</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control " maxlength="200" name="addr" value="<?= input::get('addr'); ?>" />
                                </div>
                            </div>
                           
                           <div class="form-group">
                              <label class="col-sm-3">Phone</label>
                              <div class="col-sm-5">
                                <input class="form-control" name="tele" maxlength="10" minlength="10" value="<?=input::get('tele');?>" onkeyup="validInt(this)" >
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-3">Mobile</label>
                              <div class="col-sm-5">
                                <input type="text" class="form-control" maxlength="10" minlength="10" name="mobile" value="<?= input::get('mobile') ?>" onkeyup="validInt(this)" >
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-3">Email</label>
                              <div class="col-sm-9">
                                <input type="email" class="form-control" maxlength="150"  name="email" value="<?= input::get('email') ?>">
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-3">Incharge Rank and Name<sup class="text-red">*</sup></label>

                              <div class="col-sm-9">
                                <input type="text" class="form-control " maxlength="250" name="incharge_detail" value="<?= input::get('incharge_detail') ?>">
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-3">Default Item Category<sup class="text-red">*</sup></label>
                              <div class="col-sm-9"> 
                                <select class="select2 form-control " name="default_item_cat_id">
                                  <option></option>
                                  <?php foreach ($data['category'] as $key => $value) {  ?>
                                  <option <?= (input::get('default_item_cat_id')==$value->item_cat_id)?'selected="true"':'';?> value="<?=$value->item_cat_id?>" ><?=$value->cat_name?></option>
                                <?php } ?>
                                </select>
                              </div>
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
      </script>



