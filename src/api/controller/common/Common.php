<?php
namespace controller\common;

use service\ConfigService;

class Common
{
    protected $table = 'common';

    public function __construct()
    {
        ConfigService::ConfigInit();
    }
    public function __destruct()
    {

    }
}
