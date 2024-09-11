<!DOCTYPE html> 
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Centralized Medical Stock Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?=HTTP_PATH?>public/login/css/bootstrap.css">
    <link rel="stylesheet" href="<?=HTTP_PATH?>public/login/css/custom.css">
    <link rel="stylesheet" href="<?=HTTP_PATH?>public/login/css/custom.min.css">
   
    <link rel="stylesheet" href="<?=HTTP_PATH?>public/login/css/animate.css">
<!--     <link rel="stylesheet" href="css/prism-okaidia.css"> 
    <link rel="stylesheet" href="css/font-awesome.min.css"> 
     -->
    <script src="<?=HTTP_PATH?>public/login/js/wow.min.js"></script>
    <script>
    new WOW().init();
    </script>

  </head>
   <body class="body_bg_image">

      <?=$data['content']?>
      
        </div> 
  <div class="newstyle fixed-bottom">
      <div class="container">
          <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <center><a href="#" class="footer_text">Software Solution by Dte of IT - SL Army</a></center>
            </div>
            <div class="col-lg-2 "><a href="#" class="footer_text"> Version 0.1.1 </a></div>
          </div>
      </div>
        
      </div>


  </body>
   <script src="<?=HTTP_PATH?>public/login/js/jquery.min.js"></script>
   <script src="<?=HTTP_PATH?>public/login/js/bootstrap.bundle.min.js"></script>
   <script src="<?=HTTP_PATH?>public/login/js/prism.js" data-manual></script>  
   <script src="<?=HTTP_PATH?>public/login/js/custom.js"></script> 
       
      <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
</html>
