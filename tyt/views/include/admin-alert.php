   <?php
   
    $success  = session::flash('success');
    $error = session::flash('error');
    $info = session::flash('info');
     if($success!=''){
        ?>
      <div class="box-body">
                      <div class="alert alert-success alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Success !</h4>
                        <?=$success?>
                      </div>
                  </div>  
   <?php }elseif($error!=''){ ?>
        <div class="box-body">
                      <div class="alert alert-warning alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                        <?=$error?>
                      </div>
                  </div>
    <?php }elseif($info!=''){ ?>
        <div class="box-body">
                      <div class="alert alert-info alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Info! </h4>
                        <?=$info?>
                      </div>
                  </div>
    <?php  } ?>