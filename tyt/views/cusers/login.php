 <div class="container">
          <?php if(isset($data['error'])){ 
        echo '<div class="alert alert-danger alert-dismissable">
                   <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
                   <!-- <h5><i class="icon fa fa-ban"></i> Error !</h5> --><ul>';
        foreach ($data['error'] as $value) {    
        echo '<li>'.$value.'</li>';
        }
        echo '</ul> </div>';
        }
        ?>
      <div class="row">
        <div class="col-lg-2"></div>
         <div class="col-lg-4">

            <div class="wow fadeInLeft" style="text-align: center;">
                <img src="<?=HTTP_PATH?>public/login/img/Army_Logo_my.png" width="200px" align="centere" >
            <p width="350px" align="centere"  style="color:rgb(255, 165, 0); font-size:35px; text-align: center; font-family: sans-serif; text-shadow: 1px 1px 2px black;"  >
<b>Centralized Medical Stock</b> Management System</p> 
                </div>
           <br>  
         </div>
        <div class="col-lg-4 align-self-center wow fadeInRight">
            
            <form action="" method="post" autocomplete="off" >
                <input type="hidden" name="token" value="<?=$data['token']?>" > 
                <div class="form-group">
                    <label class="form-label mt-4 login_title">Login </label>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="floatingInput" placeholder="User name" name="username" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" value="<?=  input::get('username')?>">
                      <label for="floatingInput">User name</label>
                    </div>
                    <div class="form-floating">
                      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" name="password" >
                      <label for="floatingPassword">Password</label>
                    </div>
                </div>
                <div class="form-group">
                   <br>
                   <input  type="submit" class="btn btn-outline-info" value="Login">
                   <span class="text_white"title="" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Please type your valid user informations to login to system." data-bs-original-title="Help box">
                       Help
                    </span>

                </div>

            </form>
        </div>
        <div class="col-lg-12">
 
 

        </div>
      </div>
