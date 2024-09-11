</head>
  <body class="hold-transition skin-blue sidebar-mini">
      <div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
        <section class="content-header">
          <h1>
          <!--  Centralized Medical stock Management System -->
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>
          <!-- Main content -->
          
          
          
        <?php
          if($this->data['admin']->station_id >2){ 
            /*if user is other station except bmd or DAMPS */
              
            ?>
           <section class="content">
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green-gradient">
                <div class="inner">
                  <h3>RV</h3>
                  <p> Receiving Voucher </p>
                </div>
                <div class="icon">
                  <i class="fa fa-hospital-o"></i>
                </div>
                <a href="<?=HTTP_PATH.'rv'?>" class="small-box-footer">More Details<i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
            
           <div class="col-lg-3 col-xs-6">
              <!-- small box -->
             <div class="small-box bg-yellow-gradient">
                <div class="inner">
                  <h3>ledger</h3>
                  <p>Issuing Voucher</p>
                </div>
                <div class="icon">
                  <i class="fa fa-external-link"></i>
                </div>
                <a href="<?=HTTP_PATH.'iv'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->

          <?php } ?>

         <?php
          if($this->data['admin']->station_id ==2){ 
            /*if user is bmd */
            ?> 
        <section class="content">
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green-gradient">
                <div class="inner">
                  <h3>CRV</h3>
                  <p>Certificate of Receiving Voucher  </p>
                </div>
                <div class="icon">
                  <i class="fa fa-hospital-o"></i>
                </div>
                <a href="<?=HTTP_PATH.'/crv'?>" class="small-box-footer">More Details<i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
            
           <div class="col-lg-3 col-xs-6">
              <!-- small box -->
             <div class="small-box bg-yellow-gradient">
                <div class="inner">
                  <h3>Ledger</h3>
                  <p>Issuing Voucher</p>
                </div>
                <div class="icon">
                  <i class="fa fa-external-link"></i>
                </div>
                <a href="<?=HTTP_PATH.'bmdiv'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
          <?php } ?>
          <?php /* BMD AND DAMPS */ if($this->data['admin']->station_id >0 and $this->data['admin']->station_id <3){ ?>
             <div class="col-lg-3 col-xs-6">
              <!-- small box -->
            <div class="small-box bg-lime-gradient">
                <div class="inner">
                  <h3>Reports</h3>
                  <p>All Reports</p>
                </div>
                <div class="icon">
                  <i class="fa fa-file-text-o"></i>
                </div>
                <a href="<?=HTTP_PATH.'report/singleitem'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
          <?php } ?>
              <div class="col-lg-3 col-xs-6">
              <!-- small box -->
            <div class="small-box bg-orange-gradient">
                <div class="inner">
                  <h3>Profile</h3>
                  <p>My Profile</p>
                </div>
                <div class="icon">
                  <i class="fa fa-user"></i>
                </div>
                <a href="<?=HTTP_PATH.'cusers/profile/'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
             <?php  if($this->data['admin']->station_id==2){  ?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
             <div class="small-box bg-aqua-gradient">
                <div class="inner">
                  <h3>Items</h3>
                  <p>All drugs and items</p>
                </div>
                <div class="icon">
                  <i class="fa fa-cubes "></i>
                </div>
                <a href="<?=HTTP_PATH.'item'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
             <div class="small-box bg-navy-gradient">
                <div class="inner">
                  <h3>Category</h3>
                  <p>All Categories </p>
                </div>
                <div class="icon">
                  <i class="fa fa-building-o"></i>
                </div>
                <a href="<?=HTTP_PATH.'category'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
            <div class="small-box bg-blue-gradient">
                <div class="inner">
                  <h3>Units</h3>
                  <p>All Measure Units</p>
                </div>
                <div class="icon">
                  <i class="fa fa-bars"></i>
                </div>
                <a href="<?=HTTP_PATH.'measure'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
            <div class="small-box bg-teal-gradient">
                <div class="inner">
                  <h3>Suppliers</h3>
                  <p>All registered Suppliers </p>
                </div>
                <div class="icon">
                  <i class="fa fa-truck"></i>
                </div>
                <a href="<?=HTTP_PATH.'supplier'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
            <div class="small-box bg-olive-gradient">
                <div class="inner">
                  <h3>Stations</h3>
                  <p>All Stations </p>
                </div>
                <div class="icon">
                  <i class="fa fa-home"></i>
                </div>
                <a href="<?=HTTP_PATH.'station'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
          <?php } ?>
           
 <?php if($this->data['admin']->privilage_id >1){   ?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
            <div class="small-box bg-maroon-gradient">
                <div class="inner">
                  <h3>Users</h3>
                  <p>Manage users</p>
                </div>
                <div class="icon">
                  <i class="fa fa-users"></i>
                </div>
                <a href="<?=HTTP_PATH.'manageuser'?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
              </div>
            </div><!-- ./col -->
<?php } ?>
          
             
            
          </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>



