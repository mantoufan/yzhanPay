<?php
function Output($data)
{
    echo json_encode($data);
}
function Cors()
{
    $_ORIGIN = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    header('Access-Control-Allow-Origin:' . $_ORIGIN);
    header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
    header('Access-Control-Expose-Headers: *');
    header('Access-Control-Allow-Headers: *');
}
function SplAutoLoadRegister()
{
    spl_autoload_register(function ($class) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    });
}
function getGets()
{
    return $_GET;
}
function getPosts()
{
    return $_SERVER['CONTENT_TYPE'] === 'application/json' ? json_decode(file_get_contents('php://input'), true) : $_POST;
}
function getPath()
{
    $dirname = dirname($_SERVER['PHP_SELF']);
    $search = str_replace($dirname . '/', '', $_SERVER['REQUEST_URI']);
    $query = explode('?', $search);
    return $query[0];
}
