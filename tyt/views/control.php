<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="<?=HTTP_PATH?>public/images/favicon.ico" type="image/x-icon"> 
    <title><?=  config::get('site_name').' | '.App::getRouter()->getController().' | '.App::getRouter()->getAction()?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?=HTTP_PATH?>public/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link href="<?=HTTP_PATH?>/public/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?=HTTP_PATH?>public/plugins/select2/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=HTTP_PATH?>public/dist/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?=HTTP_PATH?>public/dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="<?=HTTP_PATH?>plugins/iCheck/all.css">
    
    <?=$data['content']?>
  </body>
