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
            User
            <small><?=$data['title']?> </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>manageuser">Manage users</a></li>
            <li class="active"><?=$data['title']?> User</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-8">
                    <div class="box box-maroon">
                    <div class="box-header">
                        <h3 class="box-title"><?=$data['title']?> User</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?=  token::genarate()?>" />
                            <?=(input::get('user_id'))?'<input type="hidden" value="'.input::get('user_id').'" name="user_id" />':'';?>

                            <?php if($data['admin']->privilage_id>1 && $data['admin']->privilage_id!=4 ){ ?>

                              <input type="hidden" name="station_id" value="<?= $data['admin']->station_id ?>">

                              <div class="form-group">
                              <label class="col-sm-3">Station<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <?php foreach ($data['station'] as $key => $value) {
                                 echo ($value->station_id==$data['admin']->station_id)?$value->station_name:'';
                                } ?>
                              </div>
                            </div>
                            <?php }else{  ?>
                            <div class="form-group">
                              <label class="col-sm-3">Station<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <select class="select2 form-control" name="station_id">
                                  <option></option>
                                  <?php foreach ($data['station'] as $key => $value) { ?>
                                    <option  value="<?= $value->station_id ?>" <?= (input::get('station_id')==$value->station_id)?'selected="selected"':'';?> ><?= $value->station_name; ?></option>
                                  <?php  } ?>                                  
                                </select>
                              </div>
                            </div>
                          <?php } ?>

                            <div class="form-group">
                                <label class="col-sm-3">User Name [ID]<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                  <?php if(input::get('user_id')){ echo '<input type="hidden"  name="user_name" value="'.input::get('user_name').'" />'.input::get('user_name'); }else{  ?>
                                    <input type="text" class="form-control" maxlength="15" name="user_name" value="<?=  input::get('user_name')?>" />
                                    <small>Service number without "/" </small>
                                  <?php } ?>
                                </div>
                            </div>
                            <?php if(!input::get('user_id')){  ?>
                            <div class="form-group">
                                <label class="col-sm-3">Password<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="Password" class="form-control" maxlength="15" name="pwd" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Confirm Password<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="Password" class="form-control" maxlength="15" name="c_pwd" value="" />
                                </div>
                            </div>
                          <?php } ?>
                            <div class="form-group">
                                <label class="col-sm-3">Service No<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="30" name="service_no" value="<?=  input::get('service_no')?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Rank<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" value="<?=input::get('user_rank')?>" name="user_rank" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Name with Initials<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="150" name="name" value="<?=  input::get('name')?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3">Regiment<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control"   name="regiment" value="<?=  input::get('regiment')?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Unit<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="50" name="unit" value="<?=  input::get('unit')?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">NIC<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="12" name="nic" value="<?=  input::get('nic')?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Address <sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="250" name="addr" value="<?=  input::get('addr')?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3">Mobile <sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <input type="text" onkeyup="validInt(this)" class="form-control" maxlength="10" minlength="10" name="mobile" value="<?=  input::get('mobile')?>" />
                                </div>
                            </div> 
                           
                           <div class="form-group">
                              <label class="col-sm-3">Section<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <select class="select2 form-control" name="section" >
                                  <option></option>
                                  <?php for($i=1;$i<=$data['admin']->section;$i++){ 
                                    switch ($i) {
                                      case '1':   $x='CRV';      break;
                                      case '2':   $x='IV';      break;
                                      case '3':   $x='Issuing';      break; 
                                      case '4':   $x='All';      break; 
                                    }
                                   ?>
                                     <option value="<?=$i?>" <?= (input::get('section')==$i)?'selected="true"':''?> ><?=$x?> Section</option>
                                  <?php } ?>
                                </select> 
                              </div>
                            </div>
                              
                              <div class="form-group">
                              <label class="col-sm-3">Privilege  Level<sup class="text-red">*</sup></label>
                              <div class="col-sm-9">
                                <select class="select2 form-control" name="privilage_id" >
                                  <option></option>
                                  <?php for($i=1;$i<=$data['admin']->privilage_id;$i++){ 
                                    switch ($i) {
                                      case '1':   $x='I';      break;
                                      case '2':   $x='II';      break;
                                      case '3':   $x='III';      break;
                                      case '4':   $x='IV';      break;
                                    }
                                   ?>
                                     <option value="<?=$i?>" <?= (input::get('privilage_id')==$i)?'selected="true"':''?> >Level <?=$x?></option>
                                  <?php } ?>
                                </select> 
                              </div>
                            </div>
                              
                              
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3">
                                    <a href="<?=HTTP_PATH.'manageuser/newuser'?>" class="btn btn-sm btn-default" >Clear</a>
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



