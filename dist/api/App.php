<?php
require 'vendor/autoload.php';
require 'common/function.php';

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
        $search = str_replace($dirname, '', $_SERVER['REQUEST_URI']);
        $query = explode('?', $search);
        $path = $query[0];
        $route = preg_replace_callback('/\d+/', function ($s) {
            return '(\d{' . strlen($s[0]) . '})';
        }, $path);
        $path = preg_replace('/\/\d+/', '', $path);
        $pathAr = explode('/', $path);
        $method = array_pop($pathAr);
        $methodAr = explode('-', $method);
        for ($i = 1; $i < count($methodAr); $i++) {
            $methodAr[$i] = ucfirst($methodAr[$i]);
        }
        $method = implode('', $methodAr);
        $controller = ucfirst(array_pop($pathAr));
        $prePath = implode('/', $pathAr);
        $router->match('POST|GET', $route, ($prePath ? $prePath . '/' : '') . $controller . '@' . $method);
        return $router;
    }
    public function run()
    {
        $router = $this->getRouter();
        $router->run();
    }
}
