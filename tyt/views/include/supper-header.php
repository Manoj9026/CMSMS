<header class="main-header">
        <!-- Logo -->
        <a href="<?=HTTP_PATH?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>CMS</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>CMSMS</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <span class="top-name"><?=  config::get('site_name').' - <small>('.$data['admin']->station.')</small>'?> </span>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- Notifications: style can be found in dropdown.less -->
              
              <!-- Tasks: style can be found in dropdown.less -->
              
              <!-- User Account: style can be found in dropdown.less --> 
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" style="border-left: 2px solid #73bde9cc;" data-toggle="dropdown">
                  <?=  isset($data['pro-face'])?'<img src="'.$data['pro-face'].'" class="user-image" alt="'.$data['admin']->user_name.'">':''?>
                  <span class="hidden-xs"><?=$data['admin']->user_name?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <?=  isset($data['pro-face'])?'<img src="'.$data['pro-face'].'" class="img-circle" alt="'.$data['admin']->user_name.'">':'<span class="img-circle bg-red " style="font-size:42px; display:inline-block; width:55px; height:55px; text-align:center; border-radius:50%; line-height:100%;" >'.$data['admin']->user_name[0].'</span>'?>
                    <p>                     
                    <?php  switch ($data['admin']->privilage_id) {
                                      case '1':   $x='I';      break;
                                      case '2':   $x='II';      break;
                                      case '3':   $x='III';      break;
                                      case '4':   $x='IV';      break;
                                    } ?>
                      <?=$data['admin']->service_no.'<br/>'.$data['admin']->user_rank.' '.$data['admin']->name?> 
                      <small>Level <?=$x?></small>
                    </p>
                  </li>
                 
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?=HTTP_PATH.'cusers/profile/'?>" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?=HTTP_PATH?>cusers/logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
             <!-- <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li> -->
            </ul>
          </div>
        </nav>
      </header>