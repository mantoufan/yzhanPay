<?php
namespace service;

class ConfigService
{
    public static function Get()
    {
        return include 'common/config.php';
    }
}
