<?php
require 'vendor/autoload.php';
require 'common/function.php';
AppInit();
$router = new \Bramus\Router\Router();
$router->setNamespace('controller');
$router->options('/authenticate', function () {});
$router->match('POST', '/authenticate', 'Authenticate@checkLogin');
$router->run();
