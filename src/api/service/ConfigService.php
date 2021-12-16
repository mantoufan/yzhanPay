<?php
namespace service;

class ConfigService
{
    public static function ConfigGet()
    {
        return include 'common/config.php';
    }
}
