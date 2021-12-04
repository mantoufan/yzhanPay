<?php
require 'vendor/autoload.php';
include 'common/function.php';
AppInit();
$router = new \Bramus\Router\Router();
$router->setNamespace('controller');
$router->options('/authenticate', function () {});
$router->match('POST', '/authenticate', 'Authenticate@checkLogin');
$router->run();
