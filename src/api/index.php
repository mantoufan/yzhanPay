<?php
require __DIR__ . '/vendor/autoload.php';
include 'function.php';
SplAutoLoadRegister();
Cors();

$router = new \Bramus\Router\Router();
$router->setNamespace('controllers');
$router->options('/authenticate');
$router->match('POST', '/authenticate', '\controller\Authenticate@checkLogin');

// $router->match('POST', '/authenticate', function () {
//     $_POST = json_decode(file_get_contents('php://input'), true);
//     $_name = $_POST['username'];
//     $_password = $_POST['password'];
//     $DB = new Medoo($CONFIG['database']);
//     $data = $DB->get('user', array(
//         'id',
//         'name',
//     ), array(
//         'name' => $_name,
//         'password' => md5($_password . $CONFIG['md5_salt']),
//     ));
//     if (empty($data['id'])) {
//         return header('Status: 403');
//     }

//     EchoJson(array('auth' => JWT::encode(array(
//         'id' => $data['id'],
//         'name' => $data['name'],
//     ), $CONFIG['jwt_key'], 'HS256')));
// });

$router->run();
