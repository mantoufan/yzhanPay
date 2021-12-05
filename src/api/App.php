<?php
require 'vendor/autoload.php';
require 'common/function.php';
$_PARAMS = array('GET', 'POST');

use Bramus\Router\Router;

class App
{
    public function __construct()
    {
        Cors();
        SplAutoLoadRegister();
    }
    public function getRouter()
    {
        $router = new \Bramus\Router\Router();
        $router->setNamespace('controller');
        $router->options('/.*', function () {});
        $dirname = dirname($_SERVER['PHP_SELF']);
        $search = str_replace($_SERVER['REQUEST_URI'], $dirname, '');
        $query = explode('?', $search);
        $path = $query[0];
        $class = explode('@', $path);
        $method = $class[1];
        $controllerPath = explode('/', $class[0]);
        $endPath = ucfirst(array_pop($controllerPath));
        $router->match('POST', $path, implode('/', $controllerPath) . '/' . $endPath . '@' . $method);
        return $router;
    }
    public function getParams()
    {
        return array(
            'GET' => $_GET,
            'POST' => json_decode(file_get_contents('php://input'), true),
        );
    }
    public function run()
    {
        $_PARAMS = $this->getParams();
        $router = $this->getRouter();
        $router->run();
    }
}
