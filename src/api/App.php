<?php
require 'vendor/autoload.php';
require 'common/function.php';

use Bramus\Router\Router;

class App
{
    public function __construct($params = array())
    {
        Cors();
        SplAutoLoadRegister();
        $this->dataProvider = !empty($params['dataProvider']) ? $params['dataProvider'] : null;
    }

    public function getRouter()
    {
        $router = new \Bramus\Router\Router();
        $router->options('/.*', function () {});
        $path = getPath();
        $route = preg_replace_callback('/\d+/', function ($s) {
            return '(\d{' . strlen($s[0]) . '})';
        }, $path);
        $path = preg_replace('/\/\d+/', '', $path);

        $pathAr = explode('/', $path);
        $dataProvider = $this->dataProvider;
        if (!empty($dataProvider)) {
            $test = $dataProvider['test'];
            $use = $dataProvider['use'];
            if (preg_match($test, '/' . $path)) {
                $method = $use->getMethod();
            }
        }
        if (empty($method)) {
            $method = array_pop($pathAr);
        }
        $methodAr = explode('-', $method);
        for ($i = 1; $i < count($methodAr); $i++) {
            $methodAr[$i] = ucfirst($methodAr[$i]);
        }
        $method = implode('', $methodAr);

        $controller = array_pop($pathAr);
        $prePath = implode('\\', $pathAr);

        if ($pathAr[0] === 'plugins') {
            $controller .= '\\' . ucfirst($controller);
        } else {
            if ($pathAr[0] !== 'open') {
                $router->setNamespace('controller');
            }
            $controller = ucfirst($controller);
        }

        $path = ($prePath ? $prePath . '\\' : '') . $controller . '@' . $method;
        $GLOBALS['method'] = $method;
        $router->match('POST|GET|PUT|DELETE', $route, $path);
        return $router;
    }

    public function run()
    {
        $router = $this->getRouter();
        $router->run();
    }
}
