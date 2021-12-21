<?php
require 'App.php';
require 'extend/RaDataJsonServer.php';
$app = new App(array(
    'dataProvider' => array(
        'test' => '/\/admin\//',
        'use' => new extend\RaDataJsonServer,
    ),
));
$app->run();
