<?php 
require __DIR__ . '/vendor/autoload.php';
$router = new \Bramus\Router\Router();

$router->match('POST', '/authenticate', function() {
	$CONFIG = include './config.php';
	$_name = $_POST['username'];
	$_password = $_POST['password'];
	$DB = new Medoo\Medoo($CONFIG['database']);
	$data = $DB->select('user', array(
    'id'
	), array(
		'name' => $_name,
		'password' => md5($_password . $CONFIG['md5_salt'])
	));
});

$router->run();
?>