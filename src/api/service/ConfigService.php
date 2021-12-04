<?php
namespace service;

class ConfigService
{
    private $config = array();
    public static function ConfigInit($params = array())
    {
        $this->config = include '../common/config.php';
    }
    public static function ConfigList($params = array())
    {
        return $this->config;
    }
}
