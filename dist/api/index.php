<?php 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Medoo\Medoo;
require __DIR__ . '/vendor/autoload.php';
include('function.php');
$router = new \Bramus\Router\Router();

$router->match('POST', '/authenticate', function() {
	$CONFIG = include './config.php';
	$_POST = json_decode(file_get_contents('php://input'), true);
	$_name = $_POST['username'];
	$_password = $_POST['password'];
	$DB = new Medoo($CONFIG['database']);
	$data = $DB->select('user', array(
    'id',
		'name'
	), array(
		'name' => $_name,
		'password' => md5($_password . $CONFIG['md5_salt'])
	));
	if (empty($data['id'])) return header('Status: 403');
	echoJson(array('auth' => JWT::encode(array(
		'id' => $data['id'],
		'name' => $data['name']
	), $CONFIG['jwt_key'], 'HS256')));
});

$router->run();
?>