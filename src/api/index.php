<?php
require 'vendor/autoload.php';
include 'function.php';
AppInit();
$router = new \Bramus\Router\Router();
$router->setNamespace('controller');
$router->options('/authenticate');
$router->match('POST', '/authenticate', 'Authenticate@checkLogin');
$router->run();
