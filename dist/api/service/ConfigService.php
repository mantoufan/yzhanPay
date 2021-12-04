<?php
namespace service;

class ConfigService
{
    private static $config = array();
    public static function ConfigInit()
    {
        self::$config = include 'common/config.php';
    }
    public static function ConfigList()
    {
        return self::$config;
    }
}
