
 
<div class="hold-transition"  style="">
    <div class="login-page">
    <div class="login-box">
        <div class="login-logo" style="">
            <a href="<?=HTTP_PATH?>" >Centralized Medical Stock<b> Management System</b></a>
        </div>
        <?php if(isset($data['error'])){ 
        echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Error !</h4><ul>';
        foreach ($data['error'] as $value) {    
        echo '<li>'.$value.'</li>';
        }
        echo '</ul> </div>';
        }
        ?>
        
        <div class="login-box-body" style="border:2px #3c8dbc solid;">
            <p class="login-box-msg">Login to <b>CMSMS</b></p>
      
        <form action="" method="post" autocomplete="off" >
            <input type="hidden" name="token" value="<?=$data['token']?>" >
            <div class="form-group has-feedback" >
                <input type="text" class="form-control" placeholder="user name" name="username" value="<?=  input::get('username')?>" >
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
            
            <div class="row">
                <div class="col-xs-8">
                   <!-- <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember" > Remember me 
                    </label>
                    </div> -->
                </div>
                <div class="col-xs-4">
                    <input type="submit" class="btn btn-primary btn-block btn-flat" value="Sign In">
                </div>
            </div>
            <div class="row-border">
                <br/><br/>
            </div>
        </form>
        
          </div>
    </div>
<br/>
</div>
</div>


<footer class="main-footer-login">
        <div class="pull-right hidden-xs">
          2018 - <?=date("Y")?> <a href="<?=HTTP_PATH?>"></a>  | <b>Version</b> 2.0.1
        </div>
       
       Software Solution by Directorate of Information Technology - SRI LANKA ARMY
      </footer>
