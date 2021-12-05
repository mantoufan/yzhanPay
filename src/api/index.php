<?php
require 'App.php';
require 'extend/RaDataJsonServer.php';
$app = new App(array(
    'dataProvider' => new extend\RaDataJsonServer,
));
$app->run();
