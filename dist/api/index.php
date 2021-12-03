<?php 
require __DIR__ . '/vendor/autoload.php';
$CONFIG = include './config.php';
$router = new \Bramus\Router\Router();

$router->match('POST', 'authenticate', function() {
	$_name = $_POST['username'];
	$_password = $_POST['password'];
	$DB = new Medoo\Medoo($CONFIG['database']);
	$data = $DB->select('', array(
    'id'
	), array(
		'name' => $_name,
		'password' => md5($_password . $CONFIG['md5_salt'])
	));
});
$router->run();
?>