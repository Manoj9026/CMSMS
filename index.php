<?php
session_start();
$params = session_get_cookie_params();
setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"],
    true,  // this is the secure flag you need to set. Default is false.
    true  // this is the httpOnly flag you need to set
);
error_reporting(E_ALL);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', (dirname(__FILE__)));
define('VIEWS_PATH', ROOT.DS.'tyt'.DS.'views');
define('PUBLIC_PATH', str_replace('', '', ROOT.DS.'public'));

//define('HTTP_PATH', 'http://localhost/army/bmd/dampsv1/pdo/');
define('HTTP_PATH', 'https://cmsms.army.lk/');
//echo PUBLIC_PATH;
date_default_timezone_set('Asia/Colombo');

require_once (ROOT.DS.'tyt'.DS.'lib'.DS.'init.php');
require_once (ROOT.DS.'tyt'.DS.'lib'.DS.'sanatize.php');
$uri = $_SERVER['REQUEST_URI']; 

App::run($uri);
