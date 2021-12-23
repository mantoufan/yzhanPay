<?php
namespace service;

class TradeService
{
    public static function CreateNo()
    {
        list($ms) = explode(' ', microtime());
        return date('YmdHis') . ($ms * 1000000) . rand(00, 99);
    }
}
