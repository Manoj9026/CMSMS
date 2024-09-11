<style>
    .profile{ position: absolute; right: 50px;}
    .profile img{        border-radius: 50%;    }
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?php   include VIEWS_PATH.'/include/admin-alert.php'; ?>
        <section class="content-header">
          <h1><?=$data['admin']->user_name?>
            <small>Profile</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a class="active">Profile</a></li>
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <div class="box box-orange">
                    <div class="box-header with-border">
                        <h3 class="box-title">My Profile</h3>
                    </div>
                    <div class="box-body form-horizontal">
                         <?php   if(isset($data['profile'])){
                                echo '<span class="pull-right profile "><img src="'.$data['profile'].'" /></span>';
                            }
 
                            ?>
                        <div class="form-group">
                            <label class="col-sm-3 ">User Name</label>
                            <p ><?=$data['admin']->user_name?></p>
                           
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Name</label>
                            <p><?=$data['admin']->name?></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Rank</label>
                            <p><?=$data['admin']->user_rank?></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Number</label>
                            <p><?=$data['admin']->service_no?></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Regiment</label>
                            <p><?=$data['admin']->regiment?></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Unit</label>
                            <p><?=$data['admin']->unit?></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">NIC</label>
                            <p><?=$data['admin']->nic?></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Mobile</label>
                            <p><?=$data['admin']->mobile?></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Address</label>
                            <p><?=$data['admin']->addr?></p>
                        </div>
                        
                    </div>
                </div>
                
                
            </div>
            <!-- right column -->
            <div class="col-md-6">
              <!-- Horizontal Form -->
              <div class="box box-danger " >
                <div class="box-header with-border">
                  <h3 class="box-title"  data-widget="collapse">Change Password</h3>
                  <div class="box-tools pull-right">
                         
                      </div>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post" action="">
                    <input type="hidden" name="token" value="<?=$data['token']?>" />
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-3 control-label">User name</label>
                      <div class="col-sm-9">
                          <input type="email" class="form-control"  disabled="true" placeholder="Email" value="<?=$data['admin']->user_name?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label   class="col-sm-3 control-label">Current Password</label>
                      <div class="col-sm-9">
                          <input type="password" class="form-control" name="password" placeholder="Current Password">
                      </div>
                    </div>
                    <div class="form-group">
                      <label  class="col-sm-3 control-label">New Password</label>
                      <div class="col-sm-9">
                          <input type="password" name="newpassword" class="form-control" value="<?=  input::get('newpassword')?>"  placeholder="New Password">
                      </div>
                    </div>
                      
                      <div class="form-group">
                          
                          <label  class="col-sm-3 control-label">Re-type Password</label>
                      <div class="col-sm-9">
                          <input type="password" class="form-control" name="cpassword"  placeholder="Re-type Password">
                      </div>
                      </div>
                      
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                      <button type="reset" class="btn btn-default">Cancel</button>
                      <button type="submit" name="change" value="Update" class="btn btn-info pull-right">Update</button>
                  </div><!-- /.box-footer -->
                </form>
              </div><!-- /.box -->
              
            <!--  <div class="box box-danger" id="image">
                    <div class="box-header with-border">
                        <h3 class="box-title"  data-widget="collapse">Change Profile Image</h3>
                        <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                    <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="token" value="<?=$data['token']?>" />
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2">Image</label>
                            <div class="col-sm-10">
                                <input type="file" name="profile" />
                            </div>
                        </div>
                        
                    </div>
                        <div class="box-footer">
                            <input type="submit" value="Update" name="saveprofile"  class="btn btn-info  pull-right" />
                        </div>
                    </form>
                </div> -->
              
              
              
              <!-- general form elements disabled -->              
            </div><!--/.col (right) -->
          </div>   <!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>


      <!-- Control Sidebar -->
      
     <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
 
    <script>
    $('#pass').addClass('collapsed-box');
    $('#image').addClass('collapsed-box');
    $('#profile').addClass('collapsed-box');
    $('#dob').datepicker({
     format: "yyyy-mm-dd",
    endDate: "-15y",
    startView: 2,
    autoclose: true
    });
        </script>