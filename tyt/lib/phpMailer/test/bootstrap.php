<?php
require_once '../../control3/PHPMailer-master/test/vendor/autoload.php';
spl_autoload_register(function ($class) {
    require_once strtr($class, '\\_', '//').'.php';
});
