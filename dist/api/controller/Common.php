<?php
namespace controller;

use service\ConfigService;

class Common
{
    public function __construct()
    {
        ConfigService::ConfigInit();
    }
    public function __destruct()
    {

    }
}
