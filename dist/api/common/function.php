<?php
function EchoJson($data)
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
