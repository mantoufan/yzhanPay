<?php 
require __DIR__ . '/vendor/autoload.php';
$CONFIG = include './config.php';
$router = new \Bramus\Router\Router();

// 设置允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 设置允许的响应类型 
header('Access-Control-Allow-Methods:POST, GET'); 
// 设置允许的响应头 
header('Access-Control-Allow-Headers:x-requested-with,content-type');

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